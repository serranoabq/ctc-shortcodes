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
    
		public function ctc_locate_template( $template_names, $load = false, $require_once_ = true) {
			$located = '';
			foreach ( (array) $template_names as $template_name ) {
				if ( !$template_name )
					continue;
				if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
					$located = STYLESHEETPATH . '/' . $template_name;
					break;
				} else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
					$located = TEMPLATEPATH . '/' . $template_name;
					break;
				else 
					$located = $template_name; 
				}
			}

			if ( $load && '' != $located )
				load_template( $located, $require_once );

				return $located;
			}
		}
		
		// This handles whole listings at the CPT level
		public function cpt_shortcode( $type, $attr) {
			switch ( $type ) {
				case 'groups':
				case 'series':
				case 'books':
				case 'tags':
				case 'speakers':
					return taxlist_shortcode( $type, $attr );
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
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( $template_location . $type );
			$pag_template = $this->ctc_locate_template( $template_location . 'pagination' );
			
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
			// Use a special query variable
			$paged = ( get_query_var( $type. '_paged' ) ) ? get_query_var( $type . '_paged' ) : 1;
			
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
				'posts_per_page' 	=> $count, 
				'paged' 					=> $paged,
				'order' 					=> 'DESC'
			) ; 
			
			// get query
			$posts = new WP_Query();
			$posts -> query( $args ); 
			ob_start();  // we'll buffer the results to use templates
			if( $posts->have_posts() ){
				while( $posts->have_posts() ){
					$posts -> the_post(); 
					$post_id 	= get_the_ID();
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
					if( '' != $template ){ require( $template ); }
				}
				if( '' != $pag_template ){ require( $pag_temaplte ); }
			}
			
			wp_reset_postdata();
			$output = ob_get_clean();
			return $before . $output . $after;
						
		}
		
		public function taxlist_shortcode( $type, $attr) {
			switch ( $type ) {
				case 'groups':
					$cpt = 'ctc_person';
					$tax = '_ctc_person_group';
					break;
				case 'series':
					$cpt = 'ctc_sermon';
					$tax = '_ctc_sermon_series';
					break;
				case 'books':
					$cpt = 'ctc_sermon';
					$tax = '_ctc_sermon_book';
					break;
				case 'speaker':
					$cpt = 'ctc_sermon';
					$tax = '_ctc_sermon_speaker';
					break;
				case 'tag':
					$cpt = 'ctc_sermon';
					$tax = '_ctc_sermon_tag';
					break;
				default:
					return '';
			} //switch
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( $template_location . $type );
			$pag_template = $this->ctc_locate_template( $template_location . 'pagination' );
			
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
			// Use a special query variable
			$paged = ( get_query_var( $type. '_paged' ) ) ? get_query_var( $type . '_paged' ) : 1;
			
			$taxes = get_terms( $tax );
			ob_start();  // we'll buffer the results to use templates
			foreach( $taxes as $term ){
				$args = array(
					'post_type' 			=> $cpt, 
					'posts_per_page' 	=> 1, 
					'paged' 					=> $paged,
					'taxonomy'				=> $tax,
					'term'						=> $term->slug,
					'order' 					=> 'DESC'
				) ; 
				
			}
			// Setup the query
			
			// get query
			$posts = new WP_Query();
			$posts -> query( $args ); 
			if( $posts->have_posts() ){
				while( $posts->have_posts() ){
					$posts -> the_post(); 
					$post_id 	= get_the_ID();
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
					if( '' != $template ){ require( $template ); }
				}
				if( '' != $pag_template ){ require( $pag_temaplte ); }
			}
			
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