#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/pages/history.html"

contains_fixed_string() {
  local pattern="$1"

  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$FILE"
  else
    grep -Fq "$pattern" "$FILE"
  fi
}

if [[ ! -f "$FILE" ]]; then
  echo "history.html missing" >&2
  exit 1
fi

assert_contains() {
  local pattern="$1"

  if ! contains_fixed_string "$pattern"; then
    echo "Expected '$FILE' to contain: $pattern" >&2
    exit 1
  fi
}

assert_contains 'TJ’s opens'
assert_contains 'Growth years'
assert_contains '1997'
assert_contains 'Natalie joins the team'
assert_contains '1997–2005'
assert_contains 'Natalie married Paul in 2004'
assert_contains 'All four children attended TJ’s'
assert_contains 'At the same time, Jade joined the senior coaching team'
assert_contains 'bring their own children to TJ’s'
assert_contains "Gill at TJ's 30yrs anniversary celebrations."
assert_contains "../images/history/Jack!.JPG"
assert_contains "../images/history/Cara.jpg"
assert_contains "../images/history/mums.jpg"
assert_contains "Jack reminiscing of his younger days at TJ's!"
assert_contains "Cara couldn't resist trying out the new equipment"
assert_contains "Ex gymnasts and coaches, now Mums, enjoying a TJ's class with their Toddlers"
