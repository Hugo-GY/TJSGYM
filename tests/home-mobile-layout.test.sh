#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/index.html"
CSS_FILE="$ROOT_DIR/css/home.css"

assert_regex() {
  local pattern="$1"
  local file="$2"

  if ! rg -UPq "$pattern" "$file"; then
    echo "Expected '$file' to match regex: $pattern" >&2
    exit 1
  fi
}

if [[ ! -f "$HTML_FILE" ]]; then
  echo "index.html missing" >&2
  exit 1
fi

if [[ ! -f "$CSS_FILE" ]]; then
  echo "css/home.css missing" >&2
  exit 1
fi

assert_regex '<div class="hero-content">' "$HTML_FILE"
assert_regex '<div class="hero-visual">' "$HTML_FILE"
assert_regex '@media \(max-width: 768px\)[\s\S]*\.hero-visual \{[\s\S]*order: 1;' "$CSS_FILE"
assert_regex '@media \(max-width: 768px\)[\s\S]*\.hero-content \{[\s\S]*order: 2;' "$CSS_FILE"
