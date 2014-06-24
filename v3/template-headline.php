<?php if(!is_page_template('template-home.php') && !is_page_template('template-home-style2.php') && !is_page_template('template-home-style3.php') && !is_author() && !is_single() && !is_404() /* && is_archive() || is_search() */)
{ ?>
  <div class="row is_white">
    <div class="twelve columns">
      <div class="archiveheadline">
        <?php
        if(is_archive() || is_search())
        {
          echo archive_title();
        }
        else
        {
          echo '<h1>'.get_the_title($wp_query->get_queried_object_id()).'</h1>';
        }
        ?>
      </div>
    </div>
  </div>
<?php }