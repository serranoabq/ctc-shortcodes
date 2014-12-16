<?php
// Series template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged

// Sermon data
$series = get_terms( '_ctc_sermon_series');

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
<?php if( $ser_speakers ): ?>
		<div class="ctc-speaker"><i class="fa-user icon-user"></i><?php echo $ser_speakers; ?></div>
<?php endif; ?>
<?php if( $ser_books ): ?>
		<div class="ctc-books"><i class="fa-book icon-book"></i><?php echo $ser_books; ?></div>
<?php endif; ?>
<?php if( $ser_series ): ?>
		<div class="ctc-series"><i class="fa-th-large icon-th-large"></i><?php echo $ser_series; ?></div>
<?php endif; ?>
<?php if( $ser_tags ): ?>
		<div class="ctc-tags"><i class="fa-th-large icon-th-large"></i><?php echo $ser_tags; ?></div>
<?php endif; ?>
<?php if( $ser_video ): ?>
		<div class="ctc-video"><?php echo wp_video_shortcode( array( 'src' => $ser_video ) ); ?></div>
<?php endif; ?>
<?php if( $ser_audio ): ?>
		<div class="ctc-audio"><?php echo wp_audio_shortcode( array( 'src' => $ser_audio ) ); ?></div>
<?php endif; ?>
	</div>
<?php

?>
