# Toddler Booking Flow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a two-page `Toddler Gym` booking prototype that starts from `classes/toddler.html`, passes the selected session into a dedicated booking form page, and then transitions to a confirmation page without using a real payment backend.

**Architecture:** Keep the booking flow inside the existing static-site structure. Use URL query parameters for selected session data, `sessionStorage` for entered form values, reuse the existing shared JS file for all behavior, and keep page-specific styling inside `css/class-detail.css` so the booking flow remains visually aligned with the current class-detail system.

**Tech Stack:** HTML5, CSS3, vanilla JS in `js/main.js`, shell smoke tests with `bash` and `rg`

---

## File Structure

- `classes/toddler.html`
  Responsibility: remain the booking entry point and provide session-specific booking links for each active desktop and mobile `Book now` action.
- `classes/toddler-booking.html`
  Responsibility: render the booking form page shell, selected-session summary placeholders, booking form fields, and `Pay` action.
- `classes/toddler-booking-confirmation.html`
  Responsibility: render the confirmation shell, selected-session summary placeholders, submitted-details placeholders, and return actions.
- `css/class-detail.css`
  Responsibility: booking page, confirmation page, summary card, fallback state, and responsive layout styling consistent with the existing class-detail visual system.
- `js/main.js`
  Responsibility: generate booking URLs on the toddler page, read URL parameters on booking pages, store form data in `sessionStorage`, and hydrate the confirmation page.
- `tests/toddler-booking-flow.test.sh`
  Responsibility: smoke-test the new two-page flow markup and the key handoff hooks so regressions are caught quickly.
- `docs/superpowers/specs/2026-03-30-toddler-booking-flow-design.md`
  Responsibility: approved design reference for implementation.

## Data Contract

- Query parameter names:
  - `class`
  - `term`
  - `day`
  - `time`
  - `price`
  - `availability`
- `sessionStorage` key:
  - `toddlerBookingForm`
- Confirmation page echoed fields:
  - `childName`
  - `childAge`
  - `parentName`
  - `email`
  - `phone`
  - `message`

### Task 1: Add a Failing Booking Flow Smoke Test

**Files:**
- Create: `tests/toddler-booking-flow.test.sh`
- Reference: `docs/superpowers/specs/2026-03-30-toddler-booking-flow-design.md`

- [ ] **Step 1: Write the failing test**

```bash
#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
TODDLER_FILE="$ROOT_DIR/classes/toddler.html"
BOOKING_FILE="$ROOT_DIR/classes/toddler-booking.html"
CONFIRM_FILE="$ROOT_DIR/classes/toddler-booking-confirmation.html"

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

[[ -f "$TODDLER_FILE" ]] || { echo "classes/toddler.html missing" >&2; exit 1; }
[[ -f "$BOOKING_FILE" ]] || { echo "classes/toddler-booking.html missing" >&2; exit 1; }
[[ -f "$CONFIRM_FILE" ]] || { echo "classes/toddler-booking-confirmation.html missing" >&2; exit 1; }

assert_contains 'cd-book-btn' "$TODDLER_FILE"
assert_contains 'id="book"' "$TODDLER_FILE"
assert_contains 'Complete Your Booking' "$BOOKING_FILE"
assert_contains "Child's Name" "$BOOKING_FILE"
assert_contains "Child's Age" "$BOOKING_FILE"
assert_contains 'Parent / Carer Name' "$BOOKING_FILE"
assert_contains 'Email Address' "$BOOKING_FILE"
assert_contains 'Phone Number' "$BOOKING_FILE"
assert_contains 'Selected Session' "$BOOKING_FILE"
assert_contains 'Additional Message' "$BOOKING_FILE"
assert_contains 'Pay' "$BOOKING_FILE"
assert_contains 'Booking Request Received' "$CONFIRM_FILE"
assert_contains 'Selected Session' "$CONFIRM_FILE"
assert_contains 'Return to Toddler Gym' "$CONFIRM_FILE"

# JS contract hooks
assert_contains 'data-booking-link' "$TODDLER_FILE"
assert_contains 'data-session-field="class"' "$BOOKING_FILE"
assert_contains 'data-submitted-field="parentName"' "$CONFIRM_FILE"
```

- [ ] **Step 2: Make the test executable**

Run: `chmod +x /Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: no output

- [ ] **Step 3: Run test to verify it fails**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: FAIL with `classes/toddler-booking.html missing`

- [ ] **Step 4: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/toddler-booking-flow.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "test: add toddler booking flow smoke test"
```

### Task 2: Build the Booking and Confirmation Markup Shells

**Files:**
- Create: `classes/toddler-booking.html`
- Create: `classes/toddler-booking-confirmation.html`
- Reference: `classes/toddler.html`
- Reference: `contact.html`
- Test: `tests/toddler-booking-flow.test.sh`

- [ ] **Step 1: Copy the shared page shell into the booking page**

Use `classes/toddler.html` as the base for:
- `<head>` metadata structure
- shared header markup
- shared footer markup
- `../css/global.css`, `../css/components.css`, `../css/class-detail.css`
- `../js/main.js`

Update page-specific values:
- title
- meta description
- active nav state remains `Classes`

- [ ] **Step 2: Add the booking page content hierarchy**

Inside `<main>`, add:

```html
<section class="cd-booking-flow-hero" aria-label="Toddler Gym booking">
  <div class="container">
    <p class="page-hero-eyebrow">Toddler Gym</p>
    <h1>Complete Your Booking</h1>
    <p class="page-hero-sub">Check your selected class details, then fill in your family's information before continuing.</p>
  </div>
</section>

<section class="cd-booking-flow section" aria-label="Toddler Gym booking form">
  <div class="container">
    <div class="cd-flow-grid">
      <aside class="cd-session-summary" aria-labelledby="selected-session-title">
        <h2 id="selected-session-title">Selected Session</h2>
        <!-- session summary rows -->
      </aside>

      <div class="contact-card cd-booking-form-card">
        <form class="cd-booking-form" data-booking-form>
          <!-- form fields -->
        </form>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 3: Add the approved booking form fields and handoff hooks**

Include these controls and hooks:

```html
<input id="child-name" name="child-name" type="text" required autocomplete="name">
<input id="child-age" name="child-age" type="text" required inputmode="decimal">
<input id="parent-name" name="parent-name" type="text" required autocomplete="name">
<input id="email" name="email" type="email" required autocomplete="email">
<input id="phone" name="phone" type="tel" required autocomplete="tel">
<textarea id="message" name="message" rows="6"></textarea>
<button type="submit" class="btn btn-magenta">Pay</button>
```

Add summary placeholders with stable selectors such as:

```html
<dd data-session-field="class"></dd>
<dd data-session-field="term"></dd>
<dd data-session-field="day"></dd>
<dd data-session-field="time"></dd>
<dd data-session-field="price"></dd>
<dd data-session-field="availability"></dd>
```

- [ ] **Step 4: Build the confirmation page markup shell**

Add:

```html
<section class="cd-booking-confirmation-hero" aria-label="Booking confirmation">
  <div class="container">
    <p class="page-hero-eyebrow">Toddler Gym</p>
    <h1>Booking Request Received</h1>
    <p class="page-hero-sub">We've captured your booking details for the selected session below.</p>
  </div>
</section>

<section class="cd-booking-confirmation section" aria-label="Booking confirmation details">
  <div class="container">
    <div class="cd-confirmation-card">
      <!-- confirmation copy -->
      <!-- selected session summary -->
      <!-- submitted details summary -->
      <a href="../classes/toddler.html#book" class="btn btn-ghost-magenta">Return to Toddler Gym</a>
    </div>
  </div>
</section>
```

Use stable selectors for echoed form fields such as:

```html
<dd data-submitted-field="childName"></dd>
<dd data-submitted-field="childAge"></dd>
<dd data-submitted-field="parentName"></dd>
<dd data-submitted-field="email"></dd>
<dd data-submitted-field="phone"></dd>
<dd data-submitted-field="message"></dd>
```

Add explicit `<dt>` labels for both the selected-session summary and the submitted-details summary so the confirmation page remains accessible and scannable.

Include fallback recovery links in both pages:

```html
<a href="../classes/toddler.html#book" class="btn btn-ghost-magenta cd-fallback-link">Return to Toddler Gym</a>
```

- [ ] **Step 5: Run test to verify markup passes**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: PASS with no output

- [ ] **Step 6: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add classes/toddler-booking.html classes/toddler-booking-confirmation.html
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/toddler-booking-flow.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: add toddler booking flow pages"
```

### Task 3: Wire Session Data From the Toddler Timetable

**Files:**
- Modify: `classes/toddler.html`
- Modify: `js/main.js`
- Test: `tests/toddler-booking-flow.test.sh`

- [ ] **Step 1: Add explicit data attributes to active booking actions in `classes/toddler.html`**

For each active desktop and mobile `Book now` action, add attributes like:

```html
data-booking-link
data-class="Toddler Gym"
data-term="Spring 2026"
data-day="Tuesday"
data-time="9:40 – 10:20"
data-price="£143 / term"
data-availability="6 / 10"
```

Do not add navigation data to disabled booking actions.

- [ ] **Step 2: Implement booking-link URL generation in `js/main.js`**

Add a focused helper:

```js
function buildToddlerBookingUrl(trigger) {
  const params = new URLSearchParams({
    class: trigger.dataset.class,
    term: trigger.dataset.term,
    day: trigger.dataset.day,
    time: trigger.dataset.time,
    price: trigger.dataset.price,
    availability: trigger.dataset.availability
  });

  return `../classes/toddler-booking.html?${params.toString()}`;
}
```

On page load, find all `[data-booking-link]` nodes and set their `href` to the generated booking URL.

- [ ] **Step 3: Keep disabled booking actions inert**

Ensure links with `.is-disabled` remain non-interactive and do not receive active booking URLs.

Explicitly:
- add `aria-disabled="true"` to disabled booking anchors
- keep or set `tabindex="-1"` on disabled booking anchors if needed for consistent keyboard behavior
- prevent default on click for disabled booking anchors in `js/main.js`
- do not rewrite their `href` to a live booking page

- [ ] **Step 4: Run smoke test again**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: PASS with no output

- [ ] **Step 5: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add classes/toddler.html js/main.js
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: wire toddler booking session links"
```

### Task 4: Implement Booking Page and Confirmation Page Behavior

**Files:**
- Modify: `js/main.js`
- Modify: `classes/toddler-booking.html`
- Modify: `classes/toddler-booking-confirmation.html`
- Test: `tests/toddler-booking-flow.test.sh`

- [ ] **Step 1: Add a URL-session reader helper**

Implement:

```js
function getSelectedSession() {
  const params = new URLSearchParams(window.location.search);

  return {
    class: params.get('class') || '',
    term: params.get('term') || '',
    day: params.get('day') || '',
    time: params.get('time') || '',
    price: params.get('price') || '',
    availability: params.get('availability') || ''
  };
}
```

- [ ] **Step 2: Hydrate the booking page summary**

On `classes/toddler-booking.html`:
- populate every `[data-session-field]`
- if required session values are missing, reveal a fallback message block, hide or disable the form, and show the direct return link to `../classes/toddler.html#book`

- [ ] **Step 3: Persist submitted form values to `sessionStorage` on submit**

Implement:

```js
const formPayload = {
  childName: formData.get('child-name')?.trim() || '',
  childAge: formData.get('child-age')?.trim() || '',
  parentName: formData.get('parent-name')?.trim() || '',
  email: formData.get('email')?.trim() || '',
  phone: formData.get('phone')?.trim() || '',
  message: formData.get('message')?.trim() || ''
};

sessionStorage.setItem('toddlerBookingForm', JSON.stringify(formPayload));
window.location.href = `../classes/toddler-booking-confirmation.html?${params.toString()}`;
```

Use `event.preventDefault()` so the prototype stays fully client-side.

- [ ] **Step 4: Hydrate the confirmation page**

On `classes/toddler-booking-confirmation.html`:
- read selected session from the URL
- read submitted form details from `sessionStorage`
- populate `[data-session-field]` and `[data-submitted-field]`
- if form details are missing, show a graceful fallback notice instead of a broken confirmation card and show a direct return link back to `../classes/toddler-booking.html` or `../classes/toddler.html#book`

- [ ] **Step 5: Clear or retain storage deliberately**

Retain the stored form payload for the current prototype session unless manual QA reveals it causes confusion. Do not clear it automatically until the flow actually gains a backend or a stronger state model.

- [ ] **Step 6: Run smoke test again**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: PASS with no output

- [ ] **Step 7: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add js/main.js classes/toddler-booking.html classes/toddler-booking-confirmation.html
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: add toddler booking flow behavior"
```

### Task 5: Add Responsive Styling for the Booking Flow

**Files:**
- Modify: `css/class-detail.css`
- Modify: `classes/toddler-booking.html`
- Modify: `classes/toddler-booking-confirmation.html`
- Test: `tests/toddler-booking-flow.test.sh`

- [ ] **Step 1: Add desktop booking-flow layout blocks**

Create focused sections for:

```css
.cd-booking-flow-hero { }
.cd-booking-flow { }
.cd-flow-grid { }
.cd-session-summary { }
.cd-booking-form-card { }
.cd-booking-form { }
.cd-booking-confirmation { }
.cd-confirmation-card { }
.cd-summary-list { }
.cd-fallback-note { }
```

Keep the summary card visually distinct, but consistent with the current class-detail surfaces.

- [ ] **Step 2: Reuse the contact-form language without copying the whole page**

Style the form card to feel related to `contact.html`:
- soft white card surface
- rounded corners
- stacked labels and fields
- comfortable spacing
- clear primary action row

- [ ] **Step 3: Add mobile-first responsive behavior**

At mobile widths:
- stack summary and form in a single column
- keep summary rows readable
- make the `Pay` button full-width
- ensure confirmation page summaries stay easy to scan

At larger widths:
- allow a two-column feel only at the section level if it improves scanability, but do not make the form itself multi-column

- [ ] **Step 4: Run smoke test again**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: PASS with no output

- [ ] **Step 5: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add css/class-detail.css classes/toddler-booking.html classes/toddler-booking-confirmation.html
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "style: add toddler booking flow layout"
```

### Task 6: Verify the Flow End to End

**Files:**
- Verify: `classes/toddler.html`
- Verify: `classes/toddler-booking.html`
- Verify: `classes/toddler-booking-confirmation.html`
- Verify: `js/main.js`
- Verify: `css/class-detail.css`
- Verify: `tests/toddler-booking-flow.test.sh`

- [ ] **Step 1: Run the smoke test**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/toddler-booking-flow.test.sh`
Expected: PASS with no output

- [ ] **Step 2: Run the existing contact smoke test to catch collateral regressions**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: PASS with no output

- [ ] **Step 3: Serve the site for manual verification**

Run: `node /Users/sharktopia/Desktop/TJS_V3/serve.mjs`

Verify:
- `classes/toddler.html` still exposes the `#book` anchor used by fallback links
- active `Book now` buttons open the booking page with the correct session summary
- disabled booking buttons remain inert
- booking page form validates required fields
- `Pay` reaches the confirmation page
- confirmation page repeats the selected session and submitted details
- direct fallback states still look intentional
- mobile layout remains single-column and touch-friendly

- [ ] **Step 4: Run a lightweight browser-level behavior check**

Use the browser to verify real behavior rather than markup only:

- one active `Book now` link resolves to `toddler-booking.html` with the expected query parameters
- one disabled `Book now` control does not navigate
- submitting the booking form stores the payload and lands on the confirmation page
- direct access to booking or confirmation pages without complete state shows the fallback return link

If Playwright or the browse skill is available, prefer that over manual inspection for at least one full happy-path run.

- [ ] **Step 5: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add classes/toddler.html classes/toddler-booking.html classes/toddler-booking-confirmation.html css/class-detail.css js/main.js tests/toddler-booking-flow.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: add toddler booking prototype flow"
```
