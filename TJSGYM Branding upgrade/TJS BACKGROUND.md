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

| Name | Hex | CSS Variable | Role |
| --- | --- | --- | --- |
| Deep Plum | `#7A2A7A` | `--plum` | Primary anchor colour for headings, logo use, strong contrast blocks, and key UI structure |
| Plum Dark | `#5C1F5C` | `--plum-dark` | Footer background, darker variant for depth |
| Bright Magenta | `#D94A9F` | `--magenta` | High-energy accent within the main system, suitable for emphasis and call-to-action moments |
| Soft Blush Pink | `#F3C7E7` | `--blush` | Warm, friendly supporting background colour; also used for decorative circle accents |
| Light Pink Wash | `#F7E9F5` | `--pink-wash` | Soft surface colour for cards, card body gradients, and secondary backgrounds |

## 4b. Secondary Colours

These colours are not intended to dominate page layouts. They should be used selectively as supporting accents.

| Name | Hex | CSS Variable | Role |
| --- | --- | --- | --- |
| Mint Green | `#39C39A` | `--mint` | Fresh supporting accent for icons, highlights, trust marks, and decorative corner accents |
| Acid Lime | `#DDEB3C` | `--lime` | Small, energetic highlight colour for standout moments, badges, or emphasis points |

## 4c. Tertiary Colour

| Name | Hex | CSS Variable | Role |
| --- | --- | --- | --- |
| Warm Cream | `#F5EFE7` | `--cream` | Gentle neutral background tone for occasional breathing space and soft contrast |

## 4d. Functional Colours

| Name | Hex | CSS Variable | Role |
| --- | --- | --- | --- |
| White | `#FFFFFF` | `--white` | Card surfaces, nav background |
| Text Dark | `#2D0A2D` | `--text-dark` | Headings and high-contrast text |
| Text Body | `#4B3044` | `--text-body` | Body copy |
| Text Light | `#7A5A72` | `--text-light` | Secondary text, captions, metadata |

## 4e. Page Background

The site background is `#FFF9FD` — an extremely light, near-white pink. This is lighter than `--pink-wash` and intentionally close to white. It is overlaid with a subtle dot texture:

```css
background-color: #FFF9FD;
background-image: radial-gradient(circle, rgba(122,42,122,0.04) 1px, transparent 1px);
background-size: 22px 22px;
```

This creates a barely-visible plum-tinted dot grid that adds texture without weight. The effect should be imperceptible at a glance — it only becomes visible on close inspection.

**Do not** use large soft radial gradient orbs or glow blobs as background elements. These were evaluated and rejected during the prototype phase as too heavy and visually distracting.

## 4f. Colour Usage Principles

- The website should remain primarily main-colour led.
- Deep Plum is the structural anchor and should be the dominant text and contrast colour.
- The page base (`#FFF9FD`) is deliberately near-white — it should feel clean and airy, not pink.
- Bright Magenta should be used as a stronger emotional accent within the core identity.
- Blush and Pink Wash appear in card backgrounds, borders, and decorative accents — not as dominant page fields.
- Mint Green is used sparingly: trust checkmarks, star ratings in testimonials, and small corner circle accents.
- Acid Lime should be used only in very small touches (badges, highlights) — never as a background.
- Warm Cream should be used selectively, not as the default page base.

## 4g. Page Colour Scheme Option 1

Page Colour Scheme Option 1 is the strongest expression of the TJSGYM palette and should be used for pages that need the clearest brand presence, especially homepage-style hero layouts and primary landing sections.

Recommended emphasis:

- Deep Plum for major headings and dividers
- Near-white `#FFF9FD` with dot texture for the main page background
- Soft Blush Pink and Light Pink Wash for card surfaces and component backgrounds
- Bright Magenta for key supporting accents where stronger energy is needed

This option is the most directly aligned with the homepage visual direction and should be considered the primary brand presentation scheme.

## 4h. Page Colour Scheme Option 2

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

| Usage | Font | CSS Variable | Recommended Weight | Reason |
| --- | --- | --- | --- | --- |
| Headings | `Fredoka` | `--font-heading` | `500` | Rounded, soft, friendly, and visually aligned with a cuddly, child-focused brand tone |
| Body | `DM Sans` | `--font-body` | `300-600` | Clean, legible, modern, and highly suitable for mobile reading |

## 5b. Font Size Scale (fluid)

All font sizes use `clamp()` for fluid scaling between mobile and desktop:

| Token | CSS Variable | Range |
| --- | --- | --- |
| Small | `--text-sm` | 0.8rem → 0.875rem |
| Base | `--text-base` | 0.938rem → 1rem |
| Large | `--text-lg` | 1.063rem → 1.125rem |
| XL | `--text-xl` | 1.25rem → 1.5rem |
| 2XL | `--text-2xl` | 1.5rem → 2rem |
| 3XL | `--text-3xl` | 1.875rem → 2.5rem |
| 4XL | `--text-4xl` | 2.25rem → 3.25rem |
| 5XL | `--text-5xl` | 2.75rem → 4rem |

## 5c. Typography Rationale

- `Fredoka` gives the site warmth and personality without becoming overly childish.
- A lighter heading weight (500) works better than a heavy one; heavier weights made the lettering feel crowded and overly dense.
- `DM Sans` provides the practical clarity needed for schedules, programme details, FAQs, and booking flows.
- This pairing is more aligned with TJSGYM's target audience than a more formal editorial serif direction.

## 5d. Implementation Note

Headings should avoid feeling overly compressed or heavy. The preferred expression is soft and rounded, but still calm and readable. Large all-caps headings should be used selectively and with careful spacing. Heading colours default to `--text-dark` (`#2D0A2D`) in global styles; on the homepage, `h1` and `h2` within sections use `--plum` (`#7A2A7A`) with italic `em` spans in `--magenta` for emphasis.

---

# 6. Design Tone

The client used the word "cuddly" to describe the desired feeling. This remains an accurate and useful creative direction, and it is consistent with the current colour and typography decisions. However, it should be interpreted as soft, warm, and reassuring, rather than childish or overly decorative.

## 6a. Design Translation

- Rounded shapes throughout: generally `16-24px` border radius on cards and panels, and `999px` on pills, tags, and buttons
- Soft colour transitions: use blush, pink-wash, and plum-tinted gradients rather than harsh flat blocks
- Warm neutrals: avoid cold greys; tint neutrals subtly toward the plum-pink system
- Generous spacing: content should feel breathable and calm, never cramped
- Gentle hierarchy: clear but not aggressive; this is not a hard-sell sports marketing aesthetic
- Parent-friendly tone of voice: warm, encouraging, clear, and trustworthy
- Photography preference: real, warm, joyful imagery of children and families should always outperform generic stock imagery
- Fallback imagery: if final photography is not yet available, use warm illustrated or softly styled placeholders rather than cold empty boxes
- Circular decorative elements are part of the visual language but are used sparingly and in specific, approved locations only (see Section 7f)

## 6b. Accuracy Check

This design tone is broadly correct and aligned with the current direction. A few clarifications are important:

- "Rounded everything" is directionally right, but should still be controlled and consistent rather than exaggerated
- "Soft gradients" is correct, but some areas can still use confident solid colour if it remains within the warm palette
- "No cold greys" is correct and matches the current system well
- "Approachable copy tone" is correct and important for the parent audience
- The overall tone should feel premium and trustworthy as well as cuddly; it should not drift into nursery branding or overly childish design
- Circular and curved decorative motifs are approved, but in the implemented design they are used only on testimonial cards — not as background orbs, not on facilities feature cards, and not on the pathway strip

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

- Cards use `--radius-xl` (24px) for the main card shell; feature sub-cards use `--radius-md` (16px)
- Card body backgrounds use a subtle diagonal gradient: `linear-gradient(160deg, #ffffff 0%, var(--pink-wash) 100%)`
- Cards use layered, plum/magenta-tinted shadows rather than standard grey box-shadows:
  ```css
  box-shadow:
    2px 4px 8px rgba(0, 0, 0, 0.02),
    8px 16px 24px rgba(0, 0, 0, 0.03),
    0 32px 64px rgba(217, 74, 159, 0.10),
    inset 0 1px 1px rgba(255, 255, 255, 1),
    0 0 0 1px rgba(217, 74, 159, 0.07);
  ```
- Cards should avoid heavy borders; use the `inset` white highlight and the outer ring (0 0 0 1px) for definition
- Content inside cards should never feel cramped

## 7c. Navigation

- Navigation should feel straightforward and parent-friendly, with a clear path to the most important information pages
- Desktop navigation should be simple and uncluttered; avoid dense multi-level structures unless absolutely necessary
- Mobile navigation must prioritize speed and clarity; hamburger menu slides in from the right at ≤860px
- The current preferred desktop direction is a clean text-based header with a sticky frosted-glass bar
- Top-level navigation: `Home`, `Classes`, `Timetable`, `Coaches`, `History`, `Club Info`, `Contact Us`

## 7d. Forms and Booking UI

- Form fields should be large, touch-friendly, and easy to scan on mobile
- Labels must be clear, direct, and reassuring
- Error states should be calm and helpful, not alarming
- Booking and enquiry flows should minimize friction and reduce the number of steps wherever possible
- Payment and checkout surfaces should feel especially trustworthy, clean, and calm

## 7e. Imagery and Media Blocks

- Photography should show happy, confident children and supportive environments wherever possible
- Imagery should feel warm and natural rather than heavily filtered or corporate
- Image containers should use rounded corners and soft framing (`--radius-xl`)
- The homepage hero uses `mix-blend-mode: multiply` on the gymnast illustration, allowing white image backgrounds to dissolve into the page background — this technique is intentional and should not be applied to other card images
- Class card images use `object-fit: cover` with upper-biased `object-position` (e.g. `center 18%`) to crop into 16:9 containers while avoiding watermark areas in the lower-right of illustration images
- Class card images do not use `mix-blend-mode` — they display cleanly with white card backgrounds

## 7f. Icons and Decorative Details

- Icons should be simple, rounded, and friendly rather than technical or sharp-edged
- Decorative details should support warmth and softness: circles, pills, gentle shapes, soft dividers
- Avoid over-decorating layouts with too many stickers, doodles, or novelty effects
- Accent colours such as Mint Green and Acid Lime should be used in small details rather than large competing visual fields

### Corner Circle Accents — Approved Locations

Corner circle accents are used in exactly one location in the current design: **testimonial cards**. They are implemented as CSS `radial-gradient` hard-edge circles via the `background` shorthand:

```css
.testimonial-card {
  background:
    radial-gradient(circle at top right,  rgba(243, 199, 231, 0.75) 0, rgba(243, 199, 231, 0.75) 64px, transparent 65px),
    radial-gradient(circle at bottom left, rgba(57, 195, 154, 0.18)  0, rgba(57, 195, 154, 0.18)  52px, transparent 53px),
    var(--white);
}
```

- **Top-right**: Soft Blush Pink (`#F3C7E7` at 75% opacity), 64px radius
- **Bottom-left**: Mint Green (`#39C39A` at 18% opacity), 52px radius
- The card must have `overflow: hidden` so corners clip cleanly

**Do not** apply this pattern to:
- Facilities feature cards
- The pathway strip card
- Section backgrounds
- Any other component unless explicitly approved

### Background Texture

A barely-visible dot grid is applied globally to the body:

```css
background-image: radial-gradient(circle, rgba(122,42,122,0.04) 1px, transparent 1px);
background-size: 22px 22px;
```

This is the only approved background texture. Do not add radial gradient orbs, glow effects, or large decorative blobs to the page background.

## 7g. The Age Progression Pathway Strip

The pathway strip (`1 → 2 → 3 → 4`) appears on both the homepage and the classes page. Design rules:

- White card background with plum-tinted shadow and subtle border ring
- Numbered dots use a plum-to-magenta gradient with magenta glow shadow
- Arrows are CSS lines with a CSS border-trick arrowhead (not text characters or SVG)
- Arrow line: blush-to-magenta gradient, 2px height, 44px width
- Arrowhead: `border-left: 7px solid var(--magenta)` with transparent top/bottom borders
- On mobile (≤560px): switches to a 2-column grid; arrows are hidden

## 7h. Trust Strip

The accreditation strip (British Gymnastics, London Gymnastics, SEN Inclusive, 36+ Years) appears just below the hero on the homepage. Design rules:

- **No white card background** — content sits directly on the page background, framed only by top and bottom blush borders
- Each trust item has a fixed-height visual zone (56px) for logo/icon alignment, followed by two text lines (label + sub-label)
- Logos use `object-fit: contain` within their maximum dimensions
- The "36+ Years" item uses the Fredoka heading font at `--text-4xl` as the visual element, in `--plum` colour
- Vertical dividers between items are `1px solid var(--blush)`, hidden on mobile
- This transparent treatment is intentional — it creates a sense of calm credibility rather than a promotional badge cluster

## 7i. Testimonial Cards

- 3-column grid on desktop, 2-column on tablet (≤1024px), single column on mobile (≤768px)
- White base with blush + mint corner circle accents (see Section 7f)
- Subtle gradient border via CSS `::before` mask technique (magenta at top-left, plum at bottom-right)
- Star rating row uses `--mint` colour
- Quote text is italic, `--text-body` colour, 1.75 line-height
- Hover: `translateY(-4px)` lift

## 7j. Facilities Features

- 2×2 grid of feature cards within the facilities section
- Each card: white-to-pink-wash gradient background, 3px magenta left border accent
- Layered magenta-tinted shadow (lighter than class cards)
- **No corner circle accents** — these were evaluated and removed; the left border accent provides sufficient visual character

## 7k. Layout and Spacing Rules

- Sections should have clear breathing room between them (default: `--space-4xl` = 6rem top and bottom)
- Mobile layouts should prioritize one-column clarity first, then expand gracefully to tablet and desktop
- Important parent actions should appear early and clearly within the page flow
- Pages should feel open and calm, not busy or compressed
- Visual hierarchy should come from spacing, scale, and colour contrast rather than from excessive ornament
- The layout container is `1200px` max-width, with fluid padding: `clamp(1rem, 4vw, 2rem)`

## 7l. Footer Direction

- The footer uses `--plum-dark` (`#5C1F5C`) as its background, with white text and white/72% opacity secondary text
- Footer content is a 3-column grid: brand summary + badges | partner links | find us (address + contact)
- Footer bottom bar: copyright left, privacy policy right; thin white/10% opacity top border
- **The large TJSGYM watermark/wordmark behind the footer content has been removed** — it was visually confusing and added unnecessary weight. The footer communicates brand presence through the plum background and Fredoka headings instead.
- Newsletter modules and social icon clusters are not currently part of the preferred footer direction

---

# 8. Shadow System

Shadows in this design are intentionally warm and plum/magenta-tinted. Cold grey shadows are not used anywhere on the site.

| Token | CSS Variable | Value |
| --- | --- | --- |
| Small | `--shadow-sm` | `0 1px 3px rgba(122, 42, 122, 0.06)` |
| Medium | `--shadow-md` | `0 4px 16px rgba(122, 42, 122, 0.10)` |
| Large | `--shadow-lg` | `0 8px 32px rgba(122, 42, 122, 0.14)` |

For class cards and interactive elevated surfaces, layered shadows are preferred over the single-token shadows:

```css
box-shadow:
  2px 4px 8px rgba(0, 0, 0, 0.02),
  8px 16px 24px rgba(0, 0, 0, 0.03),
  0 32px 64px rgba(217, 74, 159, 0.10),
  inset 0 1px 1px rgba(255, 255, 255, 1),
  0 0 0 1px rgba(217, 74, 159, 0.07);
```

---

# 9. Spacing Scale

All spacing uses an 8px base system. Use CSS variables, not raw pixel values.

| Token | Value | px equivalent |
| --- | --- | --- |
| `--space-xs` | 0.25rem | 4px |
| `--space-sm` | 0.5rem | 8px |
| `--space-md` | 1rem | 16px |
| `--space-lg` | 1.5rem | 24px |
| `--space-xl` | 2rem | 32px |
| `--space-2xl` | 3rem | 48px |
| `--space-3xl` | 4rem | 64px |
| `--space-4xl` | 6rem | 96px |
| `--space-5xl` | 8rem | 128px |

---

# 10. Responsive Breakpoints

| Breakpoint | Trigger | Key changes |
| --- | --- | --- |
| ≤1024px | Tablet | Testimonials grid: 3-col → 2-col |
| ≤860px | Mobile nav | Hamburger menu appears; nav slides in from right |
| ≤768px | Mobile | Hero: 2-col → 1-col; facilities/location stack; testimonials: 1-col; footer: 1-col; `.section` padding reduced |
| ≤560px | Small mobile | Pathway strip: flex → 2×2 grid; arrows hidden |
| ≤480px | XS | Class cards: 2-col → 1-col |
