<?php

function samotanio_events_widget_content($excerpt = false) {
   $events_array = array();
   $loop = new WP_Query( array( 'post_type' => 'events', 'posts_per_page' => get_option('samotanio_events_amount') ) );
   while ( $loop->have_posts() ) : $loop->the_post();
  		$id = get_the_ID();
  		$date = strtotime(get_post_meta($id, "_date", true));
  		$location = get_post_meta($id, "_location", true);
  		$time = get_post_meta($id, "_time", true);

      $day = date('d', $date);
  		$month = date('M', $date);


  		?>
  		<a href="<?php the_permalink(); ?>">
  		<div class="samotanio-events-widget">
  			<div class="samotanio-events-date"><?php echo $day; ?><span><?php echo $month; ?></span></div>
  			<div class="samotanio-events-content">
  					<span class="samotanio-events-event-title"><?php the_title(); ?></span>
            <?php
              if($excerpt) {
                the_excerpt();
              }
             ?>
  					<span class="samotanio-events-hours"><?php echo $time; ?></span>
  					<span class="samotanio-events-location"><?php echo $location; ?></span>
  			</div>
  		</div>
  		</a>
  		<?php

   endwhile; wp_reset_query();
}


function samotanio_events_load_styles() {
  wp_enqueue_style('samotanio_events_widget_style', plugins_url('/samotanio-events/public/css/widget-style.css'));
  wp_enqueue_style('samotanio_events_shortcode_style', plugins_url('/samotanio-events/public/css/shortcode-style.css'));

  // ustawienia użytkownika
  $custom_css = "";

  if(get_option('samotanio_events_hover') == 'border'){
    $custom_css .= "
      .samotanio-events-widget:hover {
          border: 1px solid #ddd;
      }
    ";
  }
  $custom_css .= "
    .samotanio-events-widget, body .samotanio-events-widget .samotanio-events-content p {
        color: ".get_option('samotanio_events_text_color').";
    }
  ";

  wp_add_inline_style('samotanio_events_widget_style', $custom_css);

}

add_action('wp_enqueue_scripts', 'samotanio_events_load_styles');
