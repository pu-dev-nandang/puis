<style type="text/css">
    #tableData9 thead th,#tableData9 tfoot td {

        text-align: center;
        background: #20485A;
        color: #FFFFFF;

    }

    #tableData9>thead>tr>th, #tableData9>tbody>tr>th, #tableData9>tfoot>tr>th, #tableData9>thead>tr>td, #tableData9>tbody>tr>td, #tableData9>tfoot>tr>td {
        border: 1px solid #b7b7b7
    }

    .btn span.glyphicon {               
        opacity: 0;             
    }
    .btn.active span.glyphicon {                
        opacity: 1;             
    }
</style>
<style type="text/css">
    #tableData7 thead th,#tableData7 tfoot td {

        text-align: center;
        background: #20485A;
        color: #FFFFFF;

    }

    #tableData7>thead>tr>th, #tableData7>tbody>tr>th, #tableData7>tfoot>tr>th, #tableData7>thead>tr>td, #tableData7>tbody>tr>td, #tableData7>tfoot>tr>td {
        border: 1px solid #b7b7b7
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width btn-read menuConfig">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="javascript:void(0)" class="pageAnchor" page = "Set_Rad">RAD</a>
                </li>
                <li class="">
                    <a href="javascript:void(0)" class="pageAnchor" page = "Set_Approval">Approval</a>
                </li>
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <div id = "pageContent">
                   
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        LoadPage('Set_Rad');
        $(".pageAnchor").click(function(){
            var Page = $(this).attr('page');
            $(".menuConfig li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageContent").empty();
            LoadPage(Page);
        });
        
    }); // exit document Function

    function LoadPage(page)
    {
        loading_page("#pageContent");
        var url = base_url_js+'purchasing/transaction/po/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            setTimeout(function () {
                $("#pageContent").empty();
                $("#pageContent").html(html);
                loadingEnd(500);
            },1000);
            
        }); // exit spost
    }
</script>