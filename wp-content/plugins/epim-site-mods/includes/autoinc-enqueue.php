<?php
//wp_enqueue_style( 'foundation-css', epsm_PLUGINURI . 'assets/foundation/css/style.css', array(''), filemtime(epsm_PLUGINPATH . '/assets/foundation/css/style.css'), 'all' );

wp_enqueue_script( 'epsm-site-js', epsm_PLUGINURI . 'assets/scripts.js', array( 'jquery' ), filemtime(epsm_PLUGINURI . 'assets/scripts.js'), true );
wp_enqueue_style( 'epsm-site-css', epsm_PLUGINURI . 'assets/styles.css', array(), filemtime(epsm_PLUGINURI . 'assets/styles.css'), 'all' );