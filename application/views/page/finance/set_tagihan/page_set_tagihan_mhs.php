
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
        <table class="table table-bordered datatable2">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="width: 3%;"><input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll"></th>
                <th style="width: 12%;">Program Study</th>
                <th style="width: 10%;">Semester</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 5%;">NIM</th>
                <th style="width: 5%;">Foto</th>
                <th style="width: 15%;">Email PU</th>
                <th style="width: 10%;">No HP</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 10%;">Invoice</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
    <div  class="col-xs-12" align="right" id="pagination_link"></div>
    <div  class="col-xs-12" align="right"><button class="btn btn-inverse btn-notification btn-submit" id="btn-submit">Submit</button></div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum('#selectCurriculum','');
        loadSelectOptionBaseProdi('#selectProdi','');
        loadSelectOptionPaymentType('#selectPTID','');
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
      var page = $(this).data("ci-pagination-page");
      loadData(page);
      // loadData_register_document(page);
    });

    function loadData(page) {
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
                    var selecTOption = '<select class="selecTOption getDom" id="'+'discount_'+Data_mhs[i]['NPM']+'" NPM = "'+Data_mhs[i]['NPM']+'" payment-type = "'+PTID+'">';

                        for (var k = 0;k < xx['Discount'].length; k++)
                        {
                            var selected = (k==0) ? 'selected' : '';
                            selecTOption += '<option value="'+xx['Discount'][k]['Discount']+'" '+selected+'>'+xx['Discount'][k]['Discount']+'%'+'</option>';
                        }
                    selecTOption += '</select>';

                    var yy = (Data_mhs[i]['Cost'] != '') ? formatRupiah(Data_mhs[i]['Cost']) : '-';
                    var cost = '<input class="form-control costInput getDom" id="cost_'+Data_mhs[i]['NPM']+'" NPM = "'+Data_mhs[i]['NPM']+'" value = "'+yy+'" payment-type = "'+PTID+'" readonly>';

                   $('#dataRow').append('<tr>' +
                       '<td>'+'<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Name']+'"></td>' +
                       '<td>'+Data_mhs[i]['ProdiEng']+'</td>' +
                       '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
                       '<td>'+Data_mhs[i]['Name']+'</td>' +
                       '<td>'+Data_mhs[i]['NPM']+'</td>' +
                       '<td>'+img+'</td>' +
                       '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
                       '<td>'+Data_mhs[i]['HP']+'</td>' +
                       '<td>'+selecTOption+'</td>' +
                       '<td>'+cost+'</td>' +
                       '</tr>');
               }

               $("#pagination_link").html(resultJson.pagination_link);
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

            if (Discount != null){
                var arr = {
                        Nama : $(this).attr('Nama'),
                        Prodi : $(this).attr('Prodi'),
                        Invoice : Invoice,
                        Discount : Discount
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
          
            var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>';

           $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'List Checklist Data'+'</h4>');
           $('#GlobalModal .modal-body').html('test');
           $('#GlobalModal .modal-footer').html(footer);
           $('#GlobalModal').modal({
               'show' : true,
               'backdrop' : 'static'
           });

        }
        else
        {
            toastr.error("Silahkan checked dahulu", 'Failed!!');
        }
    });
</script>