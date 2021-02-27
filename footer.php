<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Quartiere fuer Menschen
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="inner">
			<div class="footer-left">
				<?php dynamic_sidebar( 'footer-left' ); ?>
			</div>
			<div class="footer-right">
				<?php dynamic_sidebar( 'footer-right' ); ?>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
