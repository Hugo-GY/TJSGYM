#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/pages/coaches.html"

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

[[ -f "$FILE" ]] || { echo "coaches.html missing" >&2; exit 1; }

assert_contains "Hannah Pettit"
assert_contains "assists our Mini Gym classes"
assert_not_contains "leads our Mini Gym classes"
assert_contains "Nadine Henry"
assert_not_contains "Natalie and Kate as a child"
assert_contains "She is a qualified physio therapist and holds the Level-1 Assistant qualification"
assert_contains "JV Mattei"
assert_contains "He holds gymnastics instructor qualification."
assert_not_contains "Beth Drew"
