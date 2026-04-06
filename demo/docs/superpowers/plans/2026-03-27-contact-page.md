# Contact Us Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a new `contact.html` page for TJSGYM using the existing site system, with a standard inner-page hero followed by a short intro line and a single enquiry form card that remains clean and touch-friendly on mobile.

**Architecture:** Reuse the existing header, footer, typography, navigation, and button system from the current static site. Add one new page-specific stylesheet for contact-page layout and one shell smoke test that verifies the approved simplified structure and field set. No backend integration or live form submission is included.

**Tech Stack:** HTML5, CSS3, vanilla JS (existing `js/main.js` only), shell smoke test with `rg`

---

## File Structure

- `contact.html`
  Responsibility: page markup, SEO metadata, shared header/footer, inner-page hero, short intro line, and the simplified enquiry form.
- `css/contact.css`
  Responsibility: page hero reuse, intro text spacing, centered form card styling, inputs/select/textarea/button layout, mobile-first responsive behavior.
- `tests/contact-page.test.sh`
  Responsibility: smoke-test the static markup so the page keeps the approved structure and fields.
- `docs/superpowers/specs/2026-03-27-contact-page-design.md`
  Responsibility: approved design reference for implementation.

### Task 1: Add a Failing Contact Page Smoke Test

**Files:**
- Create: `tests/contact-page.test.sh`
- Reference: `docs/superpowers/specs/2026-03-27-contact-page-design.md`

- [ ] **Step 1: Write the failing test**

```bash
#!/usr/bin/env bash
set -euo pipefail

FILE="/Users/sharktopia/Desktop/TJS_V3/contact.html"

[[ -f "$FILE" ]] || { echo "contact.html missing"; exit 1; }

rg -q '<section class="page-hero"' "$FILE" || { echo "page hero missing"; exit 1; }
rg -q 'Contact <em>Us</em>' "$FILE" || { echo "hero title missing"; exit 1; }
rg -q 'Tell us a little about your child' "$FILE" || { echo "intro line missing"; exit 1; }
rg -q 'Parent / Carer Name' "$FILE" || { echo "parent label missing"; exit 1; }
rg -q 'Email Address' "$FILE" || { echo "email label missing"; exit 1; }
rg -q 'Phone Number' "$FILE" || { echo "phone label missing"; exit 1; }
rg -q "Child's Age" "$FILE" || { echo "age label missing"; exit 1; }
rg -q 'Class of Interest' "$FILE" || { echo "class label missing"; exit 1; }
rg -q 'Send Enquiry' "$FILE" || { echo "submit CTA missing"; exit 1; }

! rg -q 'Reply expectations' "$FILE" || { echo "reply expectations should not exist"; exit 1; }
! rg -q 'aria-label="Contact details"' "$FILE" || { echo "contact details panel should not exist"; exit 1; }
! rg -q 'aria-label="Map and directions"' "$FILE" || { echo "map section should not exist"; exit 1; }
```

- [ ] **Step 2: Make the test executable**

Run: `chmod +x /Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: no output

- [ ] **Step 3: Run test to verify it fails**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: FAIL with `contact.html missing`

- [ ] **Step 4: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/contact-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "test: add contact page smoke test"
```

### Task 2: Build the Contact Page Markup

**Files:**
- Create: `contact.html`
- Reference: `classes.html`
- Reference: `coaches.html`
- Reference: `timetable.html`
- Test: `tests/contact-page.test.sh`

- [ ] **Step 1: Copy the shared page shell from an existing inner page**

Use `classes.html` as the base for:
- `<head>` metadata structure
- header markup
- footer markup
- `js/main.js` include

Update these page-specific values:
- title
- meta description
- active nav state on `Contact Us`
- stylesheet include to `css/contact.css`

- [ ] **Step 2: Write the failing page content against the approved structure**

Add this content hierarchy inside `<main>`:

```html
<section class="page-hero" aria-label="Contact TJ's Gymnastics Club">
  <div class="container">
    <p class="page-hero-eyebrow">Get in touch</p>
    <h1>Contact <em>Us</em></h1>
    <p class="page-hero-sub">If you'd like help choosing the right class, arranging a trial, or asking about the club, send us a quick message and we'll be in touch.</p>
  </div>
</section>

<section class="contact-form-section" aria-label="Contact form">
  <div class="container">
    <p class="contact-intro">Tell us a little about your child and what you'd like help with.</p>

    <div class="contact-card">
      <form class="contact-form" action="#" method="post">
        <!-- labels + fields -->
      </form>
    </div>
  </div>
</section>
```

- [ ] **Step 3: Add the approved fields only**

Use explicit labels and IDs for:

```html
<label for="parent-name">Parent / Carer Name</label>
<input id="parent-name" name="parent-name" type="text" autocomplete="name">

<label for="email">Email Address</label>
<input id="email" name="email" type="email" autocomplete="email">

<label for="phone">Phone Number</label>
<input id="phone" name="phone" type="tel" autocomplete="tel">

<label for="child-age">Child's Age</label>
<input id="child-age" name="child-age" type="text">

<label for="class-interest">Class of Interest</label>
<select id="class-interest" name="class-interest">
  <option value="">Please select</option>
  <option value="tiddler-gym">Tiddler Gym</option>
  <option value="toddler-gym">Toddler Gym</option>
  <option value="mini-gym">Mini Gym</option>
  <option value="gymnastics">Gymnastics</option>
  <option value="not-sure-yet">Not sure yet</option>
</select>

<label for="message">Message</label>
<textarea id="message" name="message" rows="6"></textarea>

<button type="submit" class="btn btn-magenta">Send Enquiry</button>
```

- [ ] **Step 4: Run test to verify markup passes**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: PASS with no output

- [ ] **Step 5: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add contact.html
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/contact-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: add contact page markup"
```

### Task 3: Add Contact Page Styling and Mobile Behavior

**Files:**
- Create: `css/contact.css`
- Modify: `contact.html`
- Test: `tests/contact-page.test.sh`

- [ ] **Step 1: Write the failing visual CSS skeleton**

Create the page stylesheet with these sections:

```css
h2 { color: var(--plum); }
h2 em { color: var(--magenta); font-style: normal; }

.page-hero { /* align with existing inner-page pattern */ }
.page-hero-eyebrow { /* magenta uppercase eyebrow */ }
.page-hero h1 { /* plum heading */ }
.page-hero h1 em { /* magenta emphasis */ }
.page-hero-sub { /* centered restrained width */ }

.contact-form-section { /* spacing after hero */ }
.contact-intro { /* short centered editorial line */ }
.contact-card { /* centered white card */ }
.contact-form { /* single-column stacked layout */ }
```

- [ ] **Step 2: Implement the mobile-first form card**

Add concrete rules for:
- centered readable max width around the form card
- white card surface with rounded corners, blush border/shadow language
- stacked `label`, `input`, `select`, and `textarea`
- generous tap-friendly control height
- visible focus states using site colours
- textarea resize limited to vertical
- full-width submit button on small screens

- [ ] **Step 3: Add responsive desktop refinements without breaking mobile**

At a desktop breakpoint, refine only:
- section spacing
- card padding
- intro text width
- submit button width if it looks better than full-width

Do not introduce:
- side-by-side field columns
- side information panels
- map, phone, email, or address modules

- [ ] **Step 4: Run the smoke test again**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: PASS with no output

- [ ] **Step 5: Manual browser verification**

Run: `node /Users/sharktopia/Desktop/TJS_V3/serve.mjs`
Then verify:
- desktop: hero spacing feels consistent with `classes.html`
- mobile: form remains single-column, padded, and easy to tap
- footer still renders correctly

- [ ] **Step 6: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add contact.html css/contact.css
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/contact-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "style: add responsive contact page"
```

### Task 4: Final Verification and Documentation Check

**Files:**
- Modify: `contact.html` (only if verification reveals a real issue)
- Modify: `css/contact.css` (only if verification reveals a real issue)
- Test: `tests/contact-page.test.sh`

- [ ] **Step 1: Run the smoke test one last time**

Run: `/Users/sharktopia/Desktop/TJS_V3/tests/contact-page.test.sh`
Expected: PASS with no output

- [ ] **Step 2: Check git diff for scope control**

Run: `git -C /Users/sharktopia/Desktop/TJS_V3 diff -- contact.html css/contact.css tests/contact-page.test.sh`
Expected: only the new contact page, stylesheet, and test changes appear

- [ ] **Step 3: Summarize manual QA results**

Capture:
- desktop result
- mobile result
- whether the page stayed within the approved simplified scope

- [ ] **Step 4: Commit final polish only if needed**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add contact.html css/contact.css tests/contact-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "chore: polish contact page qa fixes"
```
