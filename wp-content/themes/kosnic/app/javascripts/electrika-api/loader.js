/* global ElectrikaAPI */

(function($)
{
	var $product_categories = $('.js-product-categories');
	var $product_grid = $('.js-product-grid');
	var $breadcrumbs = $('.js-breadcrumbs');
	var node_id_data_value = $product_categories.attr('data-active-node-id');
	var active_category_node_id = node_id_data_value ? node_id_data_value : false;
	var active_node_id = node_id_data_value ? node_id_data_value : ElectrikaAPI.config.root_node_id;
	var pagination = $product_grid.attr('data-pagination') ? JSON.parse($product_grid.attr('data-pagination')) : undefined;
	var $product_search = $('.js-product-search');
	var mobile_device_width = 950;

	if ($product_categories.length > 0)
	{
		ElectrikaAPI.categories.insertCategories($product_categories, $('.js-product-category-menu'), ElectrikaAPI.config.root_node_id, active_category_node_id);

		$('.js-product-category-menu').on('categories-loaded', function()
		{
			var next_sub_category = $('.js-product-category-menu').find('.js-has-children.active').last();
			var current_category_node_id = next_sub_category.data('node-id');
			var target_sub_menu = next_sub_category.next('.js-sub-menu');

			if (target_sub_menu.data('node-loaded'))
			{
				return;
			}

			ElectrikaAPI.categories.insertCategories($product_categories, target_sub_menu, current_category_node_id, active_category_node_id);
		});

		if (!showCategoryGrid(active_node_id))
		{
			if (($product_grid.length > 0) && ($breadcrumbs.length > 0))
			{
				ElectrikaAPI.products.insertProducts($product_grid, active_node_id, pagination);
				ElectrikaAPI.pagination.updateTotal($('.js-pagination-total'), pagination, active_node_id);
				ElectrikaAPI.breadcrumbs.insertBreadcrumbs($breadcrumbs, active_node_id);
			}
		}
	}

	$('.js-product-categories').on('click', '.js-product-category-item', function(e)
	{
		var current_category_node_id = $(this).data('node-id');
		var target_sub_menu = $(this).next('.js-sub-menu');

		if ($(window).width() <= mobile_device_width)
		{
			if ($(this).hasClass('js-has-children'))
			{
				e.preventDefault();

				if (target_sub_menu.data('node-loaded') && !$(this).hasClass('.js-has-children'))
				{
					return;
				}

				ElectrikaAPI.categories.insertCategories($product_categories, target_sub_menu, current_category_node_id);
			}
		}
		else
		{
			e.preventDefault();

			if (!showCategoryGrid(current_category_node_id))
			{
				ElectrikaAPI.products.insertProducts($('.js-product-grid'), current_category_node_id);
				ElectrikaAPI.pagination.updateTotal($('.js-pagination-total'), undefined, current_category_node_id);
				ElectrikaAPI.breadcrumbs.insertBreadcrumbs($breadcrumbs, current_category_node_id);
			}

			if (target_sub_menu.data('node-loaded') && !$(this).hasClass('.js-has-children'))
			{
				return;
			}

			ElectrikaAPI.categories.insertCategories($product_categories, target_sub_menu, current_category_node_id);
		}
	});

	$(document).on('click', '.category-groups-container .product-item__outer', function(e)
	{
		let current_category_node_id = $(this).data('node-id');
		let menuItem = $(`ul.js-product-category-menu .js-product-category-item[data-node-id='${current_category_node_id}']`).first();

		menuItem.click();
	});

	$('.js-product-pagination').on('click', '.js-pagination-btn', function(e)
	{
		e.preventDefault();

		var pagination_action = $(this).attr('href').replace('#', '') + 'Page';

		if ($product_search.length > 0)
		{
			ElectrikaAPI.search[pagination_action]($product_search, $product_search.attr('data-search-term'));
		}
		else
		{
			ElectrikaAPI.pagination[pagination_action]($('.js-pagination-total'), $('.js-product-grid'), $product_categories.attr('data-active-node-id'));
		}
	});

	if ($('.js-product-single').length > 0)
	{
		var product_node_id = $('.js-product-single').attr('data-product-node-id');
		var parent_node_id = $('.js-related-products').attr('data-parent-node');

		ElectrikaAPI.breadcrumbs.insertBreadcrumbs($breadcrumbs, product_node_id);
		ElectrikaAPI.products.insertCommonProducts($('.js-common-products'), product_node_id);
		ElectrikaAPI.products.insertComponentProducts($('.js-component-products'), product_node_id);
		ElectrikaAPI.products.insertAccessoryProducts($('.js-accessory-products'), product_node_id);
		ElectrikaAPI.products.insertRelatedProducts($('.js-related-products'), parent_node_id, { page : 0, pageLimit : 4 });
	}

	if ($('.js-product-search').length > 0)
	{
		var product_search_term = $product_search.attr('data-search-term');

		ElectrikaAPI.search.insertSearchProducts($product_search, product_search_term, undefined);

		$product_search.on('search-complete', function()
		{
			var pagination = $product_search.attr('data-pagination') ? JSON.parse($product_search.attr('data-pagination')) : undefined;

			ElectrikaAPI.search.updatePaginationTotal($('.js-pagination-total'), $('.js-pagination-title'), $product_search.attr('data-num-results'), pagination);
		});
	}

	function showCategoryGrid(nodeId)
	{
		let categoryGroups = {};

		$.ajax(
		{
			type    : "GET",
			async   : false,
			url     : ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeChildren, nodeId),
			success : function(nodeChildren)
			{
				$.each(nodeChildren, function(i, nodeChild)
				{
					$.ajax(
					{
						type    : "GET",
						async   : false,
						url     : ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeAttributes, nodeChild.ID),
						success : function(nodeAttributes)
						{
							$.each(nodeAttributes, function(j, nodeAttribute)
							{
								if (nodeAttribute.Title == "EC_HeadingOverviewImage_Medium")
								{
									categoryGroups[i] =
									{
										image  : nodeAttribute.Value,
										title  : nodeChild.Name,
										nodeId : nodeChild.ID,
									};
								}
							});
						},
						error   : function(data)
						{
							console.log("error");
							console.log(data);
						},
					});
				});
			},
			error   : function(data)
			{
				console.log("error");
				console.log(data);
			},
		});

		if (Object.keys(categoryGroups).length > 0)
		{
			$(".products-container").hide();

			let html = ``;

			$.each(categoryGroups, function()
			{
				html+=
				`<a href="#" class="product-item__outer col-4" data-node-id="${this.nodeId}">
					<div class="product-item__inner" style="min-height: 270px;">
						<div class="product-item__image" style="background-image: url('${this.image}');">
						</div>
					</div>
					<h2 class="product-item__title" style="text-transform: none; color: white; background-color: #134fa0; padding-top: 5px; height: 70px; letter-spacing: 1px; font-size: 24px;">${this.title}</h2>
				</a>`;
			});

			$(".category-groups-container .product-grid").html(html);
			$(".category-groups-container").show();

			return true;
		}
		else
		{
			$(".category-groups-container").hide();
			$(".products-container").show();

			return false;
		}
	}
})(jQuery);
