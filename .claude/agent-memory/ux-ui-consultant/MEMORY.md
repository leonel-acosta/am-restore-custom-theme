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

## Contact Page / Text-Media Section Form
- Template: templates/contact-page.php + template-parts/text-media-section.php
- ACF field group: group_contact_page_001.json (default theme in ACF = 'light', not 'dark')
- Form rendered via do_shortcode() inside .text-image-section__embed
- Form CSS lives in compiled style.css lines ~6572–6889 (no dedicated SCSS partial yet — written directly to style.css)
- No .wpcf7-not-valid error state styles defined anywhere in the theme
- No prefers-reduced-motion guard on any animation in the theme
- outline: none applied globally to inputs (line 6690) — focus ring completely absent
- Label opacity: 0.7 — fails WCAG AA in dark theme (#1e1e1e bg): white*0.7 = ~rgba(255,255,255,0.7) = approx 10.8:1 (passes); body text rgba(255,255,255,0.85) = ~12.6:1 (passes). Light/white theme: 0.7 opacity on #333333 over #f5f5f3 ≈ 4.2:1 — FAILS AA by small margin
- Placeholder: rgba(255,255,255,0.4) on #1e1e1e ≈ 2.9:1 — FAILS; rgba(0,0,0,0.35) on #f5f5f3 ≈ 3.2:1 — FAILS (placeholders exempt from WCAG but still a usability concern)
- Textarea: 80px height, resize: none — too compact for message fields
- Checkbox/radio: 14x14px — below 24x24px recommended touch target
- Submit button: transparent bg, low-contrast border — does not read as primary CTA
- No wpcf7 spinner/loading state styled
- text-media-section.php uses same .text-image-section CSS classes (shared with image variant)

## New Component System (style.css — compiled, not SCSS partials)
- All new components written directly to style.css (no SCSS source): page-section, am-card, cta-section, logo-slider, text-section, text-image-section, service-card, multicolumn, featured-projects, project-hero, project-intro
- Two CSS systems coexist: legacy SCSS ($text:#777777, $primary:#e86240) and new components (#8b1a2f primary, #1e1e1e dark, #f5f5f3 light)
- $variables.scss palette is NOT used by new components — new palette is hardcoded inline in style.css

## New Component Contrast & Typography Notes
- text-section body (dark): rgba(255,255,255,0.85) on #1e1e1e — ~11.5:1 PASSES
- text-section body (light): #222222 on #f5f5f3 — ~9.7:1 PASSES
- text-section__heading (dark): #ffffff on #1e1e1e — ~16:1 PASSES, but font-weight: 400 — weak hierarchy
- text-image-section__heading: font-weight 700, uppercase, 28px/36px — strong, good
- featured-projects__subtitle (dark): rgba(255,255,255,0.65) on #1e1e1e — ~7.6:1 PASSES
- featured-projects__subtitle (light): #666666 on #f5f5f3 — ~4.9:1 PASSES
- project-hero__title: font-size 22px mobile only — very small for a hero h1
- project-hero__category: font-size 13px — below 16px minimum for body copy
- project-hero overlay: gradient to top (0.75 at bottom, 0.2 at 50%, transparent at top) — solid text contrast zone at bottom only
- multicolumn__heading: font-size 0.7rem (11.2px) uppercase — critically small
- multicolumn__label: opacity 0.6 — risky on borderline backgrounds
- multicolumn__col-heading (stat): font-weight 400 at 2.25rem — weak visual weight for primary stat
- project-intro__meta-label: 11px, rgba(255,255,255,0.5) on #1a1a1a — ~5.5:1, passes for large text but 11px is NOT large text — FAILS AA
- project-intro__meta-value: 18px rgba(255,255,255,0.9) on #1a1a1a — ~15:1 PASSES
- CTA heading: font-weight 400, 2rem/2.5rem — undersells the CTA; no font-weight hierarchy with body
- btn-cta hover: background-color transparent — button disappears on hover
- service-card body (dark/medium): rgba(255,255,255,0.8) on #1e1e1e/#3a3a3a — passes (~9:1 / ~7.5:1)
- am-card background: #e0e0e0 (gray); title/meta: #1a1a1a on #e0e0e0 — ~11:1 PASSES

## Animation / Motion Notes
- AOS.init: duration 700ms, easing ease-out, once:true, offset:60 — duration is slightly long for entrance animations
- multicolumn animate: opacity+translateY, 550ms ease — no prefers-reduced-motion guard on JS observer
- featured-projects AOS stagger: 0ms / 150ms / 300ms delay — can feel laggy on slower devices
- logo-slider has prefers-reduced-motion guard (stops animation)
- contact form has prefers-reduced-motion guard
- multicolumn JS animate does NOT check prefers-reduced-motion

## Padding / Spacing System
- New components use their own internal padding scale: sm=40px, md=80px, lg=120px
- page-section utility classes: sm=1.5rem, default=3rem, lg=5rem, xl=8rem
- page-title uses Tailwind: pt-20 lg:pt-40 pb-10 (80px top / 160px top / 40px bottom)
- Mixed px / rem / Tailwind units across sections — no single source of truth
- text-image-section desktop text padding: 80px top/bottom, 60px right (or left if reversed) — generous but asymmetric

## Details Links
- Full site review delivered on 2026-03-16.
- Contact form audit delivered on 2026-03-24.
- Full theme component review (new component system) delivered on 2026-03-30.
