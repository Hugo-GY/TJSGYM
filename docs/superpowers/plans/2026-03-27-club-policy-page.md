# Club Policy Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rework `club-policy.html` so it functions as a clear policy centre with formal policy entry cards near the top, a lead `Club Rules & Codes of Conduct` entry, and a shorter quick-rules summary lower down the page.

**Architecture:** Reuse the existing shared site shell, page-hero system, and TJSGYM colour/type system. Replace the current article-style main body with a policy-library-first structure: hero, short orientation sentence, featured lead policy card plus supporting policy grid, then a lighter quick-rules summary section. Keep this as a static HTML/CSS implementation with placeholder policy entry targets where real destinations do not yet exist.

**Tech Stack:** HTML5, CSS3, vanilla JS (existing `js/main.js` only), shell smoke test with `rg`

---

## File Structure

- `club-policy.html`
  Responsibility: page metadata, shared header/footer, hero, orientation copy, formal policy library, and quick-rules summary markup.
- `css/club-policy.css`
  Responsibility: hero styling, policy card library hierarchy, quick-rules summary styling, and responsive mobile behavior.
- `tests/club-policy-page.test.sh`
  Responsibility: smoke-test the page structure so the policy-centre layout and required policy titles remain in place.
- `docs/superpowers/specs/2026-03-27-club-policy-page-design.md`
  Responsibility: approved design reference for implementation.

### Task 1: Add a Club Policy Page Smoke Test

**Files:**
- Create: `tests/club-policy-page.test.sh`
- Reference: `docs/superpowers/specs/2026-03-27-club-policy-page-design.md`

- [ ] **Step 1: Write the failing test**

```bash
#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
FILE="$ROOT_DIR/club-policy.html"

contains_fixed_string() {
  local pattern="$1"
  if command -v rg >/dev/null 2>&1; then
    rg -Fq "$pattern" "$FILE"
  else
    grep -Fq "$pattern" "$FILE"
  fi
}

[[ -f "$FILE" ]] || { echo "club-policy.html missing"; exit 1; }

contains_fixed_string 'Club <em>Policy</em>' || { echo "hero title missing"; exit 1; }
contains_fixed_string 'Club Rules &amp; Codes of Conduct' || { echo "lead policy title missing"; exit 1; }
contains_fixed_string 'Child Safeguarding &amp; Protection Policy' || { echo "safeguarding policy missing"; exit 1; }
contains_fixed_string 'Coach Code of Conduct' || { echo "coach policy missing"; exit 1; }
contains_fixed_string 'Anti-Bullying Policy' || { echo "anti-bullying policy missing"; exit 1; }
contains_fixed_string 'Equity Policy' || { echo "equity policy missing"; exit 1; }
contains_fixed_string 'Quick Club Rules' || { echo "quick rules heading missing"; exit 1; }

! contains_fixed_string 'Terms and conditions on <em>bookings</em>' || { echo "old article heading should be removed"; exit 1; }
```

- [ ] **Step 2: Make the test executable**

Run: `chmod +x /Users/sharktopia/Desktop/TJS_V3/tests/club-policy-page.test.sh`
Expected: no output

- [ ] **Step 3: Run test to verify it fails against the current page**

Run: `cd /Users/sharktopia/Desktop/TJS_V3 && ./tests/club-policy-page.test.sh`
Expected: FAIL because the current page does not yet contain the policy-centre structure

- [ ] **Step 4: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add tests/club-policy-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "test: add club policy page smoke test"
```

### Task 2: Replace the Main Body Markup With Policy-Centre Structure

**Files:**
- Modify: `club-policy.html`
- Test: `tests/club-policy-page.test.sh`

- [ ] **Step 1: Keep the shared page shell and rewrite only the main body structure**

Preserve:
- `<head>` shell
- header
- footer
- `js/main.js`

Rewrite the page body so it contains:
- hero
- short policy-centre intro line
- formal policy card library
- quick rules summary section

- [ ] **Step 2: Update hero copy to fit the new page identity**

Use copy in this shape:

```html
<section class="page-hero" aria-label="Club policy">
  <div class="container">
    <p class="page-hero-eyebrow">Policies &amp; Guidance</p>
    <h1>Club <em>Policy</em></h1>
    <p class="page-hero-sub">Find TJ's club rules, safeguarding guidance, and key conduct policies in one place.</p>
  </div>
</section>
```

- [ ] **Step 3: Add the policy-centre intro and formal policy library**

Add a top section with:

```html
<section class="policy-hub-section" aria-label="Formal club policies">
  <div class="container">
    <p class="policy-hub-intro">Use the policy library below to find the club's main rules, safeguarding guidance, and conduct documents.</p>

    <div class="policy-library">
      <article class="policy-card policy-card--featured">
        <p class="policy-card-kicker">Start here</p>
        <h2>Club Rules &amp; Codes of Conduct</h2>
        <p>Parent-facing guidance covering the core rules and expectations that shape day-to-day club life.</p>
        <a href="#" class="btn btn-magenta">Read Policy</a>
      </article>

      <article class="policy-card">
        <h3>Child Safeguarding &amp; Protection Policy</h3>
        <p>How TJ's protects the welfare and safety of every child in our care.</p>
        <a href="#" class="btn btn-ghost-magenta btn-sm">View Guidance</a>
      </article>

      <article class="policy-card">
        <h3>Coach Code of Conduct</h3>
        <p>The standards of behaviour and professionalism expected from TJ's coaches.</p>
        <a href="#" class="btn btn-ghost-magenta btn-sm">View Guidance</a>
      </article>

      <article class="policy-card">
        <h3>Anti-Bullying Policy</h3>
        <p>Our approach to creating a supportive, respectful, and inclusive environment.</p>
        <a href="#" class="btn btn-ghost-magenta btn-sm">View Guidance</a>
      </article>

      <article class="policy-card">
        <h3>Equity Policy</h3>
        <p>How the club supports fair access, inclusion, and respectful treatment for all members.</p>
        <a href="#" class="btn btn-ghost-magenta btn-sm">View Guidance</a>
      </article>
    </div>
  </div>
</section>
```

- [ ] **Step 4: Add the secondary quick-rules summary**

Use a separate lower section, not part of the featured card block:

```html
<section class="quick-rules-section" aria-labelledby="quick-rules-title">
  <div class="container">
    <div class="quick-rules-header">
      <span class="section-label">For families</span>
      <h2 id="quick-rules-title">Quick Club Rules</h2>
      <p>Key day-to-day reminders for attending class smoothly and safely.</p>
    </div>

    <div class="quick-rules-grid">
      <!-- concise rule items only -->
    </div>
  </div>
</section>
```

Populate the rule items from the source themes:
- suitable attire / hair / jewellery
- fees paid promptly
- British Gymnastics registration
- injuries or illness reported before warm-up
- no eating during session / bring water bottle
- collection by parent or guardian
- parent access rules
- watching week
- filming and photography rules

- [ ] **Step 5: Run the smoke test to verify markup now passes**

Run: `cd /Users/sharktopia/Desktop/TJS_V3 && ./tests/club-policy-page.test.sh`
Expected: PASS with no output

- [ ] **Step 6: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add club-policy.html tests/club-policy-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "feat: restructure club policy page as policy hub"
```

### Task 3: Restyle the Page as a Policy Centre

**Files:**
- Modify: `css/club-policy.css`
- Test: `tests/club-policy-page.test.sh`

- [ ] **Step 1: Remove the old article-document styling**

Delete or replace styles tied to:
- `.policy-document-section`
- `.policy-document`
- `.policy-document-head`
- `.policy-sections`
- `.policy-block`

The page should no longer look like a single long article card.

- [ ] **Step 2: Add formal policy library styling**

Implement styles for:
- `.policy-hub-section`
- `.policy-hub-intro`
- `.policy-library`
- `.policy-card`
- `.policy-card--featured`
- `.policy-card-kicker`

Design requirements:
- the featured lead policy card stands out through hierarchy and size
- supporting cards form a clean grid
- cards feel official and easy to scan
- CTA buttons are clear and touch-friendly

- [ ] **Step 3: Add quick-rules summary styling**

Implement styles for:
- `.quick-rules-section`
- `.quick-rules-header`
- `.quick-rules-grid`
- quick rule item card or list styles

Design requirements:
- visually lighter than the policy library above
- concise, scannable, supportive
- not the dominant section on the page

- [ ] **Step 4: Add responsive mobile behaviour**

At mobile widths:
- featured policy card remains first and clear
- supporting cards collapse to one column
- buttons remain readable and easy to tap
- quick rules remain easy to scan
- no cramped two-column policy grid on small screens

- [ ] **Step 5: Run the smoke test again**

Run: `cd /Users/sharktopia/Desktop/TJS_V3 && ./tests/club-policy-page.test.sh`
Expected: PASS with no output

- [ ] **Step 6: Manual browser verification**

Run: `node /Users/sharktopia/Desktop/TJS_V3/serve.mjs`
Then verify:
- desktop: the policy card library is clearly the dominant block
- mobile: the lead policy card remains visually first and the supporting cards stack cleanly
- the quick rules summary remains secondary

- [ ] **Step 7: Commit**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add club-policy.html css/club-policy.css tests/club-policy-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "style: turn club policy page into policy centre"
```

### Task 4: Final Verification and Scope Check

**Files:**
- Modify: `club-policy.html` only if browser QA reveals a real issue
- Modify: `css/club-policy.css` only if browser QA reveals a real issue
- Test: `tests/club-policy-page.test.sh`

- [ ] **Step 1: Run the smoke test one final time**

Run: `cd /Users/sharktopia/Desktop/TJS_V3 && ./tests/club-policy-page.test.sh`
Expected: PASS with no output

- [ ] **Step 2: Check diff scope**

Run: `git -C /Users/sharktopia/Desktop/TJS_V3 diff -- club-policy.html css/club-policy.css tests/club-policy-page.test.sh`
Expected: only the club policy page, stylesheet, and test changes appear

- [ ] **Step 3: Summarize QA outcomes**

Capture:
- desktop result
- mobile result
- whether the page now reads as a policy centre
- whether quick rules stayed secondary

- [ ] **Step 4: Commit final polish only if needed**

```bash
git -C /Users/sharktopia/Desktop/TJS_V3 add club-policy.html css/club-policy.css tests/club-policy-page.test.sh
git -C /Users/sharktopia/Desktop/TJS_V3 commit -m "chore: polish club policy page qa fixes"
```
