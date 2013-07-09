<?php defined('IN_WPAPPS') || die; ?>

        <div class="wrap">
            <div class="icon32" id="icon-edit-comments"><br /></div>
            <h2>Apps - <?=$statuses[$filter_idx]?></h2>

            <ul class="subsubsub">
                <li class="all"><a <?php if (!$filter_idx) { echo 'class="current"'; } ?> href="?page=wpapps">All events</a>
                    <span class="count">(<span class="pending-count"><?=$counts->events_all?></span>)</span></a> |
                </li>
                <li class="moderated"><a <?php if ($filter_idx == 1) { echo 'class="current"'; } ?> href="?page=wpapps&amp;adm_filter=1">Pending ideas
                    <span class="count">(<span class="pending-count"><?=$counts->ideas_pending?></span>)</span></a> |
                </li>
            </ul>

            <form method="POST" action="?page=wpapps" id="comments-form" name="comments-form">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php
                $testListTable->display();
                ?>
                </form>


                <table cellspacing="0" class="widefat comments fixed">
                    <thead>
                      <tr>
                        <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
                        <th style="" class="manage-column column-title" id="title" scope="col">Event</th>
                        <th style="" class="manage-column column-comment" id="comment" scope="col">Review</th>
                      </tr>
                    </thead>

<!--                     <tfoot>
                      <tr>
                        <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox" /></th>
                        <th style="" class="manage-column column-author" scope="col">Author</th>
                        <th style="" class="manage-column column-comment" scope="col">Review</th>
                      </tr>
                    </tfoot> -->

                    <tbody class="list:comment" id="the-comment-list">
                        <?php if (count($listitems) == 0) { ?>
                            <tr><td colspan="3" align="center"><br />There are no events or ideas to list yet.<br /><br /></td></tr>
                        <?php 
                        } 
                        $listitems = [""];
                        foreach($listitems as $litem) {
                            $rid = 2;
                        ?>

                            <tr class="approved" id="review-<?php echo $rid;?>">
                            <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $rid;?>" name="delete_reviews[]" /></th>
                            <td class="post-title page-title column-title">
                                derp
                            </td>
                            <td class="comment column-comment">
                              <div class="row-actions">
                                <span class="approve <?php if ($review->status == 0 || $review->status == 2) { echo 'wpapps_show'; } else { echo 'wpapps_hide'; }?>"><a title="Mark as Approved"
                                href="?page=wpapps&amp;action=approvereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                                Mark as Approved</a>&nbsp;|&nbsp;</span>
                                <span class="unapprove <?php if ($review->status == 1 || $review->status == 2) { echo 'wpapps_show'; } else { echo 'wpapps_hide'; }?>"><a title="Mark as Unapproved"
                                href="?page=wpapps&amp;action=unapprovereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                                Mark as Unapproved</a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                                <span class="trash <?php if ($review->status == 2) { echo 'wpapps_hide'; } else { echo 'wpapps_show'; }?>"><a title="Move to Trash" 
                                href= "?page=wpapps&amp;action=trashreview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                                Move to Trash</a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                                <span class="trash <?php if ($review->status == 2) { echo 'wpapps_hide'; } else { echo 'wpapps_show'; }?>"><a title="Delete Forever" 
                                href= "?page=wpapps&amp;action=deletereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                                Delete Forever</a></span>
                              </div>
                            </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>