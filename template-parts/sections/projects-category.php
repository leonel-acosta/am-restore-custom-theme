<?php

/**
 * Template Part: Projects Category Section
 *
 * Renders one category's project cards with an optional heading.
 * Two modes:
 *   - Default (paged = 0): shows up to $limit posts, "More Projects →" link if there are more.
 *   - Paginated (paged > 0): full pagination controls below the grid.
 *
 * Args:
 *   category_slug      string  WP category slug (empty = all, excluding uncategorized)
 *   category_label     string  Heading text (defaults to category name)
 *   subtitle           string  Optional subtitle rendered below the heading row (HTML allowed)
 *   more_link_override string  URL — overrides the auto-calculated "More Projects" link
 *   limit              int     Max posts per page (default 9); -1 = show all
 *   theme              string  'light' | 'white' | 'dark' (default 'light')
 *   show_heading       bool    Render section heading (default true)
 *   paged              int     Current page number for pagination (0 = no pagination)
 *   page_id            int     Parent page ID — used to build pagination base URL
 */

$slug               = $args['category_slug']      ?? '';
$label              = $args['category_label']     ?? '';
$subtitle           = $args['subtitle']           ?? '';
$more_link_override = $args['more_link_override'] ?? '';
$limit_raw          = intval($args['limit'] ?? 9);
$limit              = ($limit_raw === -1) ? -1 : max(1, $limit_raw);
$theme              = $args['theme']              ?? 'light';
$show_heading       = $args['show_heading']       ?? true;
$paged              = intval($args['paged']       ?? 0);
$page_id            = intval($args['page_id']     ?? 0);
$paginated    = $paged > 0;

if (! in_array($theme, ['dark', 'light', 'white'], true)) {
    $theme = 'light';
}

$cat_obj = $slug ? get_category_by_slug($slug) : null;

if (! $label && $cat_obj) {
    $label = $cat_obj->name;
}

// ── Query ────────────────────────────────────────────────────────────────────
$query_args = [
    'post_type'           => 'post',
    'posts_per_page'      => $limit,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
];

if ($paginated) {
    $query_args['paged'] = $paged;
}

if ($cat_obj) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $cat_obj->term_id,
    ]];
} else {
    $uncategorized = get_category_by_slug('uncategorized');
    if ($uncategorized) {
        $query_args['category__not_in'] = [ $uncategorized->term_id ];
    }
}

$section_query = new WP_Query($query_args);

if (! $section_query->have_posts()) return;

// "More Projects" link for non-paginated mode
$has_more  = ! $paginated && $limit !== -1 && $section_query->found_posts > $limit;
$auto_link = ($has_more && $slug) ? home_url('/projekte/' . $slug . '/') : '';
$more_link = $more_link_override ?: $auto_link;
?>

<section class="projects-cat-section projects-cat-section--<?php echo esc_attr($theme); ?>">
    <div class="container">

        <?php if ($show_heading && $label) : ?>
            <div class="projects-cat-section__header">
                <h2 class="projects-cat-section__title">
                    <?php echo esc_html($label); ?>
                </h2>
                <?php if ($more_link) : ?>
                    <a href="<?php echo esc_url($more_link); ?>" class="projects-cat-section__more">
                        <?php esc_html_e('More Projects', 'am-restore'); ?>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
            <?php if ($subtitle) : ?>
                <div class="projects-cat-section__subtitle">
                    <?php echo wp_kses_post($subtitle); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="projects-cat-grid">
            <?php while ($section_query->have_posts()) :
                $section_query->the_post();
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
                <?php get_template_part('template-parts/components/card-project', null, [
                    'img_src'  => $img_src,
                    'img_alt'  => get_the_title(),
                    'type'     => $type,
                    'client'   => $client,
                    'location' => $location,
                    'year'     => $year,
                ]); ?>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

        <?php if ($paginated && $section_query->max_num_pages > 1) :
            $base_url = $slug
                ? home_url('/projekte/' . $slug . '/')
                : ($page_id ? get_permalink($page_id) : get_pagenum_link(1, false));

            $links = paginate_links([
                'base'      => $base_url . '%_%',
                'format'    => '?pg=%#%',
                'current'   => $paged,
                'total'     => $section_query->max_num_pages,
                'prev_text' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'next_text' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'type'      => 'array',
            ]);
        ?>
            <nav class="projects-pagination projects-pagination--<?php echo esc_attr($theme); ?>" aria-label="<?php esc_attr_e('Projects pagination', 'am-restore'); ?>">
                <?php foreach ($links as $link) : ?>
                    <?php echo $link; ?>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

    </div>
</section>
