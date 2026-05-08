<?php

/**
 * Template Name: Project Page
 *
 * ACF Field Groups on this page: (project page)
 */

get_header();
the_post();

$page_id = get_the_ID();
?>

<div id="content" class="site-content">
    <?php get_template_part('template-parts/sections/project-hero'); ?>
    <main id="main" class="site-main" role="main">
        <?php get_template_part('template-parts/sections/project-intro', null, ['post_id' => $page_id]); ?>
        <?php get_template_part('template-parts/sections/project-masonry', null, ['post_id' => $page_id]); ?>
    </main>
</div>

<?php if ( get_post_meta( $page_id, 'enabled', true ) !== '0' ) : ?>
<?php get_template_part('template-parts/sections/featured-projects', null, ['post_id' => $page_id]); ?>
<?php endif; ?>
<?php get_template_part('template-parts/sections/cta'); ?>

<?php get_footer(); ?>
