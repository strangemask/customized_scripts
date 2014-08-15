<?php

add_action( 'widgets_init', 'register_listing_widget' );

function register_listing_widget() {  
    register_widget( 'Listing_Widget' );  
}

class Listing_Widget extends WP_Widget {

	function Listing_Widget() {
		$widget_ops = array( 'classname' => 'listing', 'description' => __('Resale Listings Search Widget', 'listing') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'listing-widget' );
		$this->WP_Widget( 'listing-widget', __('Resale Listings Search', 'listing'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;

		echo $before_widget;

		// Display the widget title 
		if ( $title )
			echo $before_title . $title . $after_title;

		$hlayout = get_option('hlayout_options');

		$listing_ov_countries = explode(',', $hlayout['selected_listing_ov_countries']);

		$count_listing_country = 1;

		foreach($listing_ov_countries as $country) {
			if($count_listing_country == 1) {
				$saved_listing_ov_countries = '<option value="'.$country.'">'.$country.'</option>';
			}else {
				$saved_listing_ov_countries .= '<option value="'.$country.'">'.$country.'</option>';
			}
			$count_listing_country++;
		}

		echo '<form method="get" id="classifieds_searchform" class="classifieds_searchform" action="'.get_site_url().'/listing/">
				<p class="search_layer local_listing_search_wrap">
					<label>Type:</label> 
					<select name="listing_type" id="type" class="search_select">
						<option value="">Select Type</option>
						<option value="Office Building">Office Building</option>
						<option value="High-Tech Industrial">High-Tech Industrial</option>
						<option value="Shophouse Office">Shophouse Office</option>
						<option value="Serviced Office">Serviced Office</option>
						<option value="Business Park">Business Park</option>
						<option value="Science Park">Science Park</option>
						<option value="One-North">One-North</option>
					</select>
				</p>

				<p class="search_layer local_listing_search_wrap">
					<label for="location">Location:</label>
					<select name="location" id="location" class="search_select">
						<option value="">Select District</option>
						<option value="D01 - Boat Quay / Raffles Place">D01 - Boat Quay / Raffles Place</option>
						<option value="D02 - Chinatown / Tanjong Pagar">D02 - Chinatown / Tanjong Pagar</option>
						<option value="D03 - Alexandra / Commonwealth">D03 - Alexandra / Commonwealth</option>
						<option value="D04 - Harbourfront / Telok Blangah">D04 - Harbourfront / Telok Blangah</option>
						<option value="D05 - Buona Vista / West Coast">D05 - Buona Vista / West Coast</option>
						<option value="D06 - City Hall / Clarke Quay">D06 - City Hall / Clarke Quay</option>
						<option value="D07 - Beach Road / Bugis / Rochor">D07 - Beach Road / Bugis / Rochor</option>
						<option value="D08 - Farrer Park / Serangoon Rd">D08 - Farrer Park / Serangoon Rd</option>
						<option value="D09 - Orchard / River Valley">D09 - Orchard / River Valley</option>
						<option value="D10 - Tanglin / Holland">D10 - Tanglin / Holland</option>
						<option value="D11 - Newton / Novena">D11 - Newton / Novena</option>
						<option value="D12 - Balestier / Toa Payoh">D12 - Balestier / Toa Payoh</option>
						<option value="D13 - Macpherson / Potong Pasir">D13 - Macpherson / Potong Pasir</option>
						<option value="D14 - Eunos / Geylang / Paya Lebar">D14 - Eunos / Geylang / Paya Lebar</option>
						<option value="D15 - East Coast / Marine Parade">D15 - East Coast / Marine Parade</option>
						<option value="D16 - Bedok / Upper East Coast">D16 - Bedok / Upper East Coast</option>
						<option value="D17 - Changi Airport / Changi Village">D17 - Changi Airport / Changi Village</option>
						<option value="D18 - Pasir Ris / Tampines">D18 - Pasir Ris / Tampines</option>
						<option value="D19 - Hougang / Punggol / Sengkang">D19 - Hougang / Punggol / Sengkang</option>
						<option value="D20 - Ang Mo Kio / Bishan / Thomson">D20 - Ang Mo Kio / Bishan / Thomson</option>
						<option value="D21 - Clementi / Upper Bukit Timah">D21 - Clementi / Upper Bukit Timah</option>
						<option value="D22 - Boon Lay / Jurong / Tuas">D22 - Boon Lay / Jurong / Tuas</option>
						<option value="D23 - Bukit Batok / Bukit Panjang">D23 - Bukit Batok / Bukit Panjang</option>
						<option value="D24 - Choa Chu Kang / Tengah">D24 - Choa Chu Kang / Tengah</option>
						<option value="D25 - Admiralty / Woodlands">D25 - Admiralty / Woodlands</option>
						<option value="D26 - Mandai / Upper Thomson">D26 - Mandai / Upper Thomson</option>
						<option value="D27 - Sembawang / Yishun">D27 - Sembawang / Yishun</option>
						<option value="D28 - Seletar / Yio Chu Kang">D28 - Seletar / Yio Chu Kang</option>
					</select>
				</p>

				<p class="search_layer">
					<label for="lprice">Price:</label>
					<select name="lprice" id="lprice" class="lprice">
						<option value="">Select Price</option>
						<option value="4-5">S4-$5</option>
						<option value="5-6">S5-$6</option>
						<option value="6-7">S6-$7</option>
						<option value="7-8">S7-$8</option>
						<option value="8-9">S8-$9</option>
						<option value="9-10">S9-$10</option>
						<option value="10-11">S10-$11</option>
						<option value="11-12">S11-$12</option>
						<option value="12-13">S12-$13</option>
						<option value="13-14">S13-$14</option>
						<option value="14-15">S14-$15</option>
					</select>
				</p>

				<p class="search_layer">
					<label for="size">Floor Size:</label>
					<select name="min_size" id="min_size" class="min_size">
						<option value="">Min</option>
						<option value="500">500 sqft (46 sqm)</option>
						<option value="750">750 sqft (70 sqm)</option>
						<option value="1000">1000 sqft (93 sqm)</option>
						<option value="1200">1200 sqft (112 sqm)</option>
						<option value="1500">1500 sqft (139 sqm)</option>
						<option value="2000">2000 sqft (186 sqm)</option>
						<option value="2500">2500 sqft (232 sqm)</option>
						<option value="3000">3000 sqft (279 sqm)</option>
						<option value="4000">4000 sqft (372 sqm)</option>
						<option value="5000">5000 sqft (465 sqm)</option>
						<option value="7500">7500 sqft (679 sqm)</option>
						<option value="10000">10000 sqft (929 sqm)</option>
					</select>

					<select name="max_size" id="max_size" class="search_select_size_field">
						<option value="">Max</option>
						<option value="500">500 sqft (46 sqm)</option>
						<option value="750">750 sqft (70 sqm)</option>
						<option value="1000">1000 sqft (93 sqm)</option>
						<option value="1200">1200 sqft (112 sqm)</option>
						<option value="1500">1500 sqft (139 sqm)</option>
						<option value="2000">2000 sqft (186 sqm)</option>
						<option value="2500">2500 sqft (232 sqm)</option>
						<option value="3000">3000 sqft (279 sqm)</option>
						<option value="4000">4000 sqft (372 sqm)</option>
						<option value="5000">5000 sqft (465 sqm)</option>
						<option value="7500">7500 sqft (679 sqm)</option>
						<option value="10000">10000 sqft (929 sqm)</option>
					</select>
				</p>

				<input type="submit" class="classified_search_btn resale_search_btn" value="Search" />
			</form>';

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		//Set up some default widget settings.
		$defaults = array( 'title' => __('Resale Listings Search', 'listing'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!--Widget Title: Text Input.-->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'listing'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

	<?php
	}
} 