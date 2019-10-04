<div class="row">
    <div class="col-md-3 panel-admin" style="border-right: 1px solid #CCCCCC;">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Config Jabatan Per SKS</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">

                <div class="form-group">
                    <input class="hide" id="formID">
                    <label>Nama Dosen</label>
                    <br>
                    <select style="width: 100%;" id="formName" ></select>
                </div>
                <div class="form-group">
                    <input class="hide" id="formID">
                    <label>Jabatan / Posisi</label>
                    <br>
                    <select style="width: 100%;" id="formJabatan" ></select>
                </div>
                
                <div class="form-group">
                    <label>Semester</label>
                    <br>
                    <select style="width: 100%;" id="formSemester" ></select>
                </div>
                <div class="form-group">
                    <label>SKS</label>
                    <br>
                    <select style="width: 100%;" id="formSKS" ></select>
                </div>
            </div>
            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success" action= "add" data-id ="" id="btnSaveSKS">Save</button>
            </div>
        </div>
    </div>
    <div class="col-md-9">

        <div class="row">
            <div class="col-md-3 col-md-offset-4">
                <div class="well">
                    <label>Tahun Akademik</label>
                    <select class="form-control" id="filterPeriod"></select>
                </div>
                <hr/>
            </div>
        </div>

        <div class="thumbnail" style="min-height: 50px;">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th style="width: 15%;">Nama Dosen</th>
                    <th style="width: 15%;">Jabatan/Posisi</th>
                    <th style="width: 15%;">Semester</th>
                    <th style="width: 10%;">SKS</th>
                </tr>
                </thead>
                <tbody id="listJabatanSKS"></tbody>
            </table>

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
         LoadJabatan();
         LoadSemester();
         LoadSKS();

    }

    function LoadNama() {
        var selector =$('#formName');
        var url = base_url_js+'rest3/__Config_Jabatan_SKS';
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
    function LoadJabatan() {
        var selector =$('#formJabatan');
        var url = base_url_js+'rest3/__Config_Jabatan_SKS';
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'showJabatan'
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            //var response = jQuery.parseJSON(resultJson);
            selector.empty()
            for (var i = 0; i < resultJson.length; i++) {
                var Position = resultJson[i].Position
                selector.append(
                    ' <option value="'+resultJson[i].ID+'">'+Position+'</option>'
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
    function LoadSemester() {
        var selector =$('#formSemester');
        var url = base_url_js+'rest3/__Config_Jabatan_SKS';
        var selector2 =$('#filterPeriod');
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'showSemester'
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            //var response = jQuery.parseJSON(resultJson);
            selector.empty()
            selector2.empty()
            for (var i = 0; i < resultJson.length; i++) {
                var ID = resultJson[i].ID
                var Name = resultJson[i].Name
                var selected = (resultJson[i].Status==1) ? 'selected' : '';
                selector.append(
                    ' <option value="'+ID+'"  '+selected+'>'+Name+'</option>'
                    )
                selector2.append(
                    ' <option value="'+ID+'"  '+selected+'>'+Name+'</option>'
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
    function LoadSKS() {
        var selector =$('#formSKS');
        selector.empty()
        for (var i = 1; i < 11; i++) {
            
            selector.append(
                ' <option value="'+i+'">'+i+'</option>'
                )
        }
    }
        $('#btnSaveSKS').click (function(){
             var formName = $('#formName').val();
             var formJabatan = $('#formJabatan option:selected').val();
             var formSemester = $('#formSemester').val();
             var formSKS = $('#formSKS').val();
        
            if(formName !='' && formName!=null &&
             formJabatan !='' && formJabatan!=null&&
             formSemester !='' && formSemester!=null&&
             formSKS !='' && formSKS!=null){

            loading_buttonSm('#btnSaveSKS');
            var dataForm = {
             PositionID : formJabatan,
             NIP : formName,
             SemesterID : formSemester,
             SKS : formSKS,   
            };
                    
                    var url = base_url_js+'rest3/__Config_Jabatan_SKS';
                    var data = {
                                auth : 's3Cr3T-G4N',
                                mode : 'saveDataJabatanSKS',
                                dataForm : dataForm
                            };
                    var token = jwt_encode(data,'UAP)(*');
                    $.post(url,{token:token},function (result) {
                        toastr.success('Data saved','Success');
                        // setDefaultTable(formName);

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

