<?php
// Person template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

// Person data
$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
$per_group = get_the_terms( $post_id, '_ctc_person_group');
if( $per_group && ! is_wp_error( $per_group ) ) {
	$per_group = array_shift( array_values ( $per_group ) );
	$per_group = $per_group -> name;
} else {
	$per_group = '';
}
?>
	<div class="ctc-block ctc-<?php echo $type;?>">
<?php if( $thumbnail ): ?>
		<img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/>
<?php endif; ?>									
<?php if( $use_permalink ): ?>
		<div class="ctc-title"><?php echo get_the_title(); ?></div>
<?php else: ?>
		<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
<?php endif; ?>
<?php if( $per_position ): ?>
		<div class="ctc-position"><i><?php echo $per_position; ?></i></div>
<?php endif; ?>
<?php if( $per_group ): ?>
		<div class="ctc-group"><i class="fa-group icon-group"></i><i><?php echo $per_group; ?></i></div>
<?php endif; ?>
<?php if( $per_phone ): ?>
		<div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $per_phone; ?></div>
<?php endif; ?>
<?php if( $per_email ): ?>
		<div class="ctc-email"><i class="fa-envelope icon-envelope"></i><?php echo $per_email; ?></div>
<?php endif; ?>
	</div>
<?php

?>
