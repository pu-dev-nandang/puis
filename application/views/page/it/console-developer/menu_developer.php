
<div class="" style="margin-top: 30px;">
     <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
               <li class="<?php echo ($this->uri->segment(2) == 'console-developer' &&  ($this->uri->segment(3) == '' || $this->uri->segment(3) == null)  ) ? 'active' : '' ?>">
                   <a href="<?php echo base_url('it/console-developer'); ?>">Developer Mode</a>
               </li>
               <li class="<?php echo ($this->uri->segment(2) == 'console-developer' &&  $this->uri->segment(3) == 'routes'  ) ? 'active' : '' ?>">
                   <a href="<?php echo base_url('it/console-developer/routes'); ?>">Routes</a>
               </li>
               <li class="<?php echo ($this->uri->segment(2) == 'console-developer' &&  $this->uri->segment(3) == 'document-generator'  ) ? 'active' : '' ?>">
                   <a href="<?php echo base_url('it/console-developer/document-generator/privileges'); ?>">Document Generator</a>
               </li>
           </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>



