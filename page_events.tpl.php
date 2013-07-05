<?php defined('IN_WPAPPS') || die; ?>
        <style type="text/css" scoped>
            .column-when { width: 10%; }
            .column-where { width: 20%; }
        </style>

        <div class="wrap">
            <div class="icon32" id="icon-edit-comments"><br /></div>
            <h2>Apps4X - Events <a href="?page=wpapps_events&amp;action=add" class="add-new-h2">&plus; Add New</a></h2>

            <ul class="subsubsub">
                <li><span class="count">Number of events: <?=$counts->events_all?>.</span></li>
            </ul>


            <form method="GET" action="" id="comments-form" name="comments-form">
                <input type="hidden" name="page" value="wpapps_events" />
                <?php
                $eventsTable->display();
                ?>
            </form>
        </div>