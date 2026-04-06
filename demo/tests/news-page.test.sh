#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/pages/news.html"
INDEX_FILE="$ROOT_DIR/index.html"

contains_fixed_string() {
  local pattern="$1"
  local target="$2"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$target"
  else
    grep -Fq "$pattern" "$target"
  fi
}

assert_contains() {
  local pattern="$1"
  local target="$2"

  if ! contains_fixed_string "$pattern" "$target"; then
    echo "Expected '$target' to contain: $pattern" >&2
    exit 1
  fi
}

if [[ ! -f "$FILE" ]]; then
  echo "news.html missing" >&2
  exit 1
fi

assert_contains 'Club <em>News</em>' "$FILE"
assert_contains 'Congratulations!' "$FILE"
assert_contains 'Both Natalie and Jade recently passed their Level 3, Module 1 assessment.' "$FILE"
assert_contains 'Summer 2026' "$FILE"
assert_contains 'Parents Guidance for Re-opening of TJ’s Gymnastics Club' "$FILE"
assert_contains '../news/congratulations.html' "$FILE"
assert_contains '../news/summer-2026.html' "$FILE"
assert_contains '../news/parents-guidance.html' "$FILE"
assert_contains 'Continue Reading' "$FILE"
assert_contains '<a href="pages/faq.html"         class="nav-link">FAQ</a>' "$INDEX_FILE"
assert_contains '<a href="pages/news.html"        class="nav-link">News</a>' "$INDEX_FILE"
assert_contains '<a href="pages/child-info-sheet.html" class="nav-link">Child Info Sheet</a>' "$INDEX_FILE"
assert_contains '<a href="pages/contact.html"     class="nav-link">Contact Us</a>' "$INDEX_FILE"
