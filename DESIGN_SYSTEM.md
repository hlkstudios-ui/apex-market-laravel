# Apex Privé Design System

This is the go-to reference for every existing and future interface. The executable tokens live in `resources/css/design-system.css`; mandatory implementation rules and shared alignment primitives live in `resources/css/Styling-guide.css`. Page-specific components live in `resources/css/app.css` and must consume both.

## Visual direction

Apex Privé uses quiet, editorial luxury: generous negative space, strong typographic contrast, fine rules, restrained motion, and a black–ivory–champagne palette. Avoid discount-marketplace visual noise, excessive pills, bright gradients, crowded cards, and unrelated colors.

## Typography

- Display: Cormorant Garamond, weight 500. Use for page titles, section titles, large numerals, and editorial statements.
- Body/UI: DM Sans, weights 400–600. Use for navigation, controls, product data, labels, and paragraphs.
- Overlines: 11px, weight 600, uppercase, `0.16em` tracking, gold.
- Body copy: 16px with 1.5–1.8 line height. Supporting text uses the muted token.
- Never introduce another font without updating this file and the canonical CSS tokens.

## Layout

- Maximum content width: 1440px (`--content-width`).
- Page gutters: responsive `--page-gutter`; never hard-code a different outer gutter.
- Desktop sections use `--space-7` vertically. Mobile sections reduce through the token breakpoint.
- Primary grids use 4 columns for discovery, 3 for products, 2 for detail layouts, and 1 on mobile.
- Align headings, filters, cards, and footer content to the same shell edges.
- Editorial mastheads must explicitly use block flow because the application header has its own navigation layout. Overline, title, and description stack vertically and never share a row.

## Color and surfaces

- Ink `--color-ink`: primary text, navigation, strong buttons.
- Ivory `--color-ivory`: editorial feature backgrounds.
- Paper `--color-paper`: page background.
- Gold `--color-gold`: selective emphasis, never large body backgrounds.
- Rule `--color-rule`: all dividers and quiet borders.
- White: cards and elevated commerce surfaces.

## Components

- Global navigation: use a warm paper surface, centered editorial wordmark, one quiet search line, and restrained uppercase department links. Client utilities remain visually secondary; the bag count is a typographic superscript, never a marketplace badge. Desktop navigation has three measured tiers (service, identity/utilities, departments). Mobile keeps the wordmark centered, the bag visible, search immediately accessible, and departments inside one calm disclosure menu. Do not reintroduce delivery tiles, promotional color blocks, crowded dropdown labels, or an “All” menu.
- Buttons: minimum 50px high, restrained square corners, clear hover and focus states.
- Inputs: visible label where context requires it, 44px minimum height, rule-colored border, gold focus ring.
- Cards: one border language, no arbitrary shadows. Images use consistent aspect ratios within a grid.
- Brand cards: use the shared `<x-brand-logo>` component and the immutable files in `public/brand-logos`. Every card uses the same height, borderless ivory field, and enlarged logo stage. Logo images must have zero intrinsic grid minimums so square SVG canvases cannot overflow the stage. The component centers and optically fits the visible foreground artwork—not invisible canvas geometry—to compensate for differing internal viewBoxes, and automatically detects transparent light-colored artwork that needs contrast normalization; never edit a source logo merely to resize or recolor it. Brand cards do not use visible sequence numbers, boxed grid lines, or visible niche labels. Keep niche data in semantic markup and accessible names so filtering and assistive context continue to work. Use a verified SVG when available, otherwise retain the local SVG fallback.
- Product cards: image, maison overline, product name, price, single primary action.
- Empty states: centered, calm, one explanation and one next action.

### Shared component API

Build new UI from the opt-in classes in `Styling-guide.css` rather than restyling native elements page by page:

- Forms: `.ui-field`, `.ui-label`, `.ui-input`, `.ui-textarea`, `.ui-help`, and `.ui-error`.
- Dropdowns: wrap a native `<select>` with `.ui-select`; never replace native keyboard behavior with a decorative menu.
- Radio buttons and checkboxes: `.ui-choice-group` contains `.ui-choice` labels with their native input inside.
- Actions: `.ui-button`, `.ui-button--secondary`, and `.ui-button--quiet` are the only standard action levels.
- Cards: `.ui-card`, `.ui-card__media`, `.ui-card__body`, `.ui-card__eyebrow`, `.ui-card__title`, and `.ui-card__text` preserve a consistent hierarchy.
- Supporting UI: `.ui-badge`, `.ui-alert`, `.ui-empty`, `.ui-table`, `.ui-pagination`, `.ui-tabs`, `.ui-disclosure`, and `.ui-dialog`.
- Icon controls: `.ui-icon-label`, `.ui-icon-box`, and `.ui-count` keep icons, labels, and counters optically aligned.

All shared components are deliberately opt-in. To add one, define its editable tokens at the top of `Styling-guide.css`, add one `.ui-*` base class, include states for hover, focus, disabled, error, and mobile where applicable, then document the public class here. Do not add a global selector that silently changes an existing page.

## Responsive and accessibility rules

- Breakpoints are content-led; primary layout changes occur around 900px, 800px, and 560px.
- Interactive targets are at least 40px high and expose visible keyboard focus.
- Use semantic headings in order, meaningful alt text, and an accessible name on icon-only buttons.
- Mobile layouts must not depend on hover. Horizontal A–Z controls may scroll.

## Governance

Before creating a new pattern, check for a similar component. Extend the existing pattern and tokens instead of introducing one-off values. If a genuinely new global rule is needed, add it to `design-system.css` and document it here first.
