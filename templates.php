<?php

class WPAppsTemplates {

var $admin = <<<'HTML'
    <div class="wrap">
        <div class="icon32" id="icon-edit-comments"><br /></div>
        <h2>Apps - {$statuses[$filter_idx]}</h2>

        <ul class="subsubsub">
            <li class="all"><a <?php if ($filter_idx == -1) { echo 'class="current"'; } ?> href="?page=wpapps">All</a> |</li>
            <li class="moderated"><a <?php if ($this->p->review_status == 0) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=0">Pending 
                <span class="count">(<span class="pending-count"><?php echo $pending_count;?></span>)</span></a> |
            </li>
            <li class="approved"><a <?php if ($this->p->review_status == 1) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=1">Approved
                <span class="count">(<span class="pending-count"><?php echo $approved_count;?></span>)</span></a> |
            </li>
            <li class="trash"><a <?php if ($this->p->review_status == 2) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=2">Trash</a>
                <span class="count">(<span class="pending-count"><?php echo $trash_count;?></span>)</span></a>
            </li>
        </ul>
    </div>
HTML;

}