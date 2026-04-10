#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
EMAIL_FILE="$ROOT_DIR/emails/booking-confirmation.html"

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

[[ -f "$EMAIL_FILE" ]] || { echo "emails/booking-confirmation.html missing" >&2; exit 1; }

assert_contains "Booking Confirmed" "$EMAIL_FILE"
assert_contains "Your Booking Is Confirmed" "$EMAIL_FILE"
assert_contains "Booking Details" "$EMAIL_FILE"
assert_contains "Your Details" "$EMAIL_FILE"
assert_contains "TJ's Gymnastics Club" "$EMAIL_FILE"
assert_contains "{{email_subject}}" "$EMAIL_FILE"
assert_contains "{{class_name}}" "$EMAIL_FILE"
assert_contains "{{booking_type}}" "$EMAIL_FILE"
assert_contains "{{term}}" "$EMAIL_FILE"
assert_contains "{{session_day}}" "$EMAIL_FILE"
assert_contains "{{session_time}}" "$EMAIL_FILE"
assert_contains "{{venue_name}}" "$EMAIL_FILE"
assert_contains "{{price}}" "$EMAIL_FILE"
assert_contains "{{booking_reference}}" "$EMAIL_FILE"
assert_contains "{{parent_name}}" "$EMAIL_FILE"
assert_contains "{{child_name}}" "$EMAIL_FILE"
assert_contains "{{child_dob}}" "$EMAIL_FILE"
assert_contains "{{email}}" "$EMAIL_FILE"
assert_contains "{{phone}}" "$EMAIL_FILE"
assert_contains "{{message}}" "$EMAIL_FILE"
assert_contains "inline-block" "$EMAIL_FILE"
assert_contains "table" "$EMAIL_FILE"
