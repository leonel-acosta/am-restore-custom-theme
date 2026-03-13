# AM Restore — WordPress Child Theme

A custom WordPress child theme built on top of [Screenr](https://www.famethemes.com/themes/screenr) by FameThemes, developed for the AM Restore website.

## About

This theme extends the Screenr parent theme with custom styles, templates, and functionality tailored specifically for AM Restore. It is maintained as a standalone repository to version-control all customizations independently from the parent theme.

## Requirements

- WordPress 6.0+
- PHP 5.6+
- Parent theme: [Screenr](https://www.famethemes.com/themes/screenr)

## Installation

1. Install and activate the **Screenr** parent theme.
2. Upload this theme folder to `/wp-content/themes/am-restore/`.
3. Activate **AM Restore** from the WordPress admin panel under *Appearance → Themes*.

## Development

Assets are organized under `assets/`:

```
assets/
├── css/        # Compiled stylesheets
├── js/         # Scripts
├── sass/       # Source Sass files
├── images/     # Theme images
└── fontawesome-v6/
```

To work on styles, edit the source files in `assets/sass/` and compile to `assets/css/`.

## Authorship

Customizations, development, and maintenance by:

**Leonel Acosta**
- GitHub: [github.com/leonel-acosta](https://github.com/leonel-acosta)
- Web: [ducho.co](https://www.ducho.co)

## License

This child theme inherits the license of the Screenr parent theme.
GNU General Public License v2 or later — see [LICENSE](http://www.gnu.org/licenses/gpl-2.0.html).
