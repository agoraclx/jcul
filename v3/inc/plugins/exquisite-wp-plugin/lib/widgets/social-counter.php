<?php
// thb Flickr Widget
class widget_thbsocialcounter extends WP_Widget { 
	function widget_thbsocialcounter() {
		/* Widget settings. */
		$widget_ops = array('description' => __('Display a count of your RSS subscribers, Twitter followers, Facebook fans',THB_THEME_NAME) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'socialcounter' );

		/* Create the widget. */
		$this->WP_Widget( 'socialcounter', __('Exquisite - Social Counter',THB_THEME_NAME), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'] );
		$twitter = $instance['twitter'];
		$facebook = $instance['facebook'];
		$rss_text = $instance['rss_text'];
		$rss_link = $instance['rss_link'];
		
		// Output
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		//rss
		if($rss_link != '') {
			$rssurl = $rss_link;
		} else {
			$rssurl = get_bloginfo('rss2_url');
		}
		
		//facebook
		$interval = 3600;
		if($_SERVER['REQUEST_TIME'] > 1) {
			@$api = wp_remote_get('http://graph.facebook.com/' . $facebook);
			@$json = json_decode($api['body']);
			
			if($json->likes >= 1) {
				update_option('thb_facebook_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
				update_option('thb_facebook_followers', $json->likes);
				update_option('thb_facebook_link', $json->link);
			}
		}
		
		//twitter
		$interval = 3600;
		
		// Use this function to retrieve the followers count
		if(!function_exists ('thb_followers_count')) {
			function thb_followers_count($username, $consumerkey, $consumerkeysecret, $accesstoken, $accesstokensecret)
			{
				
				if(empty($username) || empty($consumerkey) || empty($consumerkeysecret) || empty($accesstoken) || empty($accesstokensecret)){
					return '0';
				}
				
				$key = 'thb_followers_count_' . $username;
			
				// Let's see if we have a cached version
				$followers_count = get_transient($key);
				
				
				if ($followers_count !== false) {
					return $followers_count;
				} else {
					// If there's no cached version we ask Twitter
					function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
					  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
					  return $connection;
					}
					  
					  							  
					$connection = getConnectionWithAccessToken($consumerkey, $consumerkeysecret, $accesstoken, $accesstokensecret);
					$json = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username) or die('Couldn\'t retrieve tweets! Wrong username?');
					
					
					if(!empty($tweets->errors)){
						return '0';
					} else {
						$count = $json[0]->user->followers_count;
						
						// Store the result in a transient, expires after 1 week
						// Also store it as the last successful using update_option
						set_transient($key, $count, 60*60*168);
						update_option($key, $count);
						return $count;
					}
				}
			}
		}
		?>
			<div class="row">
				<div class="four mobile-one columns">
					<div class="icon-holder"><a target="_blank" href="<?php echo get_option('thb_facebook_link'); ?>" class="facebook"><i class="fa fa-facebook"></i></a></div>
					<p>
						<span><?php echo get_option('thb_facebook_followers'); ?></span>
						<?php _e('Fans', THB_THEME_NAME); ?>
					</p>
				</div>
				<div class="four mobile-two columns">
					<div class="icon-holder"><a target="_blank" href="http://twitter.com/<?php echo ot_get_option('twitter_bar_username'); ?>" class="twitter"><i class="fa fa-twitter"></i></a></div>
					<p>
						<span><?php echo thb_followers_count(ot_get_option('twitter_bar_username'), ot_get_option('twitter_bar_consumerkey'), ot_get_option('twitter_bar_consumersecret'), ot_get_option('twitter_bar_accesstoken'), ot_get_option('twitter_bar_accesstokensecret')); ?></span>
						<?php _e('Followers', THB_THEME_NAME); ?>
					</p>
				</div>
				<div class="four mobile-one columns">
					<div class="icon-holder"><a target="_blank" href="<?php echo $rssurl; ?>" class="rss"><i class="fa fa-rss"></i></a></div>
					<p>
						<span><?php echo $rss_text; ?></span>
						<?php _e('Subscribers', THB_THEME_NAME); ?>
					</p>
				</div>
			</div>
		<?php
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter'] = $new_instance['twitter'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['rss_text'] = $new_instance['rss_text'];
		$instance['rss_link'] = $new_instance['rss_link'];

		return $instance;
	}
	// Settings form
	function form($instance) {
		$defaults = array(
			'title' => 'Social Counter',
			'twitter' => 'anteksiler',
			'facebook' => '256430061166535',
			'rss_text' => '1000+',
			'rss_link' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('title:', 'theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  class="widefat" />
			</p>
	
			<p>
			<label for="<?php echo $this->get_field_id( 'rss_text' ); ?>"><?php _e('rss number (if not use feedburner, or want static number)', 'theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'rss_text' ); ?>" name="<?php echo $this->get_field_name( 'rss_text' ); ?>" value="<?php echo $instance['rss_text']; ?>" class="widefat" />
			</p>
	
			<p>
			<label for="<?php echo $this->get_field_id( 'rss_link' ); ?>"><?php _e('RSS Link (leave empty to use default rss link)', 'theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'rss_link' ); ?>" name="<?php echo $this->get_field_name( 'rss_link' ); ?>" value="<?php echo $instance['rss_link']; ?>" class="widefat" />
			</p>
	
	
			<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('Twitter', 'theme'); ?></label>
			<small>Please make sure you fill out the settings inside Theme Options -> Twitter Oauth</small>
			</p>
	
			<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook page ID (<a target="_blank" href="http://findmyfacebookid.com/">more Info</a>)', 'theme'); ?></label>
			<input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" class="widefat" />
		</p>
    <?php
	}
}
function widget_thbsocialcounter_init()
{
	register_widget('widget_thbsocialcounter');
}
add_action('widgets_init', 'widget_thbsocialcounter_init');

?>