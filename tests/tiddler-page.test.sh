#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/classes/tiddler.html"
CSS_FILE="$ROOT_DIR/css/class-detail.css"

contains_fixed_string() {
  local pattern="$1"
  local file="$2"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$file"
  else
    grep -Fq "$pattern" "$file"
  fi
}

assert_contains() {
  local pattern="$1"
  local file="$2"

  if ! contains_fixed_string "$pattern" "$file"; then
    echo "Expected '$file' to contain: $pattern" >&2
    exit 1
  fi
}

assert_not_contains() {
  local pattern="$1"
  local file="$2"

  if contains_fixed_string "$pattern" "$file"; then
    echo "Expected '$file' not to contain: $pattern" >&2
    exit 1
  fi
}

[[ -f "$HTML_FILE" ]] || { echo "classes/tiddler.html missing" >&2; exit 1; }
[[ -f "$CSS_FILE" ]] || { echo "css/class-detail.css missing" >&2; exit 1; }

assert_contains '<body class="class-detail-page class-detail-page--tiddler">' "$HTML_FILE"
assert_not_contains 'cd-term-selector' "$HTML_FILE"
assert_not_contains 'id="term-summer"' "$HTML_FILE"
assert_not_contains 'id="term-winter"' "$HTML_FILE"
assert_contains '<div class="cd-hero-actions">' "$HTML_FILE"
assert_contains '<a href="#book" class="btn btn-magenta">Book Now</a>' "$HTML_FILE"

assert_contains '<section class="cd-booking-term-current" aria-label="Current term details">' "$HTML_FILE"
assert_contains 'Times, Prices and <em>Term Dates</em>' "$HTML_FILE"
assert_contains '<span class="section-label">Current Term</span>' "$HTML_FILE"
assert_contains '<article class="cd-term-card cd-term-card--static cd-term-card--current">' "$HTML_FILE"
assert_contains 'Summer 2026' "$HTML_FILE"
assert_contains 'Teaching now' "$HTML_FILE"
assert_contains 'Payment due by 12 March' "$HTML_FILE"

assert_contains '<section class="cd-booking-term-upcoming" aria-label="Upcoming term details">' "$HTML_FILE"
assert_contains '<h2><em>Next</em> Terms</h2>' "$HTML_FILE"
assert_contains '<div class="cd-booking-term-grid">' "$HTML_FILE"
assert_contains '<article class="cd-term-card cd-term-card--static cd-term-card--future">' "$HTML_FILE"
assert_contains 'Winter 2026' "$HTML_FILE"
assert_contains 'Spring 2027' "$HTML_FILE"
assert_contains 'Payment due by 26 June' "$HTML_FILE"
assert_contains 'Payment due by 27 November' "$HTML_FILE"

assert_contains 'aria-label="Summer 2026 Tiddler Gym sessions"' "$HTML_FILE"
assert_not_contains 'aria-label="Winter 2026 Tiddler Gym sessions"' "$HTML_FILE"
assert_not_contains 'Book a trial' "$HTML_FILE"

assert_contains '.cd-booking-term-current {' "$CSS_FILE"
assert_contains '.cd-booking-term-upcoming {' "$CSS_FILE"
assert_contains '.cd-term-card--current {' "$CSS_FILE"
assert_contains '.cd-booking-term-grid {' "$CSS_FILE"
assert_contains '.class-detail-page--tiddler .cd-about {' "$CSS_FILE"
assert_contains '.class-detail-page--tiddler .cd-booking {' "$CSS_FILE"
assert_contains '.class-detail-page--tiddler .cd-terms-summary {' "$CSS_FILE"
