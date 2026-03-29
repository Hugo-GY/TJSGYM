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

if [[ ! -f "$FILE" ]]; then
  echo "classes/mini-gym.html missing" >&2
  exit 1
fi

assert_contains 'Mini Gym — Ages 3–4 Years'
assert_contains 'cd-hero-card--imageless'
assert_contains 'Mini Gym'
assert_contains '3–4 Years'
assert_contains 'cd-about cd-about--single'
assert_contains 'Book a Place'
assert_contains 'cd-booking cd-booking--tiddler section'
assert_contains 'cd-booking-cards-mobile'
assert_contains 'Tuesday'
assert_contains 'Wednesday'
assert_contains 'Thursday'
assert_contains '1:20 – 2:00'
assert_contains '2:10 – 2:50'
assert_contains '£182 / term'
assert_contains 'Gallery'
assert_contains 'Photos</em> of our Mini Gym'
assert_contains '../images/classes/mini-gym/kids-mini-gym-6.jpg'
assert_contains '../images/classes/mini-gym/kids-mini-gym-1.jpg'
assert_contains '1 / 6'
