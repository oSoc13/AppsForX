<?php
/*
 * Template Name: Apps4X Event List
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
                    typeof="apps4eu:CocreationEvent">
                    <header class="entry-header">
                        <h1 class="entry-title" property="dc:title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpapps' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php echo date("F j, Y", $meta['when_start'][0]) . " &mdash; "; the_title(); ?>
                            </a>
                        </h1>
                    </header><!-- .entry-header -->

                    <div style="float:left; margin: 0 25px 25px 0" rel="foaf:logo">
                        <?php echo wp_get_attachment_image($meta['logo'][0]); ?>
                    </div>
                    <div class="entry-content" style="float:left">
                        <p>
                            <strong>Location:</strong>
                            <span property="dc:spatial" instanceof="dc:Location"><?php echo esc_attr($meta['location'][0]); ?></span>
                        </p>
                        <p>
                            <strong>Starts:</strong>
                            <meta property="schema:startDate" content="<?php echo date('Y-m-d\TH:i:s', $meta['when_start'][0]); ?>" />
                            <?php echo date("F j, Y - H:i", $meta['when_start'][0]) ?><br />

                            <strong>Ends:</strong>
                            <meta property="schema:endDate" content="<?php echo date('Y-m-d\TH:i:s', $meta['when_end'][0]); ?>" />
                            <?php echo date("F j, Y - H:i", $meta['when_end'][0]) ?>
                        </p>

                        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpapps' ) ); ?>
                        <!-- Shouldn't this be at the very very bottom? -->
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'wpapps' ), 'after' => '</div>' ) ); ?>
                    </div><!-- .entry-content -->

                    <?php if ( $connected->have_posts() ) : ?>
                    <div class="entry-content" style="clear:both">
                        <h5>Ideas</h5>
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