# BT Sidebar Author Rating

Autoren-Bewertung in der linken Sidebar der Ratgeberseiten auf beste-testsieger.de

## Funktionen
- Anzeige der aktuellen Durchschnittsbewertung (Sterne + Zahl)
- Anzahl der Bewertungen
- Interaktive Sterne zum Abgeben einer Bewertung
- AJAX-basierte Speicherung
- IP-Duplikat-Schutz
- Responsive Design (Mobile/Tablet/Desktop)

## Geänderte Dateien
- `page.php` - Rating-Box nach Autoren-Bio eingefügt (Zeile 140)
- `page-slim.php` - Rating-Box nach Autoren-Bio eingefügt (Zeile 130)
- `functions.php` - Inline-CSS + JavaScript hinzugefügt

## Abhängigkeiten
- `inc/bt-author-rating.php` - AJAX-Handler für Rating-Speicherung

## Installation
1. Code aus page.php nach Zeile mit `author-content` einfügen
2. Inline-CSS und JavaScript aus functions.php kopieren
3. Sicherstellen dass bt-author-rating.php geladen wird

## Datum
2026-02-20
