<?php
$node_id = get_query_var('node_id');
$args = [
  'post_type' => 'post',
  'category_name' => 'case-studies',
  'meta_query' => [
    [
      'key' => '_kos_cmb2_case_study_product_group',
      'value' => $node_id,
      'compare' => 'LIKE'
    ]
  ],
  'orderby' => 'rand'
];

$results = new WP_Query($args);

if($results->have_posts()):
  $post = get_post($results->posts[0]);
  setup_postdata($post);

  $case_study_fields = new CMB2Fields(get_the_ID());
  $case_study_image = wp_get_attachment_image_src(
    $case_study_fields->field('article_listing_image_id'),
    'article-listing-image'
  )[0];
?>

  <div class="related__bar">
    <h3 class="related__title">Related Case Study</h3>
  </div>
  <article class="grid article-list__item">
    <div class="col-3 article-list__image"
         style="background-image: url('<?php echo $case_study_image; ?>')"></div>
    <div class="col-9 article-list__text">
      <h2 class="article-list__title">
        <a href="<?php echo get_permalink(); ?>">
          <?php echo get_the_title(); ?>
        </a>
      </h2>
      <time class="article-list__date">
        <?php echo get_the_date('d/m/Y'); ?>
      </time>
      <p class="article-list__description"><?php echo get_the_excerpt(); ?></p>
      <a href="<?php echo get_permalink(); ?>" class="read-more">Read more</a>
    </div>
  </article>

  <?php
  wp_reset_postdata();
endif;
