<?php

if ( ! class_exists( 'CTC_Shortcodes' ) ) {
	class CTC_Shortcodes {
		
		public function __construct() {
			// Church Theme Content is REQUIRED
			if ( ! class_exists( 'Church_Theme_Content' ) ) return;
			add_shortcode( 'ctc_people', array( &$this, 'people_shortcode' ) ); 				// People list
			add_shortcode( 'ctc_events', array( &$this, 'events_shortcode' ) ); 				// Events list
			add_shortcode( 'ctc_sermons', array( &$this, 'sermons_shortcode' ) );				// Sermon list
			add_shortcode( 'ctc_locations', array( &$this, 'locations_shortcode' ) );		// Locations list
			add_shortcode( 'ctc_sermonsby', array( &$this, 'sermonsby_shortcode' ) );		// Sermon list by a parameter (speaker, book, tag, series, etc.)
			
			add_shortcode( 'ctc_group', array( &$this, 'group_shortcode' ) ); 					// Group of people
			/*
			add_shortcode( 'ctc_groups', array( &$this, 'groups_shortcode' ) ); 				// Groups List
      
      add_shortcode( 'ctc_sermon', array( &$this, 'sermon_shortcode' ) );					// Single Sermon 
			add_shortcode( 'ctc_event', array( &$this, 'event_shortcode' ) );						// Single Event
			add_shortcode( 'ctc_person', array( &$this, 'person_shortcode' ) );					// Single Person
			add_shortcode( 'ctc_location', array( &$this, 'location_shortcode' ) );			// Single Location
			*/
		}
		
    public function people_shortcode( $attr ) { return $this->cpt_shortcode( 'people', $attr ); }
		public function events_shortcode( $attr ) { return $this->cpt_shortcode( 'events', $attr ); }
		public function sermons_shortcode( $attr ) { return $this->cpt_shortcode( 'sermons', $attr ); }
		public function locations_shortcode( $attr ) { return $this->cpt_shortcode( 'locations', $attr ); }
    
		public function sermonsby_shortcode( $attr ) { return $this->tax_shortcode( 'sermonsby', $attr ); }
    
		// This handles whole listings at the CPT level
		public function cpt_shortcode( $type, $attr) {
			// $type hierarchy
			// 'people' => 'groups' => 'group' => 'person'
			// 'events' => 'event'
			// 'locations' => 'location'
			// 'sermons' => 'sermonsby' => 'sermon'
			// 'groups' and 'sermonsby'
			
			switch ( $type ) {
				case 'groups':
				case 'sermonsby':
				case 'group':
					return tax_shortcode( $type, $attr );
					break;
				case 'person':
				case 'event':
				case 'sermon':
				case 'location':
					return single_shortcode( $type, $attr );
					break;
				case 'locations':
					$cpt = 'ctc_location';
					break;
				case 'sermons':
					$cpt = 'ctc_sermon';
					break;
				case 'events':
					$cpt = 'ctc_event';
					break;
				case 'people':
					$cpt = 'ctc_person';
					break;
				default:
					return '';
			} //switch
			
			$template_location = 'ctc-shortcodes/';
			$template = locate_template( $template_location . $type );
			
			// These are basic parameters
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'count' 			=>	-1,					// Number of items to display. -1 == all
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
			), $attr ) );

			$parent_url = get_permalink();
      $use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
				'posts_per_page' 	=> $count, 
				'paged' 					=> $paged,
				'order' 					=> 'ASC'
			) ; 
			// get query
			$posts = new WP_Query();
			$posts -> query( $args ); 
			
			ob_start();  // we'll buffer the results to use templates
			if( '' != $template ){
				// template contains the loop
				// the templates would be stored from within a theme
				require( $template );
			} else {
				// this is a generic loop with most of the meta data in the various cpt
				if( $posts->have_posts() ){
					while( $posts->have_posts() ){
						$posts -> the_post(); 
						$post_id 	= get_the_ID();
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
						
						// First need to extract all relevant metadata from the CPT
						// Note that this is basically trying to extract ALL available data from
						// ALL cpt. In the templates one would use only the one relevant to that CPT.
						
						// Person data
						$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
						$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
						$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
						
						// Sermon data
						$ser_video = get_post_meta( $post_id, '_ctc_sermon_video' , true ); 
						$ser_audio = get_post_meta( $post_id, '_ctc_sermon_audio' , true ); 
						$ser_pdf = get_post_meta( $post_id, '_ctc_sermon_pdf' , true ); 
						$series = get_the_terms( $post_id, '_ctc_sermon_series');
						if( $series && ! is_wp_error( $series) ) {
							$series = array_shift( array_values ( $series ) );
							$ser_series = $series -> name;
						} else {
							$ser_series = '';
						}
						$books = get_the_terms( $post_id, '_ctc_sermon_book');
						if( $books && ! is_wp_error( $books ) ) {
							$books_A = array();
							foreach ( $books as $book ) { $books_A = $book -> name; }
							$ser_books = join( ', ', $books_A );
						} else {
							$ser_books = '';
						}
						$speakers = get_the_terms( $post_id, '_ctc_sermon_book');
						if( $speakers && ! is_wp_error( $speakers ) ) {
							$speakers_A = array();
							foreach ( $speakers as $speaker ) { $speakers_A = $speaker -> name; }
							$ser_speakers = join( ', ', $speakers_A );
						} else {
							$ser_speakers = '';
						}
						$tags = get_the_terms( $post_id, '_ctc_sermon_tag');
						if( $tags && ! is_wp_error( $tags ) ) {
							$tags_A = array();
							foreach ( $tags as $tag ) { $tags_A = $tag -> name; }
							$ser_tags = join( ', ', $tags_A );
						} else {
							$ser_tags = '';
						}
						
						// Event data
						$evt_start = get_post_meta( $post_id, '_ctc_event_start_date' , true ); 
						$evt_end = get_post_meta( $post_id, '_ctc_event_end_date' , true ); 
						$evt_time = get_post_meta( $post_id, '_ctc_event_time' , true ); 
						$evt_recurrence = get_post_meta( $post_id, '_ctc_event_recurrence' , true ); 
						$evt_recur_period = get_post_meta( $post_id, '_ctc_event_recurrence_period' , true ); 
						$evt_recur_end = get_post_meta( $post_id, '_ctc_event_recurrence_end_date' , true ); 
						$evt_venue = get_post_meta( $post_id, '_ctc_event_venue' , true ); 
						$evt_addr = get_post_meta( $post_id, '_ctc_event_address' , true ); 
						$evt_show_loc = get_post_meta( $post_id, '_ctc_event_show_directions_link' , true ); 
						
						// Location data
						$loc_addr = get_post_meta( $post_id, '_ctc_location_address' , true ); 
						$loc_show_loc = get_post_meta( $post_id, '_ctc_location_show_directions_link' , true ); 
						$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
						$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
						
						// Below is a general template. 
?>
						<div class="ctc-block ctc-<?php echo $type;?>">
<?php // Image ?>
							<?php if( $thumbnail ): ?><img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/><?php endif; ?>
<?php // Title ?>							
							<?php if( $use_permalink ): ?>
							<div class="ctc-title"><?php echo get_the_title(); ?></div>
							<?php else: ?>
							<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
							<?php endif; ?>
<?php // Sermons ?>
							<?php if( $ser_speakers ): ?><div class="ctc-speaker"><i class="fa-user icon-user"></i><?php echo $ser_speakers; ?></div><?php endif; ?>
							<?php if( $ser_books ): ?><div class="ctc-books"><i class="fa-book icon-book"></i><?php echo $ser_books; ?></div><?php endif; ?>
							<?php if( $ser_series ): ?><div class="ctc-series"><i class="fa-th-large icon-th-large"></i><?php echo $ser_series; ?></div><?php endif; ?>
							<?php if( $ser_tags ): ?><div class="ctc-tags"><i class="fa-th-large icon-th-large"></i><?php echo $ser_tags; ?></div><?php endif; ?>
							<?php if( $ser_video ): ?><div class="ctc-video"><?php echo wp_video_shortcode( array( 'src' => $ser_video ) ); ?></div><?php endif; ?>
							<?php if( $ser_audio ): ?><div class="ctc-audio"> <?php echo wp_audio_shortcode( array( 'src' => $ser_audio ) ); ?></div><?php endif; ?>
<?php // Person ?>							
							<?php if( $per_position ): ?><div class="ctc-position"><i><?php echo $per_position; ?></i></div><?php endif; ?>
							<?php if( $per_phone ): ?><div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $per_phone; ?></div><?php endif; ?>
							<?php if( $per_email ): ?><div class="ctc-email"><i class="fa-envelope icon-envelope"></i><?php echo $per_email; ?></div><?php endif; ?>
<?php // Location ?>							
							<?php if( $loc_address ): ?><div class="ctc-location"><i class="fa-map-marker icon-map-marker"></i><?php echo $loc_address; ?></div><?php endif; ?>
							<?php if( $loc_show_loc && $loc_address): ?>
							<div id="ctc-map">
								<a href="http://maps.google.com/maps?q=<?php echo urlencode($loc_address); ?>" target="_blank">
									<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($loc_address); ?>&zoom=15&size=300x200&sensor=false&scale=2&markers=color:red|<?php echo urlencode($loc_address); ?>" />
								</a>
							</div>
							<?php endif; ?>
							<?php if( $loc_phone ): ?><div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $loc_phone; ?></div><?php endif; ?>
							<?php if( $loc_times ): ?><div class="ctc-times"><i class="fa-clock icon-clock"></i><?php echo $loc_times; ?></div><?php endif; ?>
<?php // Event ?>
							<?php if( $evt_start ): ?><div class="ctc-date"><i class="fa-calendar icon-calendar"></i><?php echo $evt_start; if ( $evt_end ) echo '-'. $evt_end; ?></div><?php endif; ?>
							<?php if( $evt_time ): ?><div class="ctc-time"><i class="fa-clock icon-clock"></i><?php echo $evt_time; ?></div><?php endif; ?>
							<?php if ( $evt_recurrence && $evt_recurrence != 'none' ) : ?>
							<div class="cpt-recurrence"><i>
<?php 
							switch ( $evt_recurrence ) {
								case 'daily' : 
									printf(_n('Recurs Daily','Recurs Every %d Days',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
								break;
							case 'weekly' :
									printf(_n('Recurs Weekly','Recurs Every %d Weeks',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
								break;
							case 'monthly' :
								printf(_n('Recurs Monthly','Recurs Every %d Months',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
								break;
							case 'yearly' :
								printf(_n('Recurs Yearly','Recurs Every %d Years',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
								break;
							} // switch
?>
							</i></div>
							<?php	endif;  ?>
							<?php if( $evt_venue ): ?><div class="ctc-venue"><i class="fa-building icon-building"></i><?php echo $evt_venue; ?></div><?php endif; ?>
							<?php if( $evt_address ): ?><div class="ctc-location"><i class="fa-map-marker icon-map-marker"></i><?php echo $evt_address; ?></div><?php endif; ?>
							<?php if( $evt_show_loc && $evt_address): ?>
							<div id="ctc-map">
								<a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank">
									<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($evt_address); ?>&zoom=15&size=300x200&sensor=false&scale=2&markers=color:red|<?php echo urlencode($evt_address); ?>" />
								</a>
							</div>
							<?php endif; ?>
						</div>
<?php
					} // while
				} // if
				$has_next = $posts->max_num_pages > $paged;
				$has_prev = $paged > 1;
?>
						<div class="ctc-nav">
							<a class="ctc-prev" <?php echo ($has_prev ? 'href="' . add_query_arg( 'paged', $paged-1, $parent_url ): '' ) ; ?>"><?php __( 'Previous', 'ctc-shortcodes' ); ?></a>
							<a class="ctc-next " <?php echo ($has_next ? 'href="'. add_query_arg( 'paged', $paged+1, $parent_url ): '' ) ; ?>"><?php __( 'Next', 'ctc-shortcodes' ); ?></a>
						</div>
<?php				
			} //else
			
			wp_reset_postdata();
			$output = ob_get_clean();
			
			return $before . $output . $after;
			
		}
		
		function tax_shortcode ( $type, $attr ) {
		// $type hierarchy
			// 'people' => 'groups' => 'group' => 'person'
			// 'events' => 'event'
			// 'locations' => 'location'
			// 'sermons' => 'sermonsby' => 'sermon'
			// 'groups' and 'sermonsby'
			switch ( $type ) {
				case 'groups':
          $cpt = 'ctc_person';
          $tax = '_ctc_person_group';
          break;
				case 'group':
          $cpt = 'ctc_person';
          $tax = '_ctc_person_group';
					return tax_shortcode( $type, $attr );
					break;
				case 'sermonsby':
          $cpt = 'ctc_sermon';
          break;
				default:
					return '';
			}
			
			$template_location = 'ctc-shortcodes/';
			$template = locate_template( $template_location . $type );
			
			// These are basic parameters
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'count' 			=>	-1,					// Number of items to display. -1 == all
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
        'name'        => '',          // Term name to list (i.e., group, series, tag, book, speaker)
        'by'          => '',          // For sermons, get a list 
			), $attr ) );

      if( empty( $name ) ) return '';
      if( 'sermonsby' == $type ){
        switch ( $by ) {
          case 'series':
            $tax = '_cpt_sermon_series';
            break;
          case 'tag':
            $tax = '_cpt_sermon_tag';
            break;
          case 'speaker':
            $tax = '_cpt_sermon_speaker';
            break;
          case 'book':
            $tax = '_cpt_sermon_book';
            break;
          default:
            return '';
            break;
        }
      }
      
			$parent_url = get_permalink();
      $use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
				'posts_per_page' 	=> $count, 
				'paged' 					=> $paged,
				'order' 					=> 'ASC',
        $tax              => $name,
			) ; 
			// get query
			$posts = new WP_Query();
			$posts -> query( $args ); 
			
			ob_start();  // we'll buffer the results to use templates
			if( '' != $template ){
				// template contains the loop
				// the templates would be stored from within a theme
				require( $template );
			} else {
				// this is a generic loop with most of the meta data in the various cpt
				if( $posts->have_posts() ){
					while( $posts->have_posts() ){
						$posts -> the_post(); 
						$post_id 	= get_the_ID();
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
						
						// First need to extract all relevant metadata from the CPT
						// Note that this is basically trying to extract ALL available data from
						// ALL cpt. In the templates one would use only the one relevant to that CPT.
						
						// Person data
						$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
						$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
						$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
						
						// Sermon data
						$ser_video = get_post_meta( $post_id, '_ctc_sermon_video' , true ); 
						$ser_audio = get_post_meta( $post_id, '_ctc_sermon_audio' , true ); 
						$ser_pdf = get_post_meta( $post_id, '_ctc_sermon_pdf' , true ); 
						$series = get_the_terms( $post_id, '_ctc_sermon_series');
						if( $series && ! is_wp_error( $series) ) {
							$series = array_shift( array_values ( $series ) );
							$ser_series = $series -> name;
						} else {
							$ser_series = '';
						}
						$books = get_the_terms( $post_id, '_ctc_sermon_book');
						if( $books && ! is_wp_error( $books ) ) {
							$books_A = array();
							foreach ( $books as $book ) { $books_A = $book -> name; }
							$ser_books = join( ', ', $books_A );
						} else {
							$ser_books = '';
						}
						$speakers = get_the_terms( $post_id, '_ctc_sermon_book');
						if( $speakers && ! is_wp_error( $speakers ) ) {
							$speakers_A = array();
							foreach ( $speakers as $speaker ) { $speakers_A = $speaker -> name; }
							$ser_speakers = join( ', ', $speakers_A );
						} else {
							$ser_speakers = '';
						}
						$tags = get_the_terms( $post_id, '_ctc_sermon_tag');
						if( $tags && ! is_wp_error( $tags ) ) {
							$tags_A = array();
							foreach ( $tags as $tag ) { $tags_A = $tag -> name; }
							$ser_tags = join( ', ', $tags_A );
						} else {
							$ser_tags = '';
						}
												
						// Below is a general template. 
?>
						<div class="ctc-block ctc-<?php echo $type;?>">
<?php // Image ?>
							<?php if( $thumbnail ): ?><img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/><?php endif; ?>
<?php // Title ?>							
							<?php if( $use_permalink ): ?>
							<div class="ctc-title"><?php echo get_the_title(); ?></div>
							<?php else: ?>
							<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
							<?php endif; ?>
<?php // Sermons ?>
							<?php if( $ser_speakers ): ?><div class="ctc-speaker"><i class="fa-user icon-user"></i><?php echo $ser_speakers; ?></div><?php endif; ?>
							<?php if( $ser_books ): ?><div class="ctc-books"><i class="fa-book icon-book"></i><?php echo $ser_books; ?></div><?php endif; ?>
							<?php if( $ser_series ): ?><div class="ctc-series"><i class="fa-th-large icon-th-large"></i><?php echo $ser_series; ?></div><?php endif; ?>
							<?php if( $ser_tags ): ?><div class="ctc-tags"><i class="fa-th-large icon-th-large"></i><?php echo $ser_tags; ?></div><?php endif; ?>
							<?php if( $ser_video ): ?><div class="ctc-video"><?php do_shortcode($ser_video); ?></div><?php endif; ?>
							<?php if( $ser_audio ): ?><div class="ctc-audio"><?php do_shortcode($ser_audio); ?></div><?php endif; ?>
<?php // Person ?>							
							<?php if( $per_position ): ?><div class="ctc-position"><i><?php echo $per_position; ?></i></div><?php endif; ?>
							<?php if( $per_phone ): ?><div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $per_phone; ?></div><?php endif; ?>
							<?php if( $per_email ): ?><div class="ctc-email"><i class="fa-envelope icon-envelope"></i><?php echo $per_email; ?></div><?php endif; ?>
<?php
					} // while
				} // if
				$has_next = $posts->max_num_pages > $paged;
				$has_prev = $paged > 1;
?>
						<div class="ctc-nav">
							<a class="ctc-prev" <?php echo ($has_prev ? 'href="' . add_query_arg( 'paged', $paged-1, $parent_url ): '' ) ; ?>"><?php __( 'Previous', 'ctc-shortcodes' ); ?></a>
							<a class="ctc-next " <?php echo ($has_next ? 'href="'. add_query_arg( 'paged', $paged+1, $parent_url ): '' ) ; ?>"><?php __( 'Next', 'ctc-shortcodes' ); ?></a>
						</div>
<?php				
			} //else
			
			wp_reset_postdata();
			$output = ob_get_clean();
			
			return $before . $output . $after;
			
		}
		
		function single_shortcode ( $type, $attr ) {
		
    }
    
		
			/*
			$output = apply_filters( array( &$this, 'people_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'count' 	=>	-1,							// Number of items to display. -1 == all
				'thumb_size' => 'thumbnail',	// Image size to use
				'link_title'	=> false, 			// Link the title to a page
			), $attr ) );
			$parent_url = get_permalink();
			$use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );			
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			
			// Setup the query
			$args = array(
				'post_type' => 'ctc_person', 
				'posts_per_page' => $count, 
				'paged' => $paged,
				'order' => 'ASC'
			) ; 
			$posts = new WP_Query();
			$posts -> query( $args ); 
			
			$template = locate_template( 'ctc-shortcodes/people.php' );
			ob_start();
			if( '' != $template ){
				// template contains the loop
				require( $template );
			} else {
				// set up the query and the output
				if( $posts->have_posts() ){
					while( $posts->have_posts() ){
						$posts -> the_post(); 
						$post_id 	= get_the_ID();
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
						//get_the_post_thumbnail();
						$position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
						$email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
						$phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
?>
						<div class="ctc-person ctc-block">
							<?php if( $thumbnail ): ?><img src="<?php echo $thumbnail[0]; ?>" class="ctc-person-img"/><?php endif; ?>
							<?php if( $use_permalink ): ?>
							<i class="fa-user icon-user"></i><span class="ctc-person-name"><?php echo get_the_title(); ?></span>
							<?php else: ?>
							<i class="fa-user icon-user"></i><span class="ctc-person-name"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></span>
							<?php endif; ?>
							<?php if( $position ): ?><span class="ctc-person-position"><?php echo $position; ?></span><?php endif; ?>
							<?php if( $email ): ?><span class="ctc-person-email"><i class="fa-map-marker icon-map-marker"></i><?php echo $email; ?></span><?php endif; ?>
							<?php if( $phone ): ?><span class="ctc-person-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $phone; ?></span><?php endif; ?>
						</div>
<?php
					} // while
				} // if
				$has_next = $posts->max_num_pages > $paged;
				$has_prev = $paged > 1;
?>
	<div class="ctc-person-nav">
		<a class="ctc-prev" <?php echo $has_prev ? 'href="'. add_query_arg( 'paged', $paged-1, $parent_url ): '' ; ?>">Previous</a>
		<a class="ctc-next " <?php echo $has_next ? 'href="'. add_query_arg( 'paged', $paged+1, $parent_url ): '' ; ?>">Next</a>

	</div>
<?php
	} //else
			
			wp_reset_postdata();
			$output = ob_get_clean();
			
			// A paging setup 
			return $before . $output . $after;
			
		}*/
		
		/*
		public function group_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'group_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'count' 	=>	'',
				'group' 	=> 	''
			), $attr ) );
			
		}*/
		
    /*public function person_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'person_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'count' 	=>	''
			), $attr ) );
			
		}*/
		
    /*
    public function event_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'event_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'event' 	=>	''
			), $attr ) );
			
		}
		
		// Sermon
		public function sermonsby_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'sermonsby_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'count' 	=>	'',
				'by' 	=>	''
			), $attr ) );
			
		}
		public function sermon_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'sermon_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'sermon' 	=>	'',
				'sermon_ID' 	=>	null
			), $attr ) );
			
		}
		
		// Location 
		public function locations_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'locations_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'count' 	=>	''
			), $attr ) );
			
		}
		public function location_shortcode( $attr ) {
			$output = apply_filters( array( &$this, 'location_shortcode' ), '', $attr );
			if ( $output != '' ) return $output;
			extract( shortcode_atts( array(
				'before' 	=>	'',
				'after' 	=>	'',
				'location' 	=>	''
			), $attr ) );
			
		}
	*/
	}
}
?>