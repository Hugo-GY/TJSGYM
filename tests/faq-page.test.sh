#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/faq.html"
CSS_FILE="$ROOT_DIR/css/faq.css"

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

assert_not_contains() {
  local pattern="$1"
  local file="$2"

  if contains_fixed_string "$pattern" "$file"; then
    echo "Expected '$file' not to contain: $pattern" >&2
    exit 1
  fi
}

if [[ ! -f "$HTML_FILE" ]]; then
  echo "faq.html missing" >&2
  exit 1
fi

if [[ ! -f "$CSS_FILE" ]]; then
  echo "css/faq.css missing" >&2
  exit 1
fi

assert_contains '<body class="page-faq">' "$HTML_FILE"
assert_contains '<footer class="site-footer">' "$HTML_FILE"
assert_contains '<div class="tc-panel faq-panel" id="faq-4" hidden>' "$HTML_FILE"

assert_not_contains 'position: fixed;' "$CSS_FILE"
assert_not_contains 'padding-bottom: 22rem;' "$CSS_FILE"
