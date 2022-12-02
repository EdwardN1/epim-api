<form role="search" method="get" id="blog-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-button">H</button>
  </div>
</form>
