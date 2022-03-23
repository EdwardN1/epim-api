<?php
	/**
	* Template Name: Product Listing page
	*/
	get_header();

	get_template_part('templates/breadcrumbs', 'tpl');
?>

	<section class="product-listing">
		<div class="product-listing__grid container grid">
			<nav class="product-category col-3 js-product-categories" data-active-node-id="<?= current_category_node_id(); ?>">
				<h3 class="product-category__title">Products</h3>
				<ul class="product-category__menu js-product-category-menu"></ul>
			</nav>

			<div class="col-9 products-container">
				<div class="product-grid js-product-grid" data-pagination='<?= current_pagination(); ?>'>
				</div>

				<div class="product-pagination-container">
					<nav class="product-pagination js-product-pagination grid">
						<a class="product-pagination__btn btn js-pagination-btn" href="#first">First Page</a>
						<a class="product-pagination__btn btn js-pagination-btn" href="#prev">Prev Page</a>
						<a class="product-pagination__btn btn js-pagination-btn" href="#next">Next Page</a>
						<a class="product-pagination__btn btn js-pagination-btn" href="#last">Last Page</a>
					</nav>
					<span class="product-pagination__total js-pagination-total"></span>
				</div>
			</div>

			<div class="col-9 category-groups-container" style="display: none;">
				<div class="product-grid js-product-grid">
					categories go here
				</div>
			</div>
		</div>
	</section>

<?php
	get_footer();
