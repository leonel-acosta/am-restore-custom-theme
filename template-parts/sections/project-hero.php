<?php

/**
 * Template part for displaying the project hero section.
 */

$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
$categories    = get_the_category();
?>

<section class="project-hero" <?php if ($thumbnail_url) : ?>style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');" <?php endif; ?>>
    <div class="project-hero__overlay"></div>
    <div class="project-hero__content container" data-aos="fade-up">
        <h1 class="project-hero__title"><?php the_title(); ?></h1>
        <?php if (!empty($categories)) : ?>
            <h4 class="project-hero__category" data-aos="fade-up" data-aos-delay="80"><?php echo esc_html($categories[0]->name); ?></h4>
        <?php endif; ?>
    </div>
</section>