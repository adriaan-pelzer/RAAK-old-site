<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header() ?>

	<div id="container">
		<div id="content">

<h3>Raak Archive</h3>

   <?php query_posts('cat=-9&posts_per_page=20&orderby=date'); ?>
   <ul id="recentPosts">

   		<?php while (have_posts()) : the_post(); ?>

      <li>
        <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
        <div class="postDate"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_date('F j, Y') ?></abbr>
        					<span class="author vcard"><?php printf( __( 'By %s', 'sandbox' ), '<a class="url fn n" href="' . get_author_link( false, $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( __( 'View all posts by %s', 'sandbox' ), $authordata->display_name ) . '">' . get_the_author() . '</a>' ) ?></span>

        </div>
      </li>

   		<?php endwhile; ?>
   
    </ul>
				</div>
			</div><!-- .post -->

<?php if ( get_post_custom_values('comments') ) comments_template() // Add a key/value of "comments" to enable comments on pages! ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>