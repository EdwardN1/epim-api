<?php if(!empty($terms['vertical'])): ?>

  <div class="block__slide-item">
    <?php
    foreach($terms['vertical'] as $term):
      $block_background_style = $this->term_image($term);
    ?>

      <div class="col-6 block__grid-outer">
        <a href="<?php echo get_term_link($term); ?>" class="block__item" <?php echo $block_background_style; ?>>
          <h3 class="block__item-title"><?php echo $term->name; ?></h3>
        </a>
      </div>

    <?php endforeach; ?>
  </div>

<?php endif;
