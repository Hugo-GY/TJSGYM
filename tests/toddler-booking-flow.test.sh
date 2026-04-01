#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
TODDLER_FILE="$ROOT_DIR/classes/toddler.html"
BOOKING_FILE="$ROOT_DIR/classes/toddler-booking.html"
CONFIRM_FILE="$ROOT_DIR/classes/toddler-booking-confirmation.html"

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

[[ -f "$TODDLER_FILE" ]] || { echo "classes/toddler.html missing" >&2; exit 1; }
[[ -f "$BOOKING_FILE" ]] || { echo "classes/toddler-booking.html missing" >&2; exit 1; }
[[ -f "$CONFIRM_FILE" ]] || { echo "classes/toddler-booking-confirmation.html missing" >&2; exit 1; }

assert_contains 'cd-book-btn' "$TODDLER_FILE"
assert_contains 'id="book"' "$TODDLER_FILE"
assert_contains 'data-booking-link' "$TODDLER_FILE"
assert_contains 'data-booking-type="full"' "$TODDLER_FILE"
assert_contains 'Complete Your Booking' "$BOOKING_FILE"
assert_contains 'Booking Type' "$BOOKING_FILE"
assert_contains 'Full booking' "$BOOKING_FILE"
assert_contains 'Trial lesson' "$BOOKING_FILE"
assert_contains "Child's Name" "$BOOKING_FILE"
assert_contains "Child's date of birth" "$BOOKING_FILE"
assert_contains 'Parent / Carer Name' "$BOOKING_FILE"
assert_contains 'Email Address' "$BOOKING_FILE"
assert_contains 'Phone Number' "$BOOKING_FILE"
assert_contains 'Selected Session' "$BOOKING_FILE"
assert_contains 'Additional Message' "$BOOKING_FILE"
assert_contains 'Pay' "$BOOKING_FILE"
assert_contains 'Booking Request Received' "$CONFIRM_FILE"
assert_contains 'Selected Session' "$CONFIRM_FILE"
assert_contains 'data-session-field="bookingType"' "$CONFIRM_FILE"
assert_contains 'Return to Toddler Gym' "$CONFIRM_FILE"
assert_contains 'data-session-field="class"' "$BOOKING_FILE"
assert_contains 'data-session-field="bookingType"' "$BOOKING_FILE"
assert_contains 'data-submitted-field="parentName"' "$CONFIRM_FILE"
