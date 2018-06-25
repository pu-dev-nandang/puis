
<div class="row" style="margin-top: 30px;">
    <div class="col-md-4">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectCurriculum">
                <option selected disabled>--- Curriculum ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectProdi">
                <option selected value = ''>--- All ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectPTID">
                <option selected disabled>--- Payment Type ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <table class="table table-bordered datatable2 hide" id = "datatable2">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="width: 3%;"><input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll"></th>
                <th style="width: 12%;">Program Study</th>
                <th style="width: 10%;">Semester</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 5%;">NPM</th>
                <th style="width: 5%;">Year</th>
                <th style="width: 5%;">Foto</th>
                <th style="width: 15%;">Email PU</th>
                <th style="width: 5%;">No HP</th>
                <th style="width: 5%;">Discount</th>
                <th style="width: 20%;">Invoice</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
    <div  class="col-xs-12" align="right" id="pagination_link"></div>
    <div  class="col-xs-12" align="right"><button class="btn btn-inverse btn-notification btn-submit hide" id="btn-submit">Submit</button></div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum('#selectCurriculum','');
        loadSelectOptionBaseProdi('#selectProdi','');
        loadSelectOptionPaymentTypeMHS('#selectPTID','');
        // $("#btn-submit").addClass('hide');
    });

    $('#selectCurriculum').change(function () {
        loadData(1);
    });

    $('#selectProdi').change(function () {
        loadData(1);
    });

    $('#selectPTID').change(function () {
        loadData(1);
    });

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).attr("data-ci-pagination-page");
      if (page == null){
          page = 1;
      }
      loadData(page);
      // loadData_register_document(page);
    });

    function loadData(page) {
        $("#btn-submit").addClass('hide');
        $("#datatable2").addClass('hide');

        $('#dataResultCheckAll').prop('checked', false); // Unchecks it
        $("span").removeClass('checked');

        var ta = $('#selectCurriculum').val();
        var prodi = $('#selectProdi').val();
        var PTID = $('#selectPTID').val();
        if(ta!='' && ta!=null && PTID !='' && PTID != null){
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
            $('#dataRow').html('');
            var url = base_url_js+'finance/get_tagihan_mhs/'+page;
            var data = {
                ta : ta,
                prodi : prodi,
                PTID  : PTID
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
                var Data_mhs = resultJson.loadtable;
                var xx = resultJson.loadtable;
                var Data_mhs = Data_mhs['Data_mhs'];
                var res = ta.split(".");
               for(var i=0;i<Data_mhs.length;i++){
                    var img = '<img src="'+base_url_js+'uploads/students/ta_'+res[1]+'/'+Data_mhs[i]['Photo']+'" class="img-rounded" width="30" height="30" style="max-width: 30px;object-fit: scale-down;">';
                    var selecTOption = '<select class="selecTOption getDom" id="'+'discount_'+Data_mhs[i]['NPM']+'" NPM = "'+Data_mhs[i]['NPM']+'" payment-type = "'+PTID+'" invoice = "'+Data_mhs[i]['Cost']+'">';

                        for (var k = 0;k < xx['Discount'].length; k++)
                        {
                            var selected = (k==0) ? 'selected' : '';
                            selecTOption += '<option value="'+xx['Discount'][k]['Discount']+'" '+selected+'>'+xx['Discount'][k]['Discount']+'%'+'</option>';
                        }
                    selecTOption += '</select>';

                    var yy = (Data_mhs[i]['Cost'] != '') ? formatRupiah(Data_mhs[i]['Cost']) : '-';
                    var cost = '<input class="form-control costInput getDom" id="cost_'+Data_mhs[i]['NPM']+'" NPM = "'+Data_mhs[i]['NPM']+'" value = "'+yy+'" payment-type = "'+PTID+'" readonly>';

                   $('#dataRow').append('<tr>' +
                       '<td>'+'<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Name']+'" semester = "'+Data_mhs[i]['SemesterID']+'" ta = "'+res[1]+'"></td>' +
                       '<td>'+Data_mhs[i]['ProdiEng']+'</td>' +
                       '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
                       '<td>'+Data_mhs[i]['Name']+'</td>' +
                       '<td>'+Data_mhs[i]['NPM']+'</td>' +
                       '<td>'+Data_mhs[i]['ClassOf']+'</td>' +
                       '<td>'+img+'</td>' +
                       '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
                       '<td>'+Data_mhs[i]['HP']+'</td>' +
                       '<td>'+selecTOption+'</td>' +
                       '<td>'+cost+'</td>' +
                       '</tr>');
               }

               if(Data_mhs.length > 0)
               {
                $('#btn-submit').removeClass('hide');
                $('#datatable2').removeClass('hide');
                $("#pagination_link").html(resultJson.pagination_link);
               }
               
            }).fail(function() {
              
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
        }
    }

    $(document).on('click','#dataResultCheckAll', function () {
        $('input.uniform').not(this).prop('checked', this.checked);
    });


    function getChecboxNPM(element)
    {
         var allVals = [];
         $('.datatable2 :checked').each(function() {
            var NPM = $(this).val();
            var Invoice = $("#cost_"+NPM).val();
            var Discount = $("#discount_"+NPM).val();
            var semester = $(this).attr('semester');
            var PTID = $('#selectPTID').val();
            var ta = $(this).attr('ta');

            if (Discount != null){
                var arr = {
                        Nama : $(this).attr('Nama'),
                        NPM : NPM,
                        semester : semester,
                        Prodi : $(this).attr('Prodi'),
                        Invoice : Invoice,
                        Discount : Discount,
                        PTID : PTID,
                        ta : ta
                };
                allVals.push(arr);
            }
            
         });
         return allVals;
    }

    $(document).on('click','#btn-submit', function () {
        var arrValueCHK = getChecboxNPM();
        console.log(arrValueCHK);
        if (arrValueCHK.length > 0) {
            var html = '';
            var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                          '<thead>'+
                              '<tr>'+
                                  '<th style="width: 5px;">No</th>'+
                                  '<th style="width: 55px;">Nama</th>'+
                                  '<th style="width: 55px;">NIM</th>'+
                                  '<th style="width: 55px;">Prodi</th>'+
                                  '<th style="width: 55px;">Discount</th>'+
                                  '<th style="width: 55px;">Invoice</th>';
            table += '</tr>' ;  
            table += '</thead>' ; 
            table += '<tbody>' ;
            var isi = '';
            for (var i = 0; i < arrValueCHK.length ; i++) {
                isi += '<tr>'+
                      '<td>'+ (i+1) + '</td>'+
                      '<td>'+ (arrValueCHK[i]['Nama']) + '</td>'+
                      '<td>'+ (arrValueCHK[i]['NPM']) + '</td>'+
                      '<td>'+ (arrValueCHK[i]['Prodi']) + '</td>'+
                      '<td>'+ (arrValueCHK[i]['Discount']) + ' %</td>'+
                      '<td>'+ (arrValueCHK[i]['Invoice']) + '</td>'+
                    '<tr>';  
                
            }

            table += isi+'</tbody>' ; 
            table += '</table>' ;

            html += table;

            var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>';

           $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'List Checklist Data'+'</h4>');
           $('#GlobalModal .modal-body').html(html);
           $('#GlobalModal .modal-footer').html(footer);
           $('#GlobalModal').modal({
               'show' : true,
               'backdrop' : 'static'
           });

           $( "#ModalbtnSaveForm" ).click(function() {
            loading_button('#ModalbtnSaveForm');
            var url = base_url_js+'finance/submit_tagihan_mhs';
            var data = {
                arrValueCHK : arrValueCHK,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
               if (resultJson != '')
               {
                toastr.info(resultJson); 
               }
               else
               {
                toastr.success('Data berhasil disimpan', 'Success!');
               }
               loadData(1);
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                $('#NotificationModal').modal('hide');
            });
             
           }); // exit click function

        }
        else
        {
            toastr.error("Silahkan checked dahulu", 'Failed!!');
        }
    });

    $(document).on('change','.selecTOption', function () {
      var Discount = $(this).val();
      var Npm = $(this).attr('npm');
      var Invoice = $(this).attr('invoice');
      value_cost = Invoice - ((Discount/100)*Invoice);
      $("#cost_"+Npm).val(formatRupiah(value_cost));
    });

</script>