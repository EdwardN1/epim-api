<ul itemscope
    itemtype="http://schema.org/BreadcrumbList"
    class="container breadcrumbs__list">
  <li itemprop="itemListElement"
      itemscope
      itemtype="http://schema.org/ListItem"
      class="breadcrumb__item">
      <a itemscope
         itemtype="http://schema.org/Thing"
         itemprop="item"
         href="<?php echo site_url(); ?>"
         class="breadcrumb__item-link">
         <span itemprop="name" class="breadcrumb__item-name">Home</span>
      </a>
      <meta itemprop="position" content="1">
  </li>
  <li itemprop="itemListElement"
      itemscope
      itemtype="http://schema.org/ListItem"
      class="breadcrumb__item">
      <a itemscope
         itemtype="http://schema.org/Thing"
         itemprop="item"
         href="<?php echo site_url('/products/'); ?>"
         class="breadcrumb__item-link">
         <span itemprop="name" class="breadcrumb__item-name">Products</span>
      </a>
      <meta itemprop="position" content="2">
  </li>

  <?php
  $i = 3;

  foreach($breadcrumbs as $breadcrumb_data):
    $breadcrumb = new ElectrikaAPI\Category($breadcrumb_data);
    $breadcrumb_url = site_url(
      "/products/categories/{$breadcrumb->attributes->slug}"
    );
    ?>

    <li itemprop="itemListElement"
        itemscope
        itemtype="http://schema.org/ListItem"
        class="breadcrumb__item">
        <a itemscope
           itemtype="http://schema.org/Thing"
           itemprop="item"
           href="<?php echo $breadcrumb_url; ?>"
           class="breadcrumb__item-link">
           <span itemprop="name" class="breadcrumb__item-name">
             <?php echo $breadcrumb->name; ?>
           </span>
        </a>
        <meta itemprop="position" content="<?php echo $i; ?>">
    </li>

    <?php
    $i++;
  endforeach;
  ?>

</ul>
