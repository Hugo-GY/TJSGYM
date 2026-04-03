#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/classes/mini-gym.html"

contains_fixed_string() {
  local pattern="$1"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$FILE"
  else
    grep -Fq "$pattern" "$FILE"
  fi
}

assert_contains() {
  local pattern="$1"

  if ! contains_fixed_string "$pattern"; then
    echo "Expected '$FILE' to contain: $pattern" >&2
    exit 1
  fi
}

assert_not_contains() {
  local pattern="$1"

  if contains_fixed_string "$pattern"; then
    echo "Expected '$FILE' not to contain: $pattern" >&2
    exit 1
  fi
}

if [[ ! -f "$FILE" ]]; then
  echo "classes/mini-gym.html missing" >&2
  exit 1
fi

assert_contains 'Mini Gym — Ages 3–4 Years'
assert_contains 'cd-hero-card--imageless'
assert_contains 'Mini Gym'
assert_contains '3–4 Years'
assert_contains '<div class="cd-hero-actions">'
assert_contains '<a href="#book" class="btn btn-magenta">Book Now</a>'
assert_contains 'cd-about cd-about--single'
assert_contains 'Book a Place'
assert_contains 'cd-booking cd-booking--tiddler section'
assert_contains 'cd-booking-cards-mobile'
assert_contains 'cd-table-note cd-table-note--highlight'
assert_contains 'British Gymnastics membership required.'
assert_contains 'Watching Week:'
assert_contains 'Current Term'
assert_contains 'Summer 2026'
assert_contains 'Teaching now'
assert_contains '<h2><em>Next</em> Terms</h2>'
assert_contains 'Winter 2026'
assert_contains 'Spring 2027'
assert_contains 'Payment due by 12 March'
assert_contains 'Payment due by 26 June'
assert_contains 'Payment due by 27 November'
assert_contains 'aria-label="Summer 2026 Mini Gym sessions"'
assert_not_contains 'cd-term-selector'
assert_not_contains 'id="term-summer"'
assert_not_contains 'id="term-winter"'
assert_not_contains 'Book a trial'
assert_contains 'Monday'
assert_contains '1:20 – 2:00'
assert_contains '2:10 – 2:50'
assert_contains '£168 / term'
assert_contains 'Mini Gym trial classes are only available in the first two weeks of each term.'
assert_contains 'Online booking is open during this time only.'
assert_contains 'Please contact us for more information.'
assert_contains 'Gallery'
assert_contains 'Photos</em> of our Mini Gym'
assert_contains '../images/classes/mini-gym/kids-mini-gym-6.jpg'
assert_contains '../images/classes/mini-gym/kids-mini-gym-1.jpg'
assert_contains '1 / 6'
