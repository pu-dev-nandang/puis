<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menuConfig">
    <ul class="nav nav-tabs">
        <li class="<?php if($request==null || $request=='CodePrefix'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "PrefixCode">Code Prefix</a>
        </li>
        <li class="<?php if($request=='TimePeriod'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "TimePeriod">Time Period</a>
        </li>
        <li class="<?php if($request=='MasterPost'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "MasterPost">Master Post</a>
        </li>
        <li class="<?php if($request=='SetPostDepartement'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "SetPostDepartement">Set Post Departement</a>
        </li>
        <li class="<?php if($request=='MasterUserRole'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "MasterUserRole">Master User Role</a>
        </li>
        <li class="<?php if($request=='UserRole'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "UserRole">Set User Role Departement</a>
        </li>
        <li class="<?php if($request=='Catalog'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "Catalog">Catalog</a>
        </li>
        <li class="<?php if($request=='Supplier'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "Supplier">Supplier</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="row" id = "pageContentConfig">
           
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($request == null): ?>
            LoadCodePrefix();
        <?php else: ?>
            Load<?php echo $request ?>();    
        <?php endif ?>
        
    }); // exit document Function

    function LoadCodePrefix()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/loadCodePrefix';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#pageContentConfig").html(html);
        }); // exit spost
    }

    function LoadTimePeriod()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/LoadTimePeriod';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#pageContentConfig").html(html);
        }); // exit spost
    }

    function LoadMasterPost()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/loadMasterPost';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#pageContentConfig").html(html);
        }); // exit spost
    }

    function LoadSetPostDepartement()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/LoadSetPostDepartement';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#pageContentConfig").html(html);
        }); // exit spost
    }

    $(document).on('click','.pageAnchor', function () {
        var Page = $(this).attr('page');
        $(".menuConfig li").removeClass('active');
        $(this).parent().addClass('active');
        $("#pageContentConfig").empty();
        switch(Page) {
            case "PrefixCode":
                LoadCodePrefix();
                break;
            case "TimePeriod":
                LoadTimePeriod();
                break;
            case "MasterPost":
                LoadMasterPost();
                break;
            case "SetPostDepartement":
                LoadSetPostDepartement();
                break;    
            default:
                text = "I have never heard of that fruit...";
        }
    });
</script>