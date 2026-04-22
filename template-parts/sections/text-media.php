<?php

/**
 * Template Part: Text + Media Section
 *
 * Like text-image-section, but the right column accepts either
 * an image OR an embed / shortcode string.
 *
 * Args:
 *   heading        string   Section heading
 *   text           string   Body text / HTML
 *   button_text    string   Optional button label
 *   button_url     string   Optional button URL
 *   media_type     string   'image' | 'embed'  (default: 'image')
 *   image          array    ACF image array { url, alt }  — used when media_type = 'image'
 *   embed          string   Shortcode or raw embed HTML  — used when media_type = 'embed'
 *   embed_type     string   'video' | 'form' | ''  (default: '') — 'video' enforces 16:9 aspect ratio
 *   reversed       bool     Media left, text right (default: false = text left, media right)
 *   theme          string   'dark' (default) | 'light' | 'white'
 *   column_layout  string   'equal' (default) | 'text-wide' | 'media-wide' | 'one-column'
 *   align          string   'left' (default) | 'center'
 *   padding        string   'sm' | 'md' (default) | 'lg'
 *   border         string   'top' | 'bottom' | 'both' | ''  (default: '')
 *   border_content bool     Add 1px top border above the text column (default: false)
 *   class          string   Extra classes on the <section> element
 */

$heading       = $args['heading']       ?? '';
$text          = $args['text']          ?? '';
$button_text   = $args['button_text']   ?? '';
$button_url    = $args['button_url']    ?? '';
$media_type    = $args['media_type']    ?? 'image';
$image         = $args['image']         ?? null;
$embed         = $args['embed']         ?? '';
$embed_type    = $args['embed_type']    ?? '';
$reversed      = ! empty($args['reversed']);
$theme         = $args['theme']         ?? 'dark';
$column_layout = $args['column_layout'] ?? 'equal';
$align         = $args['align']         ?? 'left';
$padding       = $args['padding']       ?? 'md';
$border         = $args['border']        ?? '';
$border_content = ! empty($args['border_content']);
$extra_class    = $args['class']         ?? '';

$valid_col_layouts = ['equal', 'text-wide', 'media-wide', 'one-column'];
$column_layout = in_array($column_layout, $valid_col_layouts, true) ? $column_layout : 'equal';
$align         = in_array($align, ['left', 'center'], true) ? $align : 'left';
$padding       = in_array($padding, ['sm', 'md', 'lg'], true) ? $padding : 'md';

// Resolve image
$image_url = '';
$image_alt = '';
if ($image) {
    $image_url = $image['sizes']['large'] ?? $image['url'] ?? '';
    $image_alt = $image['alt'] ?? '';
}

// Determine what to show in the media column
$has_media = ($media_type === 'embed' && $embed) || ($media_type === 'image' && $image_url);

if (! $heading && ! $text && ! $has_media) {
    return;
}

$valid_themes  = ['dark', 'light', 'white'];
$valid_borders = ['top', 'bottom', 'both'];
$theme  = in_array($theme,  $valid_themes,  true) ? $theme  : 'dark';
$border = in_array($border, $valid_borders, true) ? $border : '';

// One-column: always stacked, no side-by-side layout
$one_column = $column_layout === 'one-column';

$row_dir = $one_column ? '' : ($reversed ? 'md:flex-row-reverse' : 'md:flex-row');

$section_class = implode(' ', array_filter([
    'text-image-section',
    'text-image-section--' . $theme,
    'text-image-section--padding-' . $padding,
    $reversed && ! $one_column ? 'text-image-section--reversed' : '',
    $column_layout !== 'equal' ? 'text-image-section--layout-' . $column_layout : '',
    $align === 'center' ? 'text-image-section--align-center' : '',
    $border   ? 'text-image-section--border-' . $border : '',
    $extra_class,
]));
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="text-image-section__inner flex flex-col <?php echo esc_attr($row_dir); ?> items-center gap-10">

            <div class="text-image-section__text<?php echo $border_content ? ' text-image-section__text--bordered' : ''; ?> w-full <?php echo $one_column ? '' : 'md:w-1/2'; ?> py-5">
                <?php if ($heading) : ?>
                    <h2 class="text-image-section__heading"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="text-image-section__body"><?php echo wp_kses_post(wpautop($text)); ?></div>
                <?php endif; ?>

                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="text-image-section__btn">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($has_media) : ?>
                <div class="text-image-section__image-wrap w-full <?php echo $one_column ? '' : 'md:w-1/2'; ?>">
                    <?php if ($media_type === 'embed' && $embed) : ?>
                        <?php if ($embed_type === 'video') :
                            // Detect type: direct video file, oEmbed URL, or shortcode/embed HTML
                            $video_extensions = ['mp4', 'webm', 'ogg', 'mov'];
                            $parsed_ext       = strtolower(pathinfo(strtok($embed, '?'), PATHINFO_EXTENSION));
                            $is_video_file    = in_array($parsed_ext, $video_extensions, true);
                            $is_url           = filter_var(trim($embed), FILTER_VALIDATE_URL) !== false;
                            $oembed_html      = '';
                            if ($is_url && ! $is_video_file) {
                                $oembed_html = wp_oembed_get(trim($embed), ['width' => 1280]);
                            }
                        ?>
                            <div class="text-image-section__embed text-image-section__embed--video">
                                <div class="text-image-section__video-container">
                                    <?php if ($is_video_file) : ?>
                                        <video controls preload="metadata" class="w-full h-full" style="display:block;">
                                            <source src="<?php echo esc_url(trim($embed)); ?>"
                                                    type="video/<?php echo esc_attr($parsed_ext === 'mov' ? 'mp4' : $parsed_ext); ?>">
                                        </video>
                                    <?php elseif ($oembed_html) : ?>
                                        <?php echo $oembed_html; ?>
                                    <?php else : ?>
                                        <?php echo do_shortcode($embed); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="text-image-section__embed">
                                <?php echo do_shortcode($embed); ?>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($image_url) : ?>
                        <img
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>"
                            class="text-image-section__image w-full h-full object-cover block">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
