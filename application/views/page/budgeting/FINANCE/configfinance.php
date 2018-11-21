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
            <a href="javascript:void(0)" class="pageAnchor" page = "SetPostDepartement">Set Post Department</a>
        </li>
        <li class="<?php if($request=='UserRole'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "SetUserRole">Set User Role Department</a>
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

    function LoadSetUserRole()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/LoadSetUserRole';
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
            case "SetUserRole":
                LoadSetUserRole();
                break;
            default:
                text = "I have never heard of that fruit...";
        }
    });

    function AddScriptJS(link)
    {
            var head = document.getElementsByTagName("head")[0],
                script = document.createElement('script');

            script.type = 'text/javascript'
            // script.src = link + '.js'
            script.src = link
            head.appendChild(script);
    }

    function removejscssfile(filename, filetype){
        var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
        var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
        var allsuspects=document.getElementsByTagName(targetelement)
        for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
        if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
            allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
        }
    }

    function replacejscssfile(oldfilename, newfilename, filetype){
        var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist using
        var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
        var allsuspects=document.getElementsByTagName(targetelement)
        for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
            if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(oldfilename)!=-1){
                var newelement=createjscssfile(newfilename, filetype)
                allsuspects[i].parentNode.replaceChild(newelement, allsuspects[i])
            }
        }
    }

    function createjscssfile(filename, filetype){
        if (filetype=="js"){ //if filename is a external JavaScript file
            var fileref=document.createElement('script')
            fileref.setAttribute("type","text/javascript")
            fileref.setAttribute("src", filename)
        }
        else if (filetype=="css"){ //if filename is an external CSS file
            var fileref=document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
        }
        return fileref
    }

    function dynamicSort(property) {
        var sortOrder = 1;

        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }

        return function (a,b) {
            if(sortOrder == -1){
                return b[property].localeCompare(a[property]);
            }else{
                return a[property].localeCompare(b[property]);
            }        
        }
    }
</script>