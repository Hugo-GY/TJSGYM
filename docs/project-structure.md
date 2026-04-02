Project structure

Live site files

- `index.html`: the homepage and root entry point for the static site.
- `pages/`: top-level secondary pages such as classes overview, timetable, history, FAQ, contact, and policy pages.
- `classes/`: class detail pages and booking flow pages.
- `news/`: news article pages.
- `css/`: page and shared stylesheets.
- `js/`: client-side scripts.
- `data/`: structured content and schedule data used by the site.
- `images/`: all site imagery, grouped by page or purpose.
- `tests/`: shell-based regression checks for key pages and flows.

Support directories

- `docs/`: project notes and handoff documentation.
- `reference/`: non-production reference material, including the original site export and design/layout references.
- `artifacts/`: generated output such as screenshots and Playwright captures.

Images

- `images/shared/`: global brand assets used across the site.
- `images/home/`: homepage imagery.
- `images/history/`: history page archive images.
- `images/coaches/`: coach profile images.
- `images/club-kit/`: club kit imagery.
- `images/classes/`: class-specific imagery grouped by page.
- `images/source-assets/brand/`: original source assets kept for editing/reference.
- `images/archive/legacy/`: retired or unused images not referenced by the live site.

Notes

- Keep live site assets separate from source/reference material.
- New images should go into the matching page folder under `images/`.
- New non-production references should go into `reference/`, not the project root.
