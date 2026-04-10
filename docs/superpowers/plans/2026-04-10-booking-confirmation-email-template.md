# Booking Confirmation Email Template Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a standalone HTML email template for TJSGYM booking confirmations that follows the approved contact-page-inspired design and includes explicit placeholder variables for later integration.

**Architecture:** Keep the email template isolated from the website page system. Create a self-contained HTML file under a new `emails/` directory, use table-based markup with inline styles only, reference the shared logo asset, and avoid any dependency on site CSS, JS, or page layout components.

**Tech Stack:** HTML email markup, inline CSS, shell-based smoke checks with `bash`, `rg`, and `python3 -m http.server` for local preview if needed

---

## File Structure

- `emails/booking-confirmation.html`
  Responsibility: standalone production-oriented HTML email template with inline styles, placeholder variables, branded header, booking details, submitted details, and footer.
- `tests/email-booking-confirmation.test.sh`
  Responsibility: smoke-test the presence of the template file, required placeholders, and required content sections so regressions are caught quickly.
- `docs/superpowers/specs/2026-04-10-booking-confirmation-email-design.md`
  Responsibility: approved design reference for implementation.

## Placeholder Contract

The template must include these placeholders exactly:

- `{{email_subject}}`
- `{{parent_name}}`
- `{{child_name}}`
- `{{child_dob}}`
- `{{class_name}}`
- `{{booking_type}}`
- `{{term}}`
- `{{session_day}}`
- `{{session_time}}`
- `{{venue_name}}`
- `{{price}}`
- `{{email}}`
- `{{phone}}`
- `{{message}}`
- `{{booking_reference}}`

### Task 1: Add a Failing Email Template Smoke Test

**Files:**
- Create: `tests/email-booking-confirmation.test.sh`
- Reference: `docs/superpowers/specs/2026-04-10-booking-confirmation-email-design.md`

- [ ] **Step 1: Write the failing test**

```bash
#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
EMAIL_FILE="$ROOT_DIR/emails/booking-confirmation.html"

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

[[ -f "$EMAIL_FILE" ]] || { echo "emails/booking-confirmation.html missing" >&2; exit 1; }

assert_contains "Booking Confirmed" "$EMAIL_FILE"
assert_contains "Your Booking Is Confirmed" "$EMAIL_FILE"
assert_contains "Booking Details" "$EMAIL_FILE"
assert_contains "Your Details" "$EMAIL_FILE"
assert_contains "TJ's Gymnastics Club" "$EMAIL_FILE"
assert_contains "{{email_subject}}" "$EMAIL_FILE"
assert_contains "{{class_name}}" "$EMAIL_FILE"
assert_contains "{{booking_type}}" "$EMAIL_FILE"
assert_contains "{{term}}" "$EMAIL_FILE"
assert_contains "{{session_day}}" "$EMAIL_FILE"
assert_contains "{{session_time}}" "$EMAIL_FILE"
assert_contains "{{venue_name}}" "$EMAIL_FILE"
assert_contains "{{price}}" "$EMAIL_FILE"
assert_contains "{{booking_reference}}" "$EMAIL_FILE"
assert_contains "{{parent_name}}" "$EMAIL_FILE"
assert_contains "{{child_name}}" "$EMAIL_FILE"
assert_contains "{{child_dob}}" "$EMAIL_FILE"
assert_contains "{{email}}" "$EMAIL_FILE"
assert_contains "{{phone}}" "$EMAIL_FILE"
assert_contains "{{message}}" "$EMAIL_FILE"
assert_contains "inline-block" "$EMAIL_FILE"
assert_contains "table" "$EMAIL_FILE"
```

- [ ] **Step 2: Make the test executable**

Run: `chmod +x /Users/sharktopia/Desktop/TJS_V3/tests/email-booking-confirmation.test.sh`
Expected: no output

- [ ] **Step 3: Run test to verify it fails**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/email-booking-confirmation.test.sh`
Expected: FAIL with `emails/booking-confirmation.html missing`

- [ ] **Step 4: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/email-booking-confirmation.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "test: add booking confirmation email smoke test"
```

### Task 2: Build the Standalone Email Template

**Files:**
- Create: `emails/booking-confirmation.html`
- Reference: `pages/contact.html`
- Reference: `classes/toddler-booking-confirmation.html`
- Reference: `images/shared/logo.png`
- Test: `tests/email-booking-confirmation.test.sh`

- [ ] **Step 1: Create the email shell**

Use a complete HTML document with:

- `<!DOCTYPE html>`
- `<html lang="en">`
- email-friendly `<meta charset>` and viewport
- `<title>{{email_subject}}</title>`
- `<body>` with a full-width outer background color table

Do not link `global.css`, `components.css`, `contact.css`, or any other stylesheet.

- [ ] **Step 2: Build the outer layout and brand header**

Use a centered max-width wrapper table and add:

```html
<img src="../images/shared/logo.png" alt="TJ's Gymnastics Club" width="120">
```

Include the approved content:

- eyebrow: `Booking Confirmed`
- headline: `Your Booking Is Confirmed`
- support paragraph
- records note

Use inline styles to echo the contact-page palette with plum/magenta accents and generous spacing.

- [ ] **Step 3: Build the main confirmation card**

Create a white card-style section using an inner table with:

- rounded-corner-friendly structure that still degrades acceptably in restrictive clients
- subtle border or top accent
- brief confirmation copy

Keep the wording aligned with the approved design spec:

```html
Thank you for booking with TJ's Gymnastics Club. Your place has been confirmed and the details of your booking are included below.
```

- [ ] **Step 4: Add the Booking Details section**

Create a section titled `Booking Details` and render the booking values in clear label/value rows.

Include these rows:

```html
Class: {{class_name}}
Booking Type: {{booking_type}}
Term: {{term}}
Day: {{session_day}}
Time: {{session_time}}
Venue: {{venue_name}}
Price: {{price}}
Booking Reference: {{booking_reference}}
```

Prefer a stacked mobile-safe row pattern using nested tables instead of multi-column CSS grids.

- [ ] **Step 5: Add the Your Details section**

Create a second details card titled `Your Details` with:

```html
Parent / Carer Name: {{parent_name}}
Child's Name: {{child_name}}
Child's Date of Birth: {{child_dob}}
Email Address: {{email}}
Phone Number: {{phone}}
Additional Message: {{message}}
```

Make the message row visually distinct enough that longer content still reads cleanly.

- [ ] **Step 6: Add the footer**

Include:

- `TJ's Gymnastics Club`
- `Building confidence through gymnastics since 1988.`
- `Raynes Park Sports Pavilion`
- `Taunton Avenue`
- `London SW20 0BH`
- `01252 702295`
- `07885 103080`
- `info@tjsgymclub.co.uk`

Do not include navigation links or CTA buttons.

- [ ] **Step 7: Add local preview sample values in HTML comments**

At the top or bottom of the file, include a short HTML comment block showing sample replacements for manual preview, for example:

```html
<!--
Sample data:
{{class_name}} = Toddler Gym
{{session_day}} = Saturday
{{session_time}} = 09:40-10:20
-->
```

This keeps integration intent obvious without affecting rendering.

- [ ] **Step 8: Run the smoke test**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/email-booking-confirmation.test.sh`
Expected: PASS with no output

- [ ] **Step 9: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add emails/booking-confirmation.html tests/email-booking-confirmation.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: add booking confirmation email template"
```

### Task 3: Verify Local Readability

**Files:**
- Reference: `emails/booking-confirmation.html`

- [ ] **Step 1: Serve the project locally**

Run: `python3 -m http.server 8000`
Expected: `Serving HTTP on ... port 8000`

- [ ] **Step 2: Open the email file in a browser**

Open:

- `http://127.0.0.1:8000/Desktop/TJS_V3/emails/booking-confirmation.html`

Verify:

- logo loads
- vertical spacing is balanced
- booking/details sections are easy to scan
- footer content is readable

- [ ] **Step 3: Check a narrow mobile width**

Verify at a narrow viewport that:

- no content overflows horizontally
- label/value rows remain legible
- long values such as `{{message}}` wrap cleanly

- [ ] **Step 4: Commit if any verification-only polish was needed**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add emails/booking-confirmation.html
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "style: polish booking confirmation email layout"
```
