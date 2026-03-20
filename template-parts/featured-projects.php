<?php

/**
 * Template Part: Featured Projects Section
 *
 * Args:
 *   post_id           int     Post ID to read ACF fields from (default: current post)
 *   theme_override    string  'dark' | 'light' | 'white' — overrides ACF theme field
 *
 * ACF fields (flat, on $post_id):
 *   section_title       text
 *   section_content     wysiwyg
 *   categories          checkbox  (All, Recent, Untersuchungen, Restaurierung, …)
 *   all_projects_link   url
 *   theme               select    dark | light | white
 *
 * Query logic:
 *   - Empty or ["All"] or ["Recent"] → 3 latest posts (any category)
 *   - Specific category names         → posts matching those categories (max 3)
 */

$post_id = $args['post_id'] ?? get_the_ID();
$title   = get_field('section_title',     $post_id) ?: '';
$content = get_field('section_content',   $post_id) ?: '';
$cat     = get_field('categories',        $post_id) ?: '';
$link    = get_field('all_projects_link', $post_id) ?: '';
$theme   = $args['theme_override'] ?? (get_field('theme', $post_id) ?: 'dark');

if (! in_array($theme, ['dark', 'light', 'white'], true)) {
    $theme = 'dark';
}

// ── Build WP_Query args ──────────────────────────────────────────────────────
$query_args = [
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
];

// Always exclude Uncategorized
$uncategorized = get_category_by_slug('uncategorized');
if ($uncategorized) {
    $query_args['category__not_in'] = [ $uncategorized->term_id ];
}

// If a specific category is selected (not All / Recent / empty), filter by it
if ($cat && ! in_array($cat, ['All', 'Recent'], true)) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => sanitize_title($cat),
        'operator' => 'IN',
    ]];
    unset($query_args['category__not_in']);
}

$fp_query = new WP_Query($query_args);

if (! $fp_query->have_posts()) return;
?>

<section class="featured-projects featured-projects--<?php echo esc_attr($theme); ?>">
    <div class="container">

        <?php if ($title || $content || $link) : ?>
        <div class="featured-projects__header">
            <div class="featured-projects__header-text">
                <?php if ($title) : ?>
                    <h2 class="featured-projects__title text-section__heading text-section__heading--md">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($content) : ?>
                    <div class="featured-projects__subtitle">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($link) : ?>
                <a href="<?php echo esc_url($link); ?>" class="featured-projects__all-link">
                    <?php esc_html_e('All Projects', 'am-restore'); ?>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="featured-projects__grid">
            <?php while ($fp_query->have_posts()) :
                $fp_query->the_post();
                $pid   = get_the_ID();
                $pdata = get_field('project_page', $pid) ?: [];

                $img_src = get_the_post_thumbnail_url($pid, 'large');
                if (! $img_src && ! empty($pdata['gallery'][0])) {
                    $img_src = $pdata['gallery'][0]['sizes']['large'] ?? $pdata['gallery'][0]['url'] ?? '';
                }

                $type     = ! empty($pdata['type']) ? implode(', ', (array) $pdata['type']) : '';
                $client   = $pdata['client']   ?? '';
                $location = $pdata['location'] ?? '';
                $year     = $pdata['year']     ?? '';
            ?>
                <div class="featured-projects__card">
                    <?php get_template_part('template-parts/components/card-project', null, [
                        'img_src'  => $img_src,
                        'img_alt'  => get_the_title(),
                        'type'     => $type,
                        'client'   => $client,
                        'location' => $location,
                        'year'     => $year,
                    ]); ?>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

    </div>
</section>
