# History Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a new `history.html` page for TJSGYM that preserves the original club history content while matching the existing redesign system and working cleanly on mobile and desktop.

**Architecture:** Add one standalone HTML page and one page-specific stylesheet, reusing the shared header, footer, typography, buttons, and page-hero system already established in the project. Structure the page as editorial story sections, a concise milestone strip, and a history gallery using source content from `Original web/TJs_Gym_History/`.

**Tech Stack:** HTML5, CSS3, existing shared CSS tokens/components, vanilla JS via `js/main.js`

---

## Relevant Files

| File | Responsibility |
|---|---|
| `history.html` | New history page markup using the shared site shell and page-specific content structure |
| `css/history.css` | New styles for story layout, milestone strip, and gallery |
| `css/global.css` | Existing shared tokens, layout, header, footer, and page-hero styles |
| `css/components.css` | Existing shared buttons, cards, labels, and utility patterns |
| `js/main.js` | Existing mobile navigation behavior |
| `images/history/` | Historical image assets used by the new page |
| `Original web/TJs_Gym_History/history.txt` | Source copy reference |
| `Original web/TJs_Gym_History/history.json` | Structured source reference |

## Task 1: Verify and prepare history assets

**Files:**
- Read: `Original web/TJs_Gym_History/history.txt`
- Read: `Original web/TJs_Gym_History/history.json`
- Check/Create: `images/history/`

- [ ] **Step 1: Confirm the required source assets exist**

Run: `ls -la "Original web/TJs_Gym_History"`
Expected: `history.txt`, `history.json`, and `image_01.jpg` to `image_07.jpg` are present

- [ ] **Step 2: Check whether history images are already copied into the working images directory**

Run: `ls -la images/history`
Expected: either the folder is empty / missing or already contains the required images

- [ ] **Step 3: Copy any missing history images into the live site asset folder**

Run:

```bash
mkdir -p images/history
cp "Original web/TJs_Gym_History"/image_*.jpg images/history/
```

Expected: `images/history/` contains `image_01.jpg` through `image_07.jpg`

- [ ] **Step 4: Verify copied assets**

Run: `ls -la images/history`
Expected: seven history image files are present

- [ ] **Step 5: Commit asset preparation**

```bash
git add images/history
git commit -m "chore: add history page image assets"
```

## Task 2: Create the history page markup

**Files:**
- Create: `history.html`
- Reference: `coaches.html`

- [ ] **Step 1: Use an existing inner page as the shell reference**

Read `coaches.html` and reuse:

- shared `<head>` structure
- shared header markup
- shared footer markup
- shared `<script src="js/main.js"></script>`

- [ ] **Step 2: Create the base HTML document**

Add:

- page title for History
- page-specific meta description
- favicon link
- links to `css/global.css`, `css/components.css`, and new `css/history.css`

- [ ] **Step 3: Add the standard page hero**

Include:

- eyebrow
- `h1` with `<em>`
- one short introductory paragraph

- [ ] **Step 4: Add the origin story section**

Create a section with:

- section label
- `h2`
- first two source paragraphs
- one primary history image

- [ ] **Step 5: Add the 2023 turning-point highlight**

Create a highlighted section containing:

- concise heading
- short supporting paragraph using the original move text
- milestone chips or cards for `1988` and `2023`

- [ ] **Step 6: Add the continuity/community section**

Include:

- remaining source paragraphs
- supporting image or image pair
- optional highlighted names line only if it improves readability without rewriting meaning

- [ ] **Step 7: Add the milestone summary strip**

Create a concise scan-friendly timeline row or grid with the approved milestone summaries.

- [ ] **Step 8: Add the historical gallery**

Use the remaining archive images in a clean, non-carousel grid.

- [ ] **Step 9: Mark the History nav item as active**

Ensure the header nav includes:

```html
<a href="history.html" class="nav-link active">History</a>
```

- [ ] **Step 10: Commit the markup**

```bash
git add history.html
git commit -m "feat: add history page markup"
```

## Task 3: Implement page-specific styles

**Files:**
- Create: `css/history.css`
- Reference: `css/global.css`
- Reference: `css/components.css`

- [ ] **Step 1: Create the stylesheet scaffold**

Add section blocks for:

- page spacing
- story layout
- highlight band
- milestones
- gallery
- responsive rules

- [ ] **Step 2: Style the editorial story sections**

Implement:

- two-column desktop layout
- stacked mobile layout
- comfortable text width
- image cards with rounded corners

- [ ] **Step 3: Style the turning-point highlight**

Implement:

- soft elevated card or band
- balanced spacing
- simple milestone cards / chips
- restrained emphasis using plum and magenta

- [ ] **Step 4: Style the milestone summary strip**

Implement:

- horizontal desktop presentation
- stacked mobile presentation
- concise readable labels and dates

- [ ] **Step 5: Style the history gallery**

Implement:

- clean editorial grid
- rounded images
- consistent gap spacing
- no slider controls or hover gimmicks

- [ ] **Step 6: Add responsive rules**

Verify:

- text and cards do not feel cramped below `768px`
- milestone elements wrap cleanly
- gallery remains visually balanced on mobile

- [ ] **Step 7: Commit the stylesheet**

```bash
git add css/history.css
git commit -m "feat: add history page styles"
```

## Task 4: QA the page locally

**Files:**
- Test: `history.html`
- Test: `css/history.css`

- [ ] **Step 1: Start the local dev server**

Run: `node serve.mjs`
Expected: local server starts successfully

- [ ] **Step 2: Open the History page in the browser**

Verify:

- header and footer render correctly
- nav mobile toggle still works
- all images load
- no broken spacing or overflow issues

- [ ] **Step 3: Check desktop layout**

Expected:

- story sections feel airy
- highlight section is visually distinct but not loud
- gallery closes the page cleanly

- [ ] **Step 4: Check mobile layout**

Expected:

- sections stack cleanly
- text remains readable
- milestone strip does not overflow
- gallery still feels intentional

- [ ] **Step 5: Smoke-check HTML references**

Run: `rg -n "history\\.html|history\\.css|images/history" history.html css/history.css`
Expected: file references are correct and consistent

- [ ] **Step 6: Commit the verified page**

```bash
git add history.html css/history.css
git commit -m "test: verify history page layout"
```

## Task 5: Final integration review

**Files:**
- Review: `history.html`
- Review: `css/history.css`

- [ ] **Step 1: Compare the page against the design spec**

Check against:

- `docs/superpowers/specs/2026-03-27-history-page-design.md`

- [ ] **Step 2: Ensure copy has only light cleanup**

Manually verify:

- chronology is intact
- names are preserved
- wording has not drifted into invented marketing language

- [ ] **Step 3: Check git diff for accidental unrelated edits**

Run: `git diff -- history.html css/history.css`
Expected: only history-page-specific changes appear

- [ ] **Step 4: Prepare handoff summary**

Summarize:

- files added
- source assets used
- any remaining optional polish items
