# TJSGYM Toddler Booking Flow Design

**Date:** 2026-03-30
**Feature:** `Toddler Gym` booking form and confirmation flow
**Status:** Approved for implementation

---

## Goal

Add a focused two-step booking flow for `Toddler Gym` that begins from the existing booking table on `classes/toddler.html`.

The flow should let a parent choose a specific session from the timetable, move into a dedicated booking form page styled to match the current TJSGYM redesign, and then continue to a confirmation page that repeats the selected session details.

This is a prototype booking experience only. It should feel credible and complete, but it must not attempt real payment processing yet.

## Source Material

- Entry page: `classes/toddler.html`
- Form style reference: `contact.html`
- Existing class-detail visual system: `css/class-detail.css`
- Existing shared behavior script: `js/main.js`

## UX Intent

The new flow should answer one practical parent need:

1. I picked a `Toddler Gym` class time and now want to complete a booking enquiry for that exact session.

The experience should remove ambiguity about what was selected and keep the next action obvious on both desktop and mobile.

## Chosen Direction

Use a dedicated booking page plus a dedicated confirmation page:

- `classes/toddler.html` remains the session-selection page
- clicking a live `Book now` action opens a new booking page
- the booking page shows the selected class details in a read-only summary card
- the parent completes a short form based on the `Contact Us` page style
- clicking `Pay` moves to a confirmation page
- the confirmation page repeats the selected session summary and confirms receipt of the booking prototype submission

This direction is preferable to a modal because the information volume is already beyond a lightweight pop-up, mobile usability is better, and the structure can later be adapted into a real payment flow without redesigning the whole experience.

## Information Architecture

### 1. Session Selection on `classes/toddler.html`

Each active `Book now` button in the `Toddler Gym` booking tables and mobile booking cards should navigate to the new booking page with the selected session attached as URL parameters.

Required data to carry forward:

- `class`
- `term`
- `day`
- `time`
- `price`
- `availability`

Disabled booking actions must remain disabled and should not navigate anywhere.

### 2. Booking Form Page

Create a new page:

- `classes/toddler-booking.html`

This page should feel like a natural continuation of the class detail journey rather than a generic form screen.

Required structure:

- shared site header
- compact inner-page hero
- read-only selected-session summary card near the top
- primary booking form card
- bottom action row with `Pay`
- shared footer

### 3. Confirmation Page

Create a second new page:

- `classes/toddler-booking-confirmation.html`

This page is the post-submit confirmation state for the prototype flow.

Required structure:

- shared site header
- confirmation hero or lead block
- positive confirmation message
- selected-session summary card
- short next-step explanation
- return link back to `Toddler Gym`
- shared footer

## Booking Form Content

The booking form must include these parent-entered fields:

- `Child's Name`
- `Child's Age`
- `Parent / Carer Name`
- `Email Address`
- `Phone Number`
- `Additional Message`

The selected class details must not be editable form controls. They should appear in a dedicated summary component titled something like `Selected Session`.

Recommended summary fields:

- `Class`
- `Term`
- `Day`
- `Time`
- `Price`
- `Availability`

## Page Copy Direction

### Booking Page

Required title:

- `Complete Your Booking`

Supporting copy should reassure parents that they are booking for the selected class and can check the summary before continuing.

The overall tone should be calm, clear, and practical rather than sales-heavy.

### Confirmation Page

The confirmation page should make it clear that:

- the booking details have been captured
- the selected session is repeated below for reference
- this is a prototype booking step, not a real completed payment

The language should sound finished and trustworthy without pretending that money has been taken.

Avoid wording that falsely implies a real transaction such as:

- `Payment successful`
- `Card charged`
- `Receipt emailed`

Prefer wording closer to:

- `Booking Request Received`
- `Your selected session details are below`
- `This booking prototype has recorded your information for review`

## Behaviour

### Parameter Handling

The booking page should read session data from the URL query string and render the selected-session summary from those values.

The confirmation page should also render the selected-session summary from the flow's submitted data.

Use this explicit client-side contract:

- selected session details travel from `classes/toddler.html` to `classes/toddler-booking.html` in the URL query string
- parent-entered form values travel from `classes/toddler-booking.html` to `classes/toddler-booking-confirmation.html` via `sessionStorage`
- the confirmation page may also continue to read the selected session details from the URL so the confirmation view remains inspectable and easy to debug

This split is intentional:

- session data is safe and useful in the URL because it defines the chosen class slot
- parent-entered message and contact details should not be exposed in the URL

Although normal navigation will always originate from a session-specific `Book now` button, both new pages should still include a graceful fallback state if required values are missing:

- show a concise message that no session has been selected
- offer a direct link back to `classes/toddler.html#book`

This is defensive UX, not a primary path.

### Form Validation

Use browser-native validation for the prototype.

Minimum expectations:

- required fields for child name, child age, parent name, email, and phone
- email uses `type="email"`
- phone uses `type="tel"`
- `Additional Message` remains optional

### Submission

The `Pay` button should not attempt real checkout.

For the prototype, clicking `Pay` should:

1. gather the entered form values
2. preserve the selected session data
3. navigate to `classes/toddler-booking-confirmation.html`
4. render the selected session summary and key submitted details on that page

No backend, API, or payment provider is part of this implementation.

### Confirmation Fallback

If the confirmation page is opened directly and the required submitted form values are no longer available in `sessionStorage`, the page should still fail gracefully:

- retain the selected-session summary if session URL parameters are present
- show a concise notice that the booking form details are no longer available
- provide a clear link back to `classes/toddler-booking.html` or `classes/toddler.html#book`

## Visual System

The new pages must stay tightly aligned with the current TJSGYM redesign:

- reuse existing header and footer markup
- reuse the established inner-page heading language
- visually reference the `Contact Us` form card styling
- remain consistent with the `Toddler Gym` class-detail color language

The booking page should not feel like a detached checkout product.
It should still look like part of the club site.

## Styling Guidance

- Keep the booking form in a centered, single-column card
- Make the selected-session summary visually distinct from editable fields
- Preserve generous spacing and soft rounded surfaces
- Use magenta and plum sparingly for emphasis, not as a full-page wash
- Ensure the `Pay` action is prominent, but do not make the page look like an e-commerce checkout
- Keep the confirmation page clean and reassuring rather than ornate

## Responsive Behaviour

Mobile behavior is a core requirement.

- The selected-session summary must remain easy to scan on narrow screens
- The booking form should stay single-column on mobile
- Input controls must remain comfortable for touch
- The `Pay` button should remain prominent and full-width or near full-width on smaller screens
- The confirmation page should stack cleanly without dense side-by-side layouts

## Accessibility

- Use one `h1` per page
- Use explicit `label` elements for every editable field
- Preserve visible keyboard focus styles
- Keep summary content readable by screen readers
- Ensure disabled booking actions remain clearly non-interactive

## Files Expected

- Modify: `classes/toddler.html`
- Create: `classes/toddler-booking.html`
- Create: `classes/toddler-booking-confirmation.html`
- Modify: `css/class-detail.css`
- Modify: `js/main.js`
- Create: `tests/toddler-booking-flow.test.sh`

## Acceptance Criteria

- Active `Book now` controls on `classes/toddler.html` navigate to the booking page with session-specific data
- Disabled booking controls do not navigate
- `classes/toddler-booking.html` exists and shows a read-only selected-session summary plus the approved form fields
- The booking page uses `Complete Your Booking` as its main heading
- The booking page styling clearly references the `Contact Us` form language while fitting the class-detail system
- Clicking `Pay` leads to `classes/toddler-booking-confirmation.html`
- The confirmation page repeats the selected session summary
- The confirmation page confirms receipt without falsely implying real payment was processed
- Both new pages remain usable and visually coherent on mobile and desktop
