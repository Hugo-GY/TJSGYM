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

assert_regex 'Structured weekly classes with progressive skills' "$HTML_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-hero-card--tiddler \{[\s\S]*min-height: 18\.5rem;' "$CSS_FILE"
assert_regex 'cd-booking cd-booking--gymnastics section' "$HTML_FILE"
assert_regex 'cd-booking-cards-mobile' "$HTML_FILE"
assert_regex 'cd-booking-mobile-day">Monday<' "$HTML_FILE"
assert_regex 'cd-booking-mobile-label">Group<' "$HTML_FILE"
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
assert_regex '@media \(max-width: 640px\)[\s\S]*\.cd-term-selector \{[\s\S]*gap: 0\.75rem;[\s\S]*margin-bottom: var\(--space-lg\);' "$CSS_FILE"
