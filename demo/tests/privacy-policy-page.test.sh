#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/pages/privacy-policy.html"

contains_fixed_string() {
  local pattern="$1"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$FILE"
  else
    grep -Fq "$pattern" "$FILE"
  fi
}

if [[ ! -f "$FILE" ]]; then
  echo "privacy-policy.html missing" >&2
  exit 1
fi

assert_contains() {
  local pattern="$1"

  if ! contains_fixed_string "$pattern"; then
    echo "Expected '$FILE' to contain: $pattern" >&2
    exit 1
  fi
}

assert_contains 'Privacy <em>Policy</em>'
assert_contains "TJ's Gymnastics Club Privacy Policy"
assert_contains 'How we use information about you'
assert_contains 'Why we share your information'
assert_contains 'Individual rights'
assert_contains 'This privacy notice was last updated on'
assert_contains '4 Feb 2021'
