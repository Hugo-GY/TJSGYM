#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
TIDDLER_FILE="$ROOT_DIR/classes/tiddler.html"
TODDLER_FILE="$ROOT_DIR/classes/toddler.html"
MINI_FILE="$ROOT_DIR/classes/mini-gym.html"
GYM_FILE="$ROOT_DIR/classes/gymnastics.html"
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

first_line_number() {
  local pattern="$1"
  local file="$2"

  if command -v rg >/dev/null 2>&1; then
    rg -n -m 1 "$pattern" "$file" | cut -d: -f1
  else
    grep -n -m 1 "$pattern" "$file" | cut -d: -f1
  fi
}

assert_terms_between_booking_and_gallery() {
  local file="$1"
  local booking_line
  local terms_line
  local gallery_line

  booking_line=$(first_line_number '<section class="cd-booking ' "$file")
  terms_line=$(first_line_number '<section class="cd-terms-summary"' "$file")
  gallery_line=$(first_line_number '<section class="cd-gallery ' "$file")

  if [[ -z "$booking_line" || -z "$terms_line" || -z "$gallery_line" ]]; then
    echo "Expected booking, terms, and gallery sections in $file" >&2
    exit 1
  fi

  if (( terms_line <= booking_line || terms_line >= gallery_line )); then
    echo "Expected terms section in $file to appear after booking and before gallery" >&2
    exit 1
  fi
}

[[ -f "$TIDDLER_FILE" ]] || { echo "classes/tiddler.html missing" >&2; exit 1; }
[[ -f "$TODDLER_FILE" ]] || { echo "classes/toddler.html missing" >&2; exit 1; }
[[ -f "$MINI_FILE" ]] || { echo "classes/mini-gym.html missing" >&2; exit 1; }
[[ -f "$GYM_FILE" ]] || { echo "classes/gymnastics.html missing" >&2; exit 1; }
[[ -f "$CSS_FILE" ]] || { echo "css/class-detail.css missing" >&2; exit 1; }

for file in "$TIDDLER_FILE" "$TODDLER_FILE" "$MINI_FILE"; do
  assert_contains 'cd-terms-summary' "$file"
  assert_contains 'Terms and Conditions on <em>Bookings</em>' "$file"
  assert_contains 'March 2026' "$file"
  assert_terms_between_booking_and_gallery "$file"
done

assert_not_contains 'cd-terms-summary' "$GYM_FILE"
assert_contains '.cd-terms-summary {' "$CSS_FILE"
