<?php

function samotanio_events_create_posttype()
{
  register_post_type( 'events',
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' )
            ),
            'public' => true,
            'has_archive' => true,
            //'rewrite' => array('slug' => 'events'),
            'supports' => array( 'title', 'editor', 'excerpt' ),
            'register_meta_box_cb' => 'samotanio_events_metaboxes'
        )
    );
}

add_action( 'init', 'samotanio_events_create_posttype' );


function samotanio_events_metaboxes()
{
	add_meta_box('samotanio_events_location', 'Event Location', 'samotanio_events_location', 'events', 'normal', 'default');
  add_meta_box('samotanio_events_date', 'Event Date', 'samotanio_events_date', 'events', 'side', 'default');
  add_meta_box('samotanio_events_time', 'Event Time', 'samotanio_events_time', 'events', 'side', 'default');
}


function samotanio_events_location()
{
	global $post;
	$value = get_post_meta($post->ID, '_location', true);
	echo '<input type="text" name="_location" value="' . $value  . '" class="widefat" />';
}

function samotanio_events_date()
{
	global $post;
	$value = get_post_meta($post->ID, '_date', true);
	echo '<input type="date" name="_date" value="' . $value  . '" class="widefat" />';
}

function samotanio_events_time()
{
	global $post;
	$value = get_post_meta($post->ID, '_time', true);
	echo '<input type="time" name="_time" value="' . $value  . '" class="widefat" />';

  wp_nonce_field( plugin_basename(__FILE__), 'eventmeta_field' );
}


function samotanio_events_save_meta($post_id, $post)
{
	if (isset($_POST['eventmeta_field'])) {

		if (!wp_verify_nonce($_POST['eventmeta_field'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID)) return $post->ID;

		$events_meta['_location'] = $_POST['_location'];
		$events_meta['_date'] = $_POST['_date'];
		$events_meta['_time'] = $_POST['_time'];

		foreach($events_meta as $key => $value) { 
			if ($post->post_type == 'revision') return; 
			if (get_post_meta($post->ID, $key, FALSE)) { 
				update_post_meta($post->ID, $key, $value);


			}
			else { 
				add_post_meta($post->ID, $key, $value);
			}

			if (!$value) delete_post_meta($post->ID, $key);
		}
	}
}

add_action('save_post', 'samotanio_events_save_meta', 1, 2);

function samotanio_events_add_settings_page() {
   add_submenu_page('edit.php?post_type=events', 'samotanio Events Settings',
                  'Settings', 'manage_options', 'samotanio_events_settings',
                  'samotanio_events_settings_content');
}

add_action('admin_menu', 'samotanio_events_add_settings_page');



function samotanio_events_settings_content() {
  if(! current_user_can('manage_options')) {
    wp_die(__('Nie posiadasz wystarczających uprawnień.'));
  }


  if($_REQUEST['action'] == 'save') {

      update_option('samotanio_events_hover', $_REQUEST['samotanio_events_hover']);
      update_option('samotanio_events_text_color', $_REQUEST['samotanio_events_text_color']);
      update_option('samotanio_events_amount', $_REQUEST['samotanio_events_amount']);

      ?>
        <div class="notice updated">
          <p>Zmiany zostały zapisane.</p>
        </div>
      <?php

  }

  ?>

    <div class="samotanio-events-options-wrapper">
      <form class="wrap samotanio-events-options" method="post">
        <h2>Ustawienia wtyczki samotanio Events</h2>
        <div class="samotanio-events-options-header postbox">


          <h3>Właściwości wtyczki</h3>
          <p>Tutaj możesz zmodyfikować właściwości wtyczki.</p>

          <table class="samotanio-events-widget-styles">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="samotanio_events_amount">Ilość wydarzeń</label>
                </th>
                <th scope="row">
                  <input type="number" name="samotanio_events_amount" value="<?php echo get_option('samotanio_events_amount'); ?>">
                </th>
              </tr>
            </tbody>
        </table>


            <h3>Style widgetu</h3>
            <p>Tutaj możesz zmodyfikować wygląd widgetu samotanio Events.</p>

            <table class="samotanio-events-widget-styles">
              <tbody>
                <tr>
                    <th scope="row">
                        <label for="samotanio_events_hover">Efekt hover</label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="none">Brak</label>
                            <input type="radio" value="none" name="samotanio_events_hover" <?php echo get_option( 'samotanio_events_hover')=='none' ? 'checked="checked"' : ''; ?>>
                            <label for="border">Obramowanie</label>
                            <input type="radio" value="border" name="samotanio_events_hover" <?php echo get_option( 'samotanio_events_hover')=='border' ? 'checked="checked"' : ''; ?>>
                        </fieldset>
                    </td>
                </tr>
                <table>
                	<tr>
                		<th scope="row"><label for="samotanio_events_text_color">Kolor tekstu</label>
                		</th>

                		<th scope="row"><input name="samotanio_events_text_color" type="text" value="<?php echo get_option('samotanio_events_text_color'); ?>">
                		</th>
                	</tr>
                </table>
              </tbody>
          </table>

        </div>
        <input type="hidden" name="action" value="save">
        <input type="submit" class="button button-primary" value="Zapisz zmiany">
      </form>
    </div>
  <?php
}
