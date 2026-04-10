# TJSGYM Booking Confirmation Email Design

**Date:** 2026-04-10
**Feature:** `Booking confirmation` HTML email template
**Status:** Approved for implementation

---

## Goal

Create a standalone HTML email template for successful bookings that matches the tone and visual language of the TJSGYM redesign, especially the current `Contact Us` page.

The template should feel branded, calm, and trustworthy rather than transactional or generic. It must be suitable for later integration with an email sending system and should therefore use explicit placeholder variables for all booking-specific content.

This work is limited to the email template itself. It does not include backend sending logic, template rendering, CMS integration, or delivery-provider setup.

## Source Material

- Visual reference: `pages/contact.html`
- Current booking confirmation logic reference: `classes/toddler-booking-confirmation.html`
- Shared brand asset: `images/shared/logo.png`

## UX Intent

The email should answer one immediate parent need:

1. I have completed a booking and need a clear, branded confirmation email that shows exactly what was booked and who it was booked for.

The first screenful should confirm success immediately, and the remainder should make the booking details easy to scan on both desktop and mobile email clients.

## Chosen Direction

Use a branded confirmation email with a restrained, contact-page-inspired visual system:

- a top brand block with the TJ logo and a confirmation eyebrow
- a primary confirmation card with the success message
- a dedicated `Booking Details` section for session information
- a dedicated `Your Details` section for submitted family information
- a clean footer with club contact details and address

This direction is preferable to a plain utility email because the user specifically wants continuity with the current website style, but it avoids marketing-email patterns such as prominent call-to-action buttons, navigation links, or promotional sections.

## Information Architecture

### 1. Email Subject

The template should support a subject placeholder:

- `{{email_subject}}`

Recommended default format:

- `Booking Confirmed | {{class_name}} | TJ's Gymnastics Club`

### 2. Brand Header

The top section should include:

- TJ logo
- eyebrow text: `Booking Confirmed`
- main headline: `Your Booking Is Confirmed`
- one short supporting paragraph

This section should visually echo `Contact Us` through spacing, centered hierarchy, and the club's magenta/plum accent palette, while remaining email-safe.

### 3. Booking Details Section

This is the primary reference section and should appear before family/contact details.

Required fields:

- `{{class_name}}`
- `{{booking_type}}`
- `{{term}}`
- `{{session_day}}`
- `{{session_time}}`
- `{{venue_name}}`
- `{{price}}`
- `{{booking_reference}}`

Recommended visible labels:

- `Class`
- `Booking Type`
- `Term`
- `Day`
- `Time`
- `Venue`
- `Price`
- `Booking Reference`

### 4. Your Details Section

This section should confirm the submitted parent/child information.

Required fields:

- `{{parent_name}}`
- `{{child_name}}`
- `{{child_dob}}`
- `{{email}}`
- `{{phone}}`
- `{{message}}`

Recommended visible labels:

- `Parent / Carer Name`
- `Child's Name`
- `Child's Date of Birth`
- `Email Address`
- `Phone Number`
- `Additional Message`

If `{{message}}` is empty in a future rendering system, this row may be omitted by the integration layer. The static template should still include the placeholder row so the structure is explicit.

### 5. Footer

The footer should contain:

- club name
- short brand line
- venue/address
- phone numbers
- email address

It should not include:

- full site navigation
- promotional links
- CTA buttons

## Content Direction

### Header Copy

Eyebrow:

- `Booking Confirmed`

Headline:

- `Your Booking Is Confirmed`

Supporting paragraph:

- `Thank you for booking with TJ's Gymnastics Club. Your place has been confirmed and the details of your booking are included below.`

Secondary note:

- `Please keep this email for your records. If anything needs to change, contact us using the details at the bottom of this email.`

### Section Titles

- `Booking Details`
- `Your Details`

### Footer Copy

- `Building confidence through gymnastics since 1988.`

## Placeholder Contract

The template should use a simple moustache-style placeholder format so it is easy to connect later:

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

No logic syntax such as loops, conditionals, or provider-specific template tags should be embedded at this stage.

## Technical Constraints

The template must be implemented as a standalone HTML email, not as a normal website page.

Required implementation characteristics:

- self-contained HTML file
- inline CSS only
- table-based layout for maximum email-client compatibility
- fixed max-width container suitable for desktop inboxes
- mobile-friendly stacking without relying on advanced CSS support
- no external stylesheet imports
- no JavaScript

The TJ logo may remain referenced as a known asset path during local development, but the template structure should be simple to adapt later if the sending platform requires an absolute image URL.

## Visual System

The design should borrow the following qualities from `pages/contact.html`:

- centered title hierarchy
- generous vertical spacing
- white content cards
- soft magenta/plum accent color
- calm, readable typography

It should not attempt to copy the page one-to-one. Email clients are more restrictive, so the design should translate the same brand mood into a simpler structure.

Recommended email visual characteristics:

- warm off-white page background
- white inner card panels
- subtle pink border or top accent
- clear label/value rhythm in detail rows
- restrained footer styling

## Accessibility and Readability

The email should:

- preserve a clear reading order
- use sufficient contrast for text and labels
- keep body copy concise
- make detail rows easily scannable on narrow screens
- ensure the logo has meaningful alt text

## Out of Scope

This design does not include:

- backend email sending
- provider-specific rendering syntax
- automatic conditional hiding of empty fields
- multiple confirmation variants
- request-received or pending-approval versions
- analytics tracking pixels
- unsubscribe or marketing preference controls

## Acceptance Criteria

The implementation should be considered correct when:

1. a standalone HTML email file exists in the repo
2. the template clearly reflects TJ branding and the `Contact Us` page tone
3. the file contains the full approved placeholder set
4. the email includes logo, confirmation header, booking details, your details, and footer
5. the markup uses inline styles and does not depend on project CSS or JS
6. the layout remains readable when viewed at desktop and mobile widths
