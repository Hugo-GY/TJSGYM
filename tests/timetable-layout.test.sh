#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
HTML_FILE="$ROOT_DIR/pages/timetable.html"
CSS_FILE="$ROOT_DIR/css/timetable.css"

assert_regex() {
  local pattern="$1"
  local file="$2"

  if ! rg -UPq "$pattern" "$file"; then
    echo "Expected '$file' to match regex: $pattern" >&2
    exit 1
  fi
}

[[ -f "$HTML_FILE" ]] || { echo "timetable.html missing" >&2; exit 1; }
[[ -f "$CSS_FILE" ]] || { echo "css/timetable.css missing" >&2; exit 1; }

assert_regex 'Click any class name to view full details, book your place or join the <span class="timetable-nowrap">waiting list</span>\.' "$HTML_FILE"
assert_regex '\.timetable-nowrap \{[\s\S]*white-space: nowrap;' "$CSS_FILE"
assert_regex '\.tt-table \{[\s\S]*table-layout: fixed;' "$CSS_FILE"
assert_regex '\.tt-table th,\s*[\s\S]*\.tt-table td \{[\s\S]*width: 33\.333%;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*grid-template-columns: repeat\(3, 1fr\);' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.tt-table td \{[\s\S]*text-align: center;[\s\S]*width: auto;[\s\S]*min-width: 0;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.tt-table td:not\(\.td-time\) \{[\s\S]*display: flex;[\s\S]*flex-direction: column;[\s\S]*align-items: center;[\s\S]*gap: 0\.22rem;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.tt-table td\[data-label="Class"\] \{[\s\S]*align-items: center;[\s\S]*padding-left: 0;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.td-time \{[\s\S]*display: block;[\s\S]*justify-self: center;[\s\S]*align-self: center;[\s\S]*margin: 0;[\s\S]*padding: 0;[\s\S]*background: none;[\s\S]*border: 0;' "$CSS_FILE"
assert_regex '@media \(max-width: 640px\)[\s\S]*\.td-price \{[\s\S]*min-height:' "$CSS_FILE"
