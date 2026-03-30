<?php

/**
 * Template Name: Projects - Single Category
 *
 * Displays projects from one category, either paginated or as a full list.
 * Category chosen via ACF "category_filter" taxonomy field (auto-populated from WP categories).
 *
 * ACF fields: sections_theme, posts_per_section, category_filter, enable_pagination
 */

get_header();
the_post();

$page_id = get_the_ID();
$theme   = get_field('sections_theme',    $page_id) ?: 'light';
$limit   = max(1, intval(get_field('posts_per_section', $page_id) ?: 9));

// Taxonomy field returns a WP_Term object (or null)
$cat_term = get_field('category_filter', $page_id);
$cat_slug = ($cat_term instanceof WP_Term) ? $cat_term->slug : '';

// Display mode: paginated or full list
$enable_pagination = (bool) get_field('enable_pagination', $page_id);
$paged       = $enable_pagination ? max(1, absint($_GET['pg'] ?? 1)) : 0;
$post_limit  = $enable_pagination ? $limit : -1;
?>

<div id="content" class="site-content projects-single-cat-page">

    <?php get_template_part('template-parts/sections/page-title'); ?>

    <main id="main" class="site-main" role="main">
        <?php get_template_part('template-parts/sections/projects-category', null, [
            'category_slug' => $cat_slug,
            'show_heading'  => false,
            'limit'         => $post_limit,
            'theme'         => $theme,
            'paged'         => $paged,
            'page_id'       => $page_id,
        ]); ?>
    </main>

</div>

<?php get_template_part('template-parts/sections/cta'); ?>
<?php get_footer(); ?>
