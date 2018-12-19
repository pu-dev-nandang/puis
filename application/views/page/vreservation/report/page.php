<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }

    li{
        margin: 10px 0;
    }
</style>

<div class="row btn-read" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Report </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="summary_use_room">Summary Use Room</a></li>
                         </ul>
                         <br>
                         <div id="PageNav" class="btn-read">
                                                                 
                         </div>
                        <!-- <div id="pageData" class="btn-read">
                                        
                        </div> -->
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        LoadPage('summary_use_room');
    });

    $('.tab-btn-submenu-page').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        LoadPage(page);
    });

    function LoadPage(Page)
    {
        $("#PageNav").empty();
        loading_page("#PageNav");
        var url = base_url_js+'vreservation/report/'+Page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#PageNav").html(html);
        }); // exit spost
    }

    $(document).on('click','.Detail', function () {
       var dataJson = $(this).attr('data');
       var dtarr = dataJson.split('@@');
       var room = dtarr[6];
       var tgl = dtarr[10];;
       var time =  dtarr[1];
       modal_generate('view','Form Booking Reservation',room,time,tgl,'',dtarr);
    });
</script>