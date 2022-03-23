<?php if(!empty($terms['horizontal'])): ?>

  <div class="block__slide-item">
    <div class="block__grid-outer block__grid-half">

      <?php
      foreach($terms['horizontal'] as $term):
        $block_background_style = $this->term_image($term);
      ?>

        <a href="<?php echo get_term_link($term); ?>" class="block__item block__item-half" <?php echo $block_background_style; ?>>
          <h3 class="block__item-title"><?php echo $term->name; ?></h3>
        </a>

      <?php endforeach; ?>

    </div>
  </div>

<?php endif;
