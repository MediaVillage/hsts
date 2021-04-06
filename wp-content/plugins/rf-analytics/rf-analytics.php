<?php
/**
 * Plugin Name: RF Analytics
 * Description: Add hotjar, analytics, and fullstory
 * Version: 1.0.0
 * Author: Red Fern
 * Text Domain: rfm
 */

add_action('wp_head', 'add_google_analytics');
function add_google_analytics()
{ ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1L99YYSHQ5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1L99YYSHQ5');
</script>




<?php }

