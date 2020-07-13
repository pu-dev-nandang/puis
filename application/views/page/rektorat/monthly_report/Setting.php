<div class="row">
    <div class="col-md-3 panel-admin" style="border-right: 1px solid #CCCCCC;">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Position to Credit</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">

                <div class="form-group">
                    <input class="hide" id="formID">
                    <label>Name</label>

                    <br>

                    <select style="width: 100%;" id="formName" ></select>
                </div>
                <div class="form-group">
                    <input class="hide" id="formID">
                    <label>Department</label>
                    <br>
                    <select class="select2-select-00 full-width-fix" id="Division">
                    <?php for($i = 0; $i < count($G_division); $i++): ?>
                      <option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>
                    <?php endfor ?>
                   </select>
                </div>
                <div class="form-group">
                    <label>Access</label>
                    <br>
                    <select class="form-control" id = "formInputAccess">
                    <option value="false" selected>Read</option>
                    <option value="true">Write</option>
                </select>
                </div>
            </div>
            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success" action= "add" data-id ="" id="btnSaveSetting">Save</button>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="thumbnail" style="min-height: 50px;">
            <div id="viewtable">
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var Otable;
    $(document).ready(function() {
        LoadFirstLoad();
    })

    function LoadFirstLoad() {

         LoadNama();
         loadPage();
         var firstLoad = setInterval(function () {
                 loadPage();
                 clearInterval(firstLoad);
             
         },1000);
         setTimeout(function () {
             clearInterval(firstLoad);
         },5000);

    }

    function loadPage()
    {
        var selector = $('#ListSetting');
        var url = base_url_js+'rest3/__Setting_Monthly_Report';
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'ListSetting',
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            var selector = $('#viewtable');
            var htmltable = '<table class="table table-striped" id = "tbldata">'+
                                '<thead>'+
                                '<tr>'+
                                 '   <th style="width: 1%;">No</th>'+
                                '    <th style="width: 15%;">Name</th>'+
                                '    <th style="width: 15%;">Department</th>'+
                                '    <th style="width: 10%;">Access</th>'+
                                '    <th style="width: 10%;">UpdatedBy</th>'+
                                '    <th style="width: 10%;">UpdatedAt</th>'+
                                '    <th style="width: 10%;">Action</th>'+
                               ' </tr>'+
                               ' </thead>'+
                               ' <tbody id="ListSetting"></tbody>'+
                            '</table>';
            selector.html(htmltable);                
            MakeTable(resultJson);
        }).fail(function() {
          toastr.info('No Result Data');
        }).always(function() {
                        
        });
    }

    $(document).off('click', '').on('click', '',function(e) {
        loadPage();
    })


    $(document).off('click', '.btnRemoveSKS').on('click', '.btnRemoveSKS',function(e) {
      var selector = $(this);
      if (confirm('Are you sure ?')) {
        var ID=$(this).attr('data-id')
          var url = base_url_js+'rest3/__Config_Jabatan_SKS';
          var data = {
              auth : 's3Cr3T-G4N',
              mode : 'deletelistSetting',
              ID : ID,
          };
          var token = jwt_encode(data,"UAP)(*");
            selector.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
            selector.prop('disabled',true);
          $.post(url,{ token:token },function (resultJson) {
              
          }).done(function(resultJson) {
            selector.html('<i class="fa fa-trash"></i>');
            selector.prop('disabled',false);
            loadPage();
          }).fail(function() {
            toastr.info('No Result Data');
          }).always(function() {
                          
          });
      }
      
    })

    function MakeTable(resultJson)
    {
        var dt = resultJson;
        var table = $('#tbldata').DataTable({
              "data" : dt,
              'columnDefs': [
                  {
                     'targets': 0,
                     'searchable': false,
                     'orderable': false,
                     'className': 'dt-body-center',
                     'render': function (data, type, full, meta){
                         return '';
                     }
                  },
                  {
                     'targets': 1,
                     'render': function (data, type, full, meta){
                         return full.NIP+'-'+full.Name;
                     }
                  },
                  {
                     'targets': 2,
                     'render': function (data, type, full, meta){
                         return full.Position;
                     }
                  },
                  {
                     'targets': 3,
                     'render': function (data, type, full, meta){
                         return full.SemesterName;
                     }
                  },
                  {
                     'targets': 4,
                     'render': function (data, type, full, meta){
                         return full.SKS;
                     }
                  },
                  {
                     'targets': 5,
                     'render': function (data, type, full, meta){


                      // console.log(full)
                         return '<button class="btn btn-sm btn-danger btnRemoveSKS" data-id="'+full.ID+'"><i class="fa fa-trash"></i></button>';


                     }
                  },
              ],
              'createdRow': function( row, data, dataIndex ) {
                    // $(row).attr('CodePost', data.CodePost);
                    // $(row).attr('CodeHeadAccount', data.CodeHeadAccount);
                    // $(row).attr('CodePostRealisasi', data.CodePostRealisasi);
                    // $(row).attr('money', (data.Value - data.Using) );
                    // $(row).attr('id_budget_left', data.ID);
                    // $(row).attr('NameHeadAccount', data.NameHeadAccount);
                    // $(row).attr('RealisasiPostName', data.RealisasiPostName);
              },
              // 'order': [[1, 'asc']]
        });

        table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

        Otable = table;
    }

    function LoadNama() {
        var selector =$('#formName');
        var url = base_url_js+'rest3/__Setting_Monthly_Report';
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'showDataDosen'
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            //var response = jQuery.parseJSON(resultJson);
            selector.empty()
            for (var i = 0; i < resultJson.length; i++) {
                var NIP = resultJson[i].NIP
                var Name = resultJson[i].Name
                selector.append(
                    ' <option value="'+NIP+'">'+NIP+'|'+Name+'</option>'
                    )

            }
            selector.select2({
                //allowClear: true
            });
        }).fail(function() {
          toastr.info('No Result Data');
        }).always(function() {
                        
        }); 

    }
        $('#btnSaveSKS').click (function(){
             var formName = $('#formName').val();
             var formDepartment = $('#formDepartment option:selected').val();
             var formInputAccess = $('#formInputAccess').val();
        
            if(formName !='' && formName!=null &&
             formDepartment !='' && formDepartment!=null&&
             formInputAccess !='' && formInputAccess!=null){

            loading_buttonSm('#btnSaveSKS');
            var dataForm = {
             PositionID : formDepartment,
             NIP : formName,
             Access : formInputAccess,   
            };
                    
                    var url = base_url_js+'rest3/__Setting_Monthly_Report';
                    var data = {
                                auth : 's3Cr3T-G4N',
                                mode : 'saveDataJabatanSKS',
                                dataForm : dataForm
                            };
                    var token = jwt_encode(data,'UAP)(*');
                    $.post(url,{token:token},function (result) {
                        toastr.success('Data saved','Success');

                         // setDefaultTable(formName);
                         loadPage();

                        setTimeout(function () {

                            // $('#formName').val('');
                            // $('#formJabatan').val('');
                            // $('#formSemester').val('');
                            // $('#formSKS').val('');

                            $('#btnSaveSKS').prop('disabled',false).html('Save');
                        },500);

                    }); 
            };     
        });

</script>

