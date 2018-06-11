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
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee/1">Input Tuition Fee</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Delete Tuition Fee</a></li>
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
                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/input/'+res[1];
                $.post(url,{page:page},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                });
                break;
            case 'tuition_fee_delete':
                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/delete/'+res[1];
                $.post(url,{page:page},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                });
                break;
            case 'tuition_fee_approved':
                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/approved/'+res[1];
                $.post(url,{page:page},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
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

    function process_tuition_fee_delete()
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

                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/delete_data';
                var data = arrValueCHK;
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (data_json) {
                    setTimeout(function () {
                       toastr.options.fadeOut = 10000;
                       toastr.success('Data berhasil disimpan', 'Success!');
                       loadPage('tuition_fee_delete/1');
                       $('#NotificationModal').modal('hide');
                    },500);
                }).done(function() {
                  
                }).fail(function() {
                  toastr.error('The Database connection error, please try again', 'Failed!!');
                }).always(function() {
                  $('#NotificationModal').modal('hide');
                });
            })

        }
        else
        {
            toastr.error("Silahkan checked dahulu", 'Failed!!');
        }
        
    }

    function process_tuition_fee()
    {
        arrText = [];
        arrPOST = [];
        $('.getDom').each(function(){
            var arrTemp = [];
            var id_formulir = $(this).attr('id-formulir');
            var payment_type = $(this).attr('payment-type');
            var payment_type_ID = $(this).attr('payment-type_ID');
            var valuee = $(this).val();
            arrTemp = {
                    id_formulir : id_formulir,
                    payment_type : payment_type,
                    payment_type_ID : payment_type_ID,
                    valuee : valuee
            };
            arrText.push(arrTemp);
        })

        var arrValueCHK = getValueChecbox();
        for (var i = 0; i < arrValueCHK.length; i++) {
            // console.log(arrValueCHK[i]);
            for (var j = 0; j < arrText.length; j++) {
               for (var k = 0; k < arrText.length; k++) {
                   if (arrValueCHK[i] == arrText[j]['id_formulir']) {
                        if (j != k) {
                            // console.log(arrText[j]['id_formulir']  + ' : ' + arrText[k]['id_formulir']);
                            if (arrText[j]['id_formulir'] == arrText[k]['id_formulir']) {
                                if (arrText[j]['payment_type_ID'] == arrText[k]['payment_type_ID']) {
                                    if (arrText[j]['payment_type'] != arrText[k]['payment_type']) {
                                        var arrTemp2 = [];
                                        arrTemp2 = {
                                             PTID : arrText[j]['payment_type_ID'],
                                             ID_register_formulir : arrText[j]['id_formulir'],
                                             Discount :  arrText[k]['valuee'],
                                             Pay_tuition_fee :   arrText[j]['valuee']
                                        };
                                        arrPOST.push(arrTemp2);
                                        j++;
                                        break; 
                                    }
                                }
                                
                            }
                        }
                        
                   }
               }
            }
        }

        // console.log(arrPOST);
        if (arrPOST.length > 0) {
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

                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/save';
                var data = arrPOST;
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (data_json) {
                    setTimeout(function () {
                       toastr.options.fadeOut = 10000;
                       toastr.success('Data berhasil disimpan', 'Success!');
                       loadPage('tuition_fee/1');
                       $('#NotificationModal').modal('hide');
                    },500);
                }).done(function() {
                  
                }).fail(function() {
                  toastr.error('The Database connection error, please try again', 'Failed!!');
                }).always(function() {
                  $('#NotificationModal').modal('hide');
                });
            })
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