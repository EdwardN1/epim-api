<?php
function kosnic_cat_nav( $active = 0 ) {
	$args           = array(
		'taxonomy'   => 'product_cat',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,
		'parent'     => 0,
		'fields'     => 'all'
	);
	$res            = array();
	$res['desktop'] = '';
	$res['mobile']  = '';
	ob_start();
	$parentCategories    = get_categories( $args );
	if ( $parentCategories ):
		usort($parentCategories, fn($a, $b) => strcmp($a->name, $b->name));
		?>

		<?php foreach ( $parentCategories as $parentCategory ):
		$parent_cat_id = $parentCategory->term_id;
		$parent_cat_name = $parentCategory->name;
		$parent_cat_slug = $parentCategory->slug;
		$parent_cat_link = get_term_link( $parent_cat_slug, 'product_cat' );
		?>
        <li>
            <a href="<?php echo $parent_cat_link; ?>"><?php echo $parent_cat_name; ?></a>
			<?php
			$args                = array(
				'taxonomy'   => 'product_cat',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'parent'     => $parent_cat_id
			);
			$firstNestCategories = get_categories( $args );
			if ( $firstNestCategories ):
				usort($firstNestCategories, fn($a, $b) => strcmp($a->name, $b->name));
				$fnActive = '';
				if ( $parent_cat_id == $active ) {
					$fnActive = ' is-active';
				}
				$fnChildren = get_term_children( $parent_cat_id, 'product_cat' );
				if ( $fnChildren ) {
					if ( in_array( $active, $fnChildren ) ) {
						$fnActive = ' is-active';
					}
				}
				?>
                <ul class="menu vertical nested first-nest<?php echo $fnActive; ?>">
					<?php foreach ( $firstNestCategories as $firstNestCategory ): ?>
                        <li>
							<?php
							$first_cat_id   = $firstNestCategory->term_id;
							$first_cat_name = $firstNestCategory->name;
							$first_cat_slug = $firstNestCategory->slug;
							$first_cat_link = get_term_link( $first_cat_slug, 'product_cat' );
							?>
                            <a href="<?php echo $first_cat_link; ?>"><?php echo $first_cat_name; ?></a>
							<?php
							$args                 = array(
								'taxonomy'   => 'product_cat',
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => true,
								'parent'     => $first_cat_id
							);
							$secondNestCategories = get_categories( $args );
							if ( $secondNestCategories ):
								usort($secondNestCategories, fn($a, $b) => strcmp($a->name, $b->name));
								$snActive = '';
								?>
                                <ul class="menu vertical nested second-nest is-active">
									<?php foreach ( $secondNestCategories as $secondNestCategory ): ?>
                                        <li>
											<?php
											$second_cat_id   = $secondNestCategory->term_id;
											$second_cat_name = $secondNestCategory->name;
											$second_cat_slug = $secondNestCategory->slug;
											$second_cat_link = get_term_link( $second_cat_slug, 'product_cat' );
											?>
                                            <a href="<?php echo $second_cat_link; ?>"><?php echo $second_cat_name; ?></a>
                                        </li>
									<?php endforeach; ?>
                                </ul>
							<?php endif; ?>
                        </li>
					<?php endforeach; ?>
                </ul>
			<?php endif; ?>
        </li>
	<?php endforeach; ?>

	<?php
	endif;
	$menu           = ob_get_clean();
	$res['desktop'] = '<ul class="vertical menu accordion-menu" data-accordion-menu data-submenu-toggle="true">' . $menu . '</ul>';
	$res['mobile']  = '<ul class="vertical menu accordion-menu" data-accordion-menu data-submenu-toggle="true"><li><a href="/shop/">Products</a><ul  class="menu vertical nested">' . $menu . '</li></ul></ul>';

	return $res;
}