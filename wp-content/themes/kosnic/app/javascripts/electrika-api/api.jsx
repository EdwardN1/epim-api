/* global wpAjax */

var ElectrikaAPI = ElectrikaAPI || {};

(function($) {

    $(document)
        .ajaxStart(function(){
            $(".ajax-spinner").show();
        })
        .ajaxStop(function(){
            $(".ajax-spinner").hide();
        });

	ElectrikaAPI.config = {
		base_api_endpoint: 'https://api.electrika.com/api/',
		root_node_id: 341263
	};

	ElectrikaAPI.routes = {
		productsForNode: ElectrikaAPI.config.base_api_endpoint + 'PartNodesWithinIndex/',
		productsForNodeCount: ElectrikaAPI.config.base_api_endpoint + 'PartNodesWithinIndexCount/',
		nodeChildren: ElectrikaAPI.config.base_api_endpoint + 'NodeChildren/',
		nodeBreadcrumbs: ElectrikaAPI.config.base_api_endpoint + 'Breadcrumb/',
		nodeAttributes: ElectrikaAPI.config.base_api_endpoint + 'NodeAttributes/',
		nodeItem: ElectrikaAPI.config.base_api_endpoint + 'Node/',
		nodeComponents: ElectrikaAPI.config.base_api_endpoint + 'Components/',
		nodeAccessories: ElectrikaAPI.config.base_api_endpoint + 'Accessories/',
		nodeSearch: ElectrikaAPI.config.base_api_endpoint + 'Search/',
		nodeFixings: ElectrikaAPI.config.base_api_endpoint + 'Fixings/',
		requestURLFor: function(route, node_id) {
			return route + node_id;
		}
	};

	ElectrikaAPI.categories = {
		insertCategories: function($root_el, $cat_el, node_id, active_node_id = false) {
			this.fetchCategories(node_id)
				.then(this.countChildCategories)
				.then(data => this.fetchBreadcrumbs(active_node_id, data))
				.then(this.buildCategoriesHTML)
				.then(function(data) {
					$cat_el.append(data).data('node-loaded', true);

					if(active_node_id) {
						$root_el.attr('data-active-node-id', active_node_id);
						$cat_el.trigger('categories-loaded');
					} else {
						$root_el.attr('data-active-node-id', node_id);
					}
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		fetchCategories: function(node_id) {
			return $.getJSON(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeChildren, node_id
				)
			);
		},
		buildCategoriesHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_categories',
					categories: JSON.stringify(data[0]),
					breadcrumbs: JSON.stringify(data[1])
				}
			);
		},
		countChildCategories: function(categories) {
			var updated_cats = $.map(categories, function(category) {
				return ElectrikaAPI.categories.hasChildCategories(category.ID)
					.then(function(has_children) {
						category.HasChildren = has_children;

						return category;
					});
			});

			return $.when.apply(null, updated_cats).then(function() {
				return categories;
			});
		},
		hasChildCategories: function(node_id) {
			return $.getJSON(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeChildren, node_id
				)
			).then((children) => {
				return $.getJSON(
					ElectrikaAPI.routes.requestURLFor(
						ElectrikaAPI.routes.nodeAttributes, children[0].ID
					)
				);
			}).then(function(child_attributes) {
				//Kloc adjustment this allowed the new api to pass through as certain headings gained attributes when headings didn;t have them before.
				// return child_attributes.length <= 1;
				return child_attributes.length <= 2;
			});
		},
		fetchBreadcrumbs: function(active_node_id, data) {
			if(active_node_id === false) return [data, false];

			return ElectrikaAPI.breadcrumbs.fetchBreadcrumbs(active_node_id)
				.then(function(breadcrumbs) {
					return [data, breadcrumbs];
				});
		}
	};

	ElectrikaAPI.products = {
		insertProducts: function($el, node_id, pagination = {page: 0, pageLimit: 9}) {
			return this.fetchProducts(node_id, pagination)
				.then(this.buildProductsHTML)
				.then(function(data) {
					$el.empty().append(data).data('products-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		insertRelatedProducts: function($el, node_id, pagination) {
			return this.fetchProducts(node_id, pagination)
				.then(this.buildRelatedProductsHTML)
				.then(function(data) {
					$el.empty().append(data).data('related-products-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		insertCommonProducts: function($el, node_id) {
			return this.fetchCommonProducts(node_id)
				.then(this.buildCommonProductsHTML)
				.then(function(data) {
					$el.empty().append(data).data('common-products-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		insertComponentProducts: function($el, node_id) {
			return this.fetchComponentProducts(node_id)
				.then(this.buildComponentProductsHTML)
				.then(function(data) {
					$el.empty().append(data).data('component-products-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		insertAccessoryProducts: function($el, node_id) {
			return this.fetchAccessoryProducts(node_id)
				.then(this.buildAccessoryProductsHTML)
				.then(function(data) {
					$el.empty().append(data).data('accessory-products-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		fetchProducts: function(node_id, pagination) {

			return this.mergeProductAttributes(
				this.productsRouteWithPaging(
					ElectrikaAPI.routes.productsForNode,
					node_id,
					pagination
				)
			);
		},
		fetchCommonProducts: function(node_id)
		{
			return this.mergeProductAttributes2(ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeChildren, node_id), ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeItem, node_id));
		},
		fetchComponentProducts: function(node_id) {
			return this.mergeProductAttributes(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeComponents, node_id
				)
			);
		},
		fetchAccessoryProducts: function(node_id) {
			return this.mergeProductAttributes(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeAccessories, node_id
				)
			).then(function(accessory_products) {
				return ElectrikaAPI.products.fetchFixingsProducts(node_id)
					.then(function(fixings) {
						return [accessory_products, fixings];
					});
			});
		},
		fetchFixingsProducts: function(node_id) {
			return this.mergeProductAttributes(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeFixings, node_id
				)
			);
		},
		mergeProductAttributes: function(route_url, fallback_route_url = false)
		{
			return $.getJSON(route_url).then(function(products)
			{
				if (products.length > 0)
				{
					var updated_products = $.map(products, function(product)
					{
						return $.getJSON(ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeAttributes, product.ID)).then(function(product_attributes)
						{
							product.Attributes = product_attributes;
							return product;
						});
					});

					return $.when.apply(null, updated_products).then(function()
					{
						return products;
					});
				}
				else
				{
					return [];
				}
			});
		},
        mergeProductAttributes2: function(route_url, fallback_route_url = false)
        {
            return $.getJSON(route_url).then(function(products)
            {
                if (products.length > 0)
                {
                    var updated_products = $.map(products, function(product)
                    {
                        return $.getJSON(ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeAttributes, product.ID)).then(function(product_attributes)
                        {
                            product.Attributes = product_attributes;
                            return product;
                        });
                    });

                    return $.when.apply(null, updated_products).then(function()
                    {
                        return products;
                    });
                }
                else
                {
                    return $.getJSON(fallback_route_url).then(function(products)
                    {
                        var products_array = [products];
                        var updated_products = $.map(products_array, function(product)
                        {
                            //console.log(product);
                            return $.getJSON(ElectrikaAPI.routes.requestURLFor(ElectrikaAPI.routes.nodeAttributes, product.ID)).then(function(product_attributes)
                            {
                                product.Attributes = product_attributes;
                                return product;
                            });
                        });

                        return $.when.apply(null, updated_products).then(function()
                        {
                            return products_array;
                        });
                    });
                }
            });
        },
		buildProductsHTML: function(data, searching = false) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_products',
					products: JSON.stringify(data),
					search: searching
				}
			);
		},
		buildRelatedProductsHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_related_products',
					products: JSON.stringify(data),
					search: false
				}
			);
		},
		buildCommonProductsHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_common_products',
					products: JSON.stringify(data),
					search: false
				}
			);
		},
		buildComponentProductsHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_component_products',
					products: JSON.stringify(data),
					search: false
				}
			);
		},
		buildAccessoryProductsHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{
					action: 'kosnic_build_accessory_products',
					accessories: JSON.stringify(data[0]),
					fixings: JSON.stringify(data[1]),
					search: false
				}
			);
		},
		productsRouteWithPaging: function(route, node_id, pagination) {
			return route + '?id=' + node_id + '&' + $.param(pagination);
		},
		countProducts: function(node_id) {
			return $.getJSON(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.productsForNodeCount,
					node_id
				)
			);
		}
	};

	ElectrikaAPI.pagination = {
		updateTotal: function($el, pagination = {page: 0, pageLimit: 9}, node_id) {
			return this.pageTotal(node_id, pagination).then(function(page_total) {
				var page_value = pagination.page + 1;

				if(page_value > page_total) return 'max_pagination';

				$el.text('Page ' + page_value + ' of ' + page_total);

				return 'pagination_complete';
			});
		},
		nextPage: function($pagination_total_el, $pagination_data_el, node_id) {
			var pagination_data = this.parsePaginationData($pagination_data_el),
					updated_pagination_data = {
						page: pagination_data.page + 1,
						pageLimit: pagination_data.pageLimit
					};

			this.updateProductPagination(
				$pagination_total_el,
				$pagination_data_el,
				node_id,
				updated_pagination_data
			);
		},
		prevPage: function($pagination_total_el, $pagination_data_el, node_id) {
			var pagination_data = this.parsePaginationData($pagination_data_el),
					updated_pagination_data = {
						page: pagination_data.page - 1,
						pageLimit: pagination_data.pageLimit
					};

			if(updated_pagination_data.page < 0) return;

			this.updateProductPagination(
				$pagination_total_el,
				$pagination_data_el,
				node_id,
				updated_pagination_data
			);
		},
		firstPage: function($pagination_total_el, $pagination_data_el, node_id) {
			var pagination_data = this.parsePaginationData($pagination_data_el),
					updated_pagination_data = {
						page: 0,
						pageLimit: pagination_data.pageLimit
					};

			if(pagination_data.page === 0) return;

			this.updateProductPagination(
				$pagination_total_el,
				$pagination_data_el,
				node_id,
				updated_pagination_data
			);
		},
		lastPage: function($pagination_total_el, $pagination_data_el, node_id) {
			var pagination_data = this.parsePaginationData($pagination_data_el);

			this.pageTotal(node_id, pagination_data).then((page_total) => {
				var page_total_index = page_total - 1;

				if(pagination_data.page === page_total_index) return;

				var updated_pagination_data = {
					page: page_total_index,
					pageLimit: pagination_data.pageLimit
				};

				this.updateProductPagination(
					$pagination_total_el,
					$pagination_data_el,
					node_id,
					updated_pagination_data
				);
			});
		},
		parsePaginationData: function($pagination_data_el) {
			return JSON.parse($pagination_data_el.attr('data-pagination'));
		},
		pageTotal: function(node_id, pagination) {
			return ElectrikaAPI.products.countProducts(node_id)
				.then(function(num_of_products) {
					return Math.ceil(num_of_products / pagination.pageLimit);
				});
		},
		updateProductPagination: function($pagination_total_el, $pagination_data_el, node_id, updated_pagination_data) {
			this.updateTotal(
				$pagination_total_el,
				updated_pagination_data,
				node_id
			).then(function(status) {
				if(status === 'max_pagination') return;

				$pagination_data_el.attr(
					'data-pagination',
					JSON.stringify(updated_pagination_data)
				);

				ElectrikaAPI.products.insertProducts(
					$pagination_data_el,
					node_id,
					updated_pagination_data
				);
			});
		}
	};

	ElectrikaAPI.breadcrumbs = {
		insertBreadcrumbs: function($el, node_id) {
			this.fetchBreadcrumbs(node_id)
				.then(this.buildBreadcrumbsHTML)
				.then(function(data) {
					$el.empty().append(data).data('breadcrumbs-loaded', true);
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		fetchBreadcrumbs: function(node_id) {
			return $.getJSON(
				ElectrikaAPI.routes.requestURLFor(
					ElectrikaAPI.routes.nodeBreadcrumbs, node_id
				)
			).then(function(breadcrumbs) {
				return $.getJSON(
					ElectrikaAPI.routes.requestURLFor(
						ElectrikaAPI.routes.nodeItem, node_id
					)
				).then(function(current_node) {
					breadcrumbs.unshift(current_node);

					return breadcrumbs;
				});
			});
		},
		buildBreadcrumbsHTML: function(data) {
			return $.post(
				wpAjax.ajaxurl,
				{ action: 'kosnic_build_breadcrumbs', breadcrumbs: JSON.stringify(data) }
			);
		}
	};

	ElectrikaAPI.search = {
		insertSearchProducts: function($el, search_term, pagination = {page: 0, pageLimit: 12}) {
			return this.fetchSearchProducts($el, search_term, pagination)
				.then(data => ElectrikaAPI.products.buildProductsHTML(data, true))
				.then(function(data) {
					$el.empty()
						.append(data)
						.data('products-loaded', true)
						.trigger('search-complete');
				}).fail(function(response) {
					ElectrikaAPI.error.message(response);
				});
		},
		fetchSearchProducts: function($el, search_term, pagination) {
			var per_page = pagination.pageLimit,
					current_page = pagination.page,
					search_url = ElectrikaAPI.routes.nodeSearch +
						'?&searchTerm=' +
						search_term +
						'&brandNodeId=332313&hitLimit=' + per_page +
						'&page=' + current_page;

			return $.getJSON(search_url).then(function(search_results) {
				$el.attr('data-num-results', search_results.TotalHits)
					 .attr('data-pagination', JSON.stringify(pagination));

				var searched_products = [];

				$.map(search_results.Documents, function(search_result) {
					searched_products.push({
						ID: search_result.fields[1].fieldsData,
						Name: search_result.fields[2].fieldsData
					});
				});

				var updated_products = $.map(searched_products, function(product) {
					return $.getJSON(
						ElectrikaAPI.routes.requestURLFor(
							ElectrikaAPI.routes.nodeAttributes, product.ID
						)
					).then(function(product_attributes) {
						product.Attributes = product_attributes;

						return product;
					});
				});

				return $.when.apply(null, updated_products).then(function() {
					return searched_products;
				});
			});
		},
		updatePaginationTotal: function($pagination_total_el, $pagination_title_el, total, pagination = {page: 0, pageLimit: 12}) {
			$pagination_title_el.text(' - found ' + total + ' results');

			var num_of_pages = this.getPageTotal(total, pagination),
					page_value = pagination.page + 1;

			$pagination_total_el.text('Page ' + page_value + ' of ' + num_of_pages);
		},
		getPageTotal: function(total_results, pagination) {
			return Math.ceil(total_results / pagination.pageLimit);
		},
		firstPage: function($el, search_term) {
			var pagination = ElectrikaAPI.pagination.parsePaginationData($el),
					updated_pagination_data = {
						page: 0,
						pageLimit: pagination.pageLimit
					};

			if(pagination.page === 0) return;

			this.insertSearchProducts($el, search_term, updated_pagination_data);
		},
		lastPage: function($el, search_term) {
			var pagination = ElectrikaAPI.pagination.parsePaginationData($el),
					page_total = this.getPageTotal(
						$el.attr('data-num-results'), pagination
					),
					page_total_index = page_total - 1;

			if(pagination.page === page_total_index) return;

			var updated_pagination_data = {
				page: page_total_index,
				pageLimit: pagination.pageLimit
			};

			this.insertSearchProducts($el, search_term, updated_pagination_data);
		},
		nextPage: function($el, search_term) {
			var pagination = ElectrikaAPI.pagination.parsePaginationData($el),
					page_total = this.getPageTotal(
						$el.attr('data-num-results'), pagination
					),
					page_value = pagination.page + 1;

			if(page_value >= page_total) return;

			var updated_pagination_data = {
				page: pagination.page + 1,
				pageLimit: pagination.pageLimit
			};

			this.insertSearchProducts($el, search_term, updated_pagination_data);
		},
		prevPage: function($el, search_term) {
			var pagination = ElectrikaAPI.pagination.parsePaginationData($el),
					updated_pagination_data = {
						page: pagination.page - 1,
						pageLimit: pagination.pageLimit
					};

			if(updated_pagination_data.page < 0) return;

			this.insertSearchProducts($el, search_term, updated_pagination_data);
		}
	};

	ElectrikaAPI.error = {
		message: function(response) {
			// eslint-disable-next-line
			console.log(response.status + ' - ' + response.responseJSON.Message);
		}
	};

})(jQuery);
