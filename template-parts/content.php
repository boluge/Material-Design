<?php
/**
 * Template part for displaying posts.
 *
 * @package Material Design
 */
if ( is_active_sidebar( 'sidebar-1' ) ){
	$class = 'l6 m6 s12';
} else {
	$class = 'l4 m6 s12';
}
?>

<div class="col <?php echo $class; ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
		<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'blog'); ?>

		<header class="card-thumb waves-effect waves-block waves-light">
			<a href="<?php echo get_permalink(); ?>">
				<img src="<?php echo $thumb[0]; ?>">
			</a>
		</header>

		<div class="card-date primary">
			<?php materialdesign_card_date(); ?>
		</div>

		<div class="card-body">
			<?php materialdesign_card_type(); ?>
			<div class="card-title">
				<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
			<div class="card-subtitle">
				<?php materialdesign_card_categories(); ?>
			</div>
			<div class="card-description">
				<?php the_excerpt(); ?>
			</div>
		</div>

		<footer class="card-footer">
			<?php materialdesign_card_footer(); ?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-## -->
</div>
