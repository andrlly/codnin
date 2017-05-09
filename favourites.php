<?php
/* Template Name: Favourites */

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php
                $popular_args = array(
                    'post_type' => 'films',
                    'meta_key' => 'wpb_post_views_count',
                    'posts_per_page' => 2,
                    'order' => 'DESC',
                    'orderby' => 'meta_value_num',
                );
                $query = new WP_Query($popular_args);

                /* Start the Loop */
                while ( $query->have_posts() ) : $query->the_post();
                    $price_film =  get_post_meta( get_the_ID() , 'price_film', true );
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        <?php twentyseventeen_edit_link( get_the_ID() ); ?>
                    </header><!-- .entry-header -->
                    <div class="entry-content"  style="margin-bottom: 50px;">
                        <?php if (has_post_thumbnail()) { ?>
                            <img src="<?php the_post_thumbnail_url();?>" alt="<?php the_title(); ?>">
                        <?php } ?>
                        <h2><?php the_title(); ?></h2>
                        <?php if ($price_film) { ?>
                            <h3 style="color: #aa0000; font-weight: 700;">Price: <?php echo esc_attr($price_film); ?></h3>
                        <?php } ?>
                        <?php
                        the_content();

                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div><!-- .entry-content -->
                    </article><!-- #post-## -->


                     <?php         endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
