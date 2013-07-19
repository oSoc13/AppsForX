<?php
/*
 * Template Name: Apps4X Single App
 * Description: A page template for the Apps4X template
 */

get_header();

?>
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <?php if (have_posts()) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
                             prefix="
                    apps4eu: http://apps4eu.eu/voc#
                    odapps: http://apps4eu.eu/odapps/voc#
                    foaf: http://xmlns.com/foaf/0.1/
                    typeof="odapps:Application">
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
                        <a property="apps4eu:registration" href="<?php echo esc_attr($meta['register_url'][0]); ?>">Register for this event</a>
                    </div><!-- .entry-content -->

                    <div class="entry-content" style="clear:both">
                        <p>
                            <strong>Organizer:</strong>
                            <span property="apps4eu:organizer" instanceof="foaf:Agent"><?php echo esc_attr($meta['organizer'][0]); ?></span>
                        </p>
                        <p>
                        <div style="float:left"><strong>Sponsors:</strong>&nbsp;</div>
                        <div style="float:left">
                            <?php foreach((array)$meta['sponsor'] as $sponsor) { ?>
                                <span property="apps4eu:sponsor" instanceof="foaf:Agent"><?php echo esc_attr($sponsor); ?></span><br />
                            <?php } ?>
                        </div>
                        <br style="clear:both" />
                        </p>
                        <p>
                        <div style="float:left"><strong>Jury:</strong>&nbsp;</div>
                        <div style="float:left">
                            <?php foreach((array)$meta['jury'] as $jury) {
                                $jury = unserialize($jury);
                                list($surname, $lastname) = array(esc_attr($jury['agent-surname']), esc_attr($jury['agent-name']));
                                ?>
                                <span property="apps4eu:sponsor" instanceof="foaf:Agent"><?php echo $surname.' '.$lastname; ?></span><br />
                            <?php } ?>
                        </div>
                        <br style="clear:both" />
                        </p>
                        <p>
                        <div style="float:left"><strong>Awards:</strong>&nbsp;</div>
                        <div style="float:left">
                            <?php foreach((array)$meta['award'] as $award) {
                                $award = unserialize($award);
                                list($prize, $sponsor) = array(esc_attr($award['award-prize']), esc_attr($award['award-sponsor']));
                                ?>
                                <span property="apps4eu:prize"><?php echo $prize; ?></span> offered by
                                <span property="apps4eu:sponsor" instanceof="foaf:Agent"><?php echo $sponsor; ?></span><br />
                            <?php } ?>
                        </div>
                        <br style="clear:both" />
                        </p>
                    </div>
                    <hr />
                    <?php if ( $connected->have_posts() ) : ?>
                        <div class="entry-content">
                            <h3>Ideas</h3>
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