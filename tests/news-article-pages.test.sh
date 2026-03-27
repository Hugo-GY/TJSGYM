#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

assert_contains() {
  local file="$1"
  local pattern="$2"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$file" || {
      echo "Expected '$file' to contain: $pattern" >&2
      exit 1
    }
  else
    grep -Fq "$pattern" "$file" || {
      echo "Expected '$file' to contain: $pattern" >&2
      exit 1
    }
  fi
}

CONGRATS_FILE="$ROOT_DIR/news/congratulations.html"
SUMMER_FILE="$ROOT_DIR/news/summer-2026.html"
GUIDANCE_FILE="$ROOT_DIR/news/parents-guidance.html"

for file in "$CONGRATS_FILE" "$SUMMER_FILE" "$GUIDANCE_FILE"; do
  [[ -f "$file" ]] || {
    echo "Missing article page: $file" >&2
    exit 1
  }

  assert_contains "$file" 'Back to News'
  assert_contains "$file" '../news.html'
  assert_contains "$file" '../css/news-article.css'
done

assert_contains "$CONGRATS_FILE" 'Congratulations!'
assert_contains "$SUMMER_FILE" 'Summer <em>2026</em>'
assert_contains "$GUIDANCE_FILE" 'Parents Guidance for <em>Re-opening</em>'
