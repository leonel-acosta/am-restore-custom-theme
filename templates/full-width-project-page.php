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
    <header class="entry-header page-section--primary">
        <div id="container" class="container">
            <?php the_title('<h2 class="entry-title entry-title-section mx-auto"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <?php screenr_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php
            endif; ?>

            <?php
            if (has_post_thumbnail()) {
                echo '<div class="entry-thumb">';
                the_post_thumbnail(screenr_get_layout() == 'no' ? 'large' : 'screenr-blog-list');
                echo '</div>';
            }
            ?>
        </div>
    </header>
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

    </div><!--#content-inside -->
</div><!-- #content -->

<?php get_footer(); ?>