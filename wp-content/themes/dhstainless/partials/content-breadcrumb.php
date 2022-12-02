<!-- BREADCRUMB -->
<div class="container">
  <div class="row breadcrumbs">
    <div class="col-md-12 bread">
      
      <?php
      if ( function_exists('yoast_breadcrumb') ) {
      yoast_breadcrumb('
      <ol class="breadcrumb">','</ol>
      ');
      }
      ?>
    </div>
  </div>
</div>
