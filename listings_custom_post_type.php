<?php	
	/* add custom listing page and menu */
	function my_custom_post_listing() {
		$labels = array(
			'name' => _x('Property Listings', 'post type general name'),
			'singular_name' => _x('Property Listing', 'post type singular name'),
			'add_new' => _x('Add New', 'listing'),
			'add_new_item' => __('Add New Property Listing'),
			'edit_item' => __('Edit Property Listing'),
			'new_item' => __('New Property Listing'),
			'all_items' => __('All Property Listings'),
			'view_item' => __('View Property Listing'),
			'search_items' => __('Search Property Listings'),
			'not_found' => __('No property listings found'),
			'not_found_in_trash' => __('No listings found in the Trash'),
			'parent_item_colon' => '',
			'menu_name' => 'Listing Management'
		);
		$args = array(
			'labels' => $labels,
			'description' => 'Holds our listings and listing specific data',
			'public' => true,
			'show_ui' => true,
			'rewrite' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			/*'supports' => array('title','editor','thumbnail','excerpt','comments'),*/
			'supports' => array('title','editor','thumbnail','custom-fields','author'),
			'has_archive' => true,
		);
		register_post_type('listing', $args);
	}
	add_action('init', 'my_custom_post_listing');

/* change custom post messages */
function my_updated_messages($messages) {
	global $post, $post_ID;
	$messages['listing'] = array(
		0 => '',
		1 => sprintf( __('Listing updated. <a href="%s">View Listing</a>'), esc_url(get_permalink($post_ID)) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Listing updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Listing restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Listing published. <a href="%s">View Listing</a>'), esc_url(get_permalink($post_ID)) ),
		7 => __('Listing saved.'),
		8 => sprintf( __('Listing submitted. <a target="_blank" href="%s">Preview listing</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))) ),
		9 => sprintf( __('Listing scheduled for : <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview listing</a>'), date_i18n( __('M j, Y @ G:i'), strtotime($post->post_date) ), esc_url(get_permalink($post_ID)) ),
		10 => sprintf( __('Listing draft updated. <a target="_blank" href="%s">Preview listing</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))) ),
	);
	return $messages;
}
add_filter('post_updated_messages', 'my_updated_messages');

/* add help tab for listing page */
function my_contextual_help($contexual_help, $screen_id, $screen) {
	if('listing' == $screen->id) {
		$contextual_help = '<h2>Listings</h2>
		<p>Listings show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p>
		<p>You can view/edit the details of each product by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
	}elseif('edit-listing' == $screen->id) {
		$contextual_help = '<h2>Editing listings</h2>
		<p>This page allows you to view/modify listing details. Please make sure to fill out the available boxes with the appropriate details (listing image, price, brand) and <strong>not</strong> add these details to the listing description.</p>';
	}
	return $contextual_help;
}
add_action('contextual_help', 'my_contextual_help', 10, 3);

/* add taxonomies for listing */
function my_taxonomies_listing() {
	$labels = array(
		'name' => _x('Listing Categories', 'taxonomy general name'),
		'singular_name' => _x('Listing Category', 'taxonomy singular name'),
		'search_items' => __('Search Listing Categories'),
		'all_items' => __('All Listing Categories'),
		'parent_item' => __('Parent Listing Category'),
		'parent_item_colon' => __('Parent Listing Category:'),
		'edit_item' => __('Edit Listing Category'),
		'update_item' => __('Update Listing Category'),
		'add_new_item' => __('Add New Listing Category'),
		'new_item_name' => __('New Listing Category'),
		'menu_name' => __('Listing Categories'),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
	);
	register_taxonomy('listing_category', 'listing', $args);
}
add_action('init', 'my_taxonomies_listing', 0);



/* default map country for listing category */
function listing_category_add_new_meta_field() { 
	$map_countries = array(
		'Australia',
		'Brazil',
		'China',
		'Cyprus',
		'France',
		'India',
		'Indonesia',
		'Lithuania',
		'Malaysia',
		'New Zealand',
		'Panama',
		'Philippines',
		'Singapore',
		'Spain',
		'Thailand',
		'Turkey',
		'United Kingdom',
		'United States'
	);
	$map_zoom_levels = array(3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);
?>
	<div class="form-field">
		<label for="listing_cat_meta[def_map_country]"><?php _e( 'Manage Map Setting', 'listing_category' ); ?></label>
		<div class="map_setting_wrap">
			<select name="listing_cat_meta[def_map_country]" id="listing_cat_meta[def_map_country]" class="sel_map_country map_setting_input_bdr">
				<option value="">Select Country For Google Map Setting</option>
				<?php foreach($map_countries as $map_country):?>
					<option value="<?php echo $map_country;?>"><?php echo $map_country;?></option>
				<?php endforeach;?>
			</select>
			<span class="map_setting_divider">
				<b>OR</b>
			</span>
			<div class="lat_long_wrap">
				<label for="listing_cat_meta[latitude]">Latitude</label>
				<input type="text" name="listing_cat_meta[latitude]" id="listing_cat_meta[latitude]" class="map_setting_input_bdr">
				<label for="listing_cat_meta[longitude]">Longitude</label>
				<input type="text" name="listing_cat_meta[longitude]" id="listing_cat_meta[longitude]" class="map_setting_input_bdr">
			</div>
		</div>
	</div>
	<div class="form-field">
		<label for="listing_cat_meta[zoom_level]"><?php _e( 'Select Zoom Level For Map Setting', 'listing_category' ); ?></label>
		<select name="listing_cat_meta[zoom_level]" id="listing_cat_meta[zoom_level]" class="sel_zoom_level">
			<?php foreach($map_zoom_levels as $zoom_level):?>
			<?php if($zoom_level == 11):?>
			<option value="<?php echo $zoom_level;?>" selected="selected"><?php echo $zoom_level;?></option>
			<?php else:?>
			<option value="<?php echo $zoom_level;?>"><?php echo $zoom_level;?></option>
			<?php endif;?>
			<?php endforeach;?>
		</select>
	</div>
<?php }
add_action( 'listing_category_add_form_fields', 'listing_category_add_new_meta_field', 10, 2 );

function listing_category_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
	
	$map_countries = array(
		'Australia',
		'Brazil',
		'China',
		'Cyprus',
		'France',
		'India',
		'Indonesia',
		'Lithuania',
		'Malaysia',
		'New Zealand',
		'Panama',
		'Philippines',
		'Singapore',
		'Spain',
		'Thailand',
		'Turkey',
		'United Kingdom',
		'United States'
	);
	$map_zoom_levels = array(3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);
	
	// retrieve the existing value(s) for this meta field. This returns an array
	$listing_cat_meta = get_option( "taxonomy_listing_category_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="listing_cat_meta[def_map_country]"><?php _e( 'Manage Map Setting', 'listing_category' ); ?></label></th>
		<td>
			<div class="map_setting_wrap">
				<select name="listing_cat_meta[def_map_country]" id="listing_cat_meta[def_map_country]" class="sel_map_country map_setting_input_bdr">
				<option value="">Select Country For Google Map Setting</option>
				<?php if(isset($listing_cat_meta['def_map_country']) && $listing_cat_meta['def_map_country'] != ''):?>
					<?php foreach($map_countries as $map_country):?>
						<?php if($listing_cat_meta['def_map_country'] == $map_country):?>
						<option value="<?php echo $map_country;?>" selected="selected"><?php echo $map_country;?></option>
						<?php else:?>
						<option value="<?php echo $map_country;?>"><?php echo $map_country;?></option>
						<?php endif;?>
					<?php endforeach;?>
				<?php else:?>
					<?php foreach($map_countries as $map_country):?>
						<option value="<?php echo $map_country;?>"><?php echo $map_country;?></option>
					<?php endforeach;?>
				<?php endif;?>
				</select>
				<span class="map_setting_divider">
					<b>OR</b>
				</span>
				<div class="lat_long_wrap">
					<label for="listing_cat_meta[latitude]">Latitude</label>
					<input type="text" name="listing_cat_meta[latitude]" id="listing_cat_meta[latitude]" class="map_setting_input_bdr" value="<?php echo ($listing_cat_meta['latitude'] != '' ? $listing_cat_meta['latitude'] : '');?>">
					<label for="listing_cat_meta[longitude]">Longitude</label>
					<input type="text" name="listing_cat_meta[longitude]" id="listing_cat_meta[longitude]" class="map_setting_input_bdr" value="<?php echo ($listing_cat_meta['longitude'] != '' ? $listing_cat_meta['longitude'] : '');?>">
				</div>
			</div>
		</td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><label for="listing_cat_meta[zoom_level]"><?php _e( 'Select Zoom Level For Map Setting', 'listing_category' ); ?></label></th>
		<td>
			<select name="listing_cat_meta[zoom_level]" id="listing_cat_meta[zoom_level]" class="sel_zoom_level">
			<?php if(isset($listing_cat_meta['zoom_level']) && $listing_cat_meta['zoom_level'] != ''):?>
				<?php foreach($map_zoom_levels as $zoom_level):?>
					<?php if($listing_cat_meta['zoom_level'] == $zoom_level):?>
					<option value="<?php echo $zoom_level;?>" selected="selected"><?php echo $zoom_level;?></option>
					<?php else:?>
					<option value="<?php echo $zoom_level;?>"><?php echo $zoom_level;?></option>
					<?php endif;?>
				<?php endforeach;?>
			<?php else:?>
				<?php foreach($map_zoom_levels as $zoom_level):?>
					<?php if($zoom_level == '11'):?>
					<option value="<?php echo $zoom_level;?>" selected="selected"><?php echo $zoom_level;?></option>
					<?php else:?>
					<option value="<?php echo $zoom_level;?>"><?php echo $zoom_level;?></option>
					<?php endif;?>
				<?php endforeach;?>
			<?php endif;?>
			</select>
		</td>
	</tr>
	
<?php }
add_action( 'listing_category_edit_form_fields', 'listing_category_edit_meta_field', 10, 2 );


// Save extra taxonomy fields callback function for listing category
function save_listing_category_custom_meta( $term_id ) {

	if ( isset( $_POST['listing_cat_meta'] ) ) {
		$t_id = $term_id;
		$listing_cat_meta = get_option( "taxonomy_listing_category_$t_id" );
		$cat_keys = array_keys( $_POST['listing_cat_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['listing_cat_meta'][$key] ) ) {
				$listing_cat_meta[$key] = $_POST['listing_cat_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_listing_category_$t_id", $listing_cat_meta );
	}
}  
add_action( 'edited_listing_category', 'save_listing_category_custom_meta', 10, 2 );  
add_action( 'create_listing_category', 'save_listing_category_custom_meta', 10, 2 );


// customized Listing Taxonomy Column Data
add_filter("manage_edit-listing_category_columns", 'listing_category_columns'); 
function listing_category_columns($listing_category_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'def_map_country' => ('Country (Map)'),
		'zoom_level' => ('Zoom Level (Map)'),
        'slug' => __('Slug'),
        'posts' => __('Property Listings')
        );
    return $new_columns;
}


add_filter("manage_listing_category_custom_column", 'manage_listing_category_columns', 10, 3);
 
function manage_listing_category_columns($out, $column_name, $listing_category_id) {
    //$theme = get_term($listing_category_id, 'listing_category');
    switch ($column_name) {
        case 'def_map_country': 
            // get map country
			$listing_category_meta = get_option( "taxonomy_listing_category_$listing_category_id" );
            $out .= $listing_category_meta['def_map_country']; 
            break;
		 case 'zoom_level': 
            // get zoom level
			$listing_category_meta = get_option( "taxonomy_listing_category_$listing_category_id" );
            $out .= $listing_category_meta['zoom_level']; 
            break;
 
        default:
            break;
    }
    return $out;    
}



/* custom filters for admin resale listing list page */
function restrict_listing_by_filter() {
    global $wpdb;
	
	$screen = get_current_screen();
	$hidden_columns = get_hidden_columns($screen);
	
	if(in_array('featured_listing', $hidden_columns)) {
		$featured_listing_filter_style = 'style="display:none;"'; 
	}
	?>
	
	<select name="featured_listing_filter" id="featured_listing_filter" class="column-featured_listing" <?php echo $featured_listing_filter_style;?>>
		<option value="">Show All Listings</option>
		<option value="yes">Featured</option>
		<option value="no">Non-Featured</option>
	</select>
	
    <?php
}

if($_GET['post_type'] == 'listing') {
	add_action('restrict_manage_posts','restrict_listing_by_filter');
}

function resale_posts_where( $where ) {
    if( is_admin() ) {
        global $wpdb;       
		if ( isset( $_GET['featured_listing_filter'] ) && !empty( $_GET['featured_listing_filter'] ) ) {
            $featured_listing_filter = esc_sql( like_escape( $_GET['featured_listing_filter'] ) );
			$featured_listing_filter = htmlentities($featured_listing_filter);
			
			if($_GET['featured_listing_filter'] == 'no') {
				$where .= " AND ID NOT IN (SELECT post_id FROM " . $wpdb->postmeta ." WHERE meta_key='featured_listing' AND meta_value = 'yes')";
			}else {
				$where .= " AND ID IN (SELECT post_id FROM " . $wpdb->postmeta ." WHERE meta_key='featured_listing' AND meta_value = '$featured_listing_filter' )";
			}
        }
    }   
    return $where;
}
add_filter( 'posts_where' , 'resale_posts_where' );




/* taxonomy filter column */
add_action( 'restrict_manage_posts', 'listing_cat_filter_list' );
function listing_cat_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'listing' ) {
        wp_dropdown_categories( array(
            'show_option_all' => 'Show All Categories',
            'taxonomy' => 'listing_category',
            'name' => 'listing_category',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['listing_category'] ) ? $wp_query->query['listing_category'] : '' ),
            'hierarchical' => false,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}

add_filter( 'parse_query','perform_listing_cat_filtering' );
function perform_listing_cat_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( ( $qv['listing_category'] ) && is_numeric( $qv['listing_category'] ) ) {
        $term = get_term_by( 'id', $qv['listing_category'], 'listing_category' );
        $qv['listing_category'] = $term->slug;
    }
}

/* remove column */
add_filter( 'manage_edit-listing_columns', 'listing_columns' );
function listing_columns( $columns ) {
    unset( $columns['comments'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );	
    return $columns;
}

/* add custom columns in listing */
add_filter('manage_listing_posts_columns', 'new_add_custom_column', 5);
function new_add_custom_column($cols){
	$cols['thumbnail'] = __('Thumbnail');
	$cols['status'] = __('Status');
	$cols['price'] = __('Price');
	$cols['featured_listing'] = __('Featured');
	return $cols;
}

// Hook into the posts an pages column managing. Sharing function callback again.
add_action('manage_listing_posts_custom_column', 'new_display_custom_column', 5, 3);

function new_display_custom_column($col, $id){
	switch($col){
		case 'status': 
		echo get_post_meta($id, 'listing_default_status', true);
		break;
		case 'price':
		$min_price = get_post_meta($id, 'listing_min_price', true);
		$max_price = get_post_meta($id, 'listing_max_price', true);
		if($min_price != '') {
			echo number_format(get_post_meta($id, 'listing_min_price', true));
			if($max_price != '') echo ' - ';
		}
		if($max_price != '') {
			echo number_format(get_post_meta($id, 'listing_max_price', true));
		}		
		break;
		case 'thumbnail':
		echo '<img src="'.get_post_meta($id, 'agent_default_image1', true).'" alt="" width="60" height="40" />';
		break;
		case 'featured_listing':
			if(get_post_meta($id, 'featured_listing', true) == '' || get_post_meta($id, 'featured_listing', true) == 'no') {
				echo "<div id='featured_listing-".$id."' class='normal_project'><span></span></div>";
			}else {
				echo "<div id='featured_listing-".$id."' class='featured_listing'><span></span></div>";
			}
		break;
	}
}

/* bulk or quick edit for resale listing */
add_action( 'bulk_edit_custom_box', 'listing_bulk_edit_custom_box', 10, 2 );
add_action( 'quick_edit_custom_box', 'listing_quick_edit_custom_box', 10, 2 );

function listing_bulk_edit_custom_box( $column_name, $post_type ) {
	switch ( $post_type ) {
		case 'listing':

        switch( $column_name ) {
			case 'featured_listing':
			?>
			<fieldset class='inline-edit-col-right'>
				<div class="inline-edit-group inline_featured_wrap">
					<span class="inline-edit-featured alignleft">
						<span class="title featured_title">Featured Listing</span>
						<select name="featured_listing" id="featured_listing">
							<option value="">- No Change -</option>
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</span>
				</div>
			</fieldset>
			<?php
            break;
		}
		break;
	}
}


function listing_quick_edit_custom_box( $column_name, $post_type ) {
	switch ( $post_type ) {
		case 'listing':

        switch( $column_name ) {
			case 'featured_listing':
			?>
			<fieldset class='inline-edit-col-right'>
				<div class="inline-edit-group inline_featured_wrap">
					<span class="inline-edit-featured alignleft">
						<span class="title featured_title">Featured Listing</span>
						<select name="featured_listing" id="featured_listing">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</span>
				</div>
			</fieldset>
			<?php
            break;
		}
		break;
	}
}

add_action( 'admin_print_scripts-edit.php', 'listing_enqueue_edit_scripts' );
function listing_enqueue_edit_scripts() {
   wp_enqueue_script( 'listing-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/listing_quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}


// save quick edit data
add_action( 'save_post','listing_save_post', 10, 2 );
function listing_save_post( $post_id, $post ) {

	// don't save for autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// dont save for revisions
	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
		return $post_id;

	switch( $post->post_type ) {

		case 'listing':

        // release date
		// Because this action is run in several places, checking for the array key keeps WordPress from editing
        // data that wasn't in the form, i.e. if you had this post meta on your "Quick Edit" but didn't have it
        // on the "Edit Post" screen.
		
		if ( array_key_exists( 'featured_listing', $_POST ) ) {
			update_post_meta( $post_id, 'featured_listing', $_POST[ 'featured_listing' ] );
		}
		break;
	}
}


// save bulk edit data
add_action( 'wp_ajax_listing_save_bulk_edit', 'listing_save_bulk_edit' );
function listing_save_bulk_edit() {
	// get our variables
	$post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : array();
	$featured_listing = ( isset( $_POST[ 'featured_listing' ] ) && !empty( $_POST[ 'featured_listing' ] ) ) ? $_POST[ 'featured_listing' ] : NULL;
	
	if ( !empty( $post_ids ) && is_array( $post_ids ) && !empty( $featured_listing ) ) {
		foreach( $post_ids as $post_id ) {
			update_post_meta( $post_id, 'featured_listing', $featured_listing );
		}
	}
}
/* end bulk or quick edit for resale listing */


/* add listing meta box into listing page */
function listing_default_box_add_meta() {
	add_meta_box( 'listing-box', 'Listing Default Descriptions', 'listing_default_box_meta', 'listing', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'listing_default_box_add_meta' );

/* callback function to create input fields in meta box */
function listing_default_box_meta() {
    global $post;
    // get the custom field values (if they exist) as an array
    $values = get_post_custom( $post->ID );
    // extract the members of the $values array to their own variables (which you can see below, in the HTML code)
    extract( $values, EXTR_SKIP );
    wp_nonce_field( 'listing_box_meta_action', 'listing_box_meta_nonce' );
?>
<?php 
	$hoptions = get_option( 'hlayout_options' );
	$listing_ov_country_arrays = explode(",", $hoptions['selected_listing_ov_countries']);
	foreach($listing_ov_country_arrays as $key => $value) {
		$ov_countries[$value] = $value;
	}
	$listing_country_arrays = array(
		'Singapore' => 'Singapore' 
	);
	$listing_country_arrays += $ov_countries;
	
	$status_arrays = array('For Sale','For Rent');
	$type_arrays = array('Office Building','High-Tech Industrial','Shophouse Office','Serviced Office','Business Park','Science Park','One-North');
	
	$location_district_arrays = array(
		'D01 - Boat Quay / Raffles Place' => 'D01 - Boat Quay / Raffles Place',
		'D02 - Chinatown / Tanjong Pagar' => 'D02 - Chinatown / Tanjong Pagar',
		'D03 - Alexandra / Commonwealth' => 'D03 - Alexandra / Commonwealth',
		'D04 - Harbourfront / Telok Blangah' => 'D04 - Harbourfront / Telok Blangah',
		'D05 - Buona Vista / West Coast' => 'D05 - Buona Vista / West Coast',
		'D06 - City Hall / Clarke Quay' => 'D06 - City Hall / Clarke Quay',
		'D07 - Beach Road / Bugis / Rochor' => 'D07 - Beach Road / Bugis / Rochor',
		'D08 - Farrer Park / Serangoon Rd' => 'D08 - Farrer Park / Serangoon Rd',
		'D09 - Orchard / River Valley' => 'D09 - Orchard / River Valley',
		'D10 - Tanglin / Holland' => 'D10 - Tanglin / Holland',
		'D11 - Newton / Novena' => 'D11 - Newton / Novena',
		'D12 - Balestier / Toa Payoh' => 'D12 - Balestier / Toa Payoh',
		'D13 - Macpherson / Potong Pasir' => 'D13 - Macpherson / Potong Pasir',
		'D14 - Eunos / Geylang / Paya Lebar' => 'D14 - Eunos / Geylang / Paya Lebar',
		'D15 - East Coast / Marine Parade' => 'D15 - East Coast / Marine Parade',
		'D16 - Bedok / Upper East Coast' => 'D16 - Bedok / Upper East Coast',
		'D17 - Changi Airport / Changi Village' => 'D17 - Changi Airport / Changi Village',
		'D18 - Pasir Ris / Tampines' => 'D18 - Pasir Ris / Tampines',
		'D19 - Hougang / Punggol / Sengkang' => 'D19 - Hougang / Punggol / Sengkang',
		'D20 - Ang Mo Kio / Bishan / Thomson' => 'D20 - Ang Mo Kio / Bishan / Thomson',
		'D21 - Clementi / Upper Bukit Timah' => 'D21 - Clementi / Upper Bukit Timah',
		'D22 - Boon Lay / Jurong / Tuas' => 'D22 - Boon Lay / Jurong / Tuas',
		'D23 - Bukit Batok / Bukit Panjang' => 'D23 - Bukit Batok / Bukit Panjang',
		'D24 - Choa Chu Kang / Tengah' => 'D24 - Choa Chu Kang / Tengah',
		'D25 - Admiralty / Woodlands' => 'D25 - Admiralty / Woodlands',
		'D26 - Mandai / Upper Thomson' => 'D26 - Mandai / Upper Thomson',
		'D27 - Sembawang / Yishun' => 'D27 - Sembawang / Yishun',
		'D28 - Seletar / Yio Chu Kang' => 'D28 - Seletar / Yio Chu Kang'
	);
?>
	<div id="listing_descriptions_wrap" class="listing_wrap list_desc">
		<p>
			<label for="listing_default_status" class="def_lbl">Status:</label>
			<select name="listing_default_status" id="listing_default_status" class="def_txt_box" >
				<?php foreach($status_arrays as $status_array):?>
					<?php if($listing_default_status[0] && $listing_default_status[0] == $status_array):?>
						<option value="<?php echo $status_array;?>" selected><?php echo $status_array;?></option>
					<?php else:?>
						<option value="<?php echo $status_array;?>"><?php echo $status_array;?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="listing_default_country" class="def_lbl">Country</label>
			<select name="listing_default_country" id="listing_default_country" class="def_txt_box">
				<?php foreach($listing_country_arrays as $key => $value):?>
					<?php if($listing_default_country[0] && $listing_default_country[0] == $value):?>
						<option value="<?php echo $value;?>" selected><?php echo $key;?></option>
					<?php else:?>
						<option value="<?php echo $value;?>"><?php echo $key;?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
		</p>
		<?php if($listing_default_country[0] == '' || $listing_default_country[0] == 'Singapore'):?>
		<p class="listing_local_wrap">
		<?php else:?>
		<p class="listing_local_wrap cur_hide">
		<?php endif;?>
			<label for="listing_default_type" class="def_lbl">Type:</label>
			<select name="listing_default_type" id="listing_default_type" class="def_txt_box">
				<option value="">Select Type</option>
				<?php foreach($type_arrays as $r_type):?>
				<?php if($listing_default_type[0] && html_entity_decode($listing_default_type[0]) == $r_type):?>
				<option value="<?php echo $r_type;?>" selected><?php echo $r_type;?></option>
				<?php else:?>
				<option value="<?php echo $r_type;?>"><?php echo $r_type;?></option>
				<?php endif;?>
				<?php endforeach;?>
			</select>
		</p>
		<?php if($listing_default_country[0] && $listing_default_country[0] != 'Singapore'):?>
		<p class="listing_oversea_wrap">
		<?php else:?>
		<p class="listing_oversea_wrap cur_hide">
		<?php endif;?>
			<label for="listing_oversea_type" class="def_lbl">Type:</label>
			<input type="text" name="listing_oversea_type" id="listing_oversea_type" class="def_txt_box" value="<?php echo $listing_oversea_type[0];?>" />
		</p>
		<?php if($listing_default_country[0] == '' || $listing_default_country[0] == 'Singapore'):?>
		<p class="listing_local_wrap">
		<?php else:?>
		<p class="listing_local_wrap cur_hide">
		<?php endif;?>
			<label for="listing_default_location" class="def_lbl">Location</label>
			<select name="listing_default_location" id="listing_default_location" class="def_txt_box">
				<option value="">Select District</option>
				<?php foreach($location_district_arrays as $key => $value):?>
					<?php if($listing_default_location[0] && $listing_default_location[0] == $value):?>
						<option value="<?php echo $value;?>" selected><?php echo $key;?></option>
					<?php else:?>
						<option value="<?php echo $value;?>"><?php echo $key;?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="listing_default_full_address" class="def_lbl">Address</label>
			<input type="text" name="listing_default_full_address" id="listing_default_full_address" class="def_txt_box" value="<?php if($listing_default_full_address[0]) echo $listing_default_full_address[0];?>" />
		</p>
		<p>
			<label for="listing_min_price" class="def_lbl">Min Price (psf)</label>
			<input type="text" name="listing_min_price" id="listing_min_price" class="def_txt_box add_num_format" value="<?php if($listing_min_price[0] != '') echo number_format($listing_min_price[0]);?>" />
		</p>
		<p>
			<label for="listing_max_price" class="def_lbl">Max Price (psf)</label>
			<input type="text" name="listing_max_price" id="listing_max_price" class="def_txt_box add_num_format" value="<?php if($listing_max_price[0] != '') echo number_format($listing_max_price[0]);?>" />
		</p>

		<p class="listing_floor_area_wrap">
			<label for="listing_min_floor_area" class="def_lbl">Min Floor Area (sqft)</label>
			<input type="text" name="listing_min_floor_area" id="listing_min_floor_area" class="def_txt_box add_num_format" value="<?php if($listing_min_floor_area[0]) echo number_format($listing_min_floor_area[0]); ?>" />
		</p>
		
		<p class="listing_floor_area_wrap">
			<label for="listing_max_floor_area" class="def_lbl">Max Floor Area (sqft)</label>
			<input type="text" name="listing_max_floor_area" id="listing_max_floor_area" class="def_txt_box add_num_format" value="<?php if($listing_max_floor_area[0]) echo number_format($listing_max_floor_area[0]); ?>" />
		</p>
		<p>
			<label for="listing_default_year" class="def_lbl">Year of Completion</label>
			<input type="text" name="listing_default_year" maxlength="34" id="listing_default_year" class="def_txt_box" value="<?php echo $listing_default_year[0];?>" />
			<span class="ex_txt_box">Eg. 1998</span>
		</p>
		<p>
			<label for="listing_mrt_station" class="def_lbl">Nearest MRT Station</label>
			<input type="text" name="listing_mrt_station" id="listing_mrt_station" class="def_txt_box" value="<?php echo $listing_mrt_station[0];?>" />
			<span class="ex_txt_box">Eg. Lavender,Bugis</span>
		</p>
		<p>
			<label for="listing_bld_height" class="def_lbl">Building Height</label>
			<input type="text" name="listing_bld_height" maxlength="34" id="listing_bld_height" class="def_txt_box" value="<?php echo $listing_bld_height[0];?>" />
			<span class="ex_txt_box">Eg. 25</span>
		</p>
	</div>
	<div id="def_images_wrap" class="project_wrap list_imgs">
		<?php 
		// adjust values here
		$id = "img1"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1” then $_POST[“img1”] will have all the image urls
		?>
		<?php if($agent_default_image1[0]):?>
			<?php for($i=1; $i<=20; $i++):?>
				<?php if(${'agent_default_image'.$i}[0]):?>
					<?php if($i==1):?>
						<?php $svalue = ${'agent_default_image'.$i}[0];?>
					<?php else:?>
						<?php $svalue .= ','.${'agent_default_image'.$i}[0];?>
					<?php endif;?>
				<?php endif;?>
			<?php endfor;?>
		<?php else:?>
			<?php $svalue = ""; // this will be initial value of the above form field. Image urls.?>
		<?php endif;?>
		<?php  
		$multiple = true; // allow multiple files upload
		 
		$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
		 
		$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
		?>
		 
		<label class="bulk_upload_cuimages_lbl">
			Upload Images<br/>
			(Maximum number is 20. Allowed file extensions are jpeg,jpg,gif,png)<br/>
			(Standard width and height - 620 x 500 px)
		</label> 
		<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />  
		<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">  
			<input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('From Computer'); ?>" class="button" />
			<input type="button" id="upload_btn_from_media" class="button" value="From Media Library" />
			<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
			<input type="hidden" id="plupload_post_id" value="<?php the_ID(); ?>"><br />
			<label>
				<input name="plupload_watermark_on" type="checkbox" id="plupload_watermark_on" value="1" />
				Apply watermark to current upload task
			</label>
			<br />
			<?php if ($width && $height): ?>
					<span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
					<span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
			<?php endif; ?>
			<div class="filelist"></div>
		</div>
		<ul class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?> <?php if($agent_default_image1[0]) : ?>ui-sortable<?php endif;?>" id="<?php echo $id; ?>plupload-thumbs">
		</ul> 
		<div class="clear"></div>
	</div>
	
	<!-- google map -->
	<div class="cu_gmap_wrap">	
		<h3>Google Map</h3>	
		<label for="default_address" class="def_lbl">Map:</label>	
		<input type="text" name="agent_default_address" id="default_address" class="def_txt_box" value="<?php echo $agent_default_address[0]; ?>" />	
		<input type="button" value="Search" class="mapsearch_btn" />	
		<input type="button" value="Clear" class="mapdef_btn" />	
		<span class="address_eg">Eg. Ann Siang Road, Singapore or 1.280943,103.845787 (Latitude,Longitude)</span>	
		<div id="map_cu_canvas" style="height:90%;top:30px"></div>	
		<input type="text" name="agent_default_latitude" id="agent_default_latitude" class="agent_default_latitude" value="<?php echo $agent_default_latitude[0]; ?>" />	
		<input type="text" name="agent_default_longitude" id="agent_default_longitude" class="agent_default_longitude" value="<?php echo $agent_default_longitude[0]; ?>" />
	</div>
<?php
}
function listing_default_box_save_meta( $post_id ) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if( !isset( $_POST['listing_box_meta_nonce'] ) || !wp_verify_nonce( $_POST['listing_box_meta_nonce'], 'listing_box_meta_action' ) ) return;
    if( !current_user_can( 'edit_post' ) ) return;

	// create an array of our custom fields	
    $agent_array = array(
        'listing_default_status',
		'listing_default_country',
        'listing_default_type',
		'listing_oversea_type',
		'listing_default_location',
		'listing_default_full_address',
        'listing_min_floor_area',
		'listing_max_floor_area',
		'listing_min_price',
		'listing_max_price',
		'listing_default_year',
		'listing_mrt_station',
		'listing_bld_height',
		'agent_default_address',
		'agent_default_latitude',
		'agent_default_longitude',
		'agent_default_image1'
    );

	for($i=2;$i<=20;$i++) {
		$agent_array[] = 'agent_default_image'.$i;
	}

    // create the "default" values for the array
    $agent_array_defaults = array(
        'listing_default_status' => 'None',
		'listing_default_country' => 'None',
        'listing_default_type' => 'None',
		'listing_oversea_type' => 'None',
		'listing_default_location' => 'None',
		'listing_default_full_address' => 'None',
        'listing_min_floor_area' => 'None',
		'listing_max_floor_area' => 'None',
		'listing_min_price' => 'None',
		'listing_max_price' => 'None',
		'listing_default_year' => 'None',
		'listing_mrt_station' => 'None',
		'listing_bld_height' => 'None',
		'agent_default_address' => 'None',
		'agent_default_latitude' => 'None',
		'agent_default_longitude' => 'None',
		'agent_default_image1' => 'None'
    );
    // parse 'em!
    $agent_array = wp_parse_args($agent_array, $agent_array_defaults);
    // HTML elements that are allowed inside the fields
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'em' => array(),
        'strong' => array()
    );
	
    // update the post meta fields with input fields (if they're set)
    foreach($agent_array as $item) {
        $old = get_post_meta($post_id, $item, true);
		if($item == 'listing_min_price' || $item == 'listing_max_price' || $item == 'listing_min_floor_area' || $item == 'listing_max_floor_area') {
			$_POST[$item] = str_replace(',','',$_POST[$item]);
		}
		$new = $_POST[$item];
		if ($new != '' && $new != $old) {
			update_post_meta( $post_id, $item, wp_kses($_POST[$item], $allowed_html) );
		}elseif ('' == $new && $old != '') {
			delete_post_meta($post_id, $item, $old);
		}
    }
}
add_action( 'save_post', 'listing_default_box_save_meta' );
?>
<?php
/* Custom Search */
function get_reviews_by_custom_search() {
global $wpdb;
/*$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;*/
$posts_per_page = get_option('posts_per_page');
$status = escape_check($_GET['status']);
$listing_country = escape_check($_GET['listing_country']);
$type = escape_check($_GET['listing_type']);
$location = escape_check($_GET['location']);
$l_floor_area = escape_check($_GET['l_floor_area']);
$l_price = escape_check($_GET['l_price']);

//Fix homepage pagination
if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

	if ($status != '') {
		$status_array = array('key' => 'listing_default_status','value' => $status);
	}
	if ($listing_country != '') {
		$listing_country_array = array('key' => 'listing_default_country','value' => $listing_country);
	}
	if ($type != '') {
		$type_array = array('key' => 'listing_default_type','value' => htmlentities($type));
	}
	if ($location != '') {
		$location_array = array('key' => 'listing_default_location','value' => $location);
	}
	if($l_price != '') {
		$min_price_array = array('key' => 'listing_min_price','value' => $l_price,'compare' => '<=','type' => 'numeric');
		$max_price_array = array('key' => 'listing_max_price','value' => $l_price,'compare' => '>=','type' => 'numeric');
	}
	if ($l_floor_area != '') {
		$min_size_array = array('key' => 'listing_min_floor_area','value' => $l_floor_area,'compare' => '<=','type' => 'numeric');
		$max_size_array = array('key' => 'listing_max_floor_area','value' => $l_floor_area,'compare' => '>=','type' => 'numeric');
	}
	
	$search_types = array('listing');
	
	$args = array(
		'post_type' => $search_types,
		'meta_query' => array(
			/*'relation' => 'AND',*/
			$listing_country_array,
			$type_array,
			$location_array,
			$max_price_array,
			$min_price_array,
			$min_size_array,
			$max_size_array
		),
		'paged' => $paged,
	);
	
	$searched_posts = new WP_Query( $args );

	return $searched_posts;
}

/* for map search */
function get_all_reviews_by_map_search() {
global $wpdb;
/*$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;*/
$posts_per_page = get_option('posts_per_page');
$status = escape_check($_GET['status']);
$listing_country = escape_check($_GET['listing_country']);
$type = escape_check($_GET['listing_type']);
$location = escape_check($_GET['location']);
$l_floor_area = escape_check($_GET['l_floor_area']);
$l_price = escape_check($_GET['l_price']);

	if ($status != '') {
		$status_array = array('key' => 'listing_default_status','value' => $status);
	}
	if ($listing_country != '') {
		$listing_country_array = array('key' => 'listing_default_country','value' => $listing_country);
	}
	if ($type != '') {
		$type_array = array('key' => 'listing_default_type','value' => htmlentities($type));
	}
	if ($location != '') {
		$location_array = array('key' => 'listing_default_location','value' => $location);
	}
	if($l_price != '') {
		$min_price_array = array('key' => 'listing_min_price','value' => $l_price,'compare' => '<=','type' => 'numeric');
		$max_price_array = array('key' => 'listing_max_price','value' => $l_price,'compare' => '>=','type' => 'numeric');
	}
	if ($l_floor_area != '') {
		$min_size_array = array('key' => 'listing_min_floor_area','value' => $l_floor_area,'compare' => '<=','type' => 'numeric');
		$max_size_array = array('key' => 'listing_max_floor_area','value' => $l_floor_area,'compare' => '>=','type' => 'numeric');
	}
	
	$search_types = array('listing');
	
	$args = array(
		'post_type' => $search_types,
		'meta_query' => array(
			/*'relation' => 'AND',*/
			$listing_country_array,
			$type_array,
			$location_array,
			$max_price_array,
			$min_price_array,
			$min_size_array,
			$max_size_array
		),
		'paged' => $paged,
	);
	
	$searched_posts = new WP_Query( $args );

	return $searched_posts;
}

function escape_check($des) {
	$clear = stripslashes($des);
	$clear = mysql_real_escape_string($clear);
	return $clear;
}

add_action( 'post_submitbox_misc_actions', 'listing_caption_or_not' );
add_action( 'save_post', 'save_listing_caption_or_not' );
function listing_caption_or_not() {
    global $post;
    if (get_post_type($post) == 'listing') {
		wp_nonce_field( plugin_basename(__FILE__), 'featured_listing_nonce' );
        wp_nonce_field( plugin_basename(__FILE__), 'caption_or_not_nonce' );
		wp_nonce_field( plugin_basename(__FILE__), 'caption_val_nonce' );
        $val = get_post_meta( $post->ID, 'caption_or_not', true ) ? get_post_meta( $post->ID, 'caption_or_not', true ) : 'no';
		$val2 = get_post_meta( $post->ID, 'caption_val', true );
		if((isset($_POST['featured_listing']) && $_POST['featured_listing'] == 'yes') || get_post_meta( $post->ID, 'featured_listing', true ) == 'yes') {
			$featured_option = '<option value="yes" selected="selected">Yes</option><option value="no">No</option>';
		}else {
			$featured_option = '<option value="yes">Yes</option><option value="no" selected="selected">No</option>';
		}
		
		echo '<div class="misc-pub-section custom_pub_field_wrap featured_control_area">';
		echo '<label for="featured_listing">Featured Listing</label>';
		echo '<select name="featured_listing" id="featured_listing" class="featured_listing">';
		echo $featured_option;
		echo '</select>';
		
		echo '</div>';
		
		echo '<div class="misc-pub-section custom_pub_field_wrap">';
		echo '<label for="caption_or_not">Show Caption</label>';
		echo '<select name="caption_or_not" id="caption_or_not" class="def_dorpdown_box" >';
		echo '<option value="no" '.selected($val,'no',false).'>No (Standard)</option>';
		echo '<option value="yes" '.selected($val,'yes',false).'>Yes (Highlighted)</option>';
		echo '</select>';
		echo '</div>';
		if($val == 'no') $hide = 'hide';
		echo '<div id="caption_val" class="misc-pub-section custom_pub_field_wrap misc-pub-section-last '.$hide.'">';
		echo '<label for="caption_val">Caption Text</label>';
		echo '<input type="text" name="caption_val" value="'.$val2.'" maxlength="20" />';
		echo '<span>(Limited 20 Characters)</span>';
        echo '</div>';
    }
	
}
function save_listing_caption_or_not($post_id) {
 
    if (!isset($_POST['post_type']) )
        return $post_id;
		
	if ( !wp_verify_nonce( $_POST['featured_listing_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;
 
    if ( !wp_verify_nonce( $_POST['caption_or_not_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;
		
	if ( !wp_verify_nonce( $_POST['caption_val_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;
 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
 
    if ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
		
		
	if (!isset($_POST['featured_listing']))
        return $post_id;
    else {
        update_post_meta( $post_id, 'featured_listing', $_POST['featured_listing'], get_post_meta( $post_id, 'featured_listing', true ) );
    }
		
    if (!isset($_POST['caption_or_not']))
        return $post_id;
    else {
        $mydata = $_POST['caption_or_not'];
        update_post_meta( $post_id, 'caption_or_not', $_POST['caption_or_not'], get_post_meta( $post_id, 'caption_or_not', true ) );
    }
	
	if (!isset($_POST['caption_val']))
        return $post_id;
    else {
        update_post_meta( $post_id, 'caption_val', $_POST['caption_val'], get_post_meta( $post_id, 'caption_val', true ) );
    }
 
}


/* add pdf meta box into listing page */
function listing_pdf_box_add_meta() {
	add_meta_box( 'pdf-box', 'Listing Attachments', 'listing_pdf_box_meta', 'listing', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'listing_pdf_box_add_meta' );


/* callback function to create input fields in meta box */
function listing_pdf_box_meta() {
    global $post;
    // get the custom field values (if they exist) as an array
    $values = get_post_custom( $post->ID );
    // extract the members of the $values array to their own variables (which you can see below, in the HTML code)
    extract( $values, EXTR_SKIP );
    wp_nonce_field( 'listing_pdf_box_meta_action', 'listing_pdf_box_meta_nonce' );
?>
<div>
	<ul class="single_tab_wrap">
		<li><a href="#" id="video_tab" class="single_tab single_blue_tab single_tab_active">Videos</a></li>
		<li><a href="#" id="virtualtour_tab" class="single_tab single_blue_tab">Virtual Tours</a></li>
		<li><a href="#" id="document_tab" class="single_tab single_blue_tab">Documents</a></li>
		<li><a href="#" id="pdf_tab" class="single_tab single_blue_tab">Floor Plans</a></li>
		<li><a href="#" id="location_tab" class="single_tab">Location Image</a></li>
		<li><a href="#" id="other_tab" class="single_tab">Contact Us</a></li>
	</ul>
	
	<!----------- video div ----------->
	<div id="video_tab_box" class="tab_box_wrap show_tab_box">
		<?php for($i=1;$i<=6;$i++):?>
		<div class="video_item">
			<?php
				$video_formats = array('mov','avi','wmv','flv','mp4','mov','m4v','webmv','ogv', 'webm');
				$vfilename = explode('/',${"single_video".$i}[0]);
				$vextension = explode('.',end($vfilename));
			?>
			<div class="radio_vtype_wrap">
				<?php if(in_array(end($vextension), $video_formats) || ${'single_video'.$i}[0] == ''):?>
				<input type="radio" name="video<?php echo $i;?>" value="0" class="radio_vtype" checked="checked"><span>Upload Video</span>
				<input type="radio" name="video<?php echo $i;?>" value="1" class="radio_vtype"><span>iframe</span>
				<?php else:?>
				<input type="radio" name="video<?php echo $i;?>" value="0" class="radio_vtype"><span>Upload Video</span>
				<input type="radio" name="video<?php echo $i;?>" value="1" class="radio_vtype" checked="checked"><span>iframe</span>
				<?php endif;?>
			</div>
			<div class="vthumb_area">
				<?php if(${"video_thumb".$i}[0] == ''):?>
				<img src="<?php echo get_template_directory_uri();?>/images/def_video_img.png" width="70" height="70" class="video_thumb" alt="" />
				<?php else:?>
				<img src="<?php echo ${"video_thumb".$i}[0]; ?>" width="70" height="70" class="video_thumb" alt="" />
				<?php endif;?>
				<input type="hidden" name="video_thumb<?php echo $i;?>" id="video_thumb<?php echo $i;?>" value="<?php echo ${"video_thumb".$i}[0]; ?>" />
				<a href="#" class="upload_vthumb_btn">upload thumbnail</a>
				<a href="#" class="remove_vthumb_btn">remove thumbnail</a>
			</div>
			<?php if(in_array(end($vextension), $video_formats) || ${"single_video".$i}[0] == ''):?>
			<input type="text" class="vfile_name" value="<?php echo end($vfilename);?>" />
			<textarea name="single_video<?php echo $i;?>" id="single_video<?php echo $i;?>" class="video_attachment hide_v_data"><?php echo ${"single_video".$i}[0]; ?></textarea>
			<input type="button" class="upload_video_btn" value="upload video" />
			<?php else:?>
			<input type="text" class="vfile_name hide_v_data" value="" />
			<textarea name="single_video<?php echo $i;?>" id="single_video<?php echo $i;?>" class="video_attachment"><?php echo ${"single_video".$i}[0]; ?></textarea>
			<input type="button" class="upload_video_btn hide_v_data" value="upload video" />
			<?php endif;?>
			<span class="remove_video_btn">remove</span>
		</div>
		<?php endfor;?>
	</div>
	<!----------- end video div ----------->
	
	<!----------- virtual tour div ----------->
	<div id="virtualtour_tab_box" class="tab_box_wrap">
		<?php for($i=7;$i<=12;$i++):?>
		<div class="video_item">
			<?php
				$video_formats = array('mov','avi','wmv','flv','mp4','mov','m4v','webmv','ogv', 'webm');
				$vfilename = explode('/',${"single_video".$i}[0]);
				$vextension = explode('.',end($vfilename));
			?>
			<div class="radio_vtype_wrap">
				<?php if(in_array(end($vextension), $video_formats) || ${'single_video'.$i}[0] == ''):?>
				<input type="radio" name="video<?php echo $i;?>" value="0" class="radio_vtype" checked="checked"><span>Upload Video</span>
				<input type="radio" name="video<?php echo $i;?>" value="1" class="radio_vtype"><span>iframe</span>
				<?php else:?>
				<input type="radio" name="video<?php echo $i;?>" value="0" class="radio_vtype"><span>Upload Video</span>
				<input type="radio" name="video<?php echo $i;?>" value="1" class="radio_vtype" checked="checked"><span>iframe</span>
				<?php endif;?>
			</div>
			<div class="vthumb_area">
				<?php if(${"video_thumb".$i}[0] == ''):?>
				<img src="<?php echo get_template_directory_uri();?>/images/def_video_img.png" width="70" height="70" class="video_thumb" alt="" />
				<?php else:?>
				<img src="<?php echo ${"video_thumb".$i}[0]; ?>" width="70" height="70" class="video_thumb" alt="" />
				<?php endif;?>
				<input type="hidden" name="video_thumb<?php echo $i;?>" id="video_thumb<?php echo $i;?>" value="<?php echo ${"video_thumb".$i}[0]; ?>" />
				<a href="#" class="upload_vthumb_btn">upload thumbnail</a>
				<a href="#" class="remove_vthumb_btn">remove thumbnail</a>
			</div>
			<?php if(in_array(end($vextension), $video_formats) || ${"single_video".$i}[0] == ''):?>
			<input type="text" class="vfile_name" value="<?php echo end($vfilename);?>" />
			<textarea name="single_video<?php echo $i;?>" id="single_video<?php echo $i;?>" class="video_attachment hide_v_data"><?php echo ${"single_video".$i}[0]; ?></textarea>
			<input type="button" class="upload_video_btn" value="upload video" />
			<?php else:?>
			<input type="text" class="vfile_name hide_v_data" value="" />
			<textarea name="single_video<?php echo $i;?>" id="single_video<?php echo $i;?>" class="video_attachment"><?php echo ${"single_video".$i}[0]; ?></textarea>
			<input type="button" class="upload_video_btn hide_v_data" value="upload video" />
			<?php endif;?>
			<span class="remove_video_btn">remove</span>
		</div>
		<?php endfor;?>
	</div>
	<!----------- end virtual tour div ----------->
	
	
	<!----------- Documents div ----------->
	<div id="document_tab_box" class="tab_box_wrap">
		<p>
			<i class="note_msg">Note: Only PDF, DOC, XLS, PPT, TXT documents are allowed</i>
			<?php if($listing_document1[0] && $listing_document1[0] != ''):?>
			<?php $brochure_name = explode('/',$listing_document1[0]);?>
			<input class="doc_box_lbl" value="<?php echo end($brochure_name);?>">
			<?php else:?>
			<input class="doc_box_lbl empty_doc_box_lbl" value="Upload Document">
			<?php endif;?>
			<input type="hidden" name="listing_document1" id="listing_document1" class="def_txt_box doc_url_box" value="<?php echo $listing_document1[0]; ?>" />
			<input type="button" id="doc_attachment_btn1" class="upload_doc_btn" value="Upload Document" />
			<span class="remove_doc_file">Remove Document</span>
		</p>
	</div>
	<!----------- end Documents div ----------->
	
	<!----------- floor plan div ----------->
	<div id="pdf_tab_box" class="tab_box_wrap">
		<?php for($i=1;$i<=5;$i++):?>
		<p>
			<?php if(${"single_floorplan".$i}[0] == ''):?>
			<img src="<?php echo get_template_directory_uri();?>/images/floorplan_def_img.png" alt="" class="floorplan_img" width="60" height="60" />
			<?php else:?>
			<img src="<?php echo ${"single_floorplan".$i}[0];?>" alt="" class="floorplan_img" width="60" height="60" />
			<?php endif;?>
			<input type="text" name="single_floorplan<?php echo $i;?>" id="single_floorplan<?php echo $i;?>" class="floorplan_url_box" value="<?php echo ${"single_floorplan".$i}[0];?>" />
			<input type="button" class="upload_floorplan_btn" value="Upload Floor Plan Image" />
			<span class="remove_floorplan">X</span>
		</p>
		<?php endfor;?>
		<p>
			<?php if($pdf_attachment1[0] && $pdf_attachment1[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment1[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment1" id="pdf_attachment1" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment1[0]; ?>" />
			<input type="button" id="pdf_attachment_btn1" class="upload_pdf_btn" value="Upload PDF-1" />
			<span class="remove_pdf_file">Remove PDF-1</span>
		</p>
		<p>
			<?php if($pdf_attachment2[0] && $pdf_attachment2[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment2[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment2" id="pdf_attachment2" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment2[0]; ?>" />
			<input type="button" id="pdf_attachment_btn2" class="upload_pdf_btn" value="Upload PDF-2" />
			<span class="remove_pdf_file">Remove PDF-2</span>
		</p>
		<p>
			<?php if($pdf_attachment3[0] && $pdf_attachment3[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment3[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment3" id="pdf_attachment3" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment3[0]; ?>" />
			<input type="button" id="pdf_attachment_btn3" class="upload_pdf_btn" value="Upload PDF-3" />
			<span class="remove_pdf_file">Remove PDF-3</span>
		</p>
		<p>
			<?php if($pdf_attachment4[0] && $pdf_attachment4[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment4[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment4" id="pdf_attachment4" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment4[0]; ?>" />
			<input type="button" id="pdf_attachment_btn4" class="upload_pdf_btn" value="Upload PDF-4" />
			<span class="remove_pdf_file">Remove PDF-4</span>
		</p>
		<p>
			<?php if($pdf_attachment5[0] && $pdf_attachment5[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment5[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment5" id="pdf_attachment5" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment5[0]; ?>" />
			<input type="button" id="pdf_attachment_btn5" class="upload_pdf_btn" value="Upload PDF-5" />
			<span class="remove_pdf_file">Remove PDF-5</span>
		</p>
		<p>
			<?php if($pdf_attachment6[0] && $pdf_attachment6[0] != ''):?>
			<?php $pdfname = explode('/',$pdf_attachment6[0]);?>
			<input class="pdf_box_lbl" value="<?php echo end($pdfname);?>">
			<?php else:?>
			<input class="pdf_box_lbl empty_pdf_box_lbl" value="Upload PDF Attachment File">
			<?php endif;?>
			<input type="hidden" name="pdf_attachment6" id="pdf_attachment6" class="def_txt_box pdf_url_box" value="<?php echo $pdf_attachment6[0]; ?>" />
			<input type="button" id="pdf_attachment_btn6" class="upload_pdf_btn" value="Upload PDF-6" />
			<span class="remove_pdf_file">Remove PDF-6</span>
		</p>
	</div>
	<!----------- end floor plan div ----------->	
	
	<!----------- location image div ----------->
	<div id="location_tab_box" class="tab_box_wrap">
		<?php for($i=1;$i<=5;$i++):?>
		<p>
			<?php if(${"single_map_img".$i}[0] == ''):?>
			<img src="<?php echo get_template_directory_uri();?>/images/location_def_img.png" alt="" class="map_img_img" width="60" height="60" />
			<?php else:?>
			<img src="<?php echo ${"single_map_img".$i}[0];?>" alt="" class="map_img_img" width="60" height="60" />
			<?php endif;?>
			<input type="text" name="single_map_img<?php echo $i;?>" id="single_map_img<?php echo $i;?>" class="map_img_url_box" value="<?php echo ${"single_map_img".$i}[0];?>" />
			<input type="button" class="upload_map_img_btn" value="Upload Location Image" />
			<span class="remove_map_img">X</span>
		</p>
		<?php endfor;?>
	</div>
	<!----------- end location image div ----------->
	
	
	<!----------- Other description div ----------->
	<div id="other_tab_box" class="tab_box_wrap">
		<textarea name="proj_other_des" id="proj_other_des" class="proj_other_des"><?php if(!$proj_other_des[0] && $proj_other_des[0] == ''):?>Feel free to contact us if you have any enquiries. We appreciate your feedback and suggestions. Please leave us your contact below for latest project updates.<?php else:?><?php echo $proj_other_des[0];?><?php endif;?></textarea>
	</div>
	<!----------- end Other description div ----------->
	
</div>
<?php
}
function listing_pdf_box_save_meta( $post_id ) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if( !isset( $_POST['listing_pdf_box_meta_nonce'] ) || !wp_verify_nonce( $_POST['listing_pdf_box_meta_nonce'], 'listing_pdf_box_meta_action' ) ) return;
    if( !current_user_can( 'edit_post' ) ) return;

	// create an array of our custom fields	
    $agent_array = array(
		/*'proj_shortdes',*/
		'single_floorplan1',
		'single_floorplan2',
		'single_floorplan3',
		'single_floorplan4',
		'single_floorplan5',
		'pdf_attachment1',
		'pdf_attachment2',
		'pdf_attachment3',
		'pdf_attachment4',
		'pdf_attachment5',
		'pdf_attachment6',
		'listing_document1',
		'single_video1',
		'single_video2',
		'single_video3',
		'single_video4',
		'single_video5',
		'single_video6',
		'single_video7',
		'single_video8',
		'single_video9',
		'single_video10',
		'single_video11',
		'single_video12',
		'video_thumb1',
		'video_thumb2',
		'video_thumb3',
		'video_thumb4',
		'video_thumb5',
		'video_thumb6',
		'video_thumb7',
		'video_thumb8',
		'video_thumb9',
		'video_thumb10',
		'video_thumb11',
		'video_thumb12',
		'single_map_img1',
		'single_map_img2',
		'single_map_img3',
		'single_map_img4',
		'single_map_img5',
		'proj_other_des'
    );

    // create the "default" values for the array
    $agent_array_defaults = array(
		/*'proj_shortdes' => 'None',*/
		'single_floorplan1' => 'None',
		'single_floorplan2' => 'None',
		'single_floorplan3' => 'None',
		'single_floorplan4' => 'None',
		'single_floorplan5' => 'None',
		'pdf_attachment1' => 'None',
		'pdf_attachment2' => 'None',
		'pdf_attachment3' => 'None',
		'pdf_attachment4' => 'None',
		'pdf_attachment5' => 'None',
		'pdf_attachment6' => 'None',
		'listing_document1' => 'None',
		'single_video1' => 'None',
		'single_video2' => 'None',
		'single_video3' => 'None',
		'single_video4' => 'None',
		'single_video5' => 'None',
		'single_video6' => 'None',
		'single_video7' => 'None',
		'single_video8' => 'None',
		'single_video9' => 'None',
		'single_video10' => 'None',
		'single_video11' => 'None',
		'single_video12' => 'None',
		'video_thumb1' => 'None',
		'video_thumb2' => 'None',
		'video_thumb3' => 'None',
		'video_thumb4' => 'None',
		'video_thumb5' => 'None',
		'video_thumb6' => 'None',
		'video_thumb7' => 'None',
		'video_thumb8' => 'None',
		'video_thumb9' => 'None',
		'video_thumb10' => 'None',
		'video_thumb11' => 'None',
		'video_thumb12' => 'None',
		'single_map_img1' => 'None',
		'single_map_img2' => 'None',
		'single_map_img3' => 'None',
		'single_map_img4' => 'None',
		'single_map_img5' => 'None',
		'proj_other_des' => 'None'
    );
    // parse 'em!
    $agent_array = wp_parse_args($agent_array, $agent_array_defaults);
    // HTML elements that are allowed inside the fields
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'em' => array(),
        'strong' => array(),
		'iframe' => array(
			'src' => array(),
			'width' => array(),
			'height' => array(),
			'frameborder' => array()
		)
    );

    // update the post meta fields with input fields (if they're set)
    foreach($agent_array as $item) {
		$old = get_post_meta($post_id, $item, true);
		$new = $_POST[$item];
        if( isset( $_POST[$item] ) ) {
			/*update_post_meta( $post_id, $item, wp_kses($_POST[$item], $allowed_html) );*/
		}
		if ($new && $new != $old) {
			update_post_meta( $post_id, $item, wp_kses($_POST[$item], $allowed_html) );
		}elseif ('' == $new && $old) {
			delete_post_meta($post_id, $item, $old);
		}
    }
}
add_action( 'save_post', 'listing_pdf_box_save_meta' );


