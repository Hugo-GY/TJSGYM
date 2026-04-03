#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/classes/gymnastics.html"
CSS_FILE="$ROOT_DIR/css/class-detail.css"
BASE_CSS=$(sed '/Responsive/q' "$CSS_FILE")

assert_regex() {
  local pattern="$1"
  local file="$2"

  if ! rg -UPq "$pattern" "$file"; then
    echo "Expected '$file' to match regex: $pattern" >&2
    exit 1
  fi
}

assert_not_regex() {
  local pattern="$1"
  local file="$2"

  if rg -UPq "$pattern" "$file"; then
    echo "Did not expect '$file' to match regex: $pattern" >&2
    exit 1
  fi
}

assert_text_regex() {
  local pattern="$1"
  local text="$2"

  if ! printf '%s' "$text" | rg -UPq "$pattern"; then
    echo "Expected CSS snippet to match regex: $pattern" >&2
    exit 1
  fi
}

if [[ ! -f "$HTML_FILE" ]]; then
  echo "classes/gymnastics.html missing" >&2
  exit 1
fi

if [[ ! -f "$CSS_FILE" ]]; then
  echo "css/class-detail.css missing" >&2
  exit 1
fi

assert_not_regex 'Floor &amp; Vault Programme' "$HTML_FILE"
assert_not_regex 'Progressive weekly classes with badge work' "$HTML_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-hero-card--tiddler \{[\s\S]*width: min\(100%, 21\.5rem\);[\s\S]*margin: 0 auto;[\s\S]*min-height: 11rem;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-hero-card--tiddler \.cd-hero-meta \{[\s\S]*padding: 0\.85rem 1rem;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-hero-card--tiddler \.cd-hero-title \{[\s\S]*font-size: clamp\(1\.65rem, 7\.5vw, 2\.35rem\);' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-hero-card--tiddler \.cd-hero-age \{[\s\S]*font-size: 0\.78rem;' "$CSS_FILE"
assert_regex 'cd-booking cd-booking--gymnastics section' "$HTML_FILE"
assert_regex 'Times, Prices and <em>Term Dates</em>' "$HTML_FILE"
assert_regex '<div class="cd-hero-actions">' "$HTML_FILE"
assert_regex '<a href="#book" class="btn btn-magenta">View Class Times</a>' "$HTML_FILE"
assert_regex 'Current Term' "$HTML_FILE"
assert_regex '<h2><em>Next</em> Terms</h2>' "$HTML_FILE"
assert_regex 'Summer 2026 Gymnastics sessions' "$HTML_FILE"
assert_regex 'Winter 2026' "$HTML_FILE"
assert_regex 'Spring 2027' "$HTML_FILE"
assert_regex 'cd-table-note cd-table-note--highlight' "$HTML_FILE"
assert_regex 'British Gymnastics membership' "$HTML_FILE"
assert_regex 'Watching Week' "$HTML_FILE"
assert_regex 'Payment due by 12 March' "$HTML_FILE"
assert_regex 'Payment due by 26 June' "$HTML_FILE"
assert_regex 'Payment due by 27 November' "$HTML_FILE"
assert_regex 'Class Full' "$HTML_FILE"
assert_regex 'Girls Elite' "$HTML_FILE"
assert_regex 'Mixed Squad' "$HTML_FILE"
assert_regex 'Monday' "$HTML_FILE"
assert_regex 'Thursday' "$HTML_FILE"
assert_regex 'cd-booking-mobile-day">Monday<' "$HTML_FILE"
assert_regex 'cd-booking-cards-mobile' "$HTML_FILE"
assert_regex 'cd-booking-mobile-label">Group<' "$HTML_FILE"
assert_not_regex 'Enquire' "$HTML_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-booking--gymnastics \.cd-booking-table \{[\s\S]*display: none;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-booking--gymnastics \.cd-booking-cards-mobile \{[\s\S]*display: flex;' "$CSS_FILE"
assert_text_regex '\.competition-content \{[\s\S]*order: 1;' "$BASE_CSS"
assert_text_regex '\.competition-content \{[\s\S]*min-width: 0;' "$BASE_CSS"
assert_text_regex '\.competition-content \{[\s\S]*max-width: 54rem;[\s\S]*margin: 0 auto;' "$BASE_CSS"
assert_text_regex '\.comp-carousel \{[\s\S]*order: 2;' "$BASE_CSS"
assert_text_regex '\.comp-carousel \{[\s\S]*max-width: 100%;' "$BASE_CSS"
assert_text_regex '\.comp-carousel \{[\s\S]*width: min\(100%, 54rem\);' "$BASE_CSS"
assert_text_regex '\.competition-inner \{[\s\S]*grid-template-columns: 1fr;' "$BASE_CSS"
assert_text_regex '\.comp-carousel-main \{[\s\S]*width: 100%;' "$BASE_CSS"
assert_text_regex '\.comp-carousel-thumbs \{[\s\S]*max-width: 100%;' "$BASE_CSS"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.competition-inner \{[\s\S]*grid-template-columns: 1fr;[\s\S]*gap: var\(--space-xl\);' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.comp-carousel \{[\s\S]*order: 2;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.comp-carousel-main \{[\s\S]*aspect-ratio: 4 / 3;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.comp-carousel-img \{[\s\S]*object-fit: contain;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-booking-header \{[\s\S]*margin-bottom: var\(--space-lg\);' "$CSS_FILE"
assert_regex '\.cd-table-note--highlight \{[\s\S]*border-left:' "$CSS_FILE"
