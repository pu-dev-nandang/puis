<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Set Tuition Fee</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee/1">Approve Tuition Fee</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_approved/1">Tuition Fee Approved</a></li>
                        </ul>
                        <div  class="col-xs-12" align="right" id="pagination_link"></div>
                        <div id="dataPageLoad" style="margin-top:0px;">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.pageHtml = '';
    $(document).ready(function () {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('#panel_web').addClass('wrap');
            $('#panel_web').css({"padding": "0px", "padding-right": "20px"});
        }
        loadPage('tuition_fee/1');
    });

    $('.tab-btn-tuition-fee').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        loadPage(page);
    });

    function loadPage(page) { 
        loading_page('#dataPageLoad');
        var res = page.split("/");
        switch(res[0]) {
            case 'tuition_fee':
                var url = base_url_js+'finance/approved/tuition-fee/approve/'+res[1];
                $.post(url,{page:page},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                    $('#NotificationModal').modal('hide');
                });
                break;
            case 'tuition_fee_approved':
                var url = base_url_js+'finance/approved/tuition-fee/approved/'+res[1];
                $.post(url,{page:page},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                    $('#NotificationModal').modal('hide');
                });
                break;    
            default:
                'code block'
        }
        
    }

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).data("ci-pagination-page");
      loadPage(pageHtml+'/'+page);
      // loadData_register_document(page);
    });

    $(document).on('click','#btn-Save', function () {
        switch(pageHtml)
        {
         case "tuition_fee" :
            process_tuition_fee();
         break;      
         case  "tuition_fee_delete" :
               process_tuition_fee_delete();
         break;
        }
    });

    function process_tuition_fee()
    {
        var arrValueCHK = getValueChecbox();
        if (arrValueCHK.length > 0) {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
                '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal('show');

            $("#confirmYes").click(function(){
                $('#NotificationModal .modal-header').addClass('hide');
                $('#NotificationModal .modal-body').html('<center>' +
                    '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                    '                    <br/>' +
                    '                    Loading Data . . .' +
                    '                </center>');
                $('#NotificationModal .modal-footer').addClass('hide');
                $('#NotificationModal').modal({
                    'backdrop' : 'static',
                    'show' : true
                });

                var inc = 1;
                var url = base_url_js+'finance/approved/tuition-fee/approve_save';
                for (var i = 0; i < arrValueCHK.length; i++) {
                    var data = arrValueCHK[i];
                    var token = jwt_encode(data,"UAP)(*");
                    $.post(url,{token:token},function (data_json) {
                        setTimeout(function () {
                           // toastr.options.fadeOut = 10000;
                           // toastr.success('Data berhasil disimpan', 'Success!');
                           // loadPage('tuition_fee/1');
                           // $('#NotificationModal').modal('hide');
                           // window.open(base_url_js+'fileGet/Tuition_fee.pdf','_blank');
                        },500);
                    }).done(function() {
                      
                    }).fail(function() {
                      toastr.error('The Database connection error, please try again', 'Failed!!');
                    }).always(function() {
                      // $('#NotificationModal').modal('hide');
                    });
                    inc++;
                }

                loadPage('tuition_fee/1');
                // $('#NotificationModal').modal('hide');
                
            })

        }
        else
        {
            toastr.error("Silahkan checked dahulu", 'Failed!!');
        }
        
    }

    function getValueChecbox()
    {
         var allVals = [];
         $('.tableData input[type=checkbox]:checked').each(function() {
            if($(this).val() != 'nothing')
            {
                allVals.push($(this).val());
            }
           
         });
         return allVals;
    }

    $(document).on('click','#dataResultCheckAll', function () {
        $('input.uniform').not(this).prop('checked', this.checked);
    });
    
</script>