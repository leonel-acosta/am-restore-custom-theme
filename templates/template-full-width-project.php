<?php

/**
 * Template Name: Full-Width Project Page
 *
 * The template for displaying template project pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Screenr
 */

get_header();
?>
<div id="content" class="site-content">
    <?php get_template_part('template-parts/sections/page-title'); ?>
    <div id="content-inside" class="container no-sidebar">

        <div id="primary" class="content-area">

            <main id="main" class="site-main" role="main">
                <?php
                the_post();

                get_template_part('template-parts/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_template_part('template-parts/sections/logo-slider') ?>

    </div><!--#content-inside -->

</div><!-- #content -->

<?php get_footer(); ?>