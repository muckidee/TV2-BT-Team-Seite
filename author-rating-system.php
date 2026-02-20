<?php
/**
 * Author Rating System for Test-Vergleiche.com
 * IP-basierte Bewertung fuer Autoren-Profile
 *
 * @package suspended_flavor
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get client IP address
 */
function tv_get_client_ip() {
    $ip = '';

    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] )[0];
    } elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return sanitize_text_field( trim( $ip ) );
}

/**
 * Hash IP for privacy
 */
function tv_hash_ip( $ip ) {
    return hash( 'sha256', $ip . 'tv_rating_salt_2024' );
}

/**
 * Check if IP has already voted for this author
 */
function tv_has_ip_voted( $author_id ) {
    $ip_hash = tv_hash_ip( tv_get_client_ip() );
    $voted_ips = get_post_meta( $author_id, '_tv_rating_ips', true );

    if ( ! is_array( $voted_ips ) ) {
        $voted_ips = array();
    }

    return in_array( $ip_hash, $voted_ips, true );
}

/**
 * Get author rating data
 */
function tv_get_author_rating( $author_id ) {
    $total = (float) get_post_meta( $author_id, '_tv_rating_total', true );
    $count = (int) get_post_meta( $author_id, '_tv_rating_count', true );

    // Default values if no ratings yet
    if ( $count === 0 ) {
        // Start with a base rating (4.5-4.9 range based on author ID)
        $base_rating = 4.5 + ( ( $author_id % 5 ) * 0.1 );
        $base_count = 10 + ( $author_id % 20 );
        return array(
            'average' => round( $base_rating, 1 ),
            'count'   => $base_count,
            'total'   => $base_rating * $base_count
        );
    }

    return array(
        'average' => $count > 0 ? round( $total / $count, 1 ) : 0,
        'count'   => $count,
        'total'   => $total
    );
}

/**
 * Submit a rating
 */
function tv_submit_rating( $author_id, $rating ) {
    $rating = max( 1, min( 5, (int) $rating ) );

    // Check if already voted
    if ( tv_has_ip_voted( $author_id ) ) {
        return array(
            'success' => false,
            'message' => 'Sie haben bereits bewertet.'
        );
    }

    // Get current values
    $total = (float) get_post_meta( $author_id, '_tv_rating_total', true );
    $count = (int) get_post_meta( $author_id, '_tv_rating_count', true );
    $voted_ips = get_post_meta( $author_id, '_tv_rating_ips', true );

    if ( ! is_array( $voted_ips ) ) {
        $voted_ips = array();
    }

    // Initialize with base values if first real vote
    if ( $count === 0 ) {
        $base_rating = 4.5 + ( ( $author_id % 5 ) * 0.1 );
        $base_count = 10 + ( $author_id % 20 );
        $total = $base_rating * $base_count;
        $count = $base_count;
    }

    // Add new rating
    $total += $rating;
    $count += 1;

    // Store IP hash
    $voted_ips[] = tv_hash_ip( tv_get_client_ip() );

    // Save
    update_post_meta( $author_id, '_tv_rating_total', $total );
    update_post_meta( $author_id, '_tv_rating_count', $count );
    update_post_meta( $author_id, '_tv_rating_ips', $voted_ips );

    $new_average = round( $total / $count, 1 );

    return array(
        'success' => true,
        'message' => 'Vielen Dank fuer Ihre Bewertung!',
        'average' => $new_average,
        'count'   => $count
    );
}

/**
 * AJAX handler for rating submission
 */
function tv_ajax_submit_rating() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tv_rating_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Sicherheitsfehler.' ) );
    }

    $author_id = (int) $_POST['author_id'];
    $rating = (int) $_POST['rating'];

    if ( $author_id <= 0 || $rating < 1 || $rating > 5 ) {
        wp_send_json_error( array( 'message' => 'Ungueltige Daten.' ) );
    }

    $result = tv_submit_rating( $author_id, $rating );

    if ( $result['success'] ) {
        wp_send_json_success( $result );
    } else {
        wp_send_json_error( $result );
    }
}
add_action( 'wp_ajax_tv_submit_rating', 'tv_ajax_submit_rating' );
add_action( 'wp_ajax_nopriv_tv_submit_rating', 'tv_ajax_submit_rating' );

/**
 * AJAX handler to check if user can vote
 */
function tv_ajax_check_can_vote() {
    $author_id = (int) $_POST['author_id'];

    if ( $author_id <= 0 ) {
        wp_send_json_error();
    }

    $can_vote = ! tv_has_ip_voted( $author_id );
    $rating_data = tv_get_author_rating( $author_id );

    wp_send_json_success( array(
        'can_vote' => $can_vote,
        'average'  => $rating_data['average'],
        'count'    => $rating_data['count']
    ) );
}
add_action( 'wp_ajax_tv_check_can_vote', 'tv_ajax_check_can_vote' );
add_action( 'wp_ajax_nopriv_tv_check_can_vote', 'tv_ajax_check_can_vote' );

/**
 * Generate star HTML
 */
function tv_get_stars_html( $rating, $interactive = false, $size = 'md' ) {
    $full_stars = floor( $rating );
    $has_half = ( $rating - $full_stars ) >= 0.3;
    $empty_stars = 5 - $full_stars - ( $has_half ? 1 : 0 );

    $size_class = 'tv-stars--' . $size;
    $interactive_class = $interactive ? 'tv-stars--interactive' : '';

    $html = '<div class="tv-stars ' . $size_class . ' ' . $interactive_class . '" data-rating="' . $rating . '">';

    for ( $i = 1; $i <= 5; $i++ ) {
        $star_class = 'tv-star';
        if ( $i <= $full_stars ) {
            $star_class .= ' tv-star--full';
        } elseif ( $i == $full_stars + 1 && $has_half ) {
            $star_class .= ' tv-star--half';
        } else {
            $star_class .= ' tv-star--empty';
        }

        $html .= '<span class="' . $star_class . '" data-value="' . $i . '">';
        $html .= '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
        $html .= '</span>';
    }

    $html .= '</div>';

    return $html;
}
