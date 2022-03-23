<?php
$node_id = get_query_var('node_id');
$product_request = new \ElectrikaAPI\Request($node_id);
$product_node = $product_request->for('node');
$product_datasheet_pdf = $product_request->for('datasheets_pdf');

$available_downloads = array(
  'pdf'                         => 'Kosnic Product Catalogue',
  'flipbook'                    => 'Kosnic Product Flipbook',
  'accessories'                 => 'Kosnic Product Accessories',
  'application_guide'           => 'Kosnic Application Guide',
  'brochure'                    => 'Kosnic Product Brochure',
  'dimensions'                  => 'Kosnic Product Dimensions',
  'finishes'                    => 'Kosnic Product Finishes',
  'install_instructions'        => 'Kosnic Product Install Instructions',
  'order_codes'                 => 'Kosnic Product order Codes',
  'overview'                    => 'Kosnic Product Overview',
  'product_catalogue'           => 'Product Catelogue',
  'selection_charts'            => 'Kosnic Product Selection Charts',
  'tech_and_dimensions'         => 'Kosnic Product Technical & Dimensions',
  'tech_data'                   => 'Kosnic Product Technical Data',
  'tech_data_and_dimensions'    => 'Kosnic Product Technical data & Dimensions',
  'test_cert'                   => 'Kosnic Test Certificate',
  'price_list'                  => 'Kosnic Product Price list',
  'typical_applications'        => 'Kosnic Product Application',

);

if(empty($product_node->body) || $product_node->status !== 200):
  get_header();
  get_template_part('templates/breadcrumbs', 'tpl');

  $error_message = !empty($product_node->msg) ?
    $product_node->msg :
    'Sorry, there was an error fetching this product, please try again';
  ?>

  <h2 class="api-error"><?php echo $error_message; ?></h2>

<?php
else:
  $product = new \ElectrikaAPI\ProductMapper(
    $product_request,
    $product_node->body
  );

  add_filter('wpseo_title', function($title) use ($product) {
    return $product->name->common . ' - ' . get_bloginfo('name');
  }, LOAD_AFTER_THEME);

  add_filter('wpseo_metadesc', function($desc) use ($product) {
    return $product->attributes->description->short;
  }, LOAD_AFTER_THEME);

  get_header();
  get_template_part('templates/breadcrumbs', 'tpl'); ?>

  <section class="product-single js-product-single"
           data-product-node-id="<?php echo $node_id; ?>">
    <div class="container">
      <h1 class="product-single__title"><?php echo $product->name->common; ?></h1>
      <div class="grid product-single__container">
        <div class="col-4 product-single__images">
          <div class="product-single__images-inner grid">
            <div class="col-12 product-single__images-full">
              <div class="product-single__images-main js-lightbox"
                   style="<?php echo $product->background_image_url('medium', true); ?>"
                   data-mfp-src="<?php echo $product->image_url('large', true); ?>">
              </div>
            </div>
            <div class="product-single__images-grid">

              <?php if($product->image_url('tech1')): ?>

                <div class="col-4 product-single__images-thumbnail js-lightbox"
                     data-mfp-src="<?php echo $product->image_url('tech1'); ?>">
                  <img src="<?php echo $product->image_url('tech1'); ?>"
                       alt="<?php echo $product->attributes->images->tech1->alt; ?>" />
                </div>

              <?php
              endif;
              if($product->image_url('tech2')):
              ?>

                <div class="col-4 product-single__images-thumbnail js-lightbox"
                     data-mfp-src="<?php echo $product->image_url('tech2'); ?>">
                  <img src="<?php echo $product->image_url('tech2'); ?>"
                       alt="<?php echo $product->attributes->images->tech2->alt; ?>" />
                </div>

              <?php
              endif;
              if($product->image_url('tech3')):
              ?>

                <div class="col-4 product-single__images-thumbnail js-lightbox"
                     data-mfp-src="<?php echo $product->image_url('tech3'); ?>">
                  <img src="<?php echo $product->image_url('tech3'); ?>"
                       alt="<?php echo $product->attributes->images->tech3->alt; ?>" />
                </div>

              <?php endif; ?>

            </div>
          </div>
        </div>
        <div class="col-8 product-single__tabs">
          <ul class="tabs">
            <li class="tabs__title js-tabs-title active" data-tab="#tab-description">Description</li>
            <li class="tabs-content__accordion wysiwyg js-tabs-content active" id="tab-description-accordion">
              <h2 class="tabs-content__title">Features</h2>
              <?php
              echo wpautop($product->attributes->description->long);

              if(!empty($product->datasheets->features)):
              ?>

              <ul>
                <?php foreach($product->datasheets->features as $key => $value): ?>
                  <li class="tabs-content__list"><?php echo $value; ?></li>
                <?php endforeach; ?>
              </ul>

              <?php endif; ?>
            </li>
            <li class="tabs__title js-tabs-title" data-tab="#tab-specification">Specification</li>

            <li class="tabs-content__accordion wysiwyg js-tabs-content" id="tab-specification-accordion">
              <h2 class="tabs-content__title">Specification</h2>
              <?php if(!empty($product->datasheets->specifications)): ?>

              <ul>
                <?php foreach($product->datasheets->specifications as $key => $value): ?>
                  <li class="tabs-content__list">
                    <strong><?php echo $key; ?></strong>: <?php echo $value; ?>
                  </li>
                <?php endforeach; ?>
              </ul>

              <?php endif; ?>
            </li>

            <li class="tabs__title js-tabs-title" data-tab="#tab-downloads">Downloads</li>
            <li class="tabs-content__accordion tabs__download wysiwyg js-tabs-content" id="tab-downloads-accordion">
              <ul>

                <?php
                $has_pdf = false;

                if(!empty($product->attributes->flipbook)):
                ?>

                  <li class="tabs-content__list">
                    <a class="disable-pdf-icon"
                       href="<?php echo $product->attributes->flipbook; ?>"
                       target="_blank">
                       Kosnic Product Flipbook
                    </a>
                  </li>

                  <?php
                  $has_pdf = true;
                endif;
                if(!empty($product->attributes->pdf)):
                ?>

                  <li class="tabs-content__list">
                    <a class="disable-pdf-icon"
                       href="<?php echo $product->attributes->pdf; ?>"
                       target="_blank">
                       Kosnic Product Catalogue
                    </a>
                  </li>

                  <?php
                  $has_pdf = true;
                endif;
                if($product_datasheet_pdf->status === 200 &&
                  !empty($product_datasheet_pdf->body)):
                ?>

                  <li class="tabs-content__list">
                    <a class="disable-pdf-icon"
                       href="<?php echo $product_datasheet_pdf->body; ?>"
                       target="_blank">
                       Kosnic Datasheet PDF
                    </a>
                  </li>

                  <?php
                  $has_pdf = true;
                endif;
                if($has_pdf === false):
                ?>

                  <li class="tabs-content__list">
                    No downloads available for this product
                  </li>

                <?php endif; ?>

              </ul>
            </li>
          </ul>

          <div class="tabs-content wysiwyg js-tabs-content active" id="tab-description">
            <h2 class="tabs-content__title">Features</h2>
            <?php
            echo wpautop($product->attributes->description->long);

            if(!empty($product->datasheets->features)):
            ?>

            <ul>
              <?php foreach($product->datasheets->features as $key => $value): ?>
                <li class="tabs-content__list"><?php echo $value; ?></li>
              <?php endforeach; ?>
            </ul>

            <?php endif; ?>
          </div>

          <div class="tabs-content wysiwyg js-tabs-content" id="tab-specification">
            <h2 class="tabs-content__title">Specification</h2>
            <?php if(!empty($product->datasheets->specifications)): ?>

            <ul>
              <?php foreach($product->datasheets->specifications as $key => $value): ?>
                <li class="tabs-content__list">
                  <strong><?php echo $key; ?></strong>: <?php echo $value; ?>
                </li>
              <?php endforeach; ?>
            </ul>

            <?php endif; ?>
          </div>

          <div class="tabs-content tabs__download wysiwyg js-tabs-content" id="tab-downloads">
            <ul>

              <?php
              $has_pdf = false;

              if(!empty($product->attributes->flipbook)):
              ?>

                <li class="tabs-content__list">
                  <a class="disable-pdf-icon"
                     href="<?php echo $product->attributes->flipbook; ?>"
                     target="_blank">
                     Kosnic Product Flipbook
                  </a>
                </li>

                <?php
                $has_pdf = true;
              endif;
              if(!empty($product->attributes->pdf)):
              ?>

                <li class="tabs-content__list">
                  <a class="disable-pdf-icon"
                     href="<?php echo $product->attributes->pdf; ?>"
                     target="_blank">
                     Kosnic Product Catalogue
                  </a>
                </li>

                <?php
                $has_pdf = true;
              endif;
              if($product_datasheet_pdf->status === 200 &&
                !empty($product_datasheet_pdf->body)):
              ?>

                <li class="tabs-content__list">
                  <a class="disable-pdf-icon"
                     href="<?php echo $product_datasheet_pdf->body; ?>"
                     target="_blank">
                     Kosnic Datasheet PDF
                  </a>
                </li>

                <?php
                $has_pdf = true;
              endif;
              if($has_pdf === false):
              ?>

                <li class="tabs-content__list">
                  No downloads available for this product
                </li>

              <?php endif; ?>

            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container product-single__accordion js-product-accordion">
      <h2 class="product-single__accordion-title active js-product-accordion-title">Product List</h2>
      <div class="product-single__accordion-content js-common-products"></div>
      <h2 class="product-single__accordion-title js-product-accordion-title">Components</h2>
      <div class="product-single__accordion-content js-component-products"></div>
      <h2 class="product-single__accordion-title js-product-accordion-title">Accessories</h2>
      <div class="product-single__accordion-content js-accessory-products"></div>
    </div>

    <?php
    $parent_breadcrumb = $product_request->for('breadcrumb')->body[0];
    $breadcrumb_category = new ElectrikaAPI\Category($parent_breadcrumb);
    $breadcrumb_url = site_url(
      "/products/categories/{$breadcrumb_category->attributes->slug}"
    );
    ?>

    <div class="container product-single__related">
      <section class="related container">
        <div class="related__bar">
          <h3 class="related__title">Related Products</h3>
          <a href="<?php echo $breadcrumb_url; ?>">View All</a>
        </div>

        <section class="grid owl-carousel__related js-related-products"
                 data-parent-node="<?php echo $breadcrumb_category->ID; ?>">
        </section>
      </section>

      <?php get_template_part('templates/related-article', 'tpl'); ?>
    </div>
  </section>

  <?php
endif;
get_footer();
