<?php get_header(); ?>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<?php get_template_part('partials/content', 'case-study'); ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
