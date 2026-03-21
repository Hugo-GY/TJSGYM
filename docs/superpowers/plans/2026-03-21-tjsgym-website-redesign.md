# TJSGYM Website Redesign — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build TJ's Gymnastics Club website from scratch as a static HTML/CSS/JS prototype (15 pages), applying the new "cuddly" brand system (Deep Plum + Magenta + Blush pink palette, Fredoka/DM Sans typography, rounded/organic shapes), with a clean mobile-first responsive design ready for later WordPress conversion.

**Architecture:** Each page is a standalone HTML file sharing a common CSS system (`css/global.css` for variables/reset/typography/nav/footer, `css/components.css` for reusable cards/buttons/sections, plus per-page CSS files for page-specific layout). No build tools — pure HTML/CSS/JS served via a simple static server. Content is extracted from `/Users/sharktopia/Desktop/TJS_V2/Original web/`. Design references are in `/Users/sharktopia/Desktop/TJS_V2/TJSGYM Branding upgrade/`.

**Tech Stack:** HTML5, CSS3 (custom properties, grid, flexbox), vanilla JS (mobile nav toggle, accordions), Google Fonts (Fredoka + DM Sans)

---

## Content Source Reference

All text content and images come from the original website archive. Key source locations:

| Content | Source Path |
|---|---|
| Home page text | `Original web/Home/index.html` |
| Tiddler class | `Original web/tiddler-gymnastic-classes/index.html` |
| Toddler class | `Original web/toddler-gymnastic-classes/index.html` |
| Mini Gym class | `Original web/mini-gymnastic-classes/index.html` |
| Gymnastics class | `Original web/gymnastic-classes/index.html` |
| Competition & Displays | `Original web/competition-and-displays/index.html` |
| Coaches (17 bios + photos) | `Original web/TJs_Gym_Our_Coaches/` |
| History (text + 7 photos) | `Original web/TJs_Gym_History/` |
| Facilities & Location | `Original web/facilities-location/facilities-location.txt` |
| Club Policies | `Original web/club-rules/club-rules.txt` |
| T&C on Bookings | `Original web/terms-and-conditions-on-bookings/terms-and-conditions-on-bookings.txt` |
| Timetable data | `Original web/timetable/timetable.html` |
| Term Dates & Costs | `Original web/Terms Dates& Costs/costs.html` |
| Club Kit | `Original web/clothing-supplier/clothing-supplier.txt` |
| Contact info | Footer: Raynes Park Sports Pavilion, Taunton Avenue, London SW20 0BH / 01252 702295 / info@tjsgymclub.co.uk |
| Privacy Policy | `Original web/privacy-policy/index.html` |
| Logo | `Brand assest/tjs-logo2.png` |

## Brand System Reference

Full spec: `TJSGYM Branding upgrade/TJS BACKGROUND.md`

### Colours (CSS custom properties)
```css
:root {
  /* Main */
  --plum: #7A2A7A;
  --plum-dark: #5C1F5C;
  --magenta: #D94A9F;
  --blush: #F3C7E7;
  --pink-wash: #F7E9F5;
  /* Secondary */
  --mint: #39C39A;
  --lime: #DDEB3C;
  /* Tertiary */
  --cream: #F5EFE7;
  /* Functional */
  --white: #FFFFFF;
  --text-dark: #2D0A2D;
  --text-body: #4B3044;
}
```

### Typography
- Headings: `Fredoka, sans-serif` weight 500
- Body: `'DM Sans', sans-serif` weight 300–600

### Design Tone
- "Cuddly": soft, warm, reassuring — not childish
- Rounded corners: 12–20px cards, 999px pills/buttons
- Soft gradients and warm transitions
- Generous spacing and breathing room
- Organic circular decorative accents (used sparingly)
- Mobile-first, one-column clarity
- Pupbar reference style: playful layout, organic blob shapes, circular image crops, bold typography, alternating section backgrounds

### Navigation
```
Home | Classes | Timetable | Coaches | History | Club Info | Contact Us
```
- Clean text-based header, no heavy button bar
- Mobile: hamburger → slide-in menu
- No "Book Here" CTA button in nav for v2 (timetable is informational only)

### Footer
- Deep Plum background
- Large white watermark "TJSGYM" behind content
- Simple useful links + contact info
- Privacy Policy link

---

## File Structure

```
TJS_V2/
├── index.html                    # Home
├── classes.html                  # Classes overview
├── classes/
│   ├── tiddler.html              # Tiddler Gym detail
│   ├── toddler.html              # Toddler Gym detail
│   ├── mini-gym.html             # Mini Gym detail
│   └── gymnastics.html           # Gymnastics detail
├── timetable.html                # Timetable (pure table view)
├── coaches.html                  # Our Coaches
├── history.html                  # Our Story / History
├── club-info.html                # Club Info (policies, news, kit, info sheet)
├── contact.html                  # Contact Us
├── privacy-policy.html           # Privacy Policy
├── css/
│   ├── global.css                # Reset, variables, typography, nav, footer
│   ├── components.css            # Reusable: cards, buttons, sections, badges, accordions
│   ├── home.css                  # Home page specific
│   ├── classes.css               # Classes overview specific
│   ├── class-detail.css          # Shared class detail page layout
│   ├── timetable.css             # Timetable specific
│   ├── coaches.css               # Coaches specific
│   ├── history.css               # History specific
│   ├── club-info.css             # Club Info specific
│   ├── contact.css               # Contact specific
│   └── privacy.css               # Privacy Policy specific
├── js/
│   └── main.js                   # Mobile nav, accordions, smooth scroll
├── images/
│   ├── logo.png                  # TJ's logo (copied from Brand assest/)
│   ├── favicon.png               # Favicon (generated from logo)
│   ├── coaches/                  # Coach photos (copied from Original web/)
│   ├── history/                  # History photos
│   ├── facilities/               # Facility photos
│   └── classes/                  # Class hero images and gallery photos
├── serve.mjs                     # Simple static dev server
└── docs/
    └── superpowers/
        └── plans/
            └── 2026-03-21-tjsgym-website-redesign.md  # This plan
```

Each file's responsibility:

| File | Responsibility |
|---|---|
| `css/global.css` | CSS reset, custom properties (colours, fonts, spacing scale), base typography, header/nav, footer, page-level background, responsive breakpoints |
| `css/components.css` | `.card`, `.btn`, `.btn-secondary`, `.badge`, `.section`, `.section-alt`, `.accordion`, `.hero`, `.wave-divider`, `.trust-badge`, `.cta-band` |
| `css/home.css` | Hero layout, class cards grid, facilities preview, testimonials carousel, location section |
| `css/classes.css` | Class overview grid, competition section, T&C accordion |
| `css/class-detail.css` | Shared layout for all 4 class detail pages: hero, info table, times/booking grid, photo gallery |
| `css/timetable.css` | Day-grouped table styling |
| `css/coaches.css` | Coach grid, circular avatar styling, leadership highlight cards |
| `css/history.css` | Timeline/narrative layout, historical photo gallery |
| `css/club-info.css` | Multi-section info page: policies, news, kit, download |
| `css/contact.css` | Contact form + map + info layout |
| `css/privacy.css` | Simple prose page styling |
| `js/main.js` | Mobile hamburger toggle, accordion expand/collapse, smooth scroll to anchors |

---

## Task Breakdown

**Global rule for all pages:** Every HTML page must include `<link rel="icon" type="image/png" href="images/favicon.png">` (use `../images/favicon.png` for sub-pages) and a unique `<meta name="description" content="...">` tag with page-specific SEO copy targeting parents searching for gymnastics classes in Wimbledon/SW London.

### Task 1: Project Setup & Dev Server

**Files:**
- Create: `serve.mjs`
- Create: `images/logo.png` (copy from `Brand assest/tjs-logo2.png`)
- Create: `images/` subdirectories

This task sets up the dev environment and copies all image assets into the project.

- [ ] **Step 1: Create project directories**

```bash
cd /Users/sharktopia/Desktop/TJS_V2
mkdir -p css js images/coaches images/history images/facilities images/classes classes
```

- [ ] **Step 2: Copy logo and create favicon**

```bash
cp "Brand assest/tjs-logo2.png" images/logo.png
# Create a simple favicon copy (can be refined later with proper ICO generation)
cp "Brand assest/tjs-logo2.png" images/favicon.png
```

- [ ] **Step 3: Copy coach photos**

```bash
# Copy all image files from coaches source
for f in "Original web/TJs_Gym_Our_Coaches/"*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
  [ -f "$f" ] && cp "$f" images/coaches/
done
```

- [ ] **Step 4: Copy history photos**

```bash
for f in "Original web/TJs_Gym_History/"*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
  [ -f "$f" ] && cp "$f" images/history/
done
```

- [ ] **Step 5: Copy facilities images**

```bash
cp "Original web/facilities-location/image_01.jpg" images/facilities/ 2>/dev/null || true
```

- [ ] **Step 6: Create dev server**

```javascript
// serve.mjs
import { createServer } from 'http';
import { readFile } from 'fs/promises';
import { join, extname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = fileURLToPath(new URL('.', import.meta.url));
const PORT = 3000;

const MIME = {
  '.html': 'text/html',
  '.css': 'text/css',
  '.js': 'application/javascript',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.svg': 'image/svg+xml',
  '.webp': 'image/webp',
  '.ico': 'image/x-icon',
  '.woff2': 'font/woff2',
};

createServer(async (req, res) => {
  let url = req.url === '/' ? '/index.html' : req.url;
  // Add .html if no extension and not a file
  if (!extname(url)) url += '.html';
  const filePath = join(__dirname, url);
  try {
    const data = await readFile(filePath);
    res.writeHead(200, { 'Content-Type': MIME[extname(filePath)] || 'application/octet-stream' });
    res.end(data);
  } catch {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('404 Not Found');
  }
}).listen(PORT, () => console.log(`http://localhost:${PORT}`));
```

- [ ] **Step 7: Verify server starts**

Run: `cd /Users/sharktopia/Desktop/TJS_V2 && node serve.mjs &`
Expected: `http://localhost:3000` printed to console

- [ ] **Step 8: Commit**

```bash
git init
git add serve.mjs images/
git commit -m "chore: project setup with dev server and image assets"
```

---

### Task 2: Global CSS — Variables, Reset, Typography

**Files:**
- Create: `css/global.css`

This establishes the entire design system as CSS custom properties, the reset, and base typography.

- [ ] **Step 1: Create global.css with variables and reset**

```css
/* ========================================
   TJ's Gymnastics Club — Global Styles
   ======================================== */

/* --- Google Fonts --- */
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Fredoka:wght@400;500;600&display=swap');

/* --- Design Tokens --- */
:root {
  /* Main palette */
  --plum: #7A2A7A;
  --plum-dark: #5C1F5C;
  --magenta: #D94A9F;
  --blush: #F3C7E7;
  --pink-wash: #F7E9F5;

  /* Secondary */
  --mint: #39C39A;
  --lime: #DDEB3C;

  /* Tertiary */
  --cream: #F5EFE7;

  /* Functional */
  --white: #FFFFFF;
  --text-dark: #2D0A2D;
  --text-body: #4B3044;
  --text-light: #7A5A72;

  /* Spacing scale (8px base) */
  --space-xs: 0.25rem;   /* 4px */
  --space-sm: 0.5rem;    /* 8px */
  --space-md: 1rem;      /* 16px */
  --space-lg: 1.5rem;    /* 24px */
  --space-xl: 2rem;      /* 32px */
  --space-2xl: 3rem;     /* 48px */
  --space-3xl: 4rem;     /* 64px */
  --space-4xl: 6rem;     /* 96px */
  --space-5xl: 8rem;     /* 128px */

  /* Radii */
  --radius-sm: 8px;
  --radius-md: 16px;
  --radius-lg: 20px;
  --radius-xl: 24px;
  --radius-pill: 999px;
  --radius-circle: 50%;

  /* Typography */
  --font-heading: 'Fredoka', sans-serif;
  --font-body: 'DM Sans', sans-serif;

  /* Font sizes (fluid) */
  --text-sm: clamp(0.8rem, 0.75rem + 0.25vw, 0.875rem);
  --text-base: clamp(0.938rem, 0.875rem + 0.3vw, 1rem);
  --text-lg: clamp(1.063rem, 1rem + 0.3vw, 1.125rem);
  --text-xl: clamp(1.25rem, 1.1rem + 0.5vw, 1.5rem);
  --text-2xl: clamp(1.5rem, 1.2rem + 1vw, 2rem);
  --text-3xl: clamp(1.875rem, 1.4rem + 1.5vw, 2.5rem);
  --text-4xl: clamp(2.25rem, 1.6rem + 2vw, 3.25rem);
  --text-5xl: clamp(2.75rem, 1.8rem + 3vw, 4rem);

  /* Shadows */
  --shadow-sm: 0 1px 3px rgba(122, 42, 122, 0.06);
  --shadow-md: 0 4px 12px rgba(122, 42, 122, 0.08);
  --shadow-lg: 0 8px 24px rgba(122, 42, 122, 0.1);

  /* Transitions */
  --ease: cubic-bezier(0.4, 0, 0.2, 1);
  --duration: 0.25s;

  /* Layout */
  --container-max: 1200px;
  --container-padding: var(--space-lg);
}

/* --- Reset --- */
*,
*::before,
*::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
  -webkit-text-size-adjust: 100%;
}

body {
  font-family: var(--font-body);
  font-weight: 400;
  font-size: var(--text-base);
  line-height: 1.65;
  color: var(--text-body);
  background-color: var(--white);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

img {
  max-width: 100%;
  height: auto;
  display: block;
}

a {
  color: var(--plum);
  text-decoration: none;
  transition: color var(--duration) var(--ease);
}
a:hover {
  color: var(--magenta);
}

/* --- Typography --- */
h1, h2, h3, h4, h5, h6 {
  font-family: var(--font-heading);
  font-weight: 500;
  color: var(--text-dark);
  line-height: 1.2;
}

h1 { font-size: var(--text-5xl); }
h2 { font-size: var(--text-4xl); }
h3 { font-size: var(--text-3xl); }
h4 { font-size: var(--text-2xl); }
h5 { font-size: var(--text-xl); }
h6 { font-size: var(--text-lg); }

p + p { margin-top: var(--space-md); }

/* --- Container --- */
.container {
  width: 100%;
  max-width: var(--container-max);
  margin: 0 auto;
  padding-left: var(--container-padding);
  padding-right: var(--container-padding);
}

/* --- Section spacing --- */
.section {
  padding-top: var(--space-4xl);
  padding-bottom: var(--space-4xl);
}

@media (max-width: 768px) {
  .section {
    padding-top: var(--space-3xl);
    padding-bottom: var(--space-3xl);
  }
}
```

- [ ] **Step 2: Create a minimal test page to verify**

Create a temporary `index.html` to check fonts, colors, spacing load correctly:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TJ's Gymnastics Club</title>
  <link rel="stylesheet" href="css/global.css">
</head>
<body>
  <div class="container section">
    <h1>TJ's Gymnastics Club</h1>
    <h2>Building Confidence Through Movement</h2>
    <p>Welcome to TJ's Gymnastics Club in Wimbledon.</p>
    <p style="color: var(--plum);">Plum text</p>
    <p style="color: var(--magenta);">Magenta text</p>
    <p style="background: var(--blush); padding: 1rem; border-radius: var(--radius-md);">Blush card</p>
  </div>
</body>
</html>
```

- [ ] **Step 3: Visual check with /browse**

Run: `node serve.mjs &` then use `/browse` to navigate to `http://localhost:3000` and screenshot.
Expected: Fredoka headings, DM Sans body, correct colours rendering.

- [ ] **Step 4: Commit**

```bash
git add css/global.css index.html
git commit -m "feat: global CSS design system — variables, reset, typography"
```

---

### Task 3: Header/Nav & Footer

**Files:**
- Modify: `css/global.css` (append nav + footer styles)
- Create: `js/main.js`
- Modify: `index.html` (add nav + footer markup)

The header and footer are shared across all pages. Build them as reusable HTML blocks.

- [ ] **Step 1: Add nav styles to global.css**

Append to `css/global.css`:

```css
/* ========================================
   Header / Navigation
   ======================================== */
.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: var(--white);
  box-shadow: var(--shadow-sm);
}

.nav-wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: var(--container-max);
  margin: 0 auto;
  padding: var(--space-md) var(--container-padding);
}

.logo-link {
  display: flex;
  align-items: center;
  flex-shrink: 0;
}

.logo-img {
  height: 48px;
  width: auto;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: var(--space-lg);
}

.nav-link {
  font-family: var(--font-body);
  font-size: var(--text-sm);
  font-weight: 500;
  color: var(--text-dark);
  text-decoration: none;
  padding: var(--space-xs) 0;
  border-bottom: 2px solid transparent;
  transition: all var(--duration) var(--ease);
}

.nav-link:hover,
.nav-link.active {
  color: var(--plum);
  border-bottom-color: var(--magenta);
}

/* Hamburger button */
.nav-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: var(--space-sm);
}

.nav-toggle span {
  display: block;
  width: 24px;
  height: 2px;
  background: var(--text-dark);
  margin: 5px 0;
  border-radius: 2px;
  transition: all 0.3s var(--ease);
}

/* Mobile nav */
@media (max-width: 768px) {
  .nav-toggle {
    display: block;
  }

  .nav-links {
    position: fixed;
    top: 0;
    right: -100%;
    width: 280px;
    height: 100vh;
    background: var(--white);
    flex-direction: column;
    align-items: flex-start;
    padding: var(--space-4xl) var(--space-xl) var(--space-xl);
    gap: var(--space-md);
    box-shadow: var(--shadow-lg);
    transition: right 0.35s var(--ease);
    z-index: 200;
  }

  .nav-links.open {
    right: 0;
  }

  .nav-link {
    font-size: var(--text-lg);
    width: 100%;
    padding: var(--space-sm) 0;
  }

  .nav-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(45, 10, 45, 0.4);
    z-index: 150;
  }

  .nav-overlay.open {
    display: block;
  }
}

/* ========================================
   Footer
   ======================================== */
.site-footer {
  background: var(--plum-dark);
  color: var(--white);
  position: relative;
  overflow: hidden;
}

.footer-watermark {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-family: var(--font-heading);
  font-weight: 600;
  font-size: clamp(6rem, 15vw, 14rem);
  color: rgba(255, 255, 255, 0.04);
  white-space: nowrap;
  pointer-events: none;
  user-select: none;
  letter-spacing: 0.05em;
}

.footer-inner {
  position: relative;
  z-index: 1;
  max-width: var(--container-max);
  margin: 0 auto;
  padding: var(--space-3xl) var(--container-padding) var(--space-xl);
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: var(--space-2xl);
}

.footer-section h4 {
  font-family: var(--font-heading);
  font-size: var(--text-lg);
  color: var(--white);
  margin-bottom: var(--space-md);
}

.footer-section p,
.footer-section a {
  color: rgba(255, 255, 255, 0.75);
  font-size: var(--text-sm);
  line-height: 1.7;
}

.footer-section a:hover {
  color: var(--white);
}

.footer-links {
  list-style: none;
}

.footer-links li + li {
  margin-top: var(--space-xs);
}

.footer-links a {
  color: rgba(255, 255, 255, 0.75);
  font-size: var(--text-sm);
}

.footer-bottom {
  position: relative;
  z-index: 1;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  max-width: var(--container-max);
  margin: 0 auto;
  padding: var(--space-lg) var(--container-padding);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-bottom p,
.footer-bottom a {
  font-size: var(--text-sm);
  color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
  .footer-inner {
    grid-template-columns: 1fr;
    gap: var(--space-xl);
  }

  .footer-bottom {
    flex-direction: column;
    gap: var(--space-sm);
    text-align: center;
  }
}
```

- [ ] **Step 2: Create js/main.js**

```javascript
// Mobile navigation toggle
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  const overlay = document.querySelector('.nav-overlay');

  if (toggle && navLinks) {
    toggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      overlay?.classList.toggle('open');
      toggle.setAttribute('aria-expanded',
        navLinks.classList.contains('open'));
    });

    overlay?.addEventListener('click', () => {
      navLinks.classList.remove('open');
      overlay.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    });
  }

  // Accordion
  document.querySelectorAll('.accordion-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.accordion-item');
      const isOpen = item.classList.contains('open');

      // Close siblings
      item.parentElement.querySelectorAll('.accordion-item.open').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.accordion-trigger').setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        item.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
      }
    });
  });
});
```

- [ ] **Step 3: Update index.html with nav + footer markup**

Replace the test `index.html` with:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="TJ's Gymnastics Club in Wimbledon — gymnastics classes for children from 6 months to 12+. British Gymnastics registered. SEN inclusive. Book your child's class today.">
  <title>TJ's Gymnastics Club — Wimbledon</title>
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="css/global.css">
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <nav class="nav-wrap" aria-label="Main navigation">
      <a href="index.html" class="logo-link">
        <img src="images/logo.png" alt="TJ's Gymnastics Club" class="logo-img">
      </a>
      <div class="nav-overlay"></div>
      <div class="nav-links">
        <a href="index.html" class="nav-link active">Home</a>
        <a href="classes.html" class="nav-link">Classes</a>
        <a href="timetable.html" class="nav-link">Timetable</a>
        <a href="coaches.html" class="nav-link">Coaches</a>
        <a href="history.html" class="nav-link">History</a>
        <a href="club-info.html" class="nav-link">Club Info</a>
        <a href="contact.html" class="nav-link">Contact Us</a>
      </div>
      <button class="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </header>

  <!-- Page content placeholder -->
  <main>
    <div class="container section">
      <h1>Coming Soon</h1>
    </div>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <span class="footer-watermark">TJSGYM</span>
    <div class="footer-inner">
      <div class="footer-section">
        <h4>TJ's Gymnastics Club</h4>
        <p>Building confidence through movement since 1988. British Gymnastics registered. SEN inclusive.</p>
      </div>
      <div class="footer-section">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="classes.html">Classes</a></li>
          <li><a href="timetable.html">Timetable</a></li>
          <li><a href="coaches.html">Coaches</a></li>
          <li><a href="contact.html">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h4>Find Us</h4>
        <p>Raynes Park Sports Pavilion<br>Taunton Avenue<br>London SW20 0BH</p>
        <p style="margin-top: 0.5rem;">
          <a href="tel:01252702295">01252 702295</a><br>
          <a href="mailto:info@tjsgymclub.co.uk">info@tjsgymclub.co.uk</a>
        </p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 TJ's Gymnastics Club, Wimbledon. All rights reserved.</p>
      <a href="privacy-policy.html">Privacy Policy</a>
    </div>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>
```

- [ ] **Step 4: Visual check with /browse**

Screenshot desktop and mobile (375px) views.
Expected: Sticky header with text nav links, Deep Plum footer with "TJSGYM" watermark, mobile hamburger works.

- [ ] **Step 5: Commit**

```bash
git add css/global.css js/main.js index.html
git commit -m "feat: header/nav and footer with mobile hamburger menu"
```

---

### Task 4: Component Library

**Files:**
- Create: `css/components.css`

Reusable components used across multiple pages.

- [ ] **Step 1: Create components.css**

```css
/* ========================================
   Reusable Components
   ======================================== */

/* --- Buttons --- */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-sm);
  font-family: var(--font-body);
  font-weight: 600;
  font-size: var(--text-base);
  padding: var(--space-md) var(--space-xl);
  border-radius: var(--radius-pill);
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all var(--duration) var(--ease);
  line-height: 1;
}

.btn-primary {
  background: var(--magenta);
  color: var(--white);
}
.btn-primary:hover {
  background: var(--plum);
  color: var(--white);
  transform: translateY(-1px);
  box-shadow: var(--shadow-md);
}

.btn-secondary {
  background: transparent;
  color: var(--plum);
  border: 2px solid var(--plum);
}
.btn-secondary:hover {
  background: var(--plum);
  color: var(--white);
}

.btn-plum {
  background: var(--plum);
  color: var(--white);
}
.btn-plum:hover {
  background: var(--plum-dark);
  color: var(--white);
  transform: translateY(-1px);
}

.btn-sm {
  font-size: var(--text-sm);
  padding: var(--space-sm) var(--space-lg);
}

/* --- Cards --- */
.card {
  background: var(--white);
  border-radius: var(--radius-lg);
  padding: var(--space-xl);
  box-shadow: var(--shadow-sm);
  transition: all var(--duration) var(--ease);
}

.card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.card-blush {
  background: var(--blush);
  box-shadow: none;
}

.card-pink {
  background: var(--pink-wash);
  box-shadow: none;
}

/* --- Badges --- */
.badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-xs);
  font-family: var(--font-body);
  font-size: var(--text-sm);
  font-weight: 500;
  padding: var(--space-xs) var(--space-md);
  border-radius: var(--radius-pill);
  background: var(--pink-wash);
  color: var(--plum);
}

.badge-mint {
  background: rgba(57, 195, 154, 0.15);
  color: #1a7a5a;
}

.badge-lime {
  background: rgba(221, 235, 60, 0.25);
  color: #5a6000;
}

/* --- Section backgrounds --- */
.bg-white { background: var(--white); }
.bg-pink-wash { background: var(--pink-wash); }
.bg-blush { background: var(--blush); }
.bg-cream { background: var(--cream); }
.bg-plum { background: var(--plum); color: var(--white); }
.bg-plum h2, .bg-plum h3, .bg-plum h4 { color: var(--white); }

/* --- Section header (centered) --- */
.section-header {
  text-align: center;
  margin-bottom: var(--space-2xl);
}

.section-label {
  font-family: var(--font-body);
  font-size: var(--text-sm);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--magenta);
  margin-bottom: var(--space-sm);
}

.section-header h2 {
  margin-bottom: var(--space-md);
}

.section-header p {
  max-width: 600px;
  margin: 0 auto;
  color: var(--text-light);
}

/* --- Wave divider --- */
.wave-divider {
  width: 100%;
  height: auto;
  display: block;
  margin: -1px 0;
}

/* --- CTA Band --- */
.cta-band {
  background: var(--magenta);
  padding: var(--space-3xl) var(--container-padding);
  text-align: center;
}

.cta-band h2 {
  color: var(--white);
  margin-bottom: var(--space-md);
}

.cta-band p {
  color: rgba(255, 255, 255, 0.85);
  margin-bottom: var(--space-xl);
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

.cta-band .btn {
  background: var(--white);
  color: var(--magenta);
}
.cta-band .btn:hover {
  background: var(--plum-dark);
  color: var(--white);
}

/* --- Accordion --- */
.accordion-item {
  border-bottom: 1px solid var(--blush);
}

.accordion-trigger {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-lg) 0;
  background: none;
  border: none;
  cursor: pointer;
  font-family: var(--font-heading);
  font-size: var(--text-xl);
  font-weight: 500;
  color: var(--text-dark);
  text-align: left;
}

.accordion-trigger::after {
  content: '+';
  font-size: var(--text-2xl);
  color: var(--magenta);
  transition: transform var(--duration) var(--ease);
  flex-shrink: 0;
  margin-left: var(--space-md);
}

.accordion-item.open .accordion-trigger::after {
  content: '−';
}

.accordion-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.35s var(--ease);
}

.accordion-item.open .accordion-content {
  max-height: 2000px;
}

.accordion-body {
  padding-bottom: var(--space-xl);
  color: var(--text-body);
}

/* --- Trust badges --- */
.trust-badges {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-lg);
  align-items: center;
}

/* --- Age badge (for class cards) --- */
.age-badge {
  display: inline-block;
  font-family: var(--font-heading);
  font-size: var(--text-sm);
  font-weight: 500;
  padding: var(--space-xs) var(--space-md);
  border-radius: var(--radius-pill);
  background: var(--magenta);
  color: var(--white);
}

/* --- Info table (for class detail pages) --- */
.info-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: var(--radius-md);
  overflow: hidden;
}

.info-table th,
.info-table td {
  padding: var(--space-md) var(--space-lg);
  text-align: left;
  font-size: var(--text-base);
}

.info-table th {
  background: var(--plum);
  color: var(--white);
  font-family: var(--font-heading);
  font-weight: 500;
  width: 35%;
}

.info-table td {
  background: var(--white);
  border-bottom: 1px solid var(--pink-wash);
}

.info-table tr:last-child td {
  border-bottom: none;
}

/* --- Photo gallery (2x3 grid) --- */
.photo-gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-md);
}

.photo-gallery img {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
  border-radius: var(--radius-md);
}

@media (max-width: 768px) {
  .photo-gallery {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* --- Form fields --- */
.form-group {
  margin-bottom: var(--space-lg);
}

.form-label {
  display: block;
  font-weight: 500;
  color: var(--text-dark);
  margin-bottom: var(--space-xs);
  font-size: var(--text-sm);
}

.form-input,
.form-textarea {
  width: 100%;
  padding: var(--space-md);
  font-family: var(--font-body);
  font-size: var(--text-base);
  border: 2px solid var(--blush);
  border-radius: var(--radius-md);
  background: var(--white);
  color: var(--text-dark);
  transition: border-color var(--duration) var(--ease);
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--magenta);
  box-shadow: 0 0 0 3px rgba(217, 74, 159, 0.15);
}

.form-input.error,
.form-textarea.error {
  border-color: #D94A4A;
}

.form-error {
  font-size: var(--text-sm);
  color: #D94A4A;
  margin-top: var(--space-xs);
}

.form-textarea {
  min-height: 140px;
  resize: vertical;
}

/* --- Decorative circles (Pupbar-inspired accents) --- */
.deco-circle {
  position: absolute;
  border-radius: var(--radius-circle);
  pointer-events: none;
  z-index: 0;
}
```

- [ ] **Step 2: Add components.css link to index.html**

Add after `global.css` link:
```html
<link rel="stylesheet" href="css/components.css">
```

- [ ] **Step 3: Add a few test components to index.html to verify**

Add inside `<main>`:
```html
<div class="container section">
  <div class="section-header">
    <p class="section-label">Our Programmes</p>
    <h2>Classes for Every Stage</h2>
    <p>From crawling tiddlers to confident gymnasts.</p>
  </div>
  <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
    <a href="#" class="btn btn-primary">Book a Class</a>
    <a href="#" class="btn btn-secondary">Learn More</a>
    <span class="badge">British Gymnastics</span>
    <span class="badge-mint badge">SEN Inclusive</span>
    <span class="age-badge">6-12 Months</span>
  </div>
</div>
```

- [ ] **Step 4: Visual check with /browse**

Screenshot to verify all component styles render correctly.

- [ ] **Step 5: Commit**

```bash
git add css/components.css index.html
git commit -m "feat: component library — buttons, cards, badges, accordion, gallery"
```

---

### Task 5: Home Page

**Files:**
- Create: `css/home.css`
- Modify: `index.html`

**Content source:** `Original web/Home/index.html` for text. Visual reference: `reference/visual reference1.png` and Pupbar references.

**Home page sections:**
1. Hero (headline + CTAs + decorative image area)
2. Trust badges
3. Class cards (4 cards linking to sub-pages)
4. Facilities preview
5. Location
6. Testimonials
7. Extra images (if available)

- [ ] **Step 1: Create css/home.css**

Write the full Home page layout CSS:

```css
/* ========================================
   Home Page
   ======================================== */

/* --- Hero --- */
.hero {
  position: relative;
  background: var(--pink-wash);
  overflow: hidden;
  padding: var(--space-4xl) 0 var(--space-3xl);
}

.hero-inner {
  max-width: var(--container-max);
  margin: 0 auto;
  padding: 0 var(--container-padding);
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-2xl);
  align-items: center;
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero h1 {
  color: var(--plum);
  margin-bottom: var(--space-lg);
}

.hero h1 em {
  color: var(--magenta);
  font-style: italic;
}

.hero-subtitle {
  font-size: var(--text-lg);
  color: var(--text-body);
  margin-bottom: var(--space-xl);
  max-width: 480px;
}

.hero-ctas {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-md);
}

.hero-visual {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-visual img {
  position: relative;
  z-index: 1;
  border-radius: var(--radius-xl);
  max-height: 420px;
  object-fit: cover;
}

/* Decorative circle behind hero image */
.hero-visual::before {
  content: '';
  position: absolute;
  width: 320px;
  height: 320px;
  background: var(--blush);
  border-radius: var(--radius-circle);
  z-index: 0;
  top: 50%;
  left: 50%;
  transform: translate(-45%, -50%);
}

/* --- Trust Strip --- */
.trust-strip {
  background: var(--white);
  padding: var(--space-xl) 0;
  border-bottom: 1px solid var(--pink-wash);
}

.trust-strip .container {
  display: flex;
  justify-content: center;
  gap: var(--space-2xl);
  flex-wrap: wrap;
}

.trust-item {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  font-size: var(--text-sm);
  font-weight: 500;
  color: var(--text-body);
}

.trust-icon {
  width: 20px;
  height: 20px;
  border-radius: var(--radius-circle);
  background: var(--mint);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  font-size: 12px;
}

/* --- Class Cards Grid --- */
.class-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-xl);
}

.class-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: all var(--duration) var(--ease);
  text-decoration: none;
  display: block;
}

.class-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.class-card-img {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
  background: var(--blush);
}

.class-card-body {
  padding: var(--space-lg);
}

.class-card-body h3 {
  font-size: var(--text-xl);
  margin-bottom: var(--space-xs);
}

.class-card-body p {
  font-size: var(--text-sm);
  color: var(--text-light);
  margin-bottom: var(--space-md);
}

.class-card-link {
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--magenta);
}

/* --- Facilities Preview --- */
.facilities-preview {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-2xl);
  align-items: center;
}

.facilities-img {
  border-radius: var(--radius-xl);
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
}

.facilities-features {
  list-style: none;
  margin-top: var(--space-lg);
}

.facilities-features li {
  padding: var(--space-sm) 0;
  padding-left: var(--space-xl);
  position: relative;
  color: var(--text-body);
}

.facilities-features li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: var(--mint);
  font-weight: 700;
}

/* --- Location --- */
.location-map {
  border-radius: var(--radius-lg);
  overflow: hidden;
  width: 100%;
  height: 300px;
  border: none;
}

/* --- Testimonials --- */
.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-xl);
}

.testimonial-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  padding: var(--space-xl);
  box-shadow: var(--shadow-sm);
}

.testimonial-stars {
  color: var(--lime);
  font-size: var(--text-lg);
  margin-bottom: var(--space-md);
}

.testimonial-text {
  font-size: var(--text-base);
  font-style: italic;
  color: var(--text-body);
  margin-bottom: var(--space-md);
  line-height: 1.7;
}

.testimonial-author {
  font-weight: 600;
  font-size: var(--text-sm);
  color: var(--text-dark);
}

/* --- Responsive --- */
@media (max-width: 1024px) {
  .class-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  .testimonials-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .hero-inner {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .hero-subtitle {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-ctas {
    justify-content: center;
  }

  .hero-visual {
    max-width: 300px;
    margin: 0 auto;
  }

  .class-cards {
    grid-template-columns: 1fr;
    max-width: 400px;
    margin: 0 auto;
  }

  .facilities-preview {
    grid-template-columns: 1fr;
  }

  .testimonials-grid {
    grid-template-columns: 1fr;
  }
}
```

- [ ] **Step 2: Build index.html with full Home page content**

Extract text content from `Original web/Home/index.html` and build the complete page. Include all 7 sections: Hero, Trust strip, Class cards, Facilities, Location, Testimonials, and a CTA band before the footer.

Key content to extract:
- Hero headline: "Building Confidence Through Movement"
- Hero subtitle: introductory paragraph about TJ's
- 4 class cards: Tiddler (6-12 months), Toddler (1-2¾ years), Mini Gym (3-4 years), Gymnastics (5+)
- Facilities features from `Original web/facilities-location/facilities-location.txt`
- Testimonials: source from reviews on original site or use placeholder

The page must link `css/home.css` after `css/components.css`.

- [ ] **Step 3: Visual check — desktop + mobile**

Use `/browse` to screenshot at desktop (1280px) and mobile (375px).
Expected: Pupbar-inspired layout — warm pink/blush backgrounds, rounded cards, organic feel, proper mobile stack.

- [ ] **Step 4: Commit**

```bash
git add css/home.css index.html
git commit -m "feat: home page — hero, class cards, facilities, testimonials"
```

---

### Task 6: Classes Overview Page

**Files:**
- Create: `css/classes.css`
- Create: `classes.html`

**Content source:** Class descriptions from `Original web/Home/index.html` (summary) + `Original web/competition-and-displays/index.html` + `Original web/terms-and-conditions-on-bookings/terms-and-conditions-on-bookings.txt`

**Sections:**
1. Hero/header
2. Class cards grid (same 4 cards as Home, linking to sub-pages)
3. Competition & Displays section
4. Terms & Conditions on Bookings (accordion)

- [ ] **Step 1: Create css/classes.css**

Page-specific styles for the classes overview: hero banner, enhanced card grid, competition section layout, T&C accordion section.

- [ ] **Step 2: Create classes.html**

Full page with nav, all 4 sections, footer. Extract Competition & Displays content from `Original web/competition-and-displays/index.html`. Extract T&C text from `Original web/terms-and-conditions-on-bookings/terms-and-conditions-on-bookings.txt` and format as accordion items.

- [ ] **Step 3: Visual check**

Screenshot desktop + mobile.

- [ ] **Step 4: Commit**

```bash
git add css/classes.css classes.html
git commit -m "feat: classes overview page with competition section and T&C accordion"
```

---

### Task 7: Class Detail Pages (x4)

**Files:**
- Create: `css/class-detail.css` (shared layout for all 4)
- Create: `classes/tiddler.html`
- Create: `classes/toddler.html`
- Create: `classes/mini-gym.html`
- Create: `classes/gymnastics.html`

**Content sources:**
- Tiddler: `Original web/tiddler-gymnastic-classes/index.html`
- Toddler: `Original web/toddler-gymnastic-classes/index.html`
- Mini Gym: `Original web/mini-gymnastic-classes/index.html`
- Gymnastics: `Original web/gymnastic-classes/index.html`
- Term dates/costs: `Original web/Terms Dates& Costs/costs.html`

**Each page structure:**
1. Hero image (placeholder with blush background)
2. Detailed description + key info table (age, duration, price, venue)
3. Times & Booking section (term selector + timetable + Book Now / Book Trial buttons)
4. Photo gallery (6 placeholders in 3x2 grid)
5. CTA band ("Explore other classes" or "Contact us")

- [ ] **Step 1: Create css/class-detail.css**

Shared layout for all 4 detail pages: hero with overlay text, split layout for description + info table, booking grid with term tabs, photo gallery. The Times & Booking section should show schedule data extracted from `Original web/Terms Dates& Costs/costs.html` for each class.

- [ ] **Step 2: Create classes/tiddler.html**

Build the first detail page as the template. Extract all Tiddler Gym content. Note: sub-pages use relative paths `../css/global.css`, `../images/logo.png`, etc.

- [ ] **Step 3: Visual check — tiddler.html**

Screenshot desktop + mobile.

- [ ] **Step 4: Create classes/toddler.html**

Same structure, Toddler Gym content.

- [ ] **Step 5: Create classes/mini-gym.html**

Same structure, Mini Gym content.

- [ ] **Step 6: Create classes/gymnastics.html**

Same structure, Gymnastics content. This page has additional content: badge scheme (levels 7 through 1, then Bronze/Silver/Gold/Platinum/Diamond), and watching week information.

- [ ] **Step 7: Visual check — all 4 pages**

Spot-check each page at mobile width.

- [ ] **Step 8: Commit**

```bash
git add css/class-detail.css classes/
git commit -m "feat: 4 class detail pages — tiddler, toddler, mini gym, gymnastics"
```

---

### Task 8: Timetable Page

**Files:**
- Create: `css/timetable.css`
- Create: `timetable.html`

**Content source:** `Original web/timetable/timetable.html` — extract the timetable data (Monday through Thursday, each with time/class/age rows).

**This is a pure information display page — no booking buttons.** Client wants to keep the simple table format.

**Sections:**
1. Page header
2. Timetable grouped by day (Monday, Tuesday, Wednesday, Thursday)
3. Each day is a styled table with columns: Time, Class, Age Group

- [ ] **Step 1: Create css/timetable.css**

Day-header styling, striped table rows, responsive table (horizontal scroll or card-stack on mobile), color-coded class types.

- [ ] **Step 2: Create timetable.html**

Full page. Extract all timetable data from the original site screenshot. Group by day. Each day block: colored day header + table rows.

**Timetable data (from original site):**

**Monday:**
| Time | Class | Age |
|---|---|---|
| 1.20–2.00 | Mini Gym | 3–4½ Years |
| 2.10–2.50 | Mini Gym | 3–4½ Years |
| 4.00–4.45 | Gym | Mixed Intermediate |
| 4.30–5.15 | Gym | Girls Mini Squad (warm up adjacent hall) |
| 5.00–6.00 | Gym | Boys Elite (warm up adjacent hall) |

**Tuesday:**
| Time | Class | Age |
|---|---|---|
| 9.40–10.20 | Toddler Gym | 1–3 Years |
| 10.30–11.10 | Toddler Gym | 1–3 Years |
| 1.20–2.00 | Mini Gym | 3–4½ Years |
| 2.10–2.50 | Mini Gym | 3–4½ Years |
| 4.00–4.45 | Gym | Beginners ex Mini Gym Only |
| 4.45–5.30 | Gym | Mixed Intermediate |
| 5.30–6.30 | Gym | Mixed Squad |

**Wednesday:**
| Time | Class | Age |
|---|---|---|
| 9.30–10.10 | Toddler Gym | 1–3 Years |
| 10.30–11.10 | Mini Gym | 3–4½ Years |
| 1.20–2.00 | Mini Gym | 3–4½ Years |
| 2.10–2.50 | Mini Gym | 3–4½ Years |
| 4.00–4.45 | Gym | Beginners ex Mini Gym Only |
| 4.30–5.30 | Gym | Girls Squad (warm up adjacent hall) |

**Thursday:**
| Time | Class | Age |
|---|---|---|
| 9.40–10.20 | Toddler Gym | 1–3 Years |
| 10.30–11.10 | Toddler Gym (until end of Spring term) | 1–3 Years |
| 10.30–11.10 | Tiddler Gym (starting 13 April) | 6–12 months |
| 1.20–2.00 | Mini Gym | 3–4½ Years |
| 2.10–2.50 | Mini Gym | 3–4½ Years |
| 4.00–4.45 | Gym | Beginners ex Mini Gym Only |
| 4.45–5.45 | Gym | Girls Junior Squad |
| 4.45–6.45 | Gym | Girls Elite |

- [ ] **Step 3: Visual check**

Desktop + mobile. Verify tables are readable at all sizes.

- [ ] **Step 4: Commit**

```bash
git add css/timetable.css timetable.html
git commit -m "feat: timetable page — day-grouped schedule tables"
```

---

### Task 9: Coaches Page

**Files:**
- Create: `css/coaches.css`
- Create: `coaches.html`

**Content source:** `Original web/TJs_Gym_Our_Coaches/` — 17 coaches, each with a photo and bio text file.

**Page structure:**
1. Page header
2. Leadership section — Gill Holland (founder/GM) gets a featured large card
3. Head Coaches — Natalie, Jade, Nelum as a highlighted row
4. Coaching Team — remaining coaches in a grid with circular avatars
5. CTA band

Coach hierarchy (from source):
- **Founder/GM:** Gill Holland
- **Head of General Gymnastics / Competitions:** Natalie Garlick
- **Deputy Head Coach:** Jade Shaw
- **Safeguarding Officer:** Nelum Samara
- **Coaches:** Hazel Pithers, Hannah Pettit, JV Mattei, Nadine Henry, Beth Drew, Lucy Garlick, Bea Gray, Maya Francis, Cara Kelly, Mio Nakashima, Kate Harding, Joe Kelly, Ali Cuffe

- [ ] **Step 1: Create css/coaches.css**

Featured founder card (editorial-style, large), trio row for head coaches, grid of circular avatar cards for remaining coaches. Circular photos with colored ring borders (magenta/mint/plum alternating).

- [ ] **Step 2: Create coaches.html**

Full page with all 17 coaches. Extract bio text from each `.txt` file in the source directory. Use actual coach photos from `images/coaches/`.

- [ ] **Step 3: Visual check**

Desktop + mobile. Verify circular avatars render, hierarchy is clear.

- [ ] **Step 4: Commit**

```bash
git add css/coaches.css coaches.html
git commit -m "feat: coaches page — 17 coaches with leadership hierarchy"
```

---

### Task 10: History Page

**Files:**
- Create: `css/history.css`
- Create: `history.html`

**Content source:** `Original web/TJs_Gym_History/history.txt` + 7 photos

**Page structure:**
1. Page header ("Our Story")
2. Narrative timeline — flowing story layout alternating text/image blocks
3. Key milestones highlighted (founded 1988, moved to Raynes Park 2023)
4. Historical photo gallery

- [ ] **Step 1: Create css/history.css**

Alternating text-image layout (zigzag), milestone callouts, photo gallery grid.

- [ ] **Step 2: Create history.html**

Extract history text from source. Include all 7 historical photos from `images/history/`.

- [ ] **Step 3: Visual check**

Desktop + mobile.

- [ ] **Step 4: Commit**

```bash
git add css/history.css history.html
git commit -m "feat: history page — club story with timeline and photos"
```

---

### Task 11: Club Info Page

**Files:**
- Create: `css/club-info.css`
- Create: `club-info.html`

**Content sources:**
- Club Policies: `Original web/club-rules/club-rules.txt`
- News: `Original web/category-news/index.html`
- Information Sheet: `Original web/information-sheet/index.html`
- Club Kit: `Original web/clothing-supplier/clothing-supplier.txt`

**Page structure:**
1. Page header
2. Club Policies section — list of policies with expandable accordion (Child Safeguarding, Code of Conduct, Anti-Bullying, Equity Policy, Dress Code)
3. News section — latest news items
4. Information Sheet — downloadable form / key info
5. Club Kit — brief description + link to Print My Kit shop

- [ ] **Step 1: Create css/club-info.css**

Multi-section page: each section visually distinct with alternating backgrounds (white / pink-wash). Policy accordion, news cards, download button for info sheet, external link card for club kit.

- [ ] **Step 2: Create club-info.html**

Full page. Extract all policy text from `club-rules.txt`. Format as accordion items. News items from source. Club kit with link to `https://tjs.printmykit.co.uk/`.

- [ ] **Step 3: Visual check**

Desktop + mobile. Verify accordions work.

- [ ] **Step 4: Commit**

```bash
git add css/club-info.css club-info.html
git commit -m "feat: club info page — policies, news, info sheet, club kit"
```

---

### Task 12: Contact Page

**Files:**
- Create: `css/contact.css`
- Create: `contact.html`

**Content source:** `Original web/contact-us/index.html` + footer contact details

**Page structure:**
1. Page header
2. Contact info cards (phone, email, address) — styled with icons
3. Contact form (Name, Email, Phone, Message, Submit)
4. Google Maps embed
5. Opening hours / visit info

- [ ] **Step 1: Create css/contact.css**

Two-column layout: contact form left, info + map right. On mobile: stacks to single column. Contact info cards with plum/mint accent icons.

- [ ] **Step 2: Create contact.html**

Full page. The form is purely HTML for now (no backend). Will be connected to Contact Form 7 in WordPress later.

- [ ] **Step 3: Visual check**

Desktop + mobile.

- [ ] **Step 4: Commit**

```bash
git add css/contact.css contact.html
git commit -m "feat: contact page with form and map"
```

---

### Task 13: Privacy Policy Page

**Files:**
- Create: `css/privacy.css`
- Create: `privacy-policy.html`

**Content source:** `Original web/privacy-policy/index.html`

Simple prose page — page header + privacy policy text in readable format.

- [ ] **Step 1: Create css/privacy.css**

Minimal: prose container, max-width ~720px centered, comfortable reading typography.

- [ ] **Step 2: Create privacy-policy.html**

Extract privacy policy text from source. Standard nav + footer.

- [ ] **Step 3: Commit**

```bash
git add css/privacy.css privacy-policy.html
git commit -m "feat: privacy policy page"
```

---

### Task 14: Cross-Page QA & Polish

**Files:**
- Modify: All HTML and CSS files as needed

This task verifies consistency, fixes issues, and polishes the complete site.

- [ ] **Step 1: Navigation active states**

Verify every page sets the correct `active` class on its nav link.

- [ ] **Step 2: Responsive QA**

Use `/browse` responsive command to screenshot all pages at mobile/tablet/desktop. Fix any layout breaks.

- [ ] **Step 3: Cross-page link check**

Verify all internal links work (especially sub-page relative paths `../`). Verify footer links are consistent.

- [ ] **Step 4: Typography & spacing consistency**

Check all pages use consistent section spacing, heading sizes, and component styles.

- [ ] **Step 5: Accessibility check**

Verify: semantic HTML (headings hierarchy, landmarks), alt text on images, aria labels on interactive elements, focus states on links/buttons, color contrast ratios.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "fix: cross-page QA — nav states, responsive fixes, accessibility"
```

---

### Task 15: Design Review

**Files:** None (review only)

- [ ] **Step 1: Run /design-review**

Use the `/design-review` skill to audit the complete site against the brand guidelines in `TJSGYM Branding upgrade/TJS BACKGROUND.md`. Check:
- Colour usage matches spec
- Typography (Fredoka headings, DM Sans body)
- Rounded corners consistency
- "Cuddly" tone achieved without being childish
- Mobile-first layout quality
- Pupbar-inspired organic feel
- Footer watermark treatment

- [ ] **Step 2: Fix any issues found**

Apply fixes from design review findings.

- [ ] **Step 3: Final commit**

```bash
git add -A
git commit -m "polish: design review fixes — brand alignment and visual consistency"
```

---

## Summary

| Task | Page/Component | Est. Steps |
|---|---|---|
| 1 | Project setup & dev server | 8 |
| 2 | Global CSS design system | 4 |
| 3 | Header/Nav & Footer | 5 |
| 4 | Component library | 5 |
| 5 | Home page | 4 |
| 6 | Classes overview | 4 |
| 7 | 4 class detail pages | 8 |
| 8 | Timetable page | 4 |
| 9 | Coaches page | 4 |
| 10 | History page | 4 |
| 11 | Club Info page | 4 |
| 12 | Contact page | 4 |
| 13 | Privacy Policy page | 3 |
| 14 | Cross-page QA & polish | 6 |
| 15 | Design review | 3 |
| **Total** | **15 pages + design system** | **70 steps** |

**Post-prototype (not in this plan):**
- WordPress theme conversion
- WooCommerce product setup (30 products)
- ACF fields for editable content
- Contact Form 7 integration
- Go-live migration
