<?php

/**
 * Template Part: Page Intro
 *
 * Two-column intro section: label left, bordered content right.
 * Similar to project-intro.php but without the meta column.
 *
 * Args:
 *   title        string   Short label shown in the left column
 *   content      string   Body copy / HTML (wysiwyg output)
 *   button_text  string   Optional CTA label
 *   button_url   string   Optional CTA URL
 *   theme           string   'dark' | 'light' | 'white'
 *   border_content  bool     1px top border on content column, desktop only (default: false)
 */

$title          = $args['title']          ?? '';
$content        = $args['content']        ?? '';
$button_text    = $args['button_text']    ?? '';
$button_url     = $args['button_url']     ?? '';
$theme          = $args['theme']          ?? 'light';
$border_content = ! empty($args['border_content']);

$valid_themes = ['dark', 'light', 'white'];
if (! in_array($theme, $valid_themes, true)) {
    $theme = 'light';
}

if (! $title && ! $content) return;
?>

<section class="page-intro page-intro--<?php echo esc_attr($theme); ?>">
    <div class="container">
        <div class="page-intro__inner">

            <?php if ($title) : ?>
                <div class="page-intro__label">
                    <h3 class="page-intro__label-text"><?php echo esc_html($title); ?></h3>
                </div>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="page-intro__content<?php echo $border_content ? ' page-intro__content--bordered' : ''; ?>">
                    <div class="page-intro__body"><?php echo wp_kses_post($content); ?></div>

                    <?php if ($button_text && $button_url) : ?>
                        <a href="<?php echo esc_url($button_url); ?>" class="page-intro__btn">
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>