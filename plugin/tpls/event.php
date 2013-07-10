<?php
/*
 * Template Name: Apps4X Event List
 * Description: A page template for the Apps4X template
 */


get_header();

$type = 'event';
$args = ['post_type' => $type, 'post_status' => 'publish', 'paged' => $paged, 'posts_per_page' => 4, 'ignore_sticky_posts'=> 1];
$temp = $wp_query; // is this necessary ? -> yes.
$wp_query = new WP_Query($args);

?>
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <div id="namespaces" prefix="apps4eu: http://apps4eu.eu/odapps/voc/ foaf: http://xmlns.com/foaf/0.1/ dbpedia: http://dbpedia.org/resource/ ">
            <?php if (have_posts()) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php $meta = get_post_meta( get_the_ID() ); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php /*the_post_thumbnail();*/ ?>
                        <h1 class="entry-title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpapps' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php echo date("F j, Y", $meta['when_start'][0]) . " &mdash; "; the_title(); ?>
                            </a>
                        </h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <p><strong>Starts:</strong> <?php echo date("F j, Y - H:i", $meta['when_start'][0]) ?><br />
                        <strong>Ends:</strong>  <?php echo date("F j, Y - H:i", $meta['when_end'][0]) ?></p>
                        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpapps' ) ); ?>

                        <h5>Ideas ($count)</h5>
                        list-ideas
                        <!-- Shouldn't this be at the very very bottom? -->
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'wpapps' ), 'after' => '</div>' ) ); ?>
                    </div><!-- .entry-content -->

                    <footer class="entry-meta">
                        <?php if ( comments_open() ) : ?>
                            <div class="comments-link">
                                <?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'wpapps' ) . '</span>', __( '1 Reply', 'wpapps' ), __( '% Replies', 'wpapps' ) ); ?>
                            </div><!-- .comments-link -->
                        <?php endif; // comments_open() ?>
                    </footer><!-- .entry-meta -->
                </article><!-- #post -->
            <?php endwhile; // end of the loop. ?>
            <?php else: ?>
            <h2>Not Found</h2>
            <?php endif; ?>
            </div>
        </div><!-- #content -->
    </div><!-- #primary -->
<?php
$wp_query = $temp;
get_sidebar();
get_footer();
?>