

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <!--        <li class="--><?php //if($this->uri->segment(3)=='list-student') { echo 'active'; } ?><!--">-->
        <!--            <a href="--><?php //echo base_url('academic/final-project/list-student'); ?><!--">Final Project</a>-->
        <!--        </li>-->
        <li class="<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='ticket-today') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/ticket-today'); ?>">Ticket Today</a>
        </li>


        <li class="<?php if($this->uri->segment(2)=='ticket-list') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/ticket-list'); ?>">Ticket List</a>
        </li>

        <li class="<?php if($this->uri->segment(2)=='setting') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/setting'); ?>">Setting</a>
        </li>

    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('.fixed-header').addClass('sidebar-closed');
    });
</script>