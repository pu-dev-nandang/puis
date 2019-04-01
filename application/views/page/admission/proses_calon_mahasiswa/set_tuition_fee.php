<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
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
                        <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
                          <div class="col-md-6 col-md-offset-3">
                            <div class="thumbnail" style="height: 80px">
                              <div class="col-md-6 col-md-offset-3">
                                <label>Formulir Code</label>
                                <input type="text" name="FormulirCode" id = "FormulirCode" class="form-control" placeholder="All...">
                              </div>  
                            </div>   
                          </div>
                        </div>
                        <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
                          <div id="dataPageLoad" style="margin-top:0px;">
                              
                          </div>
                        </div> 
                        <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
                            <div  class="col-md-12" align="right" id="pagination_link"></div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.pageHtml = '';
    window.max_cicilan = 4;
    $(document).ready(function () {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('#panel_web').addClass('wrap');
            $('#panel_web').css({"padding": "0px", "padding-right": "20px"});
        }
        loadPage('tuition_fee/1');

        FuncSearch();

    });

    function FuncSearch()
    {
      $("#FormulirCode").keyup(function(){
        if( this.value.length < 5 && this.value.length != 0 ) return;
           /* code to run below */
         //loadPage(pageHtml+'/1'); 
         // find page
             var ee = $('#panel_web').find('li[class="active"]');
             var page = ee.find('a').attr('data-page');
             loadPage(page)
      })
    }

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
                var FormulirCode = $("#FormulirCode").val();
                var data = {
                    FormulirCode : FormulirCode,
                };
                var token = jwt_encode(data,"UAP)(*"); 
                $.post(url,{page:page,token:token},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                });
                break;
            case 'tuition_fee_delete':
                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/delete/'+res[1];
                var FormulirCode = $("#FormulirCode").val();
                var data = {
                    FormulirCode : FormulirCode,
                };
                var token = jwt_encode(data,"UAP)(*"); 
                $.post(url,{page:page,token:token},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                });
                break;
            case 'tuition_fee_approved':
                var url = base_url_js+'admission/proses-calon-mahasiswa/set_tuition_fee/approved/'+res[1];
                var FormulirCode = $("#FormulirCode").val();
                var data = {
                    FormulirCode : FormulirCode,
                };
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{page:page,token:token},function (data_json) {
                    var obj = jQuery.parseJSON(data_json);
                    $("#dataPageLoad").html(obj.loadtable);
                    $("#pagination_link").html(obj.pagination_link);
                });
                break;    
            default:
                'code block'
        }
        $(".widget_delete").remove();
        
    }

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).attr("data-ci-pagination-page");
      if (page == null){
          page = 1;
      }
      loadPage(pageHtml+'/'+page);
      // loadData_register_document(page);
    });

    $(document).on('click','#btn-Save', function () {
        switch(pageHtml)
        {
         case "tuition_fee" :
            //process_tuition_fee();
         break;      
         case  "tuition_fee_delete" :
               process_tuition_fee_delete();
         break;
        }
    });

    // $(document).on("change", ".getBeasiswa", function(event){
    //     var fillitem = $(this).closest('tr');
    //     var fillTD = $(this).closest('td');
    //     if ($(this).val() ==  5) {
    //         var ID_register_formulir = fillitem.find('td:eq(0)').find('.uniform').val();
    //         var Email = fillitem.find('td:eq(0)').find('.uniform').attr('email');
    //         var Nama = fillitem.find('td:eq(0)').find('.uniform').attr('nama');
    //         fillTD.append('<button class = "btn btn-primary btn-show" id-register-formulir = "'+ID_register_formulir+'" email = "'+Email+'" nama = "'+Nama+'" >Doc & Exam</button>');
    //     }
    //     else
    //     {
    //         fillTD.find('.btn-show').remove();
    //     }
        
    // });

    // $(document).on('click','.btn-show', function () {
    //     var ID_register_formulir = $(this).attr('id-register-formulir');
    //     var Email = $(this).attr('email');
    //     var Nama = $(this).attr('nama');
    //     var url = base_url_js+"api/__getDocument2";
    //     var data = {
    //         ID_register_formulir : ID_register_formulir,
    //         Email : Email,
    //     };
    //     var token = jwt_encode(data,"UAP)(*");
    //     $.post(url,{ token:token }, function (json) {
    //         var doc = json['doc'];
    //         $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Document '+Nama+'</h4>');
    //         var html = '<div class = "row">'+
    //                       '<div class ="col-md-12">';
    //         var table = '';
    //         table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
    //         '<thead>'+
    //             '<tr>'+
    //                 '<th style="width: 5px;">No</th>'+
    //                 '<th style="width: 55px;">Dokumen</th>'+
    //                 '<th style="width: 55px;">Required</th>'+
    //                 '<th style="width: 55px;">Attachment</th>'+
    //                 '<th style="width: 55px;">Status</th>';
              
    //         table += '</tr>' ;  
    //         table += '</thead>' ; 
    //         table += '<tbody>' ;
    //         for (var i =0; i < doc.length; i++) {
    //           table += '<tr>'+
    //                       '<td>'+ (i+1)+'</td>'+
    //                       '<td>'+doc[i]['DocumentChecklist'] +'</td>'+
    //                       '<td>'+doc[i]['Required'] +'</td>'+
    //                       // '<td>'+'<a href = "<?php echo url_registration ?>document/'+Email+'/'+json[i]['Attachment']+'" target="_blank">File</a></td>'+
    //                       '<td>'+'<a href="javascript:void(0)" class="show_a_href" id = "show'+Email+'" filee = "'+doc[i]['Attachment']+'" Email = "'+Email+'">File</a></td>'+
    //                       '<td>'+doc[i]['Status'] +'</td>'
    //                       ; 
    //         }
             
    //         table += '</tbody>' ; 
    //         table += '</table>' ;
    //         html += table;
    //         html += '</div></div>'; // end document

    //         var ujian = json['ujian'];
    //         if (ujian.length > 0) {
    //           var kelulusan = json['kelulusan'];
    //           html += '<div class= "row" style = "margin-top ; 5px">'+
    //                     '<div class = "col- md-12">'+
    //                       '<h5>Exam Result</h5>'+
    //                       '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
    //                                   '<thead>'+
    //                                       '<tr>'+
    //                                           '<th style="width: 5px;">No</th>'+
    //                                           '<th style="width: 55px;">Exam Name</th>'+
    //                                           '<th style="width: 55px;">Bobot</th>'+
    //                                           '<th style="width: 55px;">Value</th>';
    //           html += '</tr>' ;  
    //           html += '</thead>' ; 
    //           html += '<tbody>' ;
              
    //           var jmlbobot = 0;
    //           var Nilai_bobot = 0;
    //           var nilai = 0;
    //           for (var i =0; i < ujian.length; i++) {
    //             html += '<tr>'+
    //                         '<td>'+ (i+1)+'</td>'+
    //                         '<td>'+ujian[i]['NamaUjian'] +'</td>'+
    //                         '<td>'+ujian[i]['Bobot'] +'</td>'+
    //                         '<td>'+ujian[i]['Value'] +'</td>'; 

    //             jmlbobot = parseInt(jmlbobot)  + parseInt(ujian[i]['Bobot']);
    //             Nilai_bobot = parseInt(Nilai_bobot) + ( (parseInt(ujian[i]['Value']) * parseInt(ujian[i]['Bobot']) ) )
    //           }
              
    //            nilai = parseInt(Nilai_bobot) / parseInt(jmlbobot); 

    //           html += '</tbody>' ; 
    //           html += '</table>' ;

    //           html += '<p>Jumlah Bobot : '+jmlbobot+'</p>'+
    //                   '<p>Nilai * Bobot : '+Nilai_bobot+'</p>'+
    //                   '<p>Nilai Akhir : '+nilai+'</p>'+
    //                   '<p>Status : '+kelulusan[0]['Kelulusan']+'</p>';

    //         }

    //         var footer = '<div class="col-sm-12" id="BtnFooter">'+
    //                         '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
    //                       '</div>';
    //         $('#GlobalModal .modal-body').html(html);
    //         $('#GlobalModal .modal-footer').html(footer);
    //         $('#GlobalModal').modal({
    //             'show' : true,
    //             'backdrop' : 'static'
    //         });
    //     })
    // });

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
                       $(".widget_delete").remove();
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


    // event cheklist
    $(document).on('change','.tableData input[type=checkbox]', function () {
        var Uniformvaluee = $(this).val();
        switch(pageHtml)
        {
         case "tuition_fee" :
            var Uniformvaluee = $(this).val();
            if(this.checked) {
                $('.getDom[id-formulir="'+Uniformvaluee+'"]').prop('disabled', true);
                var Nama = $(this).attr('nama');
                if (Uniformvaluee != 'nothing') {
                    // get data to make dom
                        var arrcheklist = [];
                    $('.getDom').each(function(){
                        var arrTemp = [];
                        var id_formulir = $(this).attr('id-formulir');
                        var payment_type = $(this).attr('payment-type');
                        var payment_type_ID = $(this).attr('payment-type_ID');
                        var valuee = $(this).val();
                        var getBeasiswa = $('#getBeasiswa'+Uniformvaluee).val();
                        var getDokumen = $('#getDokumen'+Uniformvaluee).val();
                        var ket = $('#ket'+Uniformvaluee).val();
                        var pt = payment_type.split('-');
                        if (pt.length == 1) {
                            var Discount = $("#"+payment_type+Uniformvaluee).val();
                            if (id_formulir == Uniformvaluee) {
                                arrTemp = {
                                        id_formulir : id_formulir,
                                        payment_type : payment_type,
                                        payment_type_ID : payment_type_ID,
                                        valuee : valuee,
                                        getBeasiswa : getBeasiswa,
                                        getDokumen : getDokumen,
                                        ket : ket,
                                        Nama : Nama,
                                        Discount : Discount
                                };
                                arrcheklist.push(arrTemp);
                            }
                        }
                        
                    })
                    console.log(arrcheklist); 
                    var arrResult =  generateArr(arrcheklist);
                    domHTMLCicilan(arrResult);  
                }
                
            }
            else
            {
                $(".widget_"+Uniformvaluee).remove();
                $('.getDom').prop('disabled', false);
            }
         break;
         case  "tuition_fee_approved" :      
         case  "tuition_fee_delete" :
            var Nama = $(this).attr('nama');
            if(this.checked) {
               var url = base_url_js + "get_detail_cicilan_fee_admisi";
               var data = {
                   ID_register_formulir : Uniformvaluee,
               }
               var token = jwt_encode(data,"UAP)(*");
               $.post(url,{token:token},function (data_json) {
                   // jsonData = data_json;
                   var obj = JSON.parse(data_json);
                   console.log(obj);
                   var bbb = '';
                   for (var i = 0; i < obj.length; i++) {
                       bbb += '<tr>'+
                                 '<td>'+ (parseInt(i)+1) + '</td>'+
                                 '<td>'+ formatRupiah(obj[i]['Invoice']) + '</td>'+
                                 '<td>'+ obj[i]['Deadline']+'</td>'+
                               '</tr>';  
                   }
                   var aaa = '<!--<div class = "row">-->'+
                                '<div id = "tblData" class="table-responsive">'+
                                    '<table class="table table-striped table-bordered table-hover table-checkable">'+
                                    '<thead>'+
                                      '<tr>'+
                                        '<th style="width: 5px;">Cicilan ke </th>'+
                                        '<th style="width: 5px;">Invoice </th>'+
                                        '<th style="width: 5px;">Deadline </th>'+
                                       '<tr>'+ 
                                    '</thead>'+
                                    '<tbody>'+
                                    bbb+
                                    '</tbody>'+'</table></div>'+
                             '<!--</div>-->';

                   var html = '<div class="widget box widget_'+Uniformvaluee+' widget_delete">'+
                       '<div class="widget-header">'+
                           '<h4 class="header"><i class="icon-reorder"></i> Detail Payment '+Nama+'</h4>'+
                       '</div>'+
                       '<div class="widget-content">'+
                           aaa
                       '</div>'+
                   '</div>';
                   $(".formAddFormKD").append(html);
                   $('html, body').animate({ scrollTop: $(".widget_"+Uniformvaluee).offset().top }, 'slow');
               }).done(function() {
                 
               }).fail(function() {
                
                 toastr.error('The Database connection error, please try again', 'Failed!!');
               }).always(function() {
                
               });
            }
            else
            {
                $(".widget_"+Uniformvaluee).remove();
            }   
         break;
        }
        
               
    });

    function generateArr(data)
    {
        var arrText = [];
        for (var i = 0; i < data.length; i++) {
            var arrTemp = {};
            arrTemp['id_formulir'] = data[i]['id_formulir'];
            arrTemp['Discount-'+data[i]['payment_type']] = data[i]['Discount'];
            arrTemp[data[i]['payment_type']] = data[i]['valuee'];
            arrTemp['getDokumen'] = data[i]['getDokumen'];
            arrTemp['getBeasiswa'] = data[i]['getBeasiswa'];
            arrTemp['ket'] = data[i]['ket'];
            arrTemp['Nama'] = data[i]['Nama'];
            for (var j = i + 1; j < data.length; j++) {
                if (data[i]['id_formulir'] == data[j]['id_formulir']) {
                    arrTemp[data[j]['payment_type']] = data[j]['valuee'];
                    arrTemp['Discount-'+data[j]['payment_type']] = data[j]['Discount'];
                    i++;
                }
            }

            arrText.push(arrTemp);

        }

        //console.log(arrText);
        return arrText;
    }

    function domHTMLCicilan(data)
    {
        console.log(data);
        //console.log(payment_type);
        //console.log(max_cicilan);
        max_cicilanString = max_cicilan[0]['max_cicilan'];
        var myJSON = jwt_encode(data,'UAP)(*');
        //var token = jwt_encode(data,'UAP)(*');
        var sss = '<select class = "full-width-fix jml_cicilan" data = "'+myJSON+'">'+
                           ' <option value = "" disabled selected>--Pilih Jumlah Cicilan--</option>';
        for (var l = 1; l <= max_cicilanString; l++) {
            sss += ' <option value = "'+l+'">'+l+'</option>'
        }

        sss += '</select>';   

        var aaa = '<div class = "row">'+
                '<div class="form-group">'+
                    '<label class="col-md-1 control-label">Set Payment</label>'+  
                    '<div class = "col-md-2">'+
                       sss+
                    '</div>'+  
                '</div>'+    
            '</div><br>'+
            '<div class = "row" id="pageSetCicilan'+data[0]['id_formulir']+'">'+
            '</div>'

        var html = '<div class="widget box widget_'+data[0]['id_formulir']+' widget_delete">'+
            '<div class="widget-header">'+
                '<h4 class="header"><i class="icon-reorder"></i> Set Payment '+data[0]['Nama']+'</h4>'+
            '</div>'+
            '<div class="widget-content">'+
                aaa
            '</div>'+
        '</div>';
        $(".formAddFormKD").append(html);
        $('html, body').animate({ scrollTop: $(".widget_"+data[0]['id_formulir']).offset().top }, 'slow');
    }

    $(document).on('change','.jml_cicilan', function () {
        var data = $(this).attr('data');
        data = jwt_decode(data,'UAP)(*');
        //console.log(data);
         $("#btn-div"+data[0]['id_formulir']).remove();
        // get all invoice
        var get_Invoice = 0;
        for (var i = 0; i < payment_type.length; i++) {
            var x = data[0][payment_type[i].Abbreviation];
            x = findAndReplace(x, ',00', '');
            x = findAndReplace(x, '.', '');
            get_Invoice = parseInt(get_Invoice)+ parseInt(x);
        }

        console.log(get_Invoice);

        var setMinimal = 500000;
        var Val = $(this).val();
        var splitcicilan = parseInt(get_Invoice) / parseInt(Val);
        var splitcicilan = parseInt(splitcicilan);
        if (Val != " " && Val != null ) 
        {
            var input = '<div class="form-group">';
            var cost = 0;
            var cost_value = splitcicilan;
           for (var i = 1 ; i <= Val; i++) {
                if (i == Val) {
                    cost_value = parseInt(get_Invoice) - parseInt(cost);
                }
                input += '<label class="col-md-1 control-label">Cicilan '+i+'</label><div class="col-md-2"><input type="text" id = "cost'+data[0]['id_formulir']+'_'+i+'" value = "'+cost_value+'" class = "form-control costInput2'+'_'+data[0]['id_formulir']+'"><br>Deadline<div id="datetimepicker'+data[0]['id_formulir']+'_'+i+'" class="input-group input-append date datetimepicker">'+
                            '<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control" id="datetime_deadline'+data[0]['id_formulir']+'_'+i+'" type="text"></input>'+
                            '<span class="input-group-addon add-on">'+
                              '<i data-time-icon="icon-time" data-date-icon="icon-calendar">'+
                              '</i>'+
                            '</span>'+
                        '</div></div>';
                cost = cost + cost_value;
            }

            input += '</div>';

           $("#pageSetCicilan"+data[0]['id_formulir']).html(input);

           var dataParsing = jwt_encode(data,'UAP)(*');
           $("#pageSetCicilan"+data[0]['id_formulir']).after('<br><div class = "col-md-12" align = "right" id="btn-div'+data[0]['id_formulir']+'"><button class="btn btn-success btn-notification btn-Save" id="btn-Save'+data[0]['id_formulir']+'" data = "'+dataParsing+'"><i class="icon-pencil icon-white"></i> Submit</button></div>');

           $('.costInput2'+'_'+data[0]['id_formulir']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
           $('.costInput2'+'_'+data[0]['id_formulir']).maskMoney('mask', '9894');

           Date.prototype.addDays = function(days) {
               var date = new Date(this.valueOf());
               date.setDate(date.getDate() + days);
               return date;
           }
           var date = new Date();
           for (var i = 1 ; i <= Val; i++) {
              $('#datetimepicker'+data[0]['id_formulir']+'_'+i).datetimepicker({
               // startDate: today,
               // startDate: '+2d',
               startDate: date.addDays(i),
              });

              $('#datetime_deadline'+data[0]['id_formulir']+'_'+i).prop('readonly',true);
           } 
           
        }

        $(".costInput2"+'_'+data[0]['id_formulir']).keyup(function(){
            var arrTemp = [];
            var setMinimal = 500000;
            $('.costInput2'+'_'+data[0]['id_formulir']).each(function(){
                var temp = findAndReplace($(this).val(), ".","");
                /*if (temp < setMinimal) {
                    $(this).val(setMinimal);
                }*/
                var arr2 = {
                    id : $(this).attr('id'),
                    valuee : $(this).val(),
                }
                arrTemp.push(arr2);
            })

            var arrValue = [];
            var count = 0;
            for (var i = 0; i < arrTemp.length; i++) {
                var temp = findAndReplace(arrTemp[i]['valuee'], ".","");
                if (count > get_Invoice) {
                    var splitcicilan = parseInt(get_Invoice) / parseInt(arrTemp.length);
                    var splitcicilan = (splitcicilan < setMinimal) ? setMinimal : parseInt(splitcicilan);
                    var cost = 0;
                    var cost_value = splitcicilan;
                    for (var j = 0; j < arrTemp.length; j++) {

                       if (j == (arrTemp.length - 1)) {
                           cost_value = parseInt(get_Invoice) - parseInt(cost);
                       }
                       var getID = parseInt(j) + 1;
                       $("#cost"+data[0]['id_formulir']+'_'+getID).val(cost_value);
                       cost = cost + cost_value; 
                    }
                }
                else
                {
                    if ((arrTemp.length - 1) == i) {
                        var getID = parseInt(i) + 1;
                        $("#cost"+data[0]['id_formulir']+'_'+getID).val(parseInt(get_Invoice) - parseInt(count));
                    }
                }
                count += parseInt(temp);
            }
            $('.costInput2'+'_'+data[0]['id_formulir']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
            $('.costInput2'+'_'+data[0]['id_formulir']).maskMoney('mask', '9894');
        });


        $('#btn-Save'+data[0]['id_formulir']).click(function(){
            var dataa = $(this).attr('data');
            dataa =  jwt_decode(dataa,'UAP)(*');
            loading_button('#btn-Save'+dataa[0]['id_formulir']);
            var arrTemp = [];
            $('.costInput2'+'_'+dataa[0]['id_formulir']).each(function(){
                var valuee = $(this).val();
                for(i = 0; i <valuee.length; i++) {
                 
                 valuee = valuee.replace(".", "");
                 
                }

                var SID = $(this).attr('id');
                var SID = SID.substr(SID.length - 1); // => "1"
                var Deadline = $("#datetime_deadline"+dataa[0]['id_formulir']+'_'+SID).val();
                data = {
                  Deadline : Deadline,
                  Payment  : valuee,
                }
                arrTemp.push(data);
            })

            // check cicilan != 0 dan Deadline is empty
            var bool = true;
            var msg = '';
            for (var i = 0; i < arrTemp.length; i++) {
              if (arrTemp[i].Payment == 0) {
                msg = 'Price Cicilan tidak boleh 0';
                bool = false
                break;
              }

              if (arrTemp[i].Deadline == "") {
                msg = 'Deadline belum diisi';
                bool = false
                break;
              }  
            }
              /*var startDate = moment("28.04.2016", "DD.MM.YYYY");
              var endDate = moment("26.04.2016", "DD.MM.YYYY");

              var result = 'Diff: ' + endDate.diff(startDate, 'days');
              console.log(result);*/

            if (bool) {
              console.log(arrTemp);
              // hitung tanggal tidak boleh melewati cicilan sebelumnya
                var bool2 = true;
                for (var i = 0; i < arrTemp.length; i++) {
                  var date1 = arrTemp[i].Deadline;
                  date1 = date1.substring(0, 10);
                   for (var j = 0; j < arrTemp.length; j++) {
                    if (i < j) {
                       var date2 = arrTemp[j].Deadline;
                       date2 = date2.substring(0, 10);

                       var startDate = moment(date1, "YYYY-MM-DD");
                       var endDate = moment(date2, "YYYY-MM-DD");
                       var result = endDate.diff(startDate, 'days');
                       result = parseInt(result);
                       console.log(result);
                       if (result <= 0) {
                        bool2 = false;
                        console.log('i ' + date1 + '< j : ' + date2);
                        break;
                       } 
                    }
                    
                   }

                   if (!bool2) {
                      break;
                      console.log('i < j');
                   }

                }
              // hitung tanggal tidak boleh melewati cicilan sebelumnya

              if (bool2) {
                var url = base_url_js + "admission/proses-calon-mahasiswa/set_input_tuition_fee_submit";
                var data = {
                    data1 : arrTemp,
                    data2 : dataa
                }
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (data_json) {
                    // jsonData = data_json;
                    var obj = JSON.parse(data_json); 
                    if(obj != ''){
                        $('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');  
                        toastr.error(obj, 'Failed!!');
                    }
                    else
                    {
                        $(".widget_"+dataa[0]['id_formulir']).remove();
                        $('.uniform[value="'+dataa[0]['id_formulir']+'"]').remove();
                        toastr.success('Data berhasil disimpan', 'Success!');
                        $('tr[id="id_formulir'+dataa[0]['id_formulir']+'"]').remove();
                    }

                }).done(function() {
                  // $('#btn-Save').prop('disabled',false).html('Submit');
                }).fail(function() {
                  $('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');  
                  toastr.error('The Database connection error, please try again', 'Failed!!');
                }).always(function() {
                 $('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');
                });

                //$('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');  
              } else {
                toastr.error('Tanggal Deadline cicilan tidak boleh mendahului tanggal cicilan sebelumnya', 'Failed!!');
                $('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');
              }

            } else {
              toastr.error(msg, 'Failed!!');
              $('#btn-Save'+dataa[0]['id_formulir']).prop('disabled',false).html('Submit');
            }

        });
    });

    $(document).on('click','#dataResultCheckAll', function () {
        $('input.uniform').not(this).prop('checked', this.checked);
    });

    $(document).on('change','.getDokumen', function () {
        var id_formulir = $(this).attr('id-formulir');
        var file__ = $(this).find(':selected').text();
        $("#show"+id_formulir).attr('filee',file__);
    });

    $(document).on('click','.show_a_href', function () {
        var file__  = $(this).attr('filee');
        var aaa = file__.split(",");
        if (aaa.length > 0) {
            var emaiil = $(this).attr('Email');
            for (var i = 0; i < aaa.length; i++) {
                window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+aaa[i],'_blank');
            }
            
        }
        else
        {
            window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+file__,'_blank');
        }
        
    });

    $(document).on('click','.showModal', function () {
      var ID_register_formulir = $(this).attr('id-register-formulir');
      var html = '';
      var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 5px;">No</th>'+
                            '<th style="width: 55px;">Note</th>'+
                            '<th style="width: 55px;">Rev By</th>'+
                            '<th style="width: 55px;">Rev At</th>';
      table += '</tr>' ;  
      table += '</thead>' ; 
      table += '<tbody>' ;

      var url = base_url_js+'finance/getRevision_detail_admission';
      var data = {
          ID_register_formulir : ID_register_formulir,
      };
      var token = jwt_encode(data,'UAP)(*');
      $.post(url,{token:token},function (resultJson) {
         var DetailArr = jQuery.parseJSON(resultJson);
         
         var isi = '';
         for (var j = 0; j < DetailArr.length; j++) {
           isi += '<tr>'+
                   '<td>'+DetailArr[j]['RevNo'] + '</td>'+
                   '<td>'+DetailArr[j]['Note'] + '</td>'+
                   '<td>'+DetailArr[j]['Name'] + '</td>'+
                   '<td>'+DetailArr[j]['RevAt'] + '</td>'+
                '<tr>'; 
         }

         table += isi+'</tbody>' ; 
         table += '</table>' ;

         html += table;

         var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
             '';

         $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Revision</h4>');
         $('#GlobalModalLarge .modal-body').html(html);
         $('#GlobalModalLarge .modal-footer').html(footer);
         $('#GlobalModalLarge').modal({
             'show' : true,
             'backdrop' : 'static'
         });   

      }).fail(function() {
        toastr.info('No Action...'); 
        // toastr.error('The Database connection error, please try again', 'Failed!!');
      }).always(function() {

      });

    });
    
</script>
