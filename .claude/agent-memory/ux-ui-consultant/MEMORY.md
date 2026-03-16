# UX/UI Consultant Memory — AM-Restore Theme

## Project Identity
- Client: AM-Restore, Berlin-based architectural concrete restoration & conservation studio
- Theme: Custom WordPress theme `am-restore`
- Branch convention: feature branches off `main`
- Developer: Leonel Acosta (github.com/leonel-acosta)

## Accessibility Target
- WCAG AA minimum (4.5:1 normal text, 3:1 large text)
- No AAA requirement confirmed yet

## Typography System
- Body font: Barlow, self-hosted woff2, weights 300/400/400i/600/700/700i
- Heading font: Barlow (same family — single-family strategy)
- Base font-size: 16px desktop / 15px tablet (max-width 991px) / 14px mobile (max-width 767px)
- Body font-size: 15px (0.9375rem) with line-height 1.7 in SCSS (_document.scss) / 1.8 in compiled editor-style.css
- Heading weight: 400 (regular) — confirmed intentional, low contrast with body
- Type scale: h1 33/40px | h2 25/32px | h3 22px | h4 20px | h5 18px | h6 16px
- Section titles: uppercase, font-weight 600, 28px mobile / 36px desktop

## Color Palette (from _variables.scss)
- $text: #777777 (body copy)
- $heading: #333333 (variable) / #444444 (actual usage in _document.scss and editor-style.css)
- $primary: #e86240 (orange-red accent)
- $primary_hover: #e86240 (SAME as primary — no hover differentiation defined at variable level)
- $secondary: #00aeef (blue — appears unused in main UI)
- $border: #e9e9e9
- $meta: #f8f9f9 (section/card background)
- Body background: #ffffff

## Known Contrast Issues (WCAG)
- #777777 on #ffffff = 4.48:1 — FAILS WCAG AA (needs 4.5:1) by a razor margin
- #999999 on #ffffff = 2.85:1 — FAILS (used in .entry-meta)
- #aaaaaa on #ffffff = 2.32:1 — FAILS (used in comment meta)
- #e86240 on #ffffff = 3.04:1 — FAILS for normal text (only passes large text / UI components)
- section-inverse: rgba(255,255,255,0.5) on #222222 — marginal, needs verification
- $secondary #00aeef on #ffffff = ~2.9:1 — FAILS

## Key SCSS Files
- Variables & mixins: assets/sass/_variables.scss
- Typography & document: assets/sass/_document.scss
- Sections & components: assets/sass/_sections.scss
- Layout: assets/sass/_layout.scss
- Content pages: assets/sass/_contents.scss
- Buttons/elements: assets/sass/_elements.scss
- Compiled editor styles: assets/css/editor-style.css
- Font declarations: assets/css/barlow.css

## Animation Patterns
- Slider intro animation: translateY(100%) to translateY(0) — 500ms linear — no prefers-reduced-motion guard
- Gallery zoom: scale(1.5) on hover — 500ms linear — no prefers-reduced-motion guard
- Form inputs: transition all 0.2s linear (acceptable duration)
- .transition5 utility: 500ms ease (borderline slow for UI)

## Confirmed Design Decisions
- Single font family (Barlow) for both body and headings — weight differentiation strategy needed
- All headings use font-weight: 400 — creates weak hierarchy
- $primary_hover == $primary — hover state indistinguishable by color alone
- outline: none on focus states (a, details:focus, btn) — accessibility concern
- Section titles use text-transform: uppercase — appropriate for the brand tone

## Details Link
See full review delivered on 2026-03-16.
