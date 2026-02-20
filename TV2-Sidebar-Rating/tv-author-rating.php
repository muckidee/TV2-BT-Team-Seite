<?php
/**
 * TV2 Author Rating System
 * IP-based rating system for author profiles in sidebar
 */

// Get client IP
function tv_get_client_ip() {
    $ip = "";
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip;
}

// Hash IP for privacy
function tv_hash_ip($ip) {
    return hash("sha256", $ip . "tv2_rating_salt_2026");
}

// Check if IP has already voted
function tv_has_ip_voted($author_id) {
    $ip_hash = tv_hash_ip(tv_get_client_ip());
    $voted_ips = get_post_meta($author_id, "_tv_rating_ips", true);
    if (!is_array($voted_ips)) {
        $voted_ips = array();
    }
    return in_array($ip_hash, $voted_ips);
}

// Get author rating data
function tv_get_author_rating($author_id) {
    $total = get_post_meta($author_id, "_tv_rating_total", true);
    $count = get_post_meta($author_id, "_tv_rating_count", true);
    
    if (empty($count) || $count == 0) {
        $slug_hash = crc32(get_post_field("post_name", $author_id));
        $default_count = 10 + (abs($slug_hash) % 30);
        $default_avg = 4.2 + ((abs($slug_hash) % 8) / 10);
        return array(
            "average" => round($default_avg, 1),
            "count" => $default_count
        );
    }
    
    return array(
        "average" => round($total / $count, 1),
        "count" => intval($count)
    );
}

// Submit rating
function tv_submit_rating($author_id, $rating) {
    if (tv_has_ip_voted($author_id)) {
        return array("success" => false, "message" => "Sie haben bereits bewertet.");
    }
    
    $rating = max(1, min(5, intval($rating)));
    
    $total = get_post_meta($author_id, "_tv_rating_total", true);
    $count = get_post_meta($author_id, "_tv_rating_count", true);
    
    if (empty($total)) $total = 0;
    if (empty($count)) $count = 0;
    
    $total += $rating;
    $count += 1;
    
    update_post_meta($author_id, "_tv_rating_total", $total);
    update_post_meta($author_id, "_tv_rating_count", $count);
    
    $voted_ips = get_post_meta($author_id, "_tv_rating_ips", true);
    if (!is_array($voted_ips)) {
        $voted_ips = array();
    }
    $voted_ips[] = tv_hash_ip(tv_get_client_ip());
    update_post_meta($author_id, "_tv_rating_ips", $voted_ips);
    
    return array(
        "success" => true,
        "new_rating" => round($total / $count, 1),
        "new_count" => $count
    );
}

// AJAX submit rating handler
function tv_ajax_submit_rating() {
    if (!wp_verify_nonce($_POST["nonce"], "tv_rating_nonce")) {
        wp_send_json_error("Sicherheitsfehler.");
        return;
    }
    
    $author_id = intval($_POST["author_id"]);
    $rating = intval($_POST["rating"]);
    
    if ($author_id <= 0 || $rating < 1 || $rating > 5) {
        wp_send_json_error("Ungueltige Daten.");
        return;
    }
    
    $result = tv_submit_rating($author_id, $rating);
    
    if ($result["success"]) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result["message"]);
    }
}
add_action("wp_ajax_tv_submit_rating", "tv_ajax_submit_rating");
add_action("wp_ajax_nopriv_tv_submit_rating", "tv_ajax_submit_rating");

// AJAX get rating data by slug
function tv_ajax_get_rating_data() {
    $slug = isset($_GET["slug"]) ? sanitize_title($_GET["slug"]) : "";
    
    if (empty($slug)) {
        wp_send_json_error("Missing slug");
    }
    
    $author = get_page_by_path($slug, OBJECT, "team");
    
    if (!$author) {
        wp_send_json_error("Author not found");
    }
    
    $author_id = $author->ID;
    $rating_data = tv_get_author_rating($author_id);
    $has_voted = tv_has_ip_voted($author_id);
    
    wp_send_json_success(array(
        "author_id" => $author_id,
        "rating" => $rating_data["average"],
        "count" => $rating_data["count"],
        "has_voted" => $has_voted
    ));
}
add_action("wp_ajax_tv_get_rating_data", "tv_ajax_get_rating_data");
add_action("wp_ajax_nopriv_tv_get_rating_data", "tv_ajax_get_rating_data");

// TV2 Author Rating - Inline CSS
function tv_author_rating_inline_css() {
    if (!is_page()) return;
    echo "<style id=\"tv-author-rating-css\">
    .tv-author-rating-box{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);border:1px solid #dee2e6;border-radius:10px;padding:14px;margin-top:14px;text-align:center}
    .tv-author-rating-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
    .tv-author-rating-label{font-size:12px;font-weight:600;color:#1a365d}
    .tv-author-rating-score{font-size:18px;font-weight:700;color:#2b6cb0}
    .tv-author-rating-stars-display{display:flex;justify-content:center;gap:3px;margin-bottom:5px}
    .tv-author-rating-stars-display .tv-star{width:20px;height:20px;fill:#cbd5e0;transition:fill .2s ease}
    .tv-author-rating-stars-display .tv-star.filled{fill:#ecc94b}
    .tv-author-rating-count{font-size:11px;color:#718096;display:block;margin-bottom:10px}
    .tv-author-rating-interactive{border-top:1px solid #e2e8f0;padding-top:10px;margin-top:6px}
    .tv-author-rating-cta{font-size:11px;color:#4a5568;display:block;margin-bottom:6px}
    .tv-author-rating-buttons{display:flex;justify-content:center;gap:5px}
    .tv-star-btn{width:28px;height:28px;padding:0;border:none;background:transparent;cursor:pointer;transition:transform .15s ease}
    .tv-star-btn:hover{transform:scale(1.15)}
    .tv-star-btn svg{width:100%;height:100%;fill:#cbd5e0;transition:fill .15s ease}
    .tv-star-btn:hover svg,.tv-star-btn.hover svg,.tv-star-btn.selected svg{fill:#ecc94b}
    .tv-author-rating-status{font-size:11px;margin:6px 0 0;min-height:16px;color:#38a169;font-weight:500}
    .tv-author-rating-status.error{color:#e53e3e}
    .tv-author-rating-voted{font-size:11px;color:#718096;font-style:italic;margin:6px 0 0}
    @media(max-width:767px){.tv-author-rating-box{padding:12px}.tv-star-btn{width:32px;height:32px}.tv-author-rating-buttons{gap:6px}}
    </style>";
}
add_action("wp_head", "tv_author_rating_inline_css");
// TV2 Author Rating - JavaScript Injection
function tv_author_rating_sidebar_script() {
    if (!is_page()) return;
    $nonce = wp_create_nonce("tv_rating_nonce");
    $ajax_url = admin_url("admin-ajax.php");
    ?>
    <script id="tv-author-rating-js">
    document.addEventListener("DOMContentLoaded", function() {
        var authorContent = document.querySelector(".author-content");
        if (!authorContent) return;
        
        var authorLink = authorContent.querySelector("a[href*='/team/']");
        if (!authorLink) return;
        
        var href = authorLink.getAttribute("href");
        var match = href.match(/\/team\/([^\/]+)/);
        if (!match) return;
        
        var authorSlug = match[1].replace(/\/$/, "");
        var ajaxUrl = "<?php echo $ajax_url; ?>";
        var nonce = "<?php echo $nonce; ?>";
        
        fetch(ajaxUrl + "?action=tv_get_rating_data&slug=" + encodeURIComponent(authorSlug) + "&nonce=" + nonce)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.success) return;
            
            var rating = data.data.rating || 0;
            var count = data.data.count || 0;
            var hasVoted = data.data.has_voted || false;
            var authorId = data.data.author_id || 0;
            
            var starPath = "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z";
            
            var starsDisplay = "";
            for (var i = 1; i <= 5; i++) {
                starsDisplay += '<svg class="tv-star ' + (i <= Math.round(rating) ? "filled" : "") + '" viewBox="0 0 24 24"><path d="' + starPath + '"/></svg>';
            }
            
            var html = '<div class="tv-author-rating-box" data-author-id="' + authorId + '">' +
                '<div class="tv-author-rating-header">' +
                '<span class="tv-author-rating-label">Autoren-Bewertung</span>' +
                '<span class="tv-author-rating-score">' + rating.toFixed(1) + '</span>' +
                '</div>' +
                '<div class="tv-author-rating-stars-display">' + starsDisplay + '</div>' +
                '<span class="tv-author-rating-count">' + count + ' Bewertung' + (count !== 1 ? "en" : "") + '</span>';
            
            if (hasVoted) {
                html += '<span class="tv-author-rating-voted">Danke für Ihre Bewertung!</span>';
            } else {
                html += '<div class="tv-author-rating-interactive">' +
                    '<span class="tv-author-rating-cta">Ihre Bewertung:</span>' +
                    '<div class="tv-author-rating-buttons">';
                for (var j = 1; j <= 5; j++) {
                    html += '<button class="tv-star-btn" data-rating="' + j + '" title="' + j + ' Stern' + (j > 1 ? "e" : "") + '"><svg viewBox="0 0 24 24"><path d="' + starPath + '"/></svg></button>';
                }
                html += '</div><div class="tv-author-rating-status"></div></div>';
            }
            html += '</div>';
            
            authorContent.insertAdjacentHTML("afterend", html);
            
            if (!hasVoted) {
                var btns = document.querySelectorAll(".tv-star-btn");
                btns.forEach(function(btn, idx) {
                    btn.addEventListener("mouseenter", function() {
                        btns.forEach(function(b, i) { b.classList.toggle("hover", i <= idx); });
                    });
                    btn.addEventListener("mouseleave", function() {
                        btns.forEach(function(b) { b.classList.remove("hover"); });
                    });
                    btn.addEventListener("click", function() {
                        var r = parseInt(this.dataset.rating);
                        var statusEl = document.querySelector(".tv-author-rating-status");
                        statusEl.textContent = "Wird gespeichert...";
                        statusEl.className = "tv-author-rating-status";
                        
                        var formData = new FormData();
                        formData.append("action", "tv_submit_rating");
                        formData.append("author_id", authorId);
                        formData.append("rating", r);
                        formData.append("nonce", nonce);
                        
                        fetch(ajaxUrl, { method: "POST", body: formData })
                        .then(function(res) { return res.json(); })
                        .then(function(res) {
                            if (res.success) {
                                statusEl.textContent = "Vielen Dank!";
                                btns.forEach(function(b, i) { b.classList.toggle("selected", i < r); b.disabled = true; });
                                document.querySelector(".tv-author-rating-score").textContent = res.data.new_rating.toFixed(1);
                                document.querySelector(".tv-author-rating-count").textContent = res.data.new_count + " Bewertung" + (res.data.new_count !== 1 ? "en" : "");
                                var starsEl = document.querySelector(".tv-author-rating-stars-display");
                                var newStars = "";
                                for (var s = 1; s <= 5; s++) {
                                    newStars += '<svg class="tv-star ' + (s <= Math.round(res.data.new_rating) ? "filled" : "") + '" viewBox="0 0 24 24"><path d="' + starPath + '"/></svg>';
                                }
                                starsEl.innerHTML = newStars;
                            } else {
                                statusEl.textContent = res.data || "Fehler";
                                statusEl.classList.add("error");
                            }
                        })
                        .catch(function() {
                            statusEl.textContent = "Netzwerkfehler";
                            statusEl.classList.add("error");
                        });
                    });
                });
            }
        });
    });
    </script>
    <?php
}
add_action("wp_footer", "tv_author_rating_sidebar_script");
