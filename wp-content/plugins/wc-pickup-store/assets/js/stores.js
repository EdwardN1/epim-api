jQuery(document).ready(function($) {
	$(document).on('change', 'select.wps-costs-per-store', function() {
		var id = $('#shipping-pickup-store-select option:selected').data('id');
		var html = wps_extra_fields_in_checkout(id);
		wps_js_update_store_costs($(this).find('option:selected').data('cost'));
	});

	$(document).on('updated_checkout', function() {
		var id = $('#shipping-pickup-store-select option:selected').data('id');
		var html = wps_extra_fields_in_checkout(id);

		if($('#store_shipping_cost').length) {
			if($('#store_shipping_cost').val() == '' && $('select.wps-costs-per-store').val() > 0) {
				wps_js_update_store_costs($('select.wps-costs-per-store').find('option:selected').data('cost'));
			}
		}
	});

	function wps_js_update_store_costs(cost) {
		$('#store_shipping_cost').val(cost);
		$('body').trigger('update_checkout');
	}

	function wps_extra_fields_in_checkout(id) {
		// var id = $('#shipping-pickup-store-select option:selected').data('id');
		var store_data = wps_ajax[id];
		var html = '';

		$.each(store_data, function(index, value) {
			// console.log(value.label); 
			html += '<span class="wps-label">' + value.label + '</span><span class="wps-value">' + value.value + '</span>';
		});

		return html;
	}

	/**
	** Set store data on Checkout page
	**/
	$(document.body).on('updated_checkout', function () {
		var id = $('#shipping-pickup-store-select').find('option:selected').data('id');
		wps_get_store_data_by_id(id);
	});

	$(document).on('change', '#shipping-pickup-store-select', function() {
		var id = $(this).find('option:selected').data('id');
		wps_get_store_data_by_id(id);
	});

	function wps_get_store_data_by_id(_id) {
		if ($('.store-template').length) {
			var post_template = wp.template('wps-store-details');
			var store = wps_ajax.stores[_id];
			var template_data = {};
			$.each(store, function(index, value) {
				template_data[value.key] = {
					key: value.key,
					value: value.value
				};
			});

			var html = post_template(template_data);

			$( '.shipping-pickup-store .store-template' ).html(html);
			$(document).trigger('pickup_store_selected', [_id]);
		}
	}
});