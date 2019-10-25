<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<?php 
$arr_data = $this->m_master->auth_access_aps_rs();
 ?>
 <!-- <pre><?php print_r($arr_data) ?></pre>                     -->
 <div class="well">
    <div class="row">
         <div id = "inputForm">
             <div class="panel panel-default">
                 <div class="panel-heading">
                     <h4 class="panel-title">Input</h4>
                 </div>
                 <div class="panel-body" style="min-height: 100px;">
                     <div class="form-group">
                         <label>Aspek Ratio</label>
                         <select name="ID_m_aspek_ratio" id="" class="form-control input"></select>
                     </div>
                     <div class="form-group">
                         <label>Tingkat Kepuasan Sangat Baik</label>
                         <input type="text" class="form-control input nominal" name = "k_sangat_baik">
                     </div>
                     <div class="form-group">
                         <label>Tingkat Kepuasan Baik</label>
                         <input type="text" class="form-control input nominal" name = "k_baik">
                     </div>
                     <div class="form-group">
                         <label>Tingkat Kepuasan Cukup</label>
                         <input type="text" class="form-control input nominal" name = "k_cukup">
                     </div>
                     <div class="form-group">
                         <label>Tingkat Kepuasan Kurang</label>
                         <input type="text" class="form-control input nominal" name = "k_kurang">
                     </div>
                     <div class="form-group">
                         <label>Rencana Tindak Lanjut oleh UPPS/PS</label>
                         <input type="text" class="form-control input" name = "RencanaTindakLanjut">
                     </div>
                     <div class="form-group">
                         <label for="">Semester</label>
                         <select name="SemesterID" class="form-control input">
                         </select>
                     </div>
                 </div>
                 <div class="panel-footer" style="text-align: right;">
                     <button class="btn btn-success" action= "kepuasan-mhs-add" data-id ="" id="btnSave">Save</button>
                 </div>
             </div>
         </div>
         <div id = "ViewData">
             <div class="panel panel-default">
                 <div class="panel-heading">
                     <h4 class="panel-title">Data</h4>
                 </div>
                 <div class="panel-body" style="min-height: 100px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="text-align: right;margin-bottom: 30px;">
                                <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="form-group">
                                <label for="">Semester</label>
                                <select name="" id="FilterSemester" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                               <table class="table dataTable2Excel" data-name="Kepuasan-mhs" id="dataTablesKM">
                                   <thead>
                                       <tr>
                                           <td style="width: 8%">No</td>
                                           <td>Aspek Ratio</td>
                                           <td>Tingkat Kepuasan Sangat Baik</td>
                                           <td>Tingkat Kepuasan Baik</td>
                                           <td>Tingkat Kepuasan Cukup</td>
                                           <td>Tingkat Kepuasan Kurang</td>
                                           <td>Rencana Tindak Lanjut oleh UPPS/PS</td>
                                           <?php if ($arr_data['input']): ?>
                                              <td>Action</td> 
                                           <?php endif ?>
                                       </tr>
                                   </thead>
                                   <tbody></tbody>
                               </table> 
                            </div>
                        </div>
                    </div>
                 </div>
                 <div class="panel-footer" style="text-align: right;">
                 </div>
             </div>
         </div>
    </div>
 </div>
<script>
var oTable;
var oSettings; 
var App_kepuasan_mhs = {
    LoadAjaxTable : function(){
        $.fn.dataTable.ext.errMode = 'throw';
        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

         var recordTable = $('#dataTablesKM').DataTable({
             "processing": true,
             "serverSide": false,
             "ajax":{
                 url : base_url_js+"rest3/__get_APS_CrudAgregatorTB5", // json datasource
                 ordering : false,
                 type: "post",  // method  , by default get
                 data : function(token){
                       // Read values
                        var ProdiID = $('#filterProdi option:selected').val();
                        var FilterSemester = $('#FilterSemester option:selected').val();
                        var data = {
                               mode : 'kepuasan_mhs',
                               auth : 's3Cr3T-G4N',
                               ProdiID : ProdiID,
                               FilterSemester : FilterSemester,
                           };
                       // Append to data
                       token.token = jwt_encode(data,'UAP)(*');
                    }                                                                     
              },
              // "order": [[ 5, "desc" ]],
               'columnDefs': [
                  {
                     'targets': 0,
                     'searchable': false,
                     'orderable': false,
                     'className': 'dt-body-center',
                  },
                  <?php if ($arr_data['input']): ?>
                          {
                         'targets': 7,
                         'searchable': false,
                         'orderable': false,
                         'className': 'dt-body-center',
                         'render': function (data, type, full, meta){
                             var btnAction = '<div class="btn-group">' +
                                 '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                 '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                 '  </button>' +
                                 '  <ul class="dropdown-menu" style="min-width:50px !important;">' +
                                 '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[7]+'" data = "'+full[8]+'" ><i class="fa fa fa-edit"></i></a></li>' +
                                 '    <li role="separator" class="divider"></li>' +
                                 '    <li><a href="javascript:void(0);" class="btnRemoveAPS" data-id="'+full[7]+'" ><i class="fa fa fa-trash"></i></a></li>' +
                                 '  </ul>' +
                                 '</div>';
                             return btnAction;
                         }
                      },
                  <?php endif ?>
                  
               ],
             'createdRow': function( row, data, dataIndex ) {
                     
             },
             dom: 'l<"toolbar">frtip',
             initComplete: function(){
               
            }  
         });

         recordTable.on( 'order.dt search.dt', function () {
                                    recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                        cell.innerHTML = i+1;
                                    } );
                                } ).draw();

         oTable = recordTable;
         oSettings = oTable.settings();
    },

    setDefaultInput : function(){
        $('.input').not('.input[name="ID_m_aspek_ratio"]').not('.input[name="SemesterID"]').val('');
        $('#btnSave').attr('action','kepuasan-mhs-add');
        $('#btnSave').attr('data-id','');
        $(".input").not('.input[name="RencanaTindakLanjut"]').not('.input[name="ID_m_aspek_ratio"]').not('.input[name="SemesterID"]').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
        $(".input").not('.input[name="RencanaTindakLanjut"]').not('.input[name="ID_m_aspek_ratio"]').not('.input[name="SemesterID"]').maskMoney('mask', '9894');
    },

    LoadSelectOptionAspekRatio : function(optionselected = null){
        var selector = $('.input[name="ID_m_aspek_ratio"]');
        var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB5";
        var data = {
               mode : 'LoadSelectOptionAspekRatio',
               auth : 's3Cr3T-G4N',
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{ token:token },function (resultJson) {
            selector.empty();
            for (var i = 0; i < resultJson.length; i++) {
                var selected = (resultJson[i].ID ==optionselected ) ? 'selected' : '';
                selector.append(
                        '<option value = "'+resultJson[i].ID+'" '+selected+' >'+resultJson[i].Aspek+'</option>'
                    );
            }
        }).fail(function() {
            toastr.error("Connection Error, Please try again", 'Error!!');
        }).always(function() {

        });
    },

    checkReadOrWrite : function(){
        var arr_access = <?php echo json_encode($arr_data)  ?>;
        if (arr_access.input) {
            $('#inputForm').attr('class','col-md-4');
            $('#ViewData').attr('class','col-md-8');
        }
        else
        {
            $('#inputForm').attr('class','hide');
            $('#ViewData').attr('class','col-md-12');
        }
    },

    SubmitData : function(action='kepuasan-mhs-add',ID='',selector){
        var data = {};
        $('.input').each(function(){
            var field = $(this).attr('name');
            if (field == 'SemesterID') {
                var s = $(this).find('option:selected').val();
                s = s.split('.');
               data.SemesterID = s[0];
            }
            else if (field == 'ID_m_aspek_ratio') {
               data.ID_m_aspek_ratio = $(this).find('option:selected').val();
            }
            else
            {
                if (field != undefined) {
                    data[field] = $(this).val(); 
                }
               
            }
        })
         var P = $('#filterProdi option:selected').val();
         P = P.split('.');
         var ProdiID = P[0];
         data['ProdiID'] = ProdiID;
        // validation 
        var validation =  true;
        if (validation) {
            if (confirm('Are you sure ?')) {
                var dataform = {
                    ID : ID,
                    data : data,
                    mode : action,
                    auth : 's3Cr3T-G4N',
                };
                var token = jwt_encode(dataform,"UAP)(*");
                loading_button2(selector);
                var url = base_url_js + "rest3/__get_APS_CrudAgregatorTB5";
                $.post(url,{ token:token },function (resultJson) {
                        
                }).done(function(resultJson) {
                    App_kepuasan_mhs.setDefaultInput();
                    end_loading_button2(selector);
                    oTable.ajax.reload( null, false );
                    toastr.success('Success');
                }).fail(function() {
                    toastr.error("Connection Error, Please try again", 'Error!!');
                    end_loading_button2(selector); 
                }).always(function() {
                     end_loading_button2(selector);              
                }); 
            }
        }

    },

    SelectFilteringProdi : function(){
        var bool = false;
        var selector = $('#filterProdi');
        var view = "<?php echo $arr_data['view'] ?>";
        if (view != 'all') {
            var jsonArr = <?php echo json_encode($arr_data['ProdiID']) ?>;
            if (jsonArr.length > 0) {
                console.log(jsonArr);
                selector.find('option').each(function(){
                    var sthis = $(this);
                    var v = $(this).val();
                    var sp = v.split('.');
                    var res = sp[0];
                    var bool2 = false;
                    for (var i = 0; i < jsonArr.length; i++) {
                        if (jsonArr[i] == res) {
                            // console.log(res);
                            bool2 = true;
                            break;
                        }
                    }

                    if (!bool2) {
                        sthis.remove();
                    }
                })
            }
            else
            {
                  // selector.find('option').each(function(){
                  //   var sthis = $(this);
                  //    sthis.remove();
                  // })
            }
        }
        bool = true;
        return bool;
    },

    loaded : function(){
        loadingStart();
        App_kepuasan_mhs.checkReadOrWrite();
        App_kepuasan_mhs.LoadSelectOptionAspekRatio();
        loSelectOptionSemester('#FilterSemester','selectedNow');
        loSelectOptionSemester('.input[name="SemesterID"]','selectedNow');
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            var filterProdi = $('#filterProdi').val();
            var ID_m_aspek_ratio = $('.input[name="ID_m_aspek_ratio"]').val();
            var FilterSemester = $('#FilterSemester').val();
            var SemesterID = $('.input[name="SemesterID"]').val();
            if (App_kepuasan_mhs.SelectFilteringProdi()) {
                if(filterProdi!='' && filterProdi!=null && ID_m_aspek_ratio != '' && ID_m_aspek_ratio != null && FilterSemester!='' && FilterSemester !=null && SemesterID != '' && SemesterID != null ){
                    $('#viewProdiID').html(filterProdi);
                    $('#viewProdiName').html($('#filterProdi option:selected').text());
                    App_kepuasan_mhs.LoadAjaxTable();
                    App_kepuasan_mhs.setDefaultInput();
                    clearInterval(firstLoad);
                    loadingEnd(500)
                }
            }
            
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_kepuasan_mhs.loaded();

});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        oTable.ajax.reload( null, false );
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
    }
});

$(document).off('change', '#FilterSemester').on('change', '#FilterSemester',function(e) {
   oTable.ajax.reload( null, false );
})

$('#saveToExcel').click(function () {
       $('select[name="dataTablesKurikulum_length"]').val(-1);
       oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
       oTable.draw();
       setTimeout(function () {
           saveTable2Excel('dataTable2Excel');
       },1000);
});

$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
    var ID = $(this).attr('data-id');
    var selector = $(this);
    var action = $(this).attr('action');
    App_kepuasan_mhs.SubmitData(action,ID,selector);
})

$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
    var ID = $(this).attr('data-id');
    var Token = $(this).attr('data');
    var data = jwt_decode(Token);
    console.log(data);
    for(var key in data) {
        if (key == 'ID_m_aspek_ratio') {
            $(".input[name='ID_m_aspek_ratio'] option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == data.ID_m_aspek_ratio; 
             }).prop("selected", true);
        }
        else if(key == 'SemesterID'){
            // $(".input[name='SemesterID'] option").filter(function() {
            //    //may want to use $.trim in here
            //    return $(this).val() == data.SemesterID; 
            // }).prop("selected", true);
            $(".input[name='SemesterID'] option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == $('#FilterSemester option:selected').val(); 
            }).prop("selected", true);
        }
        else
        {
            $('.input[name="'+key+'"]').val(data[key]);
        }
    }
    
    $('#btnSave').attr('action','kepuasan-mhs-edit');
    $('#btnSave').attr('data-id',ID);
})

$(document).off('click', '.btnRemoveAPS').on('click', '.btnRemoveAPS',function(e) {
    var ID = $(this).attr('data-id');
    var action = 'kepuasan-mhs-delete';
    App_kepuasan_mhs.SubmitData(action,ID,$(''));
})
$(document).off('keyup', '.nominal').on('keyup', '.nominal',function(e) {
    var v = $(this).val();
    if (v > 100) {
        $(this).val(100)
    }
})

</script>       