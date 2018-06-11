<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo base_url();?>assets/datepicker/datepicker.css" rel="stylesheet" type="text/css"/>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Cicilan</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-cicilan" data-page="cicilan/1">Data</a></li>
                            <!-- <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Edit Deadline</a></li> -->
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
        loadPage('cicilan/1');
    });

    $('.tab-btn-cicilan').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        loadPage(page);
    });

    function loadPage(page) { 
        loading_page('#dataPageLoad');
        var res = page.split("/");
        switch(res[0]) {
            case 'cicilan':
                var url = base_url_js+'admission/proses-calon-mahasiswa/cicilan_data/'+res[1];
                $.post(url,function (data_json) {
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

    $(document).on('click','.btn-Save', function () {
        var a = $(this).attr('ID');
        a = a.split("_");
        a = a[1];
        var idInput = a; 
        loading_button('#btn-Save_'+a);
        a = $("#deadline_"+a).val();
        var ID_register_formulir = $(this).attr('ID_register_formulir');
        var Action = (a == undefined) ? 'create_va' : 'update_va';
        var deadline_payment = (a == undefined) ? '' : a;
        var url = base_url_js+'admission/proses-calon-mahasiswa/submit_edit_deadline_cicilan';

        var data = {
                ID_register_formulir : ID_register_formulir,
                Action : Action,
                deadline_payment : deadline_payment
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               var obj = jQuery.parseJSON(data_json);
               if(obj['status'] == 1)
               {
                toastr.options.fadeOut = 10000;
                toastr.success('Data berhasil disimpan', 'Success!');
                loadPage(pageHtml+'/1');
               }
               else
               {
                toastr.error('Data gagal disimpan', 'Failed!!');
               }
               
            },500);
        }).done(function() {

        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
          
        }).always(function() {
            $('#btn-Save_'+idInput).prop('disabled',false).html('Edit Deadline');
        });
        
    });
    
</script>