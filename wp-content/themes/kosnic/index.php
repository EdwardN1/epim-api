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
            if($currentCat) {
                echo do_shortcode('[product_categories number="0" parent="'.$currentCat.'"]');
            } else {
                echo do_shortcode('[product_categories number="0" parent="0"]');
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

<?php else : ?>
    <?php
    get_header();

    $page_fields = new CMB2Fields(get_option('page_for_posts'));
    $selected_taxonomy = $page_fields->field('taxonomy_type');
    $taxonomy_terms = new TaxonomyTerms($selected_taxonomy);

    get_template_part('templates/breadcrumbs', 'tpl');
    ?>

    <section class="block test">
        <div class="container">
            <div class="block__grid grid owl-carousel js-block-slider">

                <?php
                $terms = $taxonomy_terms->terms();

                foreach ($terms as $row_index => $row_terms):
                    $layout_terms = $taxonomy_terms->layout_terms($row_terms);
                    $taxonomy_terms->render_layout($layout_terms, $row_index);
                endforeach;
                ?>

            </div>
        </div>
    </section>

    <?php
    get_footer();


    ?>
<?php endif; ?>
