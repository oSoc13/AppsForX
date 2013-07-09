<?php
/*
 * Template Name: Apps4X Event List
 * Description: A page template for the Apps4X template
 */


get_header(); ?>

    <div id="primary" class="site-content">
        <div id="content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'content', 'page' ); ?>
                <?php comments_template( '', true ); ?>
            <?php endwhile; // end of the loop. ?>

        </div><!-- #content -->
    </div><!-- #primary -->

    <div id="container">
        <div id="content">

            <?php
            $type = 'event';
            $args = ['post_type' => $type, 'post_status' => 'publish', 'paged' => $paged, 'posts_per_page' => 4, 'ignore_sticky_posts'=> 1];
            $temp = $wp_query; // is this necessary ?
            $wp_query = new WP_Query($args);
            if ( $wp_query->have_posts() ) :
                while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    echo '<h2>';
                    the_title();
                    echo '</h2>';
                    echo '<div class="entry-content">';
                    the_content();
                    echo '</div>';
                endwhile;
            else :
                echo '<h2>Not Found</h2>';
            endif;
            $wp_query = $temp;
            ?>
        </div>
    </div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>