<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="thumbnail">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Choose DB</label>
                        <select class="form-control selectDB">
                            <option value="db_it" <?php echo $Sel = ($this->session->userdata('db_select') == 'db_it') ? 'selected' : '' ?> >IT</option>
                            <option value="db_academic" <?php echo $Sel = ($this->session->userdata('db_select') == 'db_academic') ? 'selected' : '' ?>>Academic</option>
                            <option value="db_purchasing" <?php echo $Sel = ($this->session->userdata('db_select') == 'db_purchasing') ? 'selected' : '' ?>>Purchasing</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="" style="margin-top: 30px;">
     <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="<?php echo ($this->uri->segment(2) == 'menu_previleges' &&  ($this->uri->segment(3) == '' || $this->uri->segment(3) == null)  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/menu_previleges'); ?>">Menu</a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'menu_previleges' &&  $this->uri->segment(3) == 'submenu'  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/menu_previleges/submenu'); ?>">Sub Menu</a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'menu_previleges' &&  $this->uri->segment(3) == 'user_access'  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/menu_previleges/user_access'); ?>">User Access</a>
                </li>
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).off('change', '.selectDB').on('change', '.selectDB',function(e) {
        var db_select = $(this).find('option:selected').val();
        loadingStart();
        var url = base_url_js+'it/menu/changes-db';
        var data = {
                    db_select : db_select,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               location.reload();
            },1500);
            loadingEnd(2000);
        })
    });
</script>



