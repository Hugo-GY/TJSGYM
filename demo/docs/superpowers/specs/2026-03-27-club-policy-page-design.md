# TJSGYM Club Policy Page Design

**Date:** 2026-03-27
**Page:** `club-policy.html`
**Status:** Approved for implementation

---

## Goal

Refocus the TJSGYM Club Policy page so it reads as a clear policy centre rather than a single long booking-terms document.

The page should help parents and carers quickly find the club's formal policies and codes of conduct, while still retaining a shorter practical summary of everyday club rules lower down the page.

## Source Material

- Primary source text: `Original web/club-rules/club-rules.txt`
- Structured source: `Original web/club-rules/club-rules.json`
- Existing in-progress page:
  - `club-policy.html`
  - `css/club-policy.css`
- Site-wide design system context: `.impeccable.md`

## Content Rules

- The page identity should be “policy centre” first, not “single article” first.
- Formal policy entry points must be the dominant content block near the top of the page.
- The quick rules summary must remain shorter and secondary.
- Keep the tone warm, clear, and official without sounding legalistic or cold.
- Do not invent policy content that is not grounded in the source material.
- If linked destination pages do not yet exist, policy cards may act as static visual entry points for now.

## UX Intent

The page should answer these questions quickly:

1. Where can I find the club's formal policies and conduct rules?
2. Which policy is the most relevant starting point for parents?
3. What are the main practical club rules I need to know day to day?

This page should feel like a hub: easy to scan, structured, trustworthy, and more navigational than essay-like.

## Chosen Direction

Use a policy-library-first layout:

- standard inner-page hero
- one short orientation sentence
- primary policy card library
- secondary quick rules summary
- standard footer

This makes the page feel like a genuine policy centre while still preserving the most useful everyday rules for families.

## Page Structure

### 1. Page Hero

Reuse the established inner-page hero pattern already used on `classes.html`, `coaches.html`, and `timetable.html`.

Required content shape:

- eyebrow
- `h1` with magenta `<em>`
- short supporting paragraph

Recommended copy direction:

- Eyebrow: `Policies & Guidance`
- Title: `Club <em>Policy</em>`
- Subcopy: a short introduction that frames the page as the place to find TJ's club rules, safeguarding guidance, and conduct policies

The hero should remain white and restrained.

### 2. Policy Centre Intro

Directly below the hero, include one short orientation sentence explaining what the page contains.

Purpose:

- clarify that this page brings together the club's formal policy documents
- help the user understand that the cards below are entry points, not decorative boxes

This should be brief and functional, not a long introduction.

### 3. Formal Policy Card Library

This is the main page body and must visually dominate the page.

Structure:

- one featured lead policy card
- four secondary policy cards in a clean supporting grid

Recommended policy entries:

- `Club Rules & Codes of Conduct`
- `Child Safeguarding & Protection Policy`
- `Coach Code of Conduct`
- `Anti-Bullying Policy`
- `Equity Policy`

The first card should read as the main parent-facing entry point.

Each card should include:

- title
- one short explainer sentence
- clear CTA label such as `Read Policy` or `View Guidance`

If destination pages or downloadable files do not yet exist, cards may remain static or point to placeholder targets for now, but the design should clearly imply future navigability.

### 4. Quick Club Rules Summary

Below the formal policy library, add a shorter support section summarizing the practical rules families most often need.

This summary should not become another long document. It should be clearly lighter than the policy library above.

Recommended content themes from source:

- suitable clothing and tied-back hair
- prompt fee payment
- British Gymnastics registration
- reporting injuries or illness before warm-up
- no eating during session, bring water bottle
- collection by parent or guardian at session end
- parent access rules
- watching week timing
- filming and photography restrictions

Presentation guidance:

- use scannable rule items or cards
- avoid dense multi-paragraph blocks
- keep hierarchy secondary to the formal policy cards

### 5. Footer

Reuse the standard site footer with no page-specific additions.

## Visual System

The page must reuse the existing TJSGYM redesign language:

- same header and footer markup as current live pages
- same page hero system
- same typography tokens from `css/global.css`
- same component language from `css/components.css`
- same light background and plum / magenta emphasis strategy

## Styling Guidance

- The formal policy cards should feel structured, official, and easy to scan.
- Use white, pink-wash, and blush surfaces with plum/magenta emphasis.
- The lead policy card should stand out through size or hierarchy, not through a radically different style.
- Keep spacing generous so the page does not collapse into document clutter.
- Avoid making the page look like a legal PDF.
- Avoid turning the lower quick-rules section into the dominant visual block.

## Responsive Behaviour

- On mobile, the lead card should remain clearly first.
- Supporting policy cards should collapse cleanly into a one-column stack.
- CTA labels must stay readable and easy to tap.
- Quick rules summary should remain scannable on narrow screens.
- Do not create cramped multi-column layouts at mobile widths.

The page should still feel like a policy hub on mobile, not a compressed desktop grid.

## Accessibility and Content Quality

- Preserve heading hierarchy: one `h1`, section `h2`s, optional `h3`s inside cards or rule items only where useful.
- Do not rely on colour alone to distinguish the lead policy card.
- Make policy entry CTAs explicit and descriptive.
- Keep rule summaries in plain language.
- Maintain readable line lengths and sufficient contrast.

## Files Expected

- Modify: `club-policy.html`
- Modify: `css/club-policy.css`
- Reuse: `css/global.css`
- Reuse: `css/components.css`
- Reuse: `js/main.js`

## Acceptance Criteria

- `club-policy.html` clearly reads as a policy centre rather than a single long article.
- The main visual block is a formal policy card library near the top of the page.
- `Club Rules & Codes of Conduct` is the most prominent entry point.
- A shorter quick-rules summary remains present lower on the page.
- The page visually matches the current TJSGYM redesign.
- The layout works cleanly on desktop and mobile.
