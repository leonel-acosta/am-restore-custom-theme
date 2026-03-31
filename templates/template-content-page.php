<?php
/**
 * Template Name: Content Page
 *
 * For simple content pages (e.g. Datenschutz, Impressum).
 * Displays a dark-gray page title header followed by the WordPress editor content.
 */

get_header();
the_post();
?>

<div id="content" class="site-content">

    <?php get_template_part( 'template-parts/sections/page-title', null, [ 'theme' => 'dark' ] ); ?>

    <div class="container py-16 lg:py-24">
        <div class="max-w-2xl">
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
