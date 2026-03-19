<?php

/**
 * Template Part: Project Intro — multi-column section
 *
 * Reuses text-section CSS classes for consistency.
 *
 * Args:
 *   post_id   int   Post ID to pull ACF fields from
 *
 * Layout:
 *   Col 1 — Section label heading   (text-section--light)
 *   Col 2 — About content, bordered (text-section--light)
 *   Col 3 — Meta: Client, Location, Year, Duration (darker bg)
 */

$post_id  = $args['post_id'] ?? get_the_ID();
$data     = get_field('project_page', $post_id) ?: [];

$label    = $data['section_label'] ?? 'About';
$about    = $data['about']         ?? '';
$client   = $data['client']        ?? '';
$location = $data['location']      ?? '';
$year     = $data['year']          ?? '';
$duration = $data['duration']      ?? '';

$has_meta = $client || $location || $year || $duration;

if (! $about && ! $has_meta) return;

$cols = ($about && $has_meta) ? 3 : 2;
?>

<section class="text-section text-section--light project-intro project-intro--cols-<?php echo $cols; ?> gap-5">
    <div class="container">
        <div class="project-intro__inner">

            <div class="text-section__heading-col">
                <h2 class="text-section__heading text-section__heading--md">
                    <?php echo esc_html($label); ?>
                </h2>
            </div>

            <?php if ($about) : ?>
                <div class="text-section__content text-section__content--bordered">
                    <div class="text-section__body"><?php echo wp_kses_post($about); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($has_meta) : ?>
                <div class="project-intro__meta lg:my-5">
                    <?php if ($client) : ?>
                        <div class=" project-intro__meta-item">
                            <span class="project-intro__meta-label"><?php esc_html_e('Client', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($client); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($location) : ?>
                        <div class="project-intro__meta-item">
                            <span class="project-intro__meta-label"><?php esc_html_e('Location', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($location); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($year) : ?>
                        <div class="project-intro__meta-item">
                            <span class="project-intro__meta-label"><?php esc_html_e('Year', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($year); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($duration) : ?>
                        <div class="project-intro__meta-item">
                            <span class="project-intro__meta-label"><?php esc_html_e('Duration', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($duration); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>