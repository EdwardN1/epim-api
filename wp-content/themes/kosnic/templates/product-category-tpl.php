<?php

foreach($categories as $category_data):
  if($category_data['NodeType'] !== 0) continue;

  $category = new ElectrikaAPI\Category($category_data);
  $category_css_classes = $category->css_classes();

  if(!empty($breadcrumbs)):
    $active_breadcrumb_index = array_search(
      $category->ID,
      array_column($breadcrumbs, 'ID')
    );
  endif;

  if(!empty($breadcrumbs) && $active_breadcrumb_index !== false):
    $category_css_classes .= ' active';
  endif;
  ?>

  <li>
    <a href="<?php echo site_url("/products/categories/{$category->attributes->slug}"); ?>"
       class="<?php echo $category_css_classes; ?>"
       data-node-id="<?php echo $category->ID; ?>">
       <?php echo $category->name; ?>
     </a>

     <?php if($category->attributes->hasChildren === true): ?>

       <ul class="product-category__sub-menu js-sub-menu">
         <li>
           <a href="<?php echo site_url("/products/categories/{$category->attributes->slug}"); ?>"
              class="product-category__item product-category__item--view-all js-product-category-item"
              data-node-id="<?php echo $category->ID; ?>">
              View All <?php echo $category->name; ?>
            </a>
         </li>
       </ul>

     <?php endif; ?>
  </li>

<?php endforeach;
