<?php
/*
 * Template Name: Apps4X App List
 * Description: A page template for the Apps4X template
 */

get_header();
// Nice RDFa example: http://rdfa.info/play/

?>
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <?php if (have_posts()) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    $meta = get_post_meta( get_the_ID() );
                    $connected = new WP_Query( array(
                        'connected_type' => 'events_to_ideas',
                        'connected_items' => $post,
                        'nopaging' => true,
                    ) );
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
                             prefix="
                    apps4eu: http://apps4eu.eu/voc#
                    odapps: http://apps4eu.eu/odapps/voc#
                    foaf: http://xmlns.com/foaf/0.1/
                    dc: http://purl.org/dc/terms/
                    schema: http://schema.org/
                    typeof="odapps:Application">
                    <header class="entry-header">
                        <h1 class="entry-title" property="dc:title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpapps' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php echo the_title(); ?>
                            </a>
                        </h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content" style="float:left">
                        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpapps' ) ); ?>
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
        </div><!-- #content -->
    </div><!-- #primary -->
<?php
get_sidebar();
get_footer();
?>