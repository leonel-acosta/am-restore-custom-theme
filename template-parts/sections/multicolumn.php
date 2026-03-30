<?php

/**
 * Template Part: Multi-Column Section
 *
 * Reusable 2–4 column grid. Each column can have an image (flush/square),
 * an icon, label, heading, body text and a button link.
 * Supports section theming, per-column background contrast, and optional
 * scroll-in animation with staggered delay.
 *
 * Args:
 *   section_heading  string   Optional heading above the grid
 *   section_theme    string   'dark' (default) | 'light' | 'white'
 *   columns_align    string   'left' (default) | 'center'
 *   animate          bool     Fade-in entrance animation (default: true)
 *   columns          array    Repeater rows, each containing:
 *     icon_type      string   'none' | 'image' | 'icon_class'
 *     icon_image     array    ACF image array { url, alt }
 *     icon_class     string   CSS icon class e.g. "fa-solid fa-star"
 *     column_label   string   Small label (e.g. "Since", "Category")
 *     column_heading string   Prominent text (stat, name, title…)
 *     column_text    string   Optional body text
 *     button_text    string
 *     button_url     string
 *     column_bg      string   'none' | 'card' | 'accent'
 */

$section_heading    = $args['section_heading']    ?? '';
$section_subheading = $args['section_subheading'] ?? '';
$section_theme      = $args['section_theme']      ?? 'dark';
$heading_weight     = $args['heading_weight']     ?? 'normal';
$columns_align    = $args['columns_align']    ?? 'left';
$animate          = isset($args['animate']) ? (bool) $args['animate'] : true;
$columns         = $args['columns']         ?? [];

if (empty($columns)) {
    return;
}

$valid_themes  = ['dark', 'light', 'white'];
$valid_bg      = ['none', 'card', 'accent'];
$section_theme  = in_array($section_theme,  $valid_themes,            true) ? $section_theme  : 'dark';
$heading_weight = in_array($heading_weight, ['normal', 'semibold'],   true) ? $heading_weight : 'normal';
$columns_align  = in_array($columns_align,  ['left', 'center'],       true) ? $columns_align  : 'left';

$col_count  = max(2, min(6, count($columns)));
$section_id = 'multicolumn-' . uniqid();
?>

<section
    class="multicolumn multicolumn--<?php echo esc_attr($section_theme); ?> multicolumn--align-<?php echo esc_attr($columns_align); ?> multicolumn--heading-<?php echo esc_attr($heading_weight); ?>"
    id="<?php echo esc_attr($section_id); ?>">

    <div class="container">

        <?php if ($section_heading || $section_subheading) : ?>
            <div class="multicolumn__header" data-aos="fade-up">
                <?php if ($section_heading) : ?>
                    <h2 class="multicolumn__heading"><?php echo esc_html($section_heading); ?></h2>
                <?php endif; ?>
                <?php if ($section_subheading) : ?>
                    <p class="multicolumn__subheading"><?php echo wp_kses($section_subheading, ['br' => [], 'strong' => [], 'em' => []]); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="multicolumn__grid multicolumn__grid--<?php echo $col_count; ?>">

            <?php foreach ($columns as $idx => $col) :
                $icon_type  = $col['icon_type']      ?? 'none';
                $icon_image = $col['icon_image']     ?? null;
                $icon_class = $col['icon_class']     ?? '';
                $label      = $col['column_label']   ?? '';
                $heading    = $col['column_heading'] ?? '';
                $text       = $col['column_text']    ?? '';
                $btn_text   = $col['button_text']    ?? '';
                $btn_url    = $col['button_url']     ?? '';
                $col_bg     = in_array($col['column_bg'] ?? '', $valid_bg, true) ? $col['column_bg'] : 'card';

                $has_image = ($icon_type === 'image' && $icon_image);

                $col_classes = implode(' ', array_filter([
                    'multicolumn__col',
                    'multicolumn__col--bg-' . $col_bg,
                    $has_image ? 'multicolumn__col--has-image' : '',
                    $animate   ? 'multicolumn__col--animate'   : '',
                ]));
            ?>

                <div
                    class="<?php echo esc_attr($col_classes); ?>"
                    <?php if ($animate) : ?>style="--col-delay:<?php echo $idx * 120; ?>ms"<?php endif; ?>>

                    <?php if ($has_image) : ?>
                        <div class="multicolumn__image-wrap">
                            <img
                                src="<?php echo esc_url($icon_image['sizes']['medium'] ?? $icon_image['url'] ?? ''); ?>"
                                alt="<?php echo esc_attr($icon_image['alt'] ?? ''); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="multicolumn__body">

                        <?php if ($icon_type === 'icon_class' && $icon_class) : ?>
                            <div class="multicolumn__icon">
                                <i class="<?php echo esc_attr($icon_class); ?>" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>

                        <?php if ($label) : ?>
                            <p class="multicolumn__label"><?php echo esc_html($label); ?></p>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                            <div class="multicolumn__col-heading"><?php echo esc_html($heading); ?></div>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <p class="multicolumn__text"><?php echo wp_kses($text, ['br' => [], 'strong' => [], 'em' => []]); ?></p>
                        <?php endif; ?>

                        <?php if ($btn_text && $btn_url) : ?>
                            <a href="<?php echo esc_url($btn_url); ?>" class="multicolumn__btn">
                                <?php echo esc_html($btn_text); ?>
                            </a>
                        <?php endif; ?>

                    </div><!-- .multicolumn__body -->

                </div>

            <?php endforeach; ?>

        </div><!-- .multicolumn__grid -->

    </div><!-- .container -->

</section>

<?php if ($animate) : ?>
<script>
(function () {
    var section = document.getElementById('<?php echo esc_js($section_id); ?>');
    if (!section) return;

    var cols = section.querySelectorAll('.multicolumn__col--animate');
    if (!cols.length) return;

    if (!('IntersectionObserver' in window)) {
        cols.forEach(function (c) { c.classList.add('is-visible'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var col   = entry.target;
            var delay = parseInt(col.style.getPropertyValue('--col-delay'), 10) || 0;
            setTimeout(function () { col.classList.add('is-visible'); }, delay);
            observer.unobserve(col);
        });
    }, { threshold: 0.15 });

    cols.forEach(function (c) { observer.observe(c); });
}());
</script>
<?php endif; ?>
