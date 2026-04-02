#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

FILES=(
  "$ROOT_DIR/index.html"
  "$ROOT_DIR/pages/classes.html"
  "$ROOT_DIR/pages/timetable.html"
  "$ROOT_DIR/pages/contact.html"
  "$ROOT_DIR/pages/faq.html"
  "$ROOT_DIR/pages/news.html"
  "$ROOT_DIR/pages/club-kit.html"
  "$ROOT_DIR/pages/club-policy.html"
  "$ROOT_DIR/pages/coaches.html"
  "$ROOT_DIR/pages/history.html"
  "$ROOT_DIR/pages/privacy-policy.html"
  "$ROOT_DIR/classes/tiddler.html"
  "$ROOT_DIR/classes/toddler.html"
  "$ROOT_DIR/classes/mini-gym.html"
  "$ROOT_DIR/classes/gymnastics.html"
  "$ROOT_DIR/classes/toddler-booking.html"
  "$ROOT_DIR/classes/toddler-booking-confirmation.html"
  "$ROOT_DIR/news/congratulations.html"
  "$ROOT_DIR/news/parents-guidance.html"
  "$ROOT_DIR/news/summer-2026.html"
)

for file in "${FILES[@]}"; do
  [[ -f "$file" ]] || { echo "Missing expected file: $file" >&2; exit 1; }

  if ! rg -Fq '>Classes Info<' "$file"; then
    echo "Expected navigation label 'Classes Info' in $file" >&2
    exit 1
  fi

  if ! rg -UPq 'News</a>[\s\S]*Child Info Sheet</a>[\s\S]*Contact Us</a>' "$file"; then
    echo "Expected navigation order 'News -> Child Info Sheet -> Contact Us' in $file" >&2
    exit 1
  fi

  if ! rg -Fq 'child-info-sheet.html' "$file"; then
    echo "Expected Child Info Sheet navigation link in $file" >&2
    exit 1
  fi
done
