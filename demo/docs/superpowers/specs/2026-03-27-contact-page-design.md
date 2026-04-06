# TJSGYM Contact Page Design

**Date:** 2026-03-27
**Page:** `contact.html`
**Status:** Approved for implementation

---

## Goal

Add a new Contact Us page to the TJSGYM static site that feels fully consistent with the current redesign system while staying deliberately simple: a standard inner-page hero followed by a short editorial lead-in and a single enquiry form card.

The page should help parents send a quick enquiry about classes, trial sessions, or age suitability without making them scan through extra operational detail.

## Source Material

- Primary source page: `Original web/contact-us/index.html`
- Site-wide design system context: `.impeccable.md`
- Existing reusable page patterns:
  - `classes.html`
  - `coaches.html`
  - `timetable.html`

## Content Rules

- Preserve the spirit of the original contact page: this is the place to enquire directly with the club.
- Do not recreate the clutter of the original WordPress page.
- Do not add phone, email, address, reply expectation blocks, or map sections to the main body.
- Keep copy short, warm, and practical.
- Keep the page visually lighter than a full information page.

## UX Intent

The page should answer one immediate visitor need:

1. How do I send TJ's a message about my child and class options?

This is not a contact directory page. It is a focused enquiry page.

## Chosen Direction

Use a minimal, conversion-focused structure:

- standard inner-page hero
- one short editorial introduction line below the hero
- one centered enquiry form card
- standard site footer

This direction matches the current site system and keeps the page especially usable on mobile, where most parents will likely contact the club quickly between other tasks.

## Page Structure

### 1. Page Hero

Reuse the established inner-page hero pattern already used on `classes.html`, `coaches.html`, and `timetable.html`.

Required content shape:

- eyebrow
- `h1` with magenta `<em>`
- short supporting paragraph

Recommended copy direction:

- Eyebrow: `Get in touch`
- Title: `Contact <em>Us</em>`
- Subcopy: brief, reassuring, parent-facing wording such as asking about the right class, a trial, or general club questions

The hero should remain white and restrained, consistent with the rest of the site.

### 2. Intro Line

Directly beneath the hero, include a very short editorial lead-in before the form card.

Purpose:

- soften the transition from hero to form
- make the page feel human rather than transactional
- help the form section feel intentional instead of abruptly dropped into the page

This should be a single sentence, not a full information block.

Example tone direction:

`Tell us a little about your child and what you'd like help with.`

### 3. Main Enquiry Form Card

The main page body is a single centered form card with a white surface, rounded corners, blush border accents, and comfortable interior spacing.

The form card should feel like part of the current design system:

- white surface
- rounded corners
- soft shadow, if consistent with existing components
- generous padding
- clearly stacked labels and fields

No secondary sidebars or information panels should sit beside it.

### 4. Footer

Reuse the standard site footer with no contact-page-specific additions.

## Form Fields

Keep the field set concise and parent-friendly:

- `Parent / Carer Name`
- `Email Address`
- `Phone Number`
- `Child's Age`
- `Class of Interest`
- `Message`

Field guidance:

- `Class of Interest` should be a `select`
- recommended options:
  - `Tiddler Gym`
  - `Toddler Gym`
  - `Mini Gym`
  - `Gymnastics`
  - `Not sure yet`
- `Message` should be the only multi-line field

## Form Behaviour

- This page is static for now and does not submit to a live backend.
- The form should still look complete and credible.
- Use accessible labels for every field.
- The submit control should be styled as a primary site CTA.
- Button copy: `Send Enquiry`

Implementation note:

- if a static/demo message is needed, keep it extremely subtle
- do not let the non-functional state dominate the page

## Visual System

The page must reuse the existing TJSGYM redesign language:

- same header and footer markup as current live pages
- same page hero system
- same typography tokens from `css/global.css`
- same component language from `css/components.css`
- same light background and plum / magenta emphasis strategy

## Styling Guidance

- Prefer white and pink-wash surfaces.
- Use plum and magenta for headings, labels, focus states, and button emphasis.
- Keep the form card centered with a readable max width.
- Avoid adding decorative cards, trust strips, or auxiliary contact modules.
- Maintain generous vertical spacing so the page feels calm rather than cramped.

## Responsive Behaviour

Mobile behaviour is a core requirement.

- The hero must remain compact and readable on narrow screens.
- The intro line should not create a large gap before the form.
- The form card should collapse naturally into a single-column mobile layout.
- Input controls must be large enough for touch use.
- Card padding should reduce on mobile, but still preserve breathing room.
- The submit button should remain prominent and easy to tap.
- No two-column form layout should appear at mobile widths.

The overall result should feel intentionally designed for phones first, not merely compressed from desktop.

## Accessibility and Content Quality

- Use one `h1` only.
- Preserve a clean heading hierarchy.
- Use explicit `label` elements tied to each field.
- Ensure placeholder text is never the only label.
- Maintain clear focus styles for keyboard users.
- Keep form copy plain and easy to scan.

## Files Expected

- Create: `contact.html`
- Create: `css/contact.css`
- Reuse: `css/global.css`
- Reuse: `css/components.css`
- Reuse: `js/main.js`

## Acceptance Criteria

- `contact.html` exists and is linked consistently from the site nav.
- The page uses the standard inner-page hero pattern.
- The body contains only a short intro line and a single primary form card.
- No phone, email, address, map, or reply expectation modules appear in the main page body.
- The page visually matches the current TJSGYM redesign.
- The form fields reflect the approved simplified field list.
- The layout feels deliberate and comfortable on mobile as well as desktop.
