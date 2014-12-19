<?php
// Taxonomy list template
// Defined constants in previous scope
// $post_id, $thumbnail, $type, $use_permalink, $count, $paged, $tag, $name
// $term_title, $term_desc, $term_link

?>
<div class="ctc-block ctc-tax-list">
<?php if( $thumbnail ): ?>
	<img src="<?php echo $thumbnail; ?>" class="ctc-img"/>
<?php endif; ?>										
<?php if( $use_permalink ): ?>
	<div class="ctc-title"><?php echo $term_title; ?></div>
<?php else: ?>
	<div class="ctc-title"><a href="<?php echo $term_link; ?>"><?php echo $term_title; ?></a></div>
<?php endif; ?>
<?php if( $term_desc ): ?>
	<div class="ctc-term-desc"><?php echo $term_desc; ?></div>
<?php endif; ?>										

</div>
<?php

?>
