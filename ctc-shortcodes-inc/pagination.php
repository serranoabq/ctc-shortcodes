<?php 
// Pagination template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

$has_prev = ($paged > 1);
$prev_href = $has_prev ? add_query_arg( $type . '_paged', ($paged-1 == 1 ? '' : $paged-1) ) : ''; 
$has_next = ($posts->max_num_pages > $paged);
$next_href = $has_next ? add_query_arg( $type . '_paged', $paged+1) : ''; 

if( $has_prev || $has_next ) {
?>
<div class="ctc-nav">
	<a class="ctc-prev button <?php echo !$has_prev? 'disabled' : ''?>" <?php echo $prev_href ? 'href="' . $prev_href . '"' : ''; ?>><?php _e( 'Previous', 'ctc-shortcodes' ); ?></a>
	<a class="ctc-next button <?php echo !$has_next? 'disabled' : ''?>" <?php echo $next_href ? 'href="' . $next_href . '"' : ''; ?>><?php _e( 'Next', 'ctc-shortcodes' ); ?></a>

</div>
<?php } ?>