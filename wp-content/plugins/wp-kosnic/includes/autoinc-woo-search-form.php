<?php
add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );
function woo_custom_product_searchform( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" class="search-form js-search-form">
 <div>
 <label class="screen-reader-text" for="s">' . __( 'Enter Keyword or Product Number', 'woocommerce' ) . '</label>
 <input type="text" class="search-form__input" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Enter Keyword or Product Number', 'woocommerce' ) . '" />
 
 <button type="submit" id="searchsubmit" class="search-form__submit">
            <i class="fa fa-search"></i>
          </button>
 <input type="hidden" name="post_type" value="product" />
 </div>
 </form>';

    return $form;

}