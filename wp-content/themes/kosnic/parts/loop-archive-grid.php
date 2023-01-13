<?php
/**
 * The template part for displaying a grid of posts
 *
 * For more info: http://jointswp.com/docs/grid-archive/
 */

// Adjust the amount of rows in the grid
$grid_columns = 4; ?>

<?php if ( 0 === ( $wp_query->current_post ) % $grid_columns ): ?>

<div class="grid-x grid-margin-x grid-padding-x archive-grid" data-equalizer> <!--Begin Grid-->

	<?php endif; ?>

    <!--Item: -->
    <div class="small-6 medium-3 large-3 cell panel" data-equalizer-watch>

        <article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?> role="article">

            <section class="featured-image" itemprop="text">
                <a href="<?php the_permalink() ?>" class="image-link">
					<?php
					if ( get_post_type() == 'product' ) {
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'full' );
						} else {
							echo wc_placeholder_img( 'full' );
						}
					} else {
						the_post_thumbnail( 'full' );
					} ?>
                </a>
            </section> <!-- end article section -->

            <header class="article-header">
				<?php
				if ( get_post_type() == 'product' ) {
					$this_product = wc_get_product( get_the_ID() );
					if ( $this_product ) {
						$product_attributes = $this_product->get_attributes();
						$web_description_id = $product_attributes['pa_web-description']['options']['0'];
						if ( $web_description_id ) {
							$web_description = get_term( $web_description_id )->name;
							if ( $web_description ) {
							} else {
								echo '<a href="' . get_the_permalink() . '" rel="bookmark" style="text-decoration: none;"><h3 class="woocommerce-loop-product__title sku-title title">SKU: ' . $this_product->get_sku() . '<br>' . esc_html( $this_product->get_title() ) . '</h3></a>';
							}
						} else {
							echo '<a href="' . get_the_permalink() . '" rel="bookmark" style="text-decoration: none;"><h3 class="woocommerce-loop-product__title sku-title title">SKU: ' . $this_product->get_sku() . '<br>' . esc_html( $this_product->get_title() ) . '</h3></a>';
						}
					}
				} else {
					?>

                    <h3 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"
                                         title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<?php } ?>

				<?php //get_template_part('parts/content', 'byline'); ?>
                <!--<a href="<?php /*the_permalink() */ ?>" class="button product_type_simple" rel="nofollow">Read more</a>-->
            </header> <!-- end article header -->

            <section class="entry-content" itemprop="text">
				<?php //the_content('<button class="tiny">' . __('Read more...', 'jointswp') . '</button>'); ?>
            </section> <!-- end article section -->

        </article> <!-- end article -->

    </div>

	<?php if ( 0 === ( $wp_query->current_post + 1 ) % $grid_columns || ( $wp_query->current_post + 1 ) === $wp_query->post_count ): ?>

</div>  <!--End Grid -->

<?php endif; ?>

