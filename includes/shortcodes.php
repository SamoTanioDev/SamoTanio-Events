<?php

function samotanio_events_shortcode() {
  samotanio_events_widget_content(true);
}

add_shortcode('samotanio_events', 'samotanio_events_shortcode');
