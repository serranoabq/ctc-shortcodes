<?php
// Location template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

// Location data
$loc_addr = get_post_meta( $post_id, '_ctc_location_address' , true ); 
$loc_show_loc = get_post_meta( $post_id, '_ctc_location_show_directions_link' , true ); 
$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 

?>
<div class="ctc-block ctc-location">
<?php if( $thumbnail ): ?>
	<img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/>
<?php endif; ?>										
<?php if( $use_permalink ): ?>
	<div class="ctc-title"><?php echo get_the_title(); ?></div>
<?php else: ?>
	<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
<?php endif; ?>
<?php if( $loc_phone ): ?>
	<div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $loc_phone; ?></div>
<?php endif; ?>
<?php if( $loc_times ): ?>
		<div class="ctc-times"><i class="fa-time icon-time"></i><?php echo nl2br( $loc_times ); ?></div>
<?php endif; ?>
<?php if( $loc_addr ): ?>
<div class="ctc-address"><i class="fa-map-marker icon-map-marker"></i><?php echo nl2br( $loc_addr ); ?></div>
<?php endif; ?>
<?php if( $loc_show_loc && $loc_addr): ?>
	<div class="ctc-map">
		<a href="http://maps.google.com/maps?q=<?php echo urlencode($loc_addr); ?>" target="_blank">
			<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($loc_addr); ?>&zoom=15&size=300x200&sensor=false&scale=1&markers=color:red|<?php echo urlencode($loc_addr); ?>" />
		</a>
	</div>
<?php endif; ?>
</div>

<?php

?>
