<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Quartiere fuer Menschen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		$icon_output = '';
		if(get_post_type() === 'location') {
			$community = '';
			if(trim(get_post_meta($post->ID,'location-nickname',true))) {
				$community = '-community';
			}
			$terms = get_the_terms($post->ID,'location-type');
			$attachment = false;
			if(qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug ) && !get_post_meta($post->ID,'location-use-alternative-icon',true)==1) {
				$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.$community );
				if(!is_object($attachment)) $attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug );
			}
			elseif(qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.'-2' ) && get_post_meta($post->ID,'location-use-alternative-icon',true)==1) {
				$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.$community.'-2' );
				if(!is_object($attachment)) $attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.'-2' );
			}
		}
		
		if(is_object($attachment)) {
			$icon_output = '<span class="marker-icon"><img src="'.wp_get_attachment_url($attachment->ID).'" alt="Icon" /></span>';
		}
		
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">'.$icon_output, '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">'.$icon_output, '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				qfm_posted_on();
				qfm_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif;
		
		if ( 'location' === get_post_type() && get_post_meta($post->ID,'location-nickname',true)) :
			?>
			<div class="entry-meta">
				<?php
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( DATE_W3C ) ),
					esc_html( get_the_date() ),
					esc_attr( get_the_modified_date( DATE_W3C ) ),
					esc_html( get_the_modified_date() )
				);
				
				echo '<span class="posted-by-community">'.sprintf(esc_html('Dies ist ein Community-Beitrag, verfasst von %1$s und veröffentlicht am %2$s','qfm'),'<strong>'.get_post_meta($post->ID,'location-nickname',true).'</strong>','<strong>'.$time_string.'</strong>').'</span>';
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php
		global $post;
		$header_img_src = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		if($header_img_src) { ?>
	<div id="header-image" style="background-image:url(<?php echo $header_img_src; ?>)"></div>
	<?php } ?>

	<?php if('location' === get_post_type() && get_post_meta($post->ID,'kuula-id',true)) {
		echo '
		<div class="kuula-box">
			<script src="https://static.kuula.io/embed.js" data-kuula="https://kuula.co/share/'.get_post_meta($post->ID,'kuula-id',true).'?fs=1&vr=0&sd=1&thumbs=1&inst=de&info=1&logo=1" data-css="ku-embed"></script>
		</div>';
	}
	?>

	<div class="entry-content">
	
		<?php
		
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'qfm' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'qfm' ),
				'after'  => '</div>',
			)
		);

		?>
		
	</div><!-- .entry-content -->
	
	<div class="qfm-karte-wrapper">
		<?php 
		// QFM Karte
			if('location' === get_post_type()) {
				echo do_shortcode('[qfm-karte single]');
			}
		?>
	</div>

	<footer class="entry-footer">
		<?php qfm_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
