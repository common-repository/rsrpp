<div class="rsrpp">
<h2 class="rsrpp__title"><?php echo $related_post_title ?></h2>
<div class="rsrpp__posts">
	<?php foreach ( $related_posts as $related_post ): ?>
		<a class="rsrpp__posts__post" rel="external" href="<?php echo get_permalink($related_post->ID) ?>">				
			<span  class="rsrpp__posts__post__title"><?php echo $related_post->post_title; ?></span>
		</a>		
	<?php endforeach; ?>
</div>

