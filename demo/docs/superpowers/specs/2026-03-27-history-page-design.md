# TJSGYM History Page Design

**Date:** 2026-03-27
**Page:** `history.html`
**Status:** Approved for implementation

---

## Goal

Add a new History page to the TJSGYM static site that feels like a natural sibling of the existing Coaches, Classes, and Timetable pages. The page should tell the club's story with warmth and clarity, preserve the original source facts and tone, and help parents understand that TJ's is a long-running, friendly, community-rooted club.

## Source Material

- Primary copy source: `Original web/TJs_Gym_History/history.txt`
- Structured source: `Original web/TJs_Gym_History/history.json`
- Image source: `Original web/TJs_Gym_History/image_01.jpg` through `image_07.jpg`

## Content Rules

- Preserve the original facts, chronology, and names.
- Only make light editorial adjustments for sentence flow, paragraphing, and web readability.
- Do not add invented milestones, marketing claims, or rewritten brand copy that changes meaning.
- Keep the tone warm, modest, and community-oriented.

## UX Intent

The page should answer three parent-facing questions quickly:

1. How long has TJ's existed?
2. What values has the club stayed true to?
3. Why does that history make the club feel trustworthy today?

This page is not meant to function as a dense archive. It is a story-led supporting page with strong visual pacing and a short, scannable timeline close.

## Chosen Direction

Use a mixed structure:

- top half: editorial story layout
- lower half: concise milestone band / timeline summary
- bottom: historical gallery

This structure fits the available source content better than a full timeline, and keeps the page aligned with the existing brand language.

## Page Structure

### 1. Page Hero

Use the standard inner-page hero pattern already established elsewhere on the site:

- eyebrow
- `h1` with magenta `<em>`
- short supporting paragraph

Recommended content direction:

- Eyebrow: history / since 1988
- Title: `Our <em>History</em>`
- Subcopy: a short introduction framing TJ's as a long-standing, caring gymnastics club for local families

The hero remains white and restrained, matching the existing site system.

### 2. Origin Story Section

First major content band introduces the founding story.

Layout:

- two-column layout on desktop
- stacked layout on mobile
- text on one side, one strong archival image on the other

Content:

- paragraph 1 from source
- paragraph 2 from source

Visual treatment:

- soft white or pink-wash card surfaces
- rounded corners
- generous spacing
- no heavy decorative effects

The goal is to make the origin story feel intimate and trustworthy rather than dramatic.

### 3. Key Turning Point Highlight

Create a distinct band for the January 2023 move to Raynes Park Sports Pavilion.

Layout:

- one highlighted card or strip spanning the content width
- two compact stat chips or milestone callouts for `1988` and `2023`
- supporting text using the original sentence about the move

Purpose:

- give the page a clear chronological anchor
- help readers quickly understand that the club is both long-established and actively evolving

This section should feel slightly more emphatic than the surrounding prose, but still remain elegant and light.

### 4. Community and Continuity Section

This section covers:

- Gill as coach and parent
- long-term pride in the club
- former children growing into adults who remained involved

Layout:

- editorial text block paired with a small supporting image cluster or secondary image
- optional subtle list or highlighted names line if it improves readability without sounding promotional

Purpose:

- reinforce continuity, loyalty, and intergenerational trust
- keep the story emotionally grounded

### 5. Milestone Timeline Close

A short closing timeline or milestone strip should summarize the main story beats without repeating the full text.

Recommended milestones:

- 1988: TJ's opens
- Growth years: more coaches and classes added
- 1997 / 2005: Gill's family chapter
- 2023: move to Raynes Park Sports Pavilion

This is intentionally concise. It is a scan layer, not the main reading experience.

### 6. Historical Photo Gallery

Close the page with a gallery using the remaining archive photos.

Requirements:

- regular grid, not carousel
- balanced editorial arrangement
- rounded image corners
- consistent gaps
- no masonry behavior

The gallery should feel calm and archival, not busy.

## Visual System

The page must reuse the existing TJSGYM redesign language:

- same header and footer markup as current live pages
- same page hero system
- same typography tokens from `css/global.css`
- same component language from `css/components.css`
- same light background and plum / magenta emphasis strategy

## Styling Guidance

- Prefer off-white, white, pink-wash, and blush surfaces.
- Keep plum and magenta for headings, accents, labels, and selective emphasis.
- Use soft shadows only where they already exist in the system.
- Avoid turning the page into a dark, high-contrast storytelling page.
- Avoid heavy gradients or loud decorative shapes.
- Allow generous vertical rhythm between sections.

## Responsive Behavior

- Hero text remains centered and readable on narrow screens.
- Story sections collapse cleanly into one column.
- Highlight / milestone elements stack without becoming cramped.
- Gallery shifts from multi-column desktop layout to two-column or one-column mobile layout depending on image density.
- All text blocks retain comfortable reading width and spacing on mobile.

## Accessibility and Content Quality

- Every image needs specific alt text based on visible content or historical relevance.
- Preserve heading hierarchy: one `h1`, section `h2`s, optional `h3`s only where useful.
- Do not embed crucial information only in decorative timeline chips.
- Maintain contrast levels consistent with the rest of the site.

## Files Expected

- Create: `history.html`
- Create: `css/history.css`
- Reuse: `css/global.css`
- Reuse: `css/components.css`
- Reuse: `js/main.js`
- Reuse existing images by copying history assets into `images/history/` if not already present

## Acceptance Criteria

- `history.html` exists and is linked consistently from the site nav.
- The page clearly presents the club origin story, 2023 move, and continuity message.
- The original source content is preserved with only light editorial cleanup.
- The page visually matches the current TJSGYM redesign.
- The layout works on desktop and mobile.
- The page uses the historical image set effectively without feeling cluttered.
