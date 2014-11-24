<?php
/**
 * The template for displaying all single posts.
 *
 * @package sneakers
 */

get_header(); ?>


	<div class="sneakerWrapper">

		<div id="primary" class="content-area span8">
			<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

				<div class="aboutDetail">
					<div class="contentDetail">
						<?php the_content(); ?>					

						<?php get_template_part('inc/single-social-share'); ?>

					</div>
				</div>



			<?php endwhile; // end of the loop. ?>

			<!-- Video Extra Loop -->
			<div class="sneakerTag">
				<?php

				//$page_object = get_queried_object();
				$page_id = get_queried_object_id();

				amc_global_extras_loop($page_id, 'brand_tax', 'videos');

				?>
			</div>
			<!-- END Video Extra Loop -->

			

			</main><!-- #main -->
		</div><!-- #primary -->

	<?php get_sidebar(); ?>

	</div><!-- .filmWrapper -->

<?php get_footer(); ?>