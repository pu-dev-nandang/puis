<div class="row">
     <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
               <li class="<?php echo ($this->uri->segment(2) == 'console-developer' &&  $this->uri->segment(3) == 'document-generator' && $this->uri->segment(4) == 'privileges'  ) ? 'active' : '' ?>">
                   <a href="<?php echo base_url('it/console-developer/document-generator/privileges'); ?>">Privileges</a>
               </li>
               <li class="<?php echo ($this->uri->segment(2) == 'console-developer' &&  $this->uri->segment(3) == 'document-generator' && $this->uri->segment(4) == 'api_table'  ) ? 'active' : '' ?>">
                   <a href="<?php echo base_url('it/console-developer/document-generator/api_table'); ?>">API Table</a>
               </li>
           </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>



