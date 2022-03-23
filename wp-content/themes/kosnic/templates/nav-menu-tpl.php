  <section class="nav-menu">
    <nav class="container">
      <div class="nav-menu__items js-navigation-menu">
        <ul class="nav-menu__list">
          <?php get_template_part('templates/telelphone-menu-item', 'tpl'); ?>
        </ul>

        <?php
        wp_nav_menu([
          'theme_location' => 'main_menu',
          'container' => false,
          'menu_class' => 'nav-menu__list'
        ]);
        wp_nav_menu([
          'theme_location' => 'top_right_nav',
          'container' => false,
          'menu_class' => 'nav-menu__list nav-menu__list--mobile'
        ]);
        ?>

      </div>
      <form action="/product-search" method="post" class="search-form js-search-form">
        <input type="search"
               class="search-form__input"
               name="product_search"
               placeholder="Search Products" />
        <button type="submit" class="search-form__submit">
          <i class="fa fa-search"></i>
        </button>
      </form>
    </nav>
  </section>
