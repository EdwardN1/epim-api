<?php if (is_woocommerce()): ?>
    <?php
    get_header();
    get_template_part('templates/breadcrumbs', 'tpl');
    $full_width_fields = new CMB2Fields(get_the_ID());
    ?>

    <section class="full-width">
        <article class="full-width__inner full-width__container wysiwyg">
            <h1 class="title">
                <?php the_title(); ?>
            </h1>

            <?php
            $currentCat = $wp_query->get_queried_object()->term_id;
            if ($currentCat) {
                echo do_shortcode('[product_categories number="0" parent="' . $currentCat . '"]');
            } else {
                //echo do_shortcode('[product_categories number="0" parent="0"]');
                /*$args = array(
                    'taxonomy' => 'product_cat',
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => true,
                    'parent' => 0
                );
                $accordion = '';
                $parentCategories = get_categories($args);
                if ($parentCategories) {
                    $accordion = '<ul class="vertical menu accordion-menu" data-accordion-menu data-submenu-toggle="true">';
                    foreach ($parentCategories as $parentCategory) {
                        $accordion .= '<li>';
                        $parent_cat_id = $parentCategory->term_id;
                        $parent_cat_name = $parentCategory->name;
                        $parent_cat_slug = $parentCategory->slug;
                        $parent_cat_link = get_term_link($parent_cat_slug, 'product_cat');
                        $accordion .= '<a href="' . $parent_cat_link . '">' . $parent_cat_name . '</a>';
                        $args = array(
                            'taxonomy' => 'product_cat',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => true,
                            'parent' => $parent_cat_id
                        );
                        $firstNestCategories = get_categories($args);
                        if ($firstNestCategories) {
                            $accordion .= '<ul class="menu vertical nested first-nest">';
                            foreach ($firstNestCategories as $firstNestCategory) {
                                $accordion .= '<li>';
                                $first_cat_id = $firstNestCategory->term_id;
                                $first_cat_name = $firstNestCategory->name;
                                $first_cat_slug = $firstNestCategory->slug;
                                $first_cat_link = get_term_link($first_cat_slug, 'product_cat');
                                $accordion .= '<a href="' . $first_cat_link . '">' . $first_cat_name . '</a>';
                                $args = array(
                                    'taxonomy' => 'product_cat',
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => true,
                                    'parent' => $first_cat_id
                                );
                                $secondNestCategories = get_categories($args);
                                if ($secondNestCategories) {
                                    $accordion .= '<ul class="menu vertical nested second-nest">';
                                    foreach ($secondNestCategories as $secondNestCategory) {
                                        $accordion .= '<li>';
                                        $second_cat_id = $secondNestCategory->term_id;
                                        $second_cat_name = $secondNestCategory->name;
                                        $second_cat_slug = $secondNestCategory->slug;
                                        $second_cat_link = get_term_link($second_cat_slug, 'product_cat');
                                        $accordion .= '<a href="' . $second_cat_link . '">' . $second_cat_name . '</a>';

                                        $accordion .= '</li>';
                                    }
                                    $accordion .= '</ul>';
                                    $accordion .= '</li>';
                                }
                                $accordion .= '</ul>';
                            }
                            $accordion .= '</li>';
                        }
                        $accordion .= '</ul>';
                    }
                    echo $accordion;*/
            }
            the_content();
            $read_more_content = $full_width_fields->field('read_more_content');
            if ($read_more_content):
                ?>

                <a href="#" class="read-more js-read-more">Read more</a>
                <div class="read-more__content js-read-more-content">

                    <?php echo $full_width_fields->format_content($read_more_content); ?>

                </div>

            <?php endif; ?>

        </article>
    </section>

    <?php get_footer(); ?>
<?php else: ?>
    <?php
    get_header();
    get_template_part('templates/breadcrumbs', 'tpl');

    $post_id = get_the_ID();
    $showcase_fields = new CMB2Fields($post_id);
    ?>

    <section class="showcase">
        <div class="showcase__hero"
             style="background-image: url('<?php echo $showcase_fields->get_featured_image('showcase-hero-image'); ?>')"></div>
        <article class="showcase__inner container wysiwyg">
            <h1 class="title">
                <?php the_title(); ?>
            </h1>

            <?php
            the_content();
            $read_more_content = $showcase_fields->field('read_more_content');
            if ($read_more_content):
                ?>

                <a href="#" class="read-more js-read-more">Read more</a>
                <div class="read-more__content js-read-more-content">

                    <?php echo $showcase_fields->format_content($read_more_content); ?>

                </div>

            <?php endif; ?>

        </article>

        <?php
        $post_type = get_post_type();

        if (get_post_type() === 'post'):
            $category = get_the_category()[0];
            $related_name = $category->name;
            $related_link = get_category_link($category->term_id);
            $related_args = [
                'category_name' => $related_name
            ];
        else:
            $related_name = get_post_type_object($post_type)->label;;
            $related_link = get_post_type_archive_link($post_type);
            $related_args = [
                'post_type' => $post_type,
            ];
        endif;

        $args = array_merge(
            $related_args,
            [
                'no_found_rows' => true,
                'post__not_in' => [$post_id],
                'posts_per_page' => -1
            ]
        );

        $related_posts = new WP_Query($args);

        if ($related_posts->have_posts()):
            ?>

            <section class="related">
                <div class="related__bar">
                    <h3 class="related__title">
                        Related <?php echo $related_name; ?>
                    </h3>
                    <a href="<?php echo $related_link; ?>">View All</a>
                </div>
                <div class="grid owl-carousel__related owl-carousel">

                    <?php
                    while ($related_posts->have_posts()): $related_posts->the_post();
                        $related_fields = new CMB2Fields(get_the_ID());
                        $related_image = wp_get_attachment_image_src($related_fields->field('article_listing_image_id'),
                            'article-listing-image'
                        )[0];

                        $render_args = [
                            'title' => get_the_title(),
                            'snippet' => get_the_excerpt(),
                            'image' => $related_image,
                            'link' => get_the_permalink()
                        ];

                        $related_fields->render('templates/related-carousel-tpl.php', $render_args);
                    endwhile;
                    ?>

                </div>
            </section>

        <?php endif; ?>

    </section>

    <?php get_footer(); ?>
<?php endif; ?>
<?php

