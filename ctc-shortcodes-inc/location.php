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
	<div class="ctc-block ctc-<?php echo $type;?>">
<?php if( $thumbnail ): ?>
		<img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/>
<?php endif; ?>										
<?php if( $use_permalink ): ?>
		<div class="ctc-title"><?php echo get_the_title(); ?></div>
<?php else: ?>
		<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
<?php endif; ?>
<?php if( $loc_address ): ?>
	<div class="ctc-location"><i class="fa-map-marker icon-map-marker"></i><?php echo $loc_address; ?></div>
<?php endif; ?>
<?php if( $loc_show_loc && $loc_address): ?>
		<div id="ctc-map">
			<a href="http://maps.google.com/maps?q=<?php echo urlencode($loc_address); ?>" target="_blank">
				<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($loc_address); ?>&zoom=15&size=300x200&sensor=false&scale=2&markers=color:red|<?php echo urlencode($loc_address); ?>" />
			</a>
		</div>
<?php endif; ?>
<?php if( $loc_phone ): ?>
		<div class="ctc-phone"><i class="fa-mobile icon-mobile-phone"></i><?php echo $loc_phone; ?></div>
<?php endif; ?>
<?php if( $loc_times ): ?>
			<div class="ctc-times"><i class="fa-clock icon-clock"></i><?php echo $loc_times; ?></div>
<?php endif; ?>
	</div>
<?php

?>
