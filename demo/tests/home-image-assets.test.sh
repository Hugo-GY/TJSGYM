#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
INDEX_FILE="$ROOT_DIR/index.html"
CLASSES_FILE="$ROOT_DIR/pages/classes.html"

assert_contains() {
  local pattern="$1"
  local file="$2"

  if ! rg -Fq "$pattern" "$file"; then
    echo "Expected '$file' to contain: $pattern" >&2
    exit 1
  fi
}

assert_file_exists() {
  local file="$1"

  if [[ ! -f "$file" ]]; then
    echo "Expected asset to exist: $file" >&2
    exit 1
  fi
}

assert_contains 'src="images/home/2026-02-23 11.49.47.jpg"' "$INDEX_FILE"
assert_contains 'src="images/home/class-card-gymnastics.jpg"' "$INDEX_FILE"
assert_contains 'src="../images/home/class-card-gymnastics.jpg"' "$CLASSES_FILE"

assert_file_exists "$ROOT_DIR/images/home/2026-02-23 11.49.47.jpg"
assert_file_exists "$ROOT_DIR/images/home/class-card-gymnastics.jpg"
