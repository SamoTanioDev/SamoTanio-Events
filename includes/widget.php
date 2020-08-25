<?php
class samotanio_events_Widget extends WP_Widget

{
	function __construct()
	{
		parent::__construct('samotanio_events_widget', 'samotanio Events', array(
			'description' => 'Widget pokazuje zaplanowane wydarzenia.'
		));
	}

	function widget($args, $instance)
	{
		echo $args['before_widget'];
		$title = apply_filters('widget_title', $instance['title']);
		echo $args['before_title'] . $title . $args['after_title'];

		samotanio_events_widget_content(); // wyświetlamy wydarzenia

		echo $args['after_widget'];
	}

	function form($instance)
	{
		$defaults = array(
			'title' => 'Events'
		);
		$instance = wp_parse_args((array)$instance, $defaults);
   ?>

      <p>
        <label for="title">Tytuł</label>
        <input type="text" id="<?php
      		echo $this->get_field_id('title'); ?>" name="<?php
      		echo $this->get_field_name('title'); ?>" value="<?php
      		echo $instance['title']; ?>"/>
      </p>

  <?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
}

function samotanio_events_load_widget()
{
	register_widget('samotanio_events_widget');
}

add_action('widgets_init', 'samotanio_events_load_widget');
