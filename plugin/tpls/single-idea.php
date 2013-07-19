<?php
/*
 * Template Name: Apps4X Single Event
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
                    <meta property="dc:language" instanceof="dc:LinguisticSystem" content="<?php echo esc_attr($meta['language'][0]); ?>" />
                    <header class="entry-header">
                        <h1 class="entry-title" property="dc:title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpapps' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content" style="float:left">
                        <p>
                            <strong>Summary:</strong>
                            <span property="odapps:keyword"><?php echo esc_attr($meta['summary'][0]); ?></span>
                        </p>
                    </div><!-- .entry-content -->

                    <div class="entry-content" style="clear:both">
                        <p>
                        <div style="float:left"><strong>Conceivers:</strong>&nbsp;</div>
                        <div style="float:left">
                            <?php foreach((array)$meta['conceivers'] as $conceiver) { ?>
                                <span property="odapps:conceived" instanceof="foaf:Agent"><?php echo esc_attr($conceiver); ?></span><br />
                            <?php } ?>
                        </div>
                        <br style="clear:both" />
                        </p>
                        <p>
                            <strong>Contact:</strong>
                            <span property="odapps:contact" instanceof="foaf:Agent"><?php echo esc_attr($meta['contact'][0]); ?></span>
                        </p>
                        <p>
                            <strong>Homepage:</strong>
                            <a property="foaf:homepage" instanceof="schema:WebPage" href="<?php echo esc_attr($meta['homepage'][0]); ?>">Visit homepage</a>
                        </p>
                    </div>
                    <hr />
                    <?php if ( $connected->have_posts() ) : ?>
                        <div class="entry-content" style="clear:both">
                            <h3>Applications</h3>
                            <ul>
                                <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </article><!-- #post -->
            <?php endwhile; // end of the loop. ?>
            <?php else: ?>
                <h2>Not Found</h2>
            <?php endif; ?>
            <?php comments_template( '', true ); ?>
        </div><!-- #content -->
    </div><!-- #primary -->
<?php
get_sidebar();
get_footer();
?>