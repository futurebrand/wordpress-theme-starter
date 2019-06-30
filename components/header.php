<header class="header">
  <button type="button" class="header__toggle">open nav</button>
  <nav class="header-nav">
    <?php
      wp_nav_menu( array(
        'theme_location' => 'header-nav',
        'container' => 'ul',
        'container_class' => 'header-nav-container',
        'menu_class' => 'header-nav-items'
      ));
    ?>
  </nav>
</header>