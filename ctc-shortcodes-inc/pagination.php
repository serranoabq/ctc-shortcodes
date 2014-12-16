<?php 
// Pagination template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

$has_next = $posts->max_num_pages > $paged;
$has_prev = $paged > 1;
?>
<div class="ctc-nav">
	<a class="ctc-prev" <?php echo ($has_prev ? 'href="' . add_query_arg( $type . '_paged', $paged-1, $parent_url ): '' ) ; ?>"><?php __( 'Previous', 'ctc-shortcodes' ); ?></a>
	<a class="ctc-next " <?php echo ($has_next ? 'href="'. add_query_arg( $type . '_paged', $paged+1, $parent_url ): '' ) ; ?>"><?php __( 'Next', 'ctc-shortcodes' ); ?></a>
</div>
