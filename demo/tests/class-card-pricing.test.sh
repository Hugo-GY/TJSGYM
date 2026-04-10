#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HOME_FILE="$ROOT_DIR/index.html"
CLASSES_FILE="$ROOT_DIR/pages/classes.html"

assert_contains() {
  local pattern="$1"
  local file="$2"

  if ! rg -Fq "$pattern" "$file"; then
    echo "Expected '$file' to contain: $pattern" >&2
    exit 1
  fi
}

assert_contains '£10' "$HOME_FILE"
assert_contains 'per class' "$HOME_FILE"
assert_contains 'Pay as you go' "$HOME_FILE"

assert_contains '£169' "$HOME_FILE"
assert_contains '£182' "$HOME_FILE"
assert_contains 'From £195' "$HOME_FILE"
assert_contains '13 weeks' "$HOME_FILE"

assert_contains '£10' "$CLASSES_FILE"
assert_contains 'per class' "$CLASSES_FILE"
assert_contains 'Pay as you go' "$CLASSES_FILE"

assert_contains '£169' "$CLASSES_FILE"
assert_contains '£182' "$CLASSES_FILE"
assert_contains 'From £195' "$CLASSES_FILE"
assert_contains '13 weeks' "$CLASSES_FILE"
