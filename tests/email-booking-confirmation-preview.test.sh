#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
PREVIEW_FILE="$ROOT_DIR/emails/booking-confirmation-preview.html"

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

[[ -f "$PREVIEW_FILE" ]] || { echo "emails/booking-confirmation-preview.html missing" >&2; exit 1; }

assert_contains "Booking Confirmed | Toddler Gym | TJ's Gymnastics Club" "$PREVIEW_FILE"
assert_contains "Booking Date" "$PREVIEW_FILE"
assert_contains "10 April 2026" "$PREVIEW_FILE"
assert_contains "Alex Smith" "$PREVIEW_FILE"
assert_contains "Jamie Smith" "$PREVIEW_FILE"
assert_contains "Saturday" "$PREVIEW_FILE"
assert_contains "Raynes Park Sports Pavilion" "$PREVIEW_FILE"
assert_contains "data:image/png;base64," "$PREVIEW_FILE"
