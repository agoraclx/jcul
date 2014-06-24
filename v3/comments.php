<?php
/* ----------------------------------------------------------------------------------- */
/*  Begin processing our comments
  /*----------------------------------------------------------------------------------- */

wp_enqueue_script('parsley');
if(post_password_required()) :
  ?>
  <p class="nopassword"><?php _e("Silahkan masukkan password untuk melihat artikel ini", THB_THEME_NAME); ?></p>
  </div><!-- #comments -->
  <?php
  return;
endif;

if(have_comments()) :
  ?>
  <div class="headline"><h2><?php comments_number(__('No Responses', THB_THEME_NAME), __('1 komentar', THB_THEME_NAME), __('% komentar', THB_THEME_NAME)); ?></h2> <a href="#respond"><?php _e("Tinggalkan komentar jculers", THB_THEME_NAME); ?></a></div>
  <ol class="commentlist">
    <?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
  </ol>

  <?php if(get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?  ?>
    <div class="navigation">
      <div class="nav-previous"><?php previous_comments_link(); ?></div>
      <div class="nav-next"><?php next_comments_link(); ?></div>
    </div><!-- .navigation -->
    <?php
  endif;
  if(!empty($comments_by_type['pings'])) :
    ?>
    <div class="headline"><h2><?php _e("Trackbacks/Pingbacks", THB_THEME_NAME); ?></h2></div>
    <ol class="pingslist">
      <?php wp_list_comments('type=pings&callback=list_pings'); ?>
    </ol>
    <?php
  endif;
else :
  if(!comments_open()) :
    ?>
    <p class="nocomments"><?php // _e("Diskusi sudah ditutup", THB_THEME_NAME);         ?></p>
    <?php
  endif; // end ! comments_open()
endif; // end have_comments()
// Comment Form
$commenter = wp_get_current_commenter();
$req       = get_option('require_name_email');
$aria_req  = ( $req ? ' aria-required="true" data-required="true"' : '' );

$defaults = array('fields'               => apply_filters('comment_form_default_fields', array(
    'author' => '<div class="row"><div class="four columns"><label>'.__('Name <span>*</span>', THB_THEME_NAME).'</label><input id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30"'.$aria_req.' /></div>',
    'email'  => '<div class="four columns"><label>'.__('Email <span>*</span>', THB_THEME_NAME).'</label><input id="email" name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.$aria_req.' /></div>',
    'url'    => '<div class="four columns"><label>'.__('Website', THB_THEME_NAME).'</label><input name="url" size="30" id="url" value="'.esc_attr($commenter['comment_author_url']).'" type="text" /></div></div>')),
  'comment_field'        => '<div class="row"><div class="twelve columns"><label>'.__('Comment', THB_THEME_NAME).'</label><textarea name="comment" id="comment"'.$aria_req.' rows="10" cols="58"></textarea></div></div>',
  'must_log_in'          => '<p class="must-log-in">'.sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', THB_THEME_NAME), wp_login_url(apply_filters('the_permalink', get_permalink()))).'</p>',
  'logged_in_as'         => '<p class="logged-in-as">'.sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', THB_THEME_NAME), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))).'</p>',
  'comment_notes_before' => '<p class="comment-notes">'.__('Alamat email jculers tidak pernah dipublikasikan, fields yang bertanda * harus diisi.').'</p>',
  'comment_notes_after'  => '',
  'id_form'              => 'form-comment',
  'id_submit'            => 'submit',
  'title_reply'          => __('Tinggalkan komentar jculers', THB_THEME_NAME),
  'title_reply_to'       => __('Tinggalkan komentar jculers untuk %s', THB_THEME_NAME),
  'cancel_reply_link'    => __('Batal untuk memberikan komentar', THB_THEME_NAME),
  'label_submit'         => __('Submit Komentar mu', THB_THEME_NAME),
);
comment_form($defaults);
