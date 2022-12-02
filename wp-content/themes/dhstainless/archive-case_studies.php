<?php get_header(); ?>

  <!-- TOP IMAGE -->
  <div class="top-image-push"></div>

  <div class="top-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-3.jpg'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.4); background-blend-mode: multiply;"></div>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>

  <div class="container">

      <div class="row">

        <?php get_template_part('partials/sidebar', 'help'); ?>

        <!-- MAIN -->
        <div class="col-md-9">

          <div class="introduction">

            <h1>Case Studies</h1>
            <div class="line-break"></div>

            <p class="large-paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In feugiat sem justo, vitae ornare metus porta ultrices. Quisque hendrerit purus a nunc aliquet iaculis.</p>
            <p>uisque porta sagittis vehicula. In a quam elit. Nam semper, libero eu facilisis placerat, libero libero convallis enim, ut feugiat nulla ex ultricies orci. Mauris porta, nunc sit amet dapibus tincidunt, urna felis gravida justo.</p>

          </div>

          <div class="clearfix products-inner">

            <?php if(have_posts()) : ?>

              <?php while(have_posts()) : the_post(); ?>
              <?php get_template_part('partials/content', 'case-study'); ?>
              <?php endwhile; ?>

            <?php else : ?>

              <p>No case studies</p>

            <?php endif; ?>

          </div>

        </div>

      </div>

    </div>

<?php get_footer(); ?>
