<?php if (is_woocommerce()): ?>
    <?php if (is_product()): ?>
        <?php get_header();
        get_template_part('templates/breadcrumbs', 'tpl');
        ?>
        <section class="full-width">
            <div class="kosnic-product">
                <?php
                global $product;

                /**
                * Hook: woocommerce_before_single_product.
                *
                * @hooked wc_print_notices - 10
                */
                do_action( 'woocommerce_before_single_product' );

                if ( post_password_required() ) {
                echo get_the_password_form(); // WPCS: XSS ok.
                return;
                }
                ?>
                <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
                    <div class="grid-x">
                        <div class="cell large-4 medium-3 small-12">
                            <?php do_action( 'woocommerce_before_single_product_summary' );?>
                        </div>
                        <div class="cell large-8 medium-9 small-12">
                            <?php do_action( 'woocommerce_after_single_product_summary' );?>
                        </div>
                    </div>

                    <?php
                    /**
                     * Hook: woocommerce_before_single_product_summary.
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    //do_action( 'woocommerce_before_single_product_summary' );
                    ?>

                    <!--<div class="summary entry-summary">-->
                        <?php
                        /**
                         * Hook: woocommerce_single_product_summary.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         * @hooked WC_Structured_Data::generate_product_data() - 60
                         */
                        //do_action( 'woocommerce_single_product_summary' );
                        ?>
                    <!--</div>-->

                    <?php
                    /**
                     * Hook: woocommerce_after_single_product_summary.
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_upsell_display - 15
                     * @hooked woocommerce_output_related_products - 20
                     */
                    //do_action( 'woocommerce_after_single_product_summary' );
                    ?>
                </div>

                <?php do_action( 'woocommerce_after_single_product' ); ?>
            </div>
        </section>
        <?php get_footer(); ?>
    <?php else: ?>
        <?php
        get_header();
        get_template_part('templates/breadcrumbs', 'tpl');
        $full_width_fields = new CMB2Fields(get_the_ID());
        ?>

        <section class="full-width">
            <article class="kosnic-listing">
                <h1 class="title">
                    <?php the_title(); ?>
                </h1>
                <div class="grid-x">
                    <div class="cell shrink">
                        <?php
                        $currentCat = $wp_query->get_queried_object()->term_id;
                        if ($currentCat) {
                            echo kosnic_cat_nav($currentCat);
                            //echo do_shortcode('[product_categories number="0" parent="'.$currentCat.'"]');
                        } else {
                            echo kosnic_cat_nav();
                        } ?>
                    </div>
                    <div class="cell auto">
                        <?php if ($currentCat): ?>
                            <div class="kosnic-cats">
                                <?php echo do_shortcode('[product_categories number="0" parent="' . $currentCat . '" columns="3"]'); ?>
                            </div>
                            <div class="kosnic-prods">
                                <?php the_content(); ?>
                            </div>
                        <?php else: ?>
                            <div class="kosnic-cats">
                                <?php echo do_shortcode('[product_categories number="0" parent="0" columns="3"]'); ?>
                            </div>
                            <div class="kosnic-prods">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <?php
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
    <?php endif; ?>
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
            $related_name = get_post_type_object($post_type)->label;
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

