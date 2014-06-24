<?php
if(post_password_required())
return;
?>
<div id="comments" class="comments-area">
<?php if(have_comments()) : ?>
<h2 class="comments-title">
<?php printf(_n('Ada %1$s Komentar di %2$s', 'Ada %1$s komentar di %2$s', get_comments_number()), number_format_i18n(get_comments_number()), '<span>'.get_the_title().'</span>');?>
</h2>
<ol class="commentlist">
<?php wp_list_comments(array ('callback' => 'jcul_agora_comment', 'reverse_top_level' => true, 'style'    => 'ol')); ?>
</ol>
<?php if(get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
<nav id="comment-nav-below" class="navigation" role="navigation">
<h1 class="assistive-text section-heading">Comment navigation</h1>
<div class="nav-previous"><?php previous_comments_link('&larr; Komentar lama'); ?></div>
<div class="nav-next"><?php next_comments_link('komentar baru &rarr;'); ?></div>
</nav>
<?php
endif;
if(!comments_open() && get_comments_number()) :
?>
<p class="nocomments">berkomentar untuk artikel ini sudah ditutup</p>
<?php endif;
endif;
comment_form_jcul();
?>
</div>