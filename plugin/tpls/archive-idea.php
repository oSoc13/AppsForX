<?php
/*
 * Template Name: Apps4X Idea List
 * Description: A page template for the Apps4X template
 */

get_header();

?>
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <?php if (have_posts()) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                        $meta = get_post_meta( get_the_ID() );
                        $connected = new WP_Query( array(
                            'connected_type' => 'ideas_to_apps',
                            'connected_items' => $post,
                            'nopaging' => true,
                        ) );
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
                             prefix="
                    apps4eu: http://apps4eu.eu/voc#
                    odapps: http://apps4eu.eu/odapps/voc#
                    foaf: http://xmlns.com/foaf/0.1/
                    typeof="odapps:AppConcept">
                    <header class="entry-header">
                        <h1 class="entry-title" property="dc:title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpapps' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <p>
                            <strong>Summary:</strong>
                            <span property="odapps:keyword"><?php echo esc_attr($meta['summary'][0]); ?></span>
                        </p>

                        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpapps' ) ); ?>
                        <!-- Shouldn't this be at the very very bottom? -->
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'wpapps' ), 'after' => '</div>' ) ); ?>
                    </div><!-- .entry-content -->

                    <?php if ( $connected->have_posts() ) : ?>
                        <div class="entry-content" style="clear:both">
                            <h5>Applications</h5>
                            <ul>
                                <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

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