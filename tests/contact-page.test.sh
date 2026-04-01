#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/contact.html"

if [[ ! -f "$FILE" ]]; then
  echo "contact.html missing" >&2
  exit 1
fi

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

assert_contains '<section class="page-hero"'
assert_contains 'Contact <em>Us</em>'
assert_contains 'Tell us a little about your child'
assert_contains 'Parent / Carer Name'
assert_contains 'Email Address'
assert_contains 'Phone Number'
assert_contains "Child's date of birth"
assert_contains 'Class of Interest'
assert_contains 'Send Enquiry'

assert_not_contains 'Reply expectations'
assert_not_contains 'aria-label="Contact details"'
assert_not_contains 'aria-label="Map and directions"'
assert_not_contains 'Child Information Sheet'
assert_not_contains 'Download Form'
