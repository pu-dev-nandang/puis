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
<style type="text/css">
    .imgtd {
        position: relative;
        text-align: center;
        color: white;
    }
    /* Centered text */
    .centeredimgtd {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .imgtd img {
        max-width: 80px;
    }

    .panel-red {
        font-size: 10px;
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 1px;
        background: #e98180;
        /*max-width: 100px;*/
        height: 45px;
        border: 1px dotted #333;
    }

    .panel-green {
        font-size: 10px;
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 1px;
        background: #20c51b;
        /*max-width: 100px;*/
        height: 45px;
        border: 1px dotted #333;
    }
    .panel-blue {
        font-size: 10px;
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 1px;
        background: #6ba5c1;
        /*max-width: 100px;*/
        height: 45px;
        border: 1px dotted #333;
    }
    .panel-orange {
        font-size: 10px;
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 1px;
        background: #ffb848;
        /*max-width: 100px;*/
        height: 45px;
        border: 1px dotted #333;
    }

    /*.table-responsive {
      
      height: auto !important;  
      max-height: 450px;
      overflow-y: auto;
    }*/

    .pointer {cursor: pointer;}
</style>
<div class="row btn-read" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Transaction Equipment </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <ul class="nav nav-tabs">
                            <!-- <li role="presentation"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="set_return">Set Return</a></li> -->
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="eq_history">History</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="eq_schedule">Schedule</a></li>
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
        //loadDataListApprove();
        LoadPage('eq_history');
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
        var url = base_url_js+'vreservation/t_eq/'+Page;
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