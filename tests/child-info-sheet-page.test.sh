#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/pages/child-info-sheet.html"
CSS_FILE="$ROOT_DIR/css/child-info-sheet.css"

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

[[ -f "$FILE" ]] || { echo "child-info-sheet.html missing" >&2; exit 1; }
[[ -f "$CSS_FILE" ]] || { echo "css/child-info-sheet.css missing" >&2; exit 1; }

assert_contains 'Child Information <em>Sheet</em>' "$FILE"
assert_contains 'Download the form and email the completed copy to' "$FILE"
assert_contains 'Download Form' "$FILE"
assert_contains 'info@tjsgymclub.co.uk' "$FILE"
assert_contains 'https://www.tjsgymclub.co.uk/wp-content/uploads/2024/08/v8-Information-Sheet-TJS.docx' "$FILE"
assert_contains '<a href="child-info-sheet.html" class="nav-link active" aria-current="page">Child Info Sheet</a>' "$FILE"
