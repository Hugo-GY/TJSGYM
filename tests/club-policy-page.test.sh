#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/club-policy.html"

if [[ ! -f "$FILE" ]]; then
  echo "club-policy.html missing" >&2
  exit 1
fi

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

assert_contains 'Club <em>Policy</em>'
assert_contains 'Club Policies and Codes of Conduct'
assert_contains 'We are fully committed to safeguarding and promoting the well-being of all our members.'
assert_contains 'Quick Club Rules'
assert_contains 'Policy Downloads'
assert_contains 'Child Safeguarding &amp; Protection Policy'
assert_contains 'Code of Conduct &amp; Club Rules For Parents &amp; Gymnasts'
assert_contains 'Coach Code Of Conduct Policy'
assert_contains 'Anti Bullying Policy'
assert_contains 'Equity Policy'
assert_not_contains 'Start here'

rule_count=$( (command -v rg >/dev/null 2>&1 && rg -c 'class="policy-rule-item"' "$FILE") || grep -c 'class="policy-rule-item"' "$FILE" )
[[ "$rule_count" -eq 9 ]] || { echo "Expected 9 policy-rule-item entries, found $rule_count"; exit 1; }

download_count=$( (command -v rg >/dev/null 2>&1 && rg -c 'class="policy-download-item"' "$FILE") || grep -c 'class="policy-download-item"' "$FILE" )
[[ "$download_count" -eq 5 ]] || { echo "Expected 5 policy-download-item entries, found $download_count"; exit 1; }
