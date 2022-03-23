<?php
		$site_options = new SiteOptions();
		$copyright_text = $site_options->field('copyright_text');
?>
		<section class="connect-links">
			<div class="container grid">
<?php
				get_template_part('templates/newsletter', 'tpl');
				get_template_part('templates/social-icons', 'tpl');
?>
			</div>
		</section>

		<section class="page-footer">
			<div class="container grid">
				<footer class="footer__copyright col-6">
<?php
					if ($copyright_text)
					{
?>
						<cite><?= $copyright_text; ?></cite><br />
<?php
					}
?>
					<cite><a href="https://kloc.co.uk/">Web Development</a> by KLOC Digital Solutions</cite><br />
					<cite>www.kosniclighting.co.uk is not a Kosnic property</cite>
				</footer>

				<nav class="page-footer__nav col-6">
<?php
					wp_nav_menu(
					[
						'theme_location' => 'footer_menu',
						'container'      => false,
						'menu_class'     => 'page-footer__menu',
					]);
?>
				</nav>
			</div>
		</section>

		<?php wp_footer(); ?>

		<script type="text/javascript">
			jQuery(function($)
			{
				$(".js-product-pagination").on("click", ".js-pagination-btn", function()
				{
					$("html, body").animate({scrollTop : "0px"}, 500);
				});
			});
		</script>
	</body>
</html>
