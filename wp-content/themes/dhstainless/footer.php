
  <!--
  <div class="container-full footer-top">
    <div class="container">
      <div class="interested">Enquire about our range of products.</div>
      <div class="quote-button"><a href="<?php bloginfo('url'); ?>/contact/" class="btn-outline">Contact Us</a></div>
    </div>
  </div>
-->

  <div class="container-full footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-md-4 copyright">
          <!--<img src="<?php /*bloginfo('template_directory'); */?>/img/dhstainless-logo@2x.png" class="footer-logo">-->
            <img src="/wp-content/themes/dhstainless/img/new-logo-dark.svg" class="footer-logo"style="width: 100% !important;">
          <p>Copyright &copy; <?php echo date("Y"); ?> DH Stainless (including DH Press Fit). All Rights Reserved.</p>

          <p>Created by <a href="https://www.21digital.agency/">21Digital</a></p>
        </div>
        <div class="col-md-3 customer-services">
          <h4>Customer Services</h4>
          <?php
            $args = array(
              'menu' => 'footer',
              'menu_class' => '',
              'container' => '',
              'fallback_cb' => false
            );
            wp_nav_menu( $args );
          ?>
        </div>
        <div class="col-md-3">
          <h4>Head Office</h4>
          <p>DH Stainless Ltd<br/>
          Units 5, Shorten Brook Way, Altham Industrial Estate, Altham, Lancashire, BB5 5YJ</p>
          <h4>Enquiries</h4>
          <p><a href="tel:01254237409" class="underline">01254 237 409</a><br><a href="mailto:sales@dhstainless.co.uk" class="underline">sales@dhstainless.co.uk</a></p>
        </div>
        <div class="col-md-2 accreditations">
          <img src="<?php bloginfo('template_directory'); ?>/img/accreditations.png" alt="">
        </div>
      </div>
    </div>
  </div>

</div><!-- WRAPPER -->

<?php wp_footer(); ?>

</body>
</html>
