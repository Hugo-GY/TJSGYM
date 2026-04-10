#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/classes/toddler.html"

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

[[ -f "$HTML_FILE" ]] || { echo "classes/toddler.html missing" >&2; exit 1; }

assert_not_contains 'cd-term-selector' "$HTML_FILE"
assert_not_contains 'id="term-summer"' "$HTML_FILE"
assert_not_contains 'id="term-winter"' "$HTML_FILE"
assert_contains '<div class="cd-hero-actions">' "$HTML_FILE"
assert_contains '<a href="#book" class="btn btn-magenta">Book Now</a>' "$HTML_FILE"

assert_contains 'Times, Prices and <em>Term Dates</em>' "$HTML_FILE"
assert_contains '<section class="cd-booking-term-current" aria-label="Current term details">' "$HTML_FILE"
assert_contains 'Current Term' "$HTML_FILE"
assert_contains 'Summer 2026' "$HTML_FILE"
assert_contains 'Teaching now' "$HTML_FILE"
assert_contains 'Payment due by 12 March' "$HTML_FILE"

assert_contains '<section class="cd-booking-term-upcoming" aria-label="Upcoming term details">' "$HTML_FILE"
assert_contains '<h2><em>Next</em> Terms</h2>' "$HTML_FILE"
assert_contains 'Winter 2026' "$HTML_FILE"
assert_contains 'Spring 2027' "$HTML_FILE"
assert_contains 'Payment due by 26 June' "$HTML_FILE"
assert_contains 'Payment due by 27 November' "$HTML_FILE"

assert_contains 'aria-label="Summer 2026 Toddler Gym sessions"' "$HTML_FILE"
assert_not_contains 'aria-label="Winter 2026 Toddler Gym sessions"' "$HTML_FILE"
assert_not_contains 'Book a trial' "$HTML_FILE"
assert_contains 'Join the Waiting List' "$HTML_FILE"
assert_contains '../pages/contact.html' "$HTML_FILE"
assert_contains 'comp-carousel-img comp-carousel-img--toddler-second" src="../images/classes/toddler/2022-08-22 20.23.45.png"' "$HTML_FILE"
assert_contains 'comp-carousel-img comp-carousel-img--toddler-sixth" src="../images/classes/toddler/92 July 10.png"' "$HTML_FILE"
