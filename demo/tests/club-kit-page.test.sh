#!/bin/sh

set -eu

ROOT_DIR=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)

assert_file() {
  if [ ! -f "$1" ]; then
    echo "Expected file to exist: $1" >&2
    exit 1
  fi
}

assert_contains() {
  file="$1"
  pattern="$2"

  if ! rg -Fq "$pattern" "$file"; then
    echo "Expected '$file' to contain: $pattern" >&2
    exit 1
  fi
}

assert_file "$ROOT_DIR/pages/club-kit.html"
assert_file "$ROOT_DIR/css/club-kit.css"

assert_contains "$ROOT_DIR/pages/club-kit.html" "TJ’s Gym Club <em>Kit</em>"
assert_contains "$ROOT_DIR/pages/club-kit.html" '../images/club-kit/leotard.png'
assert_contains "$ROOT_DIR/pages/club-kit.html" '../images/club-kit/shorts.jpg'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'available to buy directly through the club'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'Please speak to a member of staff'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'href="https://tjs.printmykit.co.uk/"'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'online@printmykit.co.uk'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'Club Shop'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'Print My Kit'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'Additional TJ’s branded clothing and accessories are available online through our Print My Kit shop.'
assert_contains "$ROOT_DIR/pages/club-kit.html" 'All 16 current pieces from the'
assert_contains "$ROOT_DIR/pages/club-kit.html" "TJ's Sweatshirt - Kids"
assert_contains "$ROOT_DIR/pages/club-kit.html" "TJ's Girls Shorts"
assert_contains "$ROOT_DIR/pages/club-kit.html" "TJ's Thermal Bottle"
assert_contains "$ROOT_DIR/pages/club-kit.html" "TJ's Teddy Bear"
assert_contains "$ROOT_DIR/pages/club-kit.html" 'id="kit-support"'
if rg -Fq 'Ordering help' "$ROOT_DIR/pages/club-kit.html"; then
  echo "Did not expect Ordering help link to remain" >&2
  exit 1
fi
if rg -Fq 'Full Range' "$ROOT_DIR/pages/club-kit.html"; then
  echo "Did not expect Full Range card to remain" >&2
  exit 1
fi
CARD_COUNT=$(rg -o 'class="kit-product-card"' "$ROOT_DIR/pages/club-kit.html" | wc -l | tr -d ' ')
if [ "$CARD_COUNT" -ne 16 ]; then
  echo "Expected 16 product cards, found $CARD_COUNT" >&2
  exit 1
fi
assert_contains "$ROOT_DIR/pages/club-kit.html" '<a href="club-kit.html"    class="nav-link active">Club Kit</a>'
assert_contains "$ROOT_DIR/index.html" '<a href="pages/club-kit.html"    class="nav-link">Club Kit</a>'
