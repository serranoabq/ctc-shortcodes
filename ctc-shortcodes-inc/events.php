<?php
// Event template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

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
<?php if( $evt_start ): ?>
		<div class="ctc-date"><i class="fa-calendar icon-calendar"></i><?php echo $evt_start; if ( $evt_end ) echo '-'. $evt_end; ?></div>
<?php endif; ?>
<?php if( $evt_time ): ?>
		<div class="ctc-time"><i class="fa-clock icon-clock"></i><?php echo $evt_time; ?></div>
<?php endif; ?>
<?php if ( $evt_recurrence && $evt_recurrence != 'none' ) : ?>
		<div class="cpt-recurrence"><i><?php 
		
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

			?></i></div>
<?php	endif;  ?>
<?php if( $evt_venue ): ?>
		<div class="ctc-venue"><i class="fa-building icon-building"></i><?php echo $evt_venue; ?></div>
<?php endif; ?>
<?php if( $evt_address ): ?>
		<div class="ctc-location"><i class="fa-map-marker icon-map-marker"></i><?php echo $evt_address; ?></div>
<?php endif; ?>
<?php if( $evt_show_loc && $evt_address): ?>
		<div id="ctc-map">
			<a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank">
				<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($evt_address); ?>&zoom=15&size=300x200&sensor=false&scale=2&markers=color:red|<?php echo urlencode($evt_address); ?>" />
			</a>
		</div>
<?php endif; ?>
	</div>
<?php

?>
