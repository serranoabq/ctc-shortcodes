<?php

if ( ! class_exists( 'CTC_Shortcodes' ) ) {
	class CTC_Shortcodes {
		
		public function __construct() {
			// Church Theme Content is REQUIRED
			if ( ! class_exists( 'Church_Theme_Content' ) ) return;
			
			// CPT Archives
			add_shortcode( 'ctc_people', array( &$this, 'people_shortcode' ) );
			add_shortcode( 'ctc_events', array( &$this, 'events_shortcode' ) );
			add_shortcode( 'ctc_sermons', array( &$this, 'sermons_shortcode' ) );
			add_shortcode( 'ctc_locations', array( &$this, 'locations_shortcode' ) );
			
			// Taxonomy lists
			add_shortcode( 'ctc_groups', array( &$this, 'groups_shortcode' ) );
			add_shortcode( 'ctc_speakers', array( &$this, 'speakers_shortcode' ) );
			add_shortcode( 'ctc_topics', array( &$this, 'topics_shortcode' ) );
			add_shortcode( 'ctc_tags', array( &$this, 'tags_shortcode' ) );
			add_shortcode( 'ctc_books', array( &$this, 'books_shortcode' ) );
			add_shortcode( 'ctc_series_list', array( &$this, 'series_list_shortcode' ) );
			
			// Taxonomy Archives
			add_shortcode( 'ctc_group', array( &$this, 'group_shortcode' ) ); 
			add_shortcode( 'ctc_topic', array( &$this, 'topic_shortcode' ) ); 
			add_shortcode( 'ctc_tag', array( &$this, 'tag_shortcode' ) ); 
			add_shortcode( 'ctc_series', array( &$this, 'series_shortcode' ) ); add_shortcode( 'ctc_speaker', array( &$this, 'speaker_shortcode' ) ); 
			add_shortcode( 'ctc_book', array( &$this, 'book_shortcode' ) );
			
			// Single posts
			add_shortcode( 'ctc_sermon', array( &$this, 'sermon_shortcode' ) );
			add_shortcode( 'ctc_event', array( &$this, 'event_shortcode' ) );
			add_shortcode( 'ctc_person', array( &$this, 'person_shortcode' ) );
			add_shortcode( 'ctc_location', array( &$this, 'location_shortcode' ) );
			
			// Add styles
			add_action( 'wp_enqueue_scripts', array( &$this, 'ctc_styles' ) );
			
			
			add_filter('widget_text', 'do_shortcode');
		}
		
/**
 * Add styles
 *
 * @since 1.0.1
 */
		function ctc_styles(){
			wp_register_style( 'ctc-style' , $this->ctc_locate_template_url( 'ctc-shortcodes-inc/style.css' ), __FILE__ );
		}
		
		// CPT Archvies
    public function people_shortcode( $attr ) { return $this->cpt_shortcode( 'person', $attr ); }
		public function events_shortcode( $attr ) { return $this->cpt_shortcode( 'event', $attr ); }
		public function sermons_shortcode( $attr ) { return $this->cpt_shortcode( 'sermon', $attr ); }
		public function locations_shortcode( $attr ) { return $this->cpt_shortcode( 'location', $attr ); }
    
		// CPT Single
    public function person_shortcode( $attr ) { return $this->single_shortcode( 'person', $attr ); }
		public function event_shortcode( $attr ) { return $this->single_shortcode( 'event', $attr ); }
		public function sermon_shortcode( $attr ) { return $this->single_shortcode( 'sermon', $attr ); }
		public function location_shortcode( $attr ) { return $this->single_shortcode( 'location', $attr ); }
		
		// Taxonomy Lists
		public function groups_shortcode( $attr ) { return $this->taxlist_shortcode( 'group_list', $attr ); }
		public function series_list_shortcode( $attr ) { return $this->taxlist_shortcode( 'series_list', $attr ); }
		public function books_shortcode( $attr ) { return $this->taxlist_shortcode( 'book', $attr ); }
		public function speakers_shortcode( $attr ) { return $this->taxlist_shortcode( 'speaker_list', $attr ); }
		public function topics_shortcode( $attr ) { return $this->taxlist_shortcode( 'topic_list', $attr ); }
		public function tags_shortcode( $attr ) { return $this->taxlist_shortcode( 'tag_list', $attr ); }
		
		// Tax archives
		public function group_shortcode( $attr ) { return $this->tax_shortcode( 'group', $attr ); }
		public function tag_shortcode( $attr ) { return $this->tax_shortcode( 'tag', $attr ); }
		public function topic_shortcode( $attr ) { return $this->tax_shortcode( 'topic', $attr ); }
		public function speaker_shortcode( $attr ) { return $this->tax_shortcode( 'speaker', $attr ); }
		public function book_shortcode( $attr ) { return $this->tax_shortcode( 'book', $attr ); }
		public function series_shortcode( $attr ) { return $this->tax_shortcode( 'series', $attr ); }
		
		
    
/**
 * Locate a template file. Similar to the @locate_template WordPress function, 
 * but also searches in the plugin directory
 *
 * @since 1.0
 * @param string $template_names Array of strings with templates to locate
 * @param bool $load Flag to load the template. (Default false)
 * @param bool $load Flag to load the template only one (Default true)
 * @return string path to the template file located (it will return the last one located)
 */
		public function ctc_locate_template( $template_names, $load = false, $require_once = true) {
			$located = '';
			foreach ( (array) $template_names as $template_name ) {
				if( !$template_name ) continue;
				if( file_exists( get_stylesheet_directory() . '/' . $template_name)) {
					// Child
					$located = get_stylesheet_directory() . '/' . $template_name;
					break;
				} elseif( file_exists( get_template_directory() . '/' . $template_name) ) {
					// Parent
					$located = get_template_directory() . '/' . $template_name;
					break;
				} elseif( file_exists( dirname(__FILE__). '/' . $template_name ) ) {
					// Plugin
					$located = dirname(__FILE__). '/' . $template_name; 
					break;
				}
			}

			if ( $load && '' != $located ) 
				load_template( $located, $require_once );
		
			return $located;
		}

/**
 * Locate a template file URL. Similar to the @locate_template WordPress function, 
 * but also searches in the plugin directory
 *
 * @since 1.0.1
 * @param string $template_names Array of strings with templates to locate
 * @param bool $load Flag to load the template. (Default false)
 * @param bool $load Flag to load the template only one (Default true)
 * @return string path to the template file located (it will return the last one located)
 */
		public function ctc_locate_template_url( $template_names, $load = false, $require_once = true) {
			$located = '';
			foreach ( (array) $template_names as $template_name ) {
				if ( !$template_name ) continue;
				if ( file_exists( get_stylesheet_directory_uri() . '/' . $template_name)) {
					// Child
					$located = get_stylesheet_directory_uri() . '/' . $template_name;
					break;
				} else if ( file_exists( get_template_directory_uri() . '/' . $template_name) ) {
					// Parent
					$located = get_template_directory_uri() . '/' . $template_name;
					break;
				} elseif( plugins_url( $template_name, __FILE__ ) ) {
					// Plugin
					$located = plugins_url( $template_name, __FILE__ ); 
					break;
				}
			}

			if ( $load && '' != $located ) 
				load_template( $located, $require_once );
		
			return $located;
		}
		
/**
 * Shortcode handler for a custom post type archive
 *
 * @since 1.0.1
 * @param string $type The type of CPT to show: 'locations', 'events', 'sermons', 'people'
 * @param mixed $args Shortcode arguments
 *    @param string $before Text to prepend the shortcode output with. (Default '')
 *    @param string $after Text to apend to the shortcode output. (Default '')
 *    @param int 		$count Number of entries to display (Default = -1; all entries)
 *    @param string $thumb_size Image size to use ('thumbnail', 'large', 'medium', 'small' or any other size defined by a theme or plugin (Default = 'thumbnail')
 *    @param bool 	$link_title Flag to determine if the title should link to a page. This only makes sense if the theme supports it. (Default = false)
 */
		public function cpt_shortcode( $type, $attr) {
			switch ( $type ) {
				case 'location':
					$cpt = 'ctc_location';
					break;
				case 'sermon':
					$cpt = 'ctc_sermon';
					break;
				case 'event':
					$cpt = 'ctc_event';
					break;
				case 'person':
					$cpt = 'ctc_person';
					break;
				default:
					return '';
			} 
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( $template_location . $type . '.php');
			if( empty( $template ) ) return '';
			
			$pag_template = $this->ctc_locate_template( $template_location . 'pagination.php' );
			
			// Parse the arguments
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'count' 			=>	-1,					// Number of items to display. -1 == all
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
			), $attr ) );

			$use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			$paged = intval( isset( $_GET[ $type . '_paged'] )  ? $_GET[ $type . '_paged'] : 1 );
			
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
				'posts_per_page' 	=> $count, 
				'paged' 					=> $paged,
				'order' 					=> 'DESC'
			) ; 
			
			// get query
			$posts = new WP_Query( $args );
			ob_start();  
			if( $posts->have_posts() ){
				while( $posts->have_posts() ){
					$posts -> the_post(); 
					$post_id 	= get_the_ID();
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
					require( $template );
				}
				if( '' != $pag_template ){ require( $pag_template ); }
			}
			
			wp_reset_postdata();
			$output = ob_get_clean();
			wp_enqueue_style( 'ctc-style' );
			return $before . $output . $after;
						
		}

/**
 * Shortcode handler for a taxonomy term list
 *
 * @since 1.0
 * @param string $type The type of taxonomy to show: 'group', 'series', 'book', 'speaker', 'tag', 'topic' 
 * @param mixed $args Shortcode arguments
 *    @param string $before Text to prepend the shortcode output with. (Default '')
 *    @param string $after Text to apend to the shortcode output. (Default '')
 *    @param int 		$count Number of entries to display (Default = -1; all entries)
 *    @param string $thumb_size Image size to use: 'thumbnail', 'large', 'medium', 'small' or any other size defined by a theme or plugin. Only works with modified CTC plugin (Default = 'thumbnail')
 *    @param bool 	$link_title Flag to determine if the title should link to a page. This only makes sense if the theme supports it. (Default = false)
 */		
		public function taxlist_shortcode( $type, $attr) {
			switch ( $type ) {
				case 'group_list':
					$cpt = 'ctc_person';
					$tax = 'ctc_person_group';
					break;
				case 'series_list':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_series';
					break;
				case 'book_list':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_book';
					break;
				case 'speaker_list':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_speaker';
					break;
				case 'tag_list':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_tag';
					break;
				case 'topic_list':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_topic';
					break;
				default:
					return '';
			} 
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( array( 
				$template_location . 'tax_list.php', 
				$template_location . $type . '.php' ) 
			);
			if( empty( $template ) ) return '';
			
			$pag_template = $this->ctc_locate_template( $template_location . 'pagination.php' );
			
			// These are basic parameters
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'count' 			=>	'',					// Number of items to display. -1 == all
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
			), $attr ) );

			$use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			
			$paged = intval( isset( $_GET[ $type . '_paged'] ) ? $_GET[ $type . '_paged'] : 1 );
			$offset = ( $paged - 1 ) * $count;
			
			$tax_args = array( 'number'=> $count, 'offset'=> $offset  );
			$taxes = get_terms( $tax, $tax_args );
			if ( empty( $taxes ) || is_wp_error( $taxes ) ) return '';
			
			ob_start();  
			foreach( $taxes as $term ){
				$term_title = $term->name;
				$term_desc = term_description( intval( $term->term_id ), $tax );
				$term_link = get_term_link( intval( $term->term_id ), $tax ); 
				if( function_exists( 'ctc_tax_img_url' ) )
					$thumbnail = ctc_tax_img_url( intval( $term->term_id ) );
				require( $template ); 
			}
			if( '' != $pag_template ){ require( $pag_template ); }
			$output = ob_get_clean();
			wp_enqueue_style( 'ctc-style' );
			return $before . $output . $after;
		
		}

/**
 * Shortcode handler for a taxonomy archive
 *
 * @since 1.0
 * @param string $type The type of taxonomy archive to show: 'group', 'series', 'book', 'speaker', 'tag', 'topic'
 * @param mixed $args Shortcode arguments
 *    @param string $before Text to prepend the shortcode output with. (Default '')
 *    @param string $after Text to apend to the shortcode output. (Default '')
 *    @param int 		$count Number of entries to display (Default = -1; all entries)
 *    @param string $thumb_size Image size to use: 'thumbnail', 'large', 'medium', 'small' or any other size defined by a theme or plugin. Only works with modified CTC plugin (Default = 'thumbnail')
 *    @param bool 	$link_title Flag to determine if the title should link to a page. This only makes sense if the theme supports it. (Default = false)
 */			
		public function tax_shortcode ( $type, $attr ) {
			switch ( $type ) {
				case 'group':
					$cpt = 'ctc_person';
					$tax = 'ctc_person_group';
					$type = 'person';
					break;
				case 'series':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_series';
					$type = 'sermon';
					break;
				case 'book':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_book';
					$type = 'sermon';
					break;
				case 'speaker':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_speaker';
					$type = 'sermon';
					break;
				case 'tag':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_tag';
					$type = 'sermon';
					break;
				case 'topic':
					$cpt = 'ctc_sermon';
					$tax = 'ctc_sermon_topic';
					$type = 'sermon';
					break;
				default:
					return '';
			}
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( array(
				$template_location . 'tax_archive.php', 
				$template_location . $type . '_tax.php', 
				$template_location . $type . '.php', 
			) );
			if( empty( $template ) ) return '';
			
			$pag_template = $this->ctc_locate_template( $template_location . 'pagination.php' );
			
			// Parse the arguments
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'count' 			=>	-1,					// Number of items to display. -1 == all
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
        'name'        => '',          // Term name (slug) to list (i.e., group, series, tag, book, speaker, topic)
			), $attr ) );

			// Name is required
      if( empty( $name ) ) return '';
			
			$use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			$paged = intval( isset( $_GET[ $type . '_paged'] ) ? $_GET[ $type . '_paged'] : 1 );
			
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
				'posts_per_page' 	=> $count, 
				'paged' 					=> $paged,
				'order' 					=> 'DESC',
				$tax							=> $name,
      ) ; 
			
			// get query
			$posts = new WP_Query( $args );
			ob_start();  
			if( $posts->have_posts() ){
				while( $posts->have_posts() ){
					$posts -> the_post(); 
					$post_id 	= get_the_ID();
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
					require ( $template );	
				}
				if( '' != $pag_template ){ require( $pag_template ); }
			}
			
			wp_reset_postdata();
			$output = ob_get_clean();			
			wp_enqueue_style( 'ctc-style' );
			return $before . $output . $after;
			
		}
		
/**
 * Shortcode handler for a single item
 *
 * @since 1.0
 * @param string $type The type of post to show: 'person', 'location', 'event', 'sermon'
 * @param mixed $args Shortcode arguments
 *    @param string $before Text to prepend the shortcode output with. (Default '')
 *    @param string $after Text to apend to the shortcode output. (Default '')
 *    @param string $name Name (slug) of post to display (either this or $id must be specified)
 *    @param mixed 	$id ID of the post to display (either this or $name must be specified)
 *    @param string $thumb_size Image size to use: 'thumbnail', 'large', 'medium', 'small' or any other size defined by a theme or plugin. Only works with modified CTC plugin (Default = 'thumbnail')
 *    @param bool 	$link_title Flag to determine if the title should link to a page. This only makes sense if the theme supports it. (Default = false)
 */			
		public function single_shortcode ( $type, $attr ) {
			switch ( $type ) {
				case 'location':
					$cpt = 'ctc_location';
					break;
				case 'sermon':
					$cpt = 'ctc_sermon';
					break;
				case 'person':
					$cpt = 'ctc_person';
					break;
				case 'event':
					$cpt = 'ctc_event';
					break;
				default:
					return '';
			}
			
			$template_location = 'ctc-shortcodes-inc/';
			$template = $this->ctc_locate_template( array( 
				$template_location . $type . '_single.php', 
				$template_location . $type . '.php', 
				) );
			if( empty( $template ) ) return '';
			
			// Parse the arguments
			extract( shortcode_atts( array(
				'before' 			=>	'',					// Prepend content
				'after' 			=>	'',					// Append content
				'name' 				=>	'',					// Post slug
				'id'					=> 	'',					// Post ID
				'thumb_size' 	=> 'thumbnail',	// Image size to use by name
				'link_title'	=> false, 			// Link the title to a page
			), $attr ) );

			$parent_url = get_permalink();
      $use_permalink = $link_title OR current_theme_supports( 'church-theme-content' );
			
			// Either name or ID are required
			if( empty( $id ) && empty( $name ) ) return '';
			
			// Setup the query
			$args = array(
				'post_type' 			=> $cpt, 
      ) ; 
			
			if( $id ) 
				$args[ 'p' ] = $id;
			else
				$args[ 'name' ] = $name;
				
			// get query
			$posts = new WP_Query( $args );
			ob_start();  
			if( $posts->have_posts() ){
				while( $posts->have_posts() ){
					$posts -> the_post(); 
					$post_id 	= get_the_ID();
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumb_size );
					require ( $template );	
				}
			}
			
			wp_reset_postdata();
			$output = ob_get_clean();			
			wp_enqueue_style( 'ctc-style' );
			return $before . $output . $after;
			
    }
		

	}
}
