#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/classes.html"

contains_fixed_string() {
  local pattern="$1"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$FILE"
  else
    grep -Fq "$pattern" "$FILE"
  fi
}

if [[ ! -f "$FILE" ]]; then
  echo "classes.html missing" >&2
  exit 1
fi

assert_contains() {
  local pattern="$1"

  if ! contains_fixed_string "$pattern"; then
    echo "Expected '$FILE' to contain: $pattern" >&2
    exit 1
  fi
}

assert_contains 'Terms and Conditions on <em>Bookings</em>'
assert_contains 'Our classes are run on a termly basis.'
assert_contains 'Trial classes are only available if we have spaces in a particular class.'
assert_contains 'We cannot offer refunds or ‘make up’ classes for those missed.'
assert_contains 'March 2026'
assert_contains 'Still Choosing the <em>Right Class?</em>'
assert_contains 'From Babies and Crawling Toddlers to Confident Walkers'
assert_contains 'Visit FAQ'
assert_contains 'Contact Us'
