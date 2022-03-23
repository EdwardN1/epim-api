<?php
if(empty($this)) return;

$slides = $this->field('slider_group');
if($slides):
?>

<section class="hero-banner">
  <div class="owl-carousel__hero hero-banner-carousel owl-carousel">

    <?php
    foreach($slides as $slide):
      $slide_image_url = wp_get_attachment_image_src(
        $slide['image_id'],
        'full'
      )[0];

      $slide_image = !empty($slide_image_url) ?
        'background-image: url(' . $slide_image_url . ');' :
        false;

      $link_url = $slide['link_url'];
      $link_text = $slide['link_text'];
    ?>

      <div class="hero-banner__item" style="<?php echo $slide_image; ?>">

        <?php if(!empty($slide['content'])): ?>

          <h2 class="hero-banner__title"><?php echo $slide['content']; ?></h2>

        <?php endif;

        if(!empty($slide['sub_content'])): ?>

          <h3 class="hero-banner__sub-title"><?php echo $slide['sub_content']; ?></h3>

        <?php endif;

        // if(!empty($link_url) && !empty($link_text)):
        if(!empty($link_url)): ?>

          <!-- <a href="<?php echo $link_url; ?>" class="hero-banner__btn btn"> -->
          <a href="<?php echo $link_url; ?>" class="hero-banner__link" style="width:100%;height:100%;">
            <!-- <?php echo $link_text; ?> -->
          </a>

        <?php endif; ?>

      </div>

    <?php endforeach; ?>

  </div>
</section>

<?php endif; ?>
