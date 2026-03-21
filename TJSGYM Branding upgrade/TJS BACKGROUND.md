# 1. Project Overview

**Client:** TJ's Gymnastics Club  
**Location:** Raynes Park Sports Pavilion, Taunton Avenue, London SW20 0BH  
**Current live site:** https://www.tjsgymclub.co.uk/  
**Contact:** 01252 702295 · info@tjsgymclub.co.uk

TJ's is a long-established gymnastics club in Wimbledon (36+ years), serving children from 6 months through school age. The project is a full visual redesign and functional upgrade of their existing website. The current live site is functional but visually dated. It uses a sidebar layout, dense navigation, and lacks any online booking capability. The redesign prototype has been delivered as a static HTML/CSS/JS prototype (10 pages). The dev team's job is to evaluate the prototype, productionise it, and build out the booking integration.

---

# 2. Client Goals

The client has three clearly stated objectives, in priority order:

## 2a. Modern, beautiful visual design

The current site looks like it was built in the early 2010s. The client wants a site that feels contemporary, trustworthy, and premium, something parents in a competitive south-west London market will feel confident booking from. The aesthetic brief is "cuddly": warm, soft, approachable, friendly, not clinical or corporate. Think: rounded corners, soft gradients, warm tones mixed into the purple brand, generous whitespace, and imagery of happy children.

## 2b. Mobile-first

A large proportion of the target audience, parents of young children, will discover and use this site on their phones. The current site is desktop-only. The redesign must be fully responsive, not just "doesn't break on mobile" but genuinely designed mobile-first.

## 2c. Online booking

This is the biggest functional gap in the current site. Parents currently have to email or call to enquire about places. The client wants parents to be able to see live availability and book, and pay, directly online. Stripe is the chosen payment processor.

---

# 3. Target Audience

| Segment | Description |
| --- | --- |
| Primary | Parents/carers of children aged 6 months - 6 years in SW London (Wimbledon, Raynes Park, Merton area). Predominantly mothers. Mobile-first. Value trust signals, clarity, and ease of booking. |
| Secondary | Parents of school-age children (5-12+) looking for after-school gymnastics. |
| Tertiary | Parents of SEN children - the club is explicitly SEN Inclusive, which is a meaningful differentiator and should be prominent. |

Key psychological drivers: trust, warmth, convenience. These parents are often time-poor; they want to check availability and book in as few taps as possible.

---

# 4. Colour System

The visual colour direction has been adapted from the Markaworks Pupbar inspiration reference and translated into a TJSGYM-specific system. The goal is not to copy the original brand directly, but to borrow its warmth, softness, playfulness, and contemporary feel, then organize those colours into a practical system for the new website.

## 4a. Main Colours

These are the primary brand colours and should carry most of the visual identity across the site.

| Name | Hex | Role |
| --- | --- | --- |
| Deep Plum | `#7A2A7A` | Primary anchor colour for headings, logo use, strong contrast blocks, and key UI structure |
| Bright Magenta | `#D94A9F` | High-energy accent within the main system, suitable for emphasis and call-to-action moments |
| Soft Blush Pink | `#F3C7E7` | Warm, friendly supporting background colour |
| Light Pink Wash | `#F7E9F5` | Soft surface colour for page backgrounds, cards, and whitespace-led layouts |

## 4b. Secondary Colours

These colours are not intended to dominate page layouts. They should be used selectively as supporting accents.

| Name | Hex | Role |
| --- | --- | --- |
| Mint Green | `#39C39A` | Fresh supporting accent for icons, highlights, categories, or secondary interface details |
| Acid Lime | `#DDEB3C` | Small, energetic highlight colour for standout moments, badges, or emphasis points |

## 4c. Tertiary Colour

| Name | Hex | Role |
| --- | --- | --- |
| Warm Cream | `#F5EFE7` | Gentle neutral background tone for occasional breathing space and soft contrast |

## 4d. Colour Usage Principles

- The website should remain primarily main-colour led.
- Deep Plum is the structural anchor and should be the dominant text and contrast colour.
- Soft Blush Pink and Light Pink Wash should carry most background and surface usage.
- Bright Magenta should be used as a stronger emotional accent within the core identity.
- Mint Green and Acid Lime should be used sparingly as accents, not as dominant page colours.
- Warm Cream should be used selectively, not as the default page base.

## 4e. Page Colour Scheme Option 1

Page Colour Scheme Option 1 is the strongest expression of the TJSGYM palette and should be used for pages that need the clearest brand presence, especially homepage-style hero layouts and primary landing sections.

Recommended emphasis:

- Deep Plum for major headings and dividers
- Soft Blush Pink and Light Pink Wash for the main background structure
- Bright Magenta for key supporting accents where stronger energy is needed

This option is the most directly aligned with the homepage visual direction and should be considered the primary brand presentation scheme.

## 4f. Page Colour Scheme Option 2

Page Colour Scheme Option 2 is intended for internal pages and supporting layouts, while still remaining visually connected to Option 1.

Recommended emphasis:

- Main Colours still lead the overall layout
- Deep Plum remains the text and structure anchor
- Soft Blush Pink and Light Pink Wash remain the main surfaces
- Mint Green and Acid Lime are used only as light accent colours to add freshness and visual variety

This option is suitable for class pages, information pages, FAQ layouts, programme pages, and secondary marketing sections where some variation is useful without drifting away from the core brand system.

---

# 5. Typography

The typography direction should support the same emotional goals as the colour system: warm, approachable, parent-friendly, and mobile-first.

## 5a. Approved Font Pairing

| Usage | Font | Recommended Weight | Reason |
| --- | --- | --- | --- |
| Headings | `Fredoka` | `500` | Rounded, soft, friendly, and visually aligned with a cuddly, child-focused brand tone |
| Body | `DM Sans` | `300-600` | Clean, legible, modern, and highly suitable for mobile reading |

## 5b. Typography Rationale

- `Fredoka` gives the site warmth and personality without becoming overly childish.
- A lighter heading weight works better than a heavy one; heavier weights made the lettering feel crowded and overly dense.
- `DM Sans` provides the practical clarity needed for schedules, programme details, FAQs, and booking flows.
- This pairing is more aligned with TJSGYM's target audience than a more formal editorial serif direction.

## 5c. Implementation Note

Headings should avoid feeling overly compressed or heavy. The preferred expression is soft and rounded, but still calm and readable. Large all-caps headings should be used selectively and with careful spacing.

---

# 6. Design Tone

The client used the word "cuddly" to describe the desired feeling. This remains an accurate and useful creative direction, and it is consistent with the current colour and typography decisions. However, it should be interpreted as soft, warm, and reassuring, rather than childish or overly decorative.

## 6a. Design Translation

- Rounded shapes throughout: generally `12-20px` border radius on cards and panels, and `999px` on pills, tags, and buttons
- Soft colour transitions: use blush, pink-wash, and plum-tinted gradients rather than harsh flat blocks
- Warm neutrals: avoid cold greys; tint neutrals subtly toward the plum-pink system
- Generous spacing: content should feel breathable and calm, never cramped
- Gentle hierarchy: clear but not aggressive; this is not a hard-sell sports marketing aesthetic
- Parent-friendly tone of voice: warm, encouraging, clear, and trustworthy
- Photography preference: real, warm, joyful imagery of children and families should always outperform generic stock imagery
- Fallback imagery: if final photography is not yet available, use warm illustrated or softly styled placeholders rather than cold empty boxes
- Circular decorative elements are part of the visual language and should be used to soften layouts, but they must remain restrained and intentional rather than covering the page

## 6b. Accuracy Check

This design tone is broadly correct and aligned with the current direction. A few clarifications are important:

- "Rounded everything" is directionally right, but should still be controlled and consistent rather than exaggerated
- "Soft gradients" is correct, but some areas can still use confident solid colour if it remains within the warm palette
- "No cold greys" is correct and matches the current system well
- "Approachable copy tone" is correct and important for the parent audience
- The overall tone should feel premium and trustworthy as well as cuddly; it should not drift into nursery branding or overly childish design
- Circular and curved decorative motifs are approved, but they should appear as occasional compositional accents rather than a constant background treatment

---

# 7. UI Component Rules

The user interface should feel soft, clear, and reassuring. Components should be simple to scan on mobile, generous in spacing, and consistent enough to create trust with parents booking classes for children.

## 7a. Buttons

- Primary buttons should use `Bright Magenta #D94A9F` or `Deep Plum #7A2A7A` depending on the surrounding layout
- Button shapes should be rounded, with a pill shape (`999px`) preferred for primary calls to action
- Button text should be short, friendly, and action-led: for example, `Book a Class`, `View Timetable`, `Get in Touch`
- Hover and active states should feel soft and polished, not aggressive or overly high-contrast
- Secondary buttons should be outlined or softly tinted rather than visually competing with the primary action
- Not every key navigation action needs to be presented as a button; in calmer header layouts, plain text navigation can be preferable

## 7b. Cards and Content Panels

- Cards should generally use `12-20px` radius with soft backgrounds and generous internal padding
- Preferred background colours are `Light Pink Wash`, `Soft Blush Pink`, or white tinted toward the plum system
- Cards should avoid heavy borders and sharp shadows; use soft separation instead
- Content inside cards should never feel cramped
- Cards should be used to simplify content and grouping, not to create visual clutter

## 7c. Navigation

- Navigation should feel straightforward and parent-friendly, with a clear path to the most important information pages
- Desktop navigation should be simple and uncluttered; avoid dense multi-level structures unless absolutely necessary
- Mobile navigation must prioritize speed and clarity
- The current preferred desktop direction is a clean text-based header rather than a heavily button-led navigation bar
- Confirmed top-level navigation language currently includes: `Home`, `Our Coaches`, `Facilities & Location`, `About`, and `Contact Us`

## 7d. Forms and Booking UI

- Form fields should be large, touch-friendly, and easy to scan on mobile
- Labels must be clear, direct, and reassuring
- Error states should be calm and helpful, not alarming
- Booking and enquiry flows should minimize friction and reduce the number of steps wherever possible
- Payment and checkout surfaces should feel especially trustworthy, clean, and calm

## 7e. Imagery and Media Blocks

- Photography should show happy, confident children and supportive environments wherever possible
- Imagery should feel warm and natural rather than heavily filtered or corporate
- Image containers should use rounded corners and soft framing
- Placeholder image treatments should remain on-brand, using soft colour fields and warm illustration-led styles

## 7f. Icons and Decorative Details

- Icons should be simple, rounded, and friendly rather than technical or sharp-edged
- Decorative details should support warmth and softness: circles, pills, gentle shapes, soft dividers
- Avoid over-decorating layouts with too many stickers, doodles, or novelty effects
- Accent colours such as Mint Green and Acid Lime should be used in small details rather than large competing visual fields
- Circular decorative graphics may be used in corners, behind content blocks, or as cropped background accents, but they should be used sparingly and never dominate the whole page

## 7g. Layout and Spacing Rules

- Sections should have clear breathing room between them
- Mobile layouts should prioritize one-column clarity first, then expand gracefully to tablet and desktop
- Important parent actions should appear early and clearly within the page flow
- Pages should feel open and calm, not busy or compressed
- Visual hierarchy should come from spacing, scale, and colour contrast rather than from excessive ornament

## 7h. Footer Direction

- The footer should use the same warm, playful visual language as the rest of the site while feeling slightly more graphic
- A large background wordmark or oversized typographic watermark can be used as a compositional device
- That background typography should behave more like a cut-off visual pattern than a readable line of text
- The current approved direction is a large white watermark-style `TJSGYM` layer behind the footer content
- Footer content should stay simple and useful: brand summary, useful links, and essential information
- Newsletter modules and social icon clusters are not currently part of the preferred footer direction
