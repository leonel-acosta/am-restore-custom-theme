<?php

/**
 * Template Part: Project Intro — multi-column section
 *
 * Args:
 *   post_id   int   Post ID to pull ACF fields from
 *
 * Layout (desktop):
 *   Col 1 — Section label heading
 *   Col 2 — Section content, bordered
 *   Col 3 — Meta: Client, Architects, Location, Year, Duration (dark bg)
 */

$post_id = $args['post_id'] ?? get_the_ID();
$data    = get_field('project_page', $post_id) ?: [];

$label  = $data['first_section_label']   ?? 'About';
$about  = $data['about']                 ?? '';

$client     = $data['client']     ?? '';
$architects = $data['architects'] ?? '';
$location   = $data['location']   ?? '';
$year       = $data['year']       ?? '';
$duration   = $data['duration']   ?? '';

$has_meta = $client || $architects || $location || $year || $duration;

if (! $about && ! $has_meta) return;

$cols        = ($about && $has_meta) ? 3 : 2;
$wide_content = $about && strlen(strip_tags($about)) > 300;
?>

<section class="text-section text-section--light project-intro project-intro--cols-<?php echo $cols; ?><?php echo $wide_content ? ' project-intro--wide-content' : ''; ?>">
    <div class="container">
        <div class="project-intro__inner">

            <?php if ($has_meta) : ?>
                <div class="project-intro__meta" data-aos="fade-right">
                    <?php if ($client) : ?>
                        <div class="project-intro__meta-item project-intro__meta-item--client">
                            <span class="project-intro__meta-label"><?php esc_html_e('Client', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($client); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($architects) : ?>
                        <div class="project-intro__meta-item project-intro__meta-item--architects">
                            <span class="project-intro__meta-label"><?php esc_html_e('Architects', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($architects); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($location) : ?>
                        <div class="project-intro__meta-item project-intro__meta-item--location">
                            <span class="project-intro__meta-label"><?php esc_html_e('Location', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($location); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($year) : ?>
                        <div class="project-intro__meta-item project-intro__meta-item--year">
                            <span class="project-intro__meta-label"><?php esc_html_e('Year', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($year); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($duration) : ?>
                        <div class="project-intro__meta-item project-intro__meta-item--duration">
                            <span class="project-intro__meta-label"><?php esc_html_e('Duration', 'am-restore'); ?></span>
                            <span class="project-intro__meta-value"><?php echo esc_html($duration); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($about) : ?>
                <div class="text-section__heading-col" data-aos="fade-up" data-aos-delay="80">
                    <h2 class="text-section__heading text-section__heading--md">
                        <?php echo esc_html($label); ?>
                    </h2>
                </div>
                <div class="text-section__content text-section__content--bordered" data-aos="fade-up" data-aos-delay="160">
                    <div class="text-section__body"><?php echo wp_kses_post($about); ?></div>
                </div>
            <?php endif; ?>


        </div>
    </div>
</section>
