<?php
	/**
	* Template Name: Form page
	*/

	get_header();
	get_template_part('templates/breadcrumbs', 'tpl');

	if (have_posts())
	{
		while (have_posts())
		{
			the_post();
?>
			<section class="page-intro">
				<h1 class="page-intro__title">
					<?php the_title(); ?>
				</h1>
			</section>
			<section class="form-page-container" style="padding: 20px; background-color: rgba(255,255,255,0.6);">
				<div class="wysiwyg">
					<?php the_content(); ?>
				</div>
			</section>
<?php
		}
	}

	get_footer();
