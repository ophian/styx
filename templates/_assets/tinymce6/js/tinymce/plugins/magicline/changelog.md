# MagicLine Plugin – Changelog Summary

## [v. 2.0.3 / 2025-06-26]
- Improved and fixed touchDevice 'touchend' eventListener linger Timeout
- Optimized some debug strings


## [v. 2.0.2 / 2025-06-26]
- Extend the 2.0.1 special case for downward line with next element being a PRE element code container


## [v. 2.0.1 / 2025-06-26]

### Improved
- Added a special case for triggering an image upward red line when the previous sibling element is a pre element code container


## [v. 2.0.0 / 2025-06-25]

### Major Features
- **Insert Paragraph Line:** Adds a visible red “magic line” between block elements, allowing users to easily insert new paragraphs anywhere in the content.
- **Full Block Support:** Works between DIVs, FIGUREs, headings, sections, articles, asides, navs, and more.
- **Image & Gallery Handling:** Robust placement logic even between image containers and galleries, with smart overlays.
- **Touch & Accessibility:** Touch device support, ARIA roles, keyboard navigation, and accessibility hints.
- **User Guidance:** Tooltip hints (configurable), first-time visual tips, and device-specific instructions.
- **Undo/Redo Awareness:** Warns users where undo may not revert certain insertions (e.g., between complex blocks).

### Improvements Over Previous Versions
- **Improved Overlay Logic:** Smarter detection and management of where the magic line appears.
- **Dark Mode Awareness:** Adapts pointer color for dark/light themes.
- **Performance:** Optimized event handling and DOM updates for large documents.
- **Configurable Styling:** Easily customize line color, linger/fade durations, and tip messages via plugin settings.

### Bug Fixes & Robustness
- Avoids inserting paragraphs into non-editable or inappropriate areas.
- Prevents runtime errors by checking for `null` elements before DOM manipulation.
- Handles edge cases where adjacent paragraphs are empty or non-existent.

### Internal & Developer Notes
- Refactored for minification safety; all block-scoped variables safely managed.
- Added debug logging option for easier troubleshooting.
- Plugin structure ready for future extension and contribution.

---

**Release Date:** 2025-06-25, v. 2.0.0
**Author:** Ian Styx for The Serendipity Styx Blog Edition
