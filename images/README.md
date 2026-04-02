Image directory structure

- `shared/`: global assets reused across the site, such as the logo, favicon, and membership/support icons.
- `home/`: homepage-only imagery, including hero, facilities, and class card artwork.
- `history/`: archive and timeline imagery used only on the history page.
- `coaches/`: coach profile headshots and portraits.
- `club-kit/`: club kit product imagery used on the club kit page.
- `classes/`: class-detail imagery grouped by page.
- `source-assets/`: raw source imagery kept for reference and future edits.

Classes subfolders

- `classes/tiddler/`: Tiddler Gym hero and gallery images.
- `classes/toddler/`: Toddler Gym hero and gallery images.
- `classes/mini-gym/`: Mini Gym gallery images.
- `classes/gymnastics/`: Gymnastics gallery images.

Archive

- `archive/legacy/`: unused or replaced images kept for reference only. Files in this folder should not be referenced by the live site.
- `source-assets/brand/`: original brand asset exports and working source images moved from the old `Brand assest/` folder.

Notes

- Page HTML should reference page-specific images from the matching folder whenever possible.
- Shared assets should stay in `shared/` rather than being duplicated across page folders.
- Source working files are stored under `source-assets/` and are not part of the deployed image structure unless they are copied into one of the live page folders above.
