<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Data Calon Mahasiswa</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="data-calon-mhs/1">Data</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="data-calon-mhs/1">To Be Mahasiswa</a></li>
                            <!--<li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register Recycled</a></li>-->
                         </ul> 
                        <div id="dataPageLoad" style="margin-top:0px;">
                            
                        </div>
                        <!-- <div  class="col-xs-12" align="right" id="pagination_link"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.pageHtml = '';
    window.temp = '';
    $(document).ready(function () {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('#panel_web').addClass('wrap');
            $('#panel_web').css({"padding": "0px", "padding-right": "20px"});
        }
        loadPage('data-calon-mhs/1');
    });

    $('.tab-btn-submenu-page').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        loadPage(page);
    });

    function loadPage(page) {
        $("#dataPageLoad").empty(); 
        var res = page.split("/");
        switch(res[0]) {
            case 'data-calon-mhs':
                var search = '<div class = "row"><div class="col-xs-2" style="">'+
                                'Nama'+
                                '<input class="form-control" id="SNama" placeholder="All...">'+
                              '</div>'+
                              '<div class="col-xs-2" style="">'+
                                'Sekolah'+
                                '<input class="form-control" id="SSekolah" placeholder="All...">'+
                              '</div>'+
                              '<div class="col-xs-2" style="">'+
                                'Email'+
                                '<input class="form-control" id="SEmail" placeholder="All...">'+
                              '</div>'+
                              '</div>'; 
                    search += '<div class = "row">'+
                                '<div class="col-xs-2" style="">'+
                                  'Bayar Formulir'+
                                  '<select class="select2-select-00 col-md-4 full-width-fix" id="selectBayarFormulir">'+
                                      '<option value = "%">All</option>'+
                                      '<option value = "0">Belum Bayar</option>'+   
                                      '<option value = "1">Sudah Bayar</option>'+
                                  '</select>'+
                                '</div>'+
                                '<div class="col-xs-2" style="">'+
                                'Rangking'+
                                '<select class="select2-select-00 col-md-4 full-width-fix" id="selectRangking">'+
                                    '<option></option>'+
                                '</select>'+
                              '</div>'+
                              '<div class="col-xs-2" style="">'+
                                'Bayar Biaya Kuliah'+
                                '<select class="select2-select-00 col-md-4 full-width-fix" id="selectBKuliah">' +
                                    '<option value = "%">All</option>'+
                                    '<option value = "0">Belum Lunas</option>'+
                                    '<option value = "1">Sudah Lunas</option>'+
                                '</select>'+
                              '</div>'+
                              '<div class="col-xs-2" style="">'+
                                'Prodi'+
                                '<select class="select2-select-00 col-md-4 full-width-fix" id="selectProgramStudy">'+
                                    '<option></option>'+
                                '</select>'+
                              '</div></div><div  class="col-xs-12" align="right" id="pagination_link"></div>';
                        search += '<div class = "row" id = "dataLoadDB">asd</div>';
                $("#dataPageLoad").html(search);
                loading_page('#dataLoadDB');

                var urlS = base_url_js+"api/__getBaseProdiSelectOption";
                $('#selectProgramStudy').empty();
                $.post(urlS,function (data_json) {
                      $('#selectProgramStudy').append('<option value="'+'%'+'" selected>'+'All'+'</option>');
                      for(var i=0;i<data_json.length;i++){
                          var selected = '';
                          //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                          $('#selectProgramStudy').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].NameEng+'</option>');
                      }
                      $('#selectProgramStudy').select2({
                         //allowClear: true
                      });
                }).done(function() {
                    $('#selectRangking').empty();
                    $('#selectRangking').append('<option value="'+'%'+'" '+'selected'+'>'+'All'+'</option>');
                    for(var i=0;i<=10;i++){
                        // var selected = (i==0) ? 'selected' : '';
                        //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                        var Nrangking = i;
                        if(i == 0)
                        {
                            Nrangking = 'No Rangking';
                        }
                        $('#selectRangking').append('<option value="'+i+'" '+''+'>'+Nrangking+'</option>');
                    }

                    autoCompleteSchool($("#SSekolah"));
                    loadDataS(res[1]);
                });
                break;
            default:
                'code block'
        }
        
    }

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).attr("data-ci-pagination-page");
      if (page == null){
          page = 1;
      }
      loadDataS(page);
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

    function autoCompleteSchool(ID)
    {
        ID.autocomplete({
          minLength: 4,
          select: function (event, ui) {
            event.preventDefault();
            var selectedObj = ui.item;
            ID.val(selectedObj.label);
            temp = selectedObj.value;
            loadPage('data-calon-mhs/1');
          },
          source:
          function(req, add)
          {
            loadingStart();
            var url = base_url_js+'api/__getAutoCompleteSchool';
            var School = ID.val();
            var data = {
                        School : School,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // var obj = JSON.parse(data_json);
                add(data_json.message); 
                loadingEnd(1000);
            })
          } 
        })
    }

    $(document).on("keyup", "#SEmail", function(event){
        var nama = $('#SEmail').val();
        var n = nama.length;
        if( this.value.length < 3 && this.value.length != 0 ) return;
           /* code to run below */
         loadDataS(1);
      
     });

    $(document).on("keyup", "#SNama", function(event){
        var nama = $('#SNama').val();
        var n = nama.length;
        if( this.value.length < 3 && this.value.length != 0 ) return;
           /* code to run below */
         loadDataS(1);
      
     });

    $(document).on('change','#selectBayarFormulir', function () {
        loadDataS(1);
    });

    $(document).on('change','#selectRangking', function () {
        loadDataS(1);
    });

    $(document).on('change','#selectBKuliah', function () {
        loadDataS(1);
    });

    $(document).on('change','#selectProgramStudy', function () {
        loadDataS(1);
    });

    function loadDataS(page)
    {
        var url = base_url_js+'admission/proses-calon-mahasiswa/data-calon-mhs/'+page;
        var data = {
                Nama : $("#SNama").val().trim(),
                Sekolah : temp,
                Email : $("#SEmail").val().trim(),
                BFormulir : $("#selectBayarFormulir").val(),
                Ranking : $("#selectRangking").val(),
                BKuliah : $("#selectBKuliah").val(),
                Prody : $('#selectProgramStudy').val(),
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            var obj = jQuery.parseJSON(data_json);
            $("#dataLoadDB").html(obj.loadtable);
            $("#pagination_link").html(obj.pagination_link);
            pageHtml = 'data-calon-mhs';
        });
    }

    $(document).on('click','.btn-show', function () {
        var ID_register_formulir = $(this).attr('id-register-formulir');
        var Email = $(this).attr('email');
        var Nama = $(this).attr('nama');
        var url = base_url_js+"api/__getDocument";
        var data = {
            ID_register_formulir : ID_register_formulir,
            Email : Email,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (json) {
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Document '+Nama+'</h4>');
            var table = '';
            table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
            '<thead>'+
                '<tr>'+
                    '<th style="width: 5px;">No</th>'+
                    '<th style="width: 55px;">Dokumen</th>'+
                    '<th style="width: 55px;">Required</th>'+
                    '<th style="width: 55px;">Attachment</th>'+
                    '<th style="width: 55px;">Status</th>';
              
            table += '</tr>' ;  
            table += '</thead>' ; 
            table += '<tbody>' ;
            for (var i =0; i < json.length; i++) {
              table += '<tr>'+
                          '<td>'+ (i+1)+'</td>'+
                          '<td>'+json[i]['DocumentChecklist'] +'</td>'+
                          '<td>'+json[i]['Required'] +'</td>'+
                          '<td>'+'<a href = "<?php echo url_registration ?>document/'+Email+'/'+json[i]['Attachment']+'" target="_blank">File</a></td>'+
                          '<td>'+json[i]['Status'] +'</td>'
                          ; 
            }
             
            table += '</tbody>' ; 
            table += '</table>' ;
            var footer = '<div class="col-sm-12" id="BtnFooter">'+
                            '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                          '</div>';
            $('#GlobalModal .modal-body').html(table);
            $('#GlobalModal .modal-footer').html(footer);
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    });

    $(document).on('click','.Detail', function () {
        var ID_register_formulir = $(this).attr('id-register-formulir');
        var Nama = $(this).attr('nama');
        var url = base_url_js+"admission/detailPayment";
        var data = {
            ID_register_formulir : ID_register_formulir,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (data) {
            var json = jQuery.parseJSON(data);
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Detail Payment '+Nama+'</h4>');

            var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">' +
            '<thead>'+
                '<tr>'+
                    '<th style="width: 5px;">No</th>'+
                    '<th style="width: 55px;">Type</th>'+
                    '<th style="width: 55px;">Discount</th>'+
                    '<th style="width: 55px;">Value</th>'+
                    '<th style="width: 55px;">Status</th>';
              
            table += '</tr>' ;  
            table += '</thead>' ; 
            table += '<tbody>' ;
            var payment_register = json.payment_register;
            for (var i =0; i < payment_register.length; i++) {
              table += '<tr>'+
                          '<td>'+ (i+1)+'</td>'+
                          '<td>'+payment_register[i]['Description'] +'</td>'+
                          '<td>'+payment_register[i]['Discount'] +'</td>'+
                          '<td>'+payment_register[i]['Pay_tuition_fee'] +'</td>'+
                          '<td>'+payment_register[i]['Status'] +'</td>'+
                        '<tr>'  
                          ; 
            }

            table += '</tbody>' ; 
            table += '</table>' ;

            var table2 = '';
            table2 = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
            '<thead>'+
                '<tr>'+
                    '<th style="width: 5px;">No</th>'+
                    '<th style="width: 55px;">Cicilan ke</th>'+
                    '<th style="width: 55px;">BilingID</th>'+
                    '<th style="width: 55px;">Invoice</th>'+
                    '<th style="width: 55px;">Status</th>'+
                    '<th style="width: 55px;">Deadline</th>';
            table2 += '</tr>' ;  
            table2 += '</thead>' ; 
            table2 += '<tbody>' ;

            var payment_pre = json.payment_pre;
            for (var i =0; i < payment_pre.length; i++) {
              table2 += '<tr>'+
                          '<td>'+ (i+1)+'</td>'+
                          '<td>'+ 'Cicilan ke '+(i+1)+'</td>'+
                          '<td>'+payment_pre[i]['BilingID'] +'</td>'+
                          '<td>'+payment_pre[i]['Invoice'] +'</td>'+
                          '<td>'+payment_pre[i]['Status'] +'</td>'+
                          '<td>'+payment_pre[i]['Deadline'] +'</td>'+
                        '<tr>'   
                          ; 
            }

            table2 += '</tbody>' ; 
            table2 += '</table>' ;        

            var footer = '<div class="col-sm-12" id="BtnFooter">'+
                            '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                          '</div>';
            $('#GlobalModal .modal-body').html(table+table2);
            $('#GlobalModal .modal-footer').html(footer);
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    });
    
</script>