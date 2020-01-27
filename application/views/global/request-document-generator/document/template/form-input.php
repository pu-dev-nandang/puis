<style type="text/css">
    #datatable_STD.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }

    #datatable_employees.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Form</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="well">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                    <label>Upload Template</label>
                    <input type="file" name = "PathTemplate" id = "UploadFile">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Sample Template 1</label>
                  <ul style="margin-left: -20px;">
                    <li><a href="<?php echo base_url().'uploads/document-generator/template/Template_Surat_GET_EMP.docx' ?>">Template Surat GET EMP</a> </li>
                    <li><a href="<?php echo base_url().'uploads/document-generator/template/Template_Surat_GET_MHS.docx' ?>">Template Surat GET MHS</a> </li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Sample Template 2</label>
                  <ul style="margin-left: -20px;">
                    <li><a href="<?php echo base_url().'uploads/document-generator/template/Template_Surat_Tugas.docx' ?>">Template Surat Tugas</a> </li>
                    <li><a href="<?php echo base_url().'uploads/document-generator/template/Template_Surat_Tugas_mengajar.docx' ?>">Template Surat Tugas mengajar</a> </li>
                  </ul>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" id = "Page_form_input">
                
            </div>
            <div class="col-md-6" id = "Page_GET">
                
            </div>   
        </div>
        <div class="row">
            <div class="col-md-4 page" id = "Page_SET">
                
            </div>
            <div class="col-md-4 page" id = "Page_USER">
                
            </div>
            <div class="col-md-4 page" id = "Page_INPUT">
                
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="col-md-3 page" id = "Page_GRAB">
                
            </div>
            <div class="col-md-9 page" id = "Page_TABLE">
                
            </div>
        </div>

    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-primary hide" id="Preview">Preview</button>
        <button class="btn btn-success" id="btnSave" disabled>Save</button>
    </div>
</div>

<script type="text/javascript">
    var S_Table_example_;
    var S_Table_example_emp;
    var S_Table_example_mhs;
    var settingTemplate;
    var App_form_input = {
        UploadChangeFunction : function(selector){
            $('#btnSave').prop('disabled',true);
            var ArrUploadFilesSelector = [];
            var UploadFile = selector;
            var valUploadFile = UploadFile.val();
            if (valUploadFile) { // if upload file
                 var NameField = UploadFile.attr('name');
                 var temp = {
                     NameField : NameField,
                     Selector : UploadFile,
                 };
                 ArrUploadFilesSelector.push(temp);
            }

            var validation = App_form_input.Validation_template(ArrUploadFilesSelector);
            if (validation) {
                var url = base_url_js+"document-generator-action/__upload_template";
                var token =  '';
                AjaxSubmitTemplate(url,token,ArrUploadFilesSelector).then(function(response){
                    if (response.status == 1) {
                        var response_callback = response.callback;
                        App_form_input.DomSetTemplate(response_callback);
                    }
                    else
                    {
                        toastr.error(response.msg);
                    }
                }).fail(function(response){
                   toastr.error('Connection error,please try again');
                })
            }
        },

        Validation_template : function(ArrUploadFilesSelector){
            var toatString = "";
            var selectorfile = ArrUploadFilesSelector[0].Selector
            var FilesValidation = file_validation_generator(selectorfile,'File Template');
            if (FilesValidation != '') {
                toatString += FilesValidation + "<br>";
            }

            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
        },

        DomSetTemplate : function(response_callback){
            $('.page').empty();

            for (variable in response_callback){
                var key = response_callback[variable];
                switch(variable) {
                  case "SET":
                    App_form_input.method_SET(key);
                    break;
                  case "USER":
                    App_form_input.method_USER(key);
                    break;
                  case "INPUT":
                    App_form_input.method_INPUT(key);
                    break;
                  case "GRAB":
                    App_form_input.method_GRAB(key);
                    break;
                  case "TABLE":
                    App_form_input.method_TABLE(key);
                    break;
                  case "GET":
                    App_form_input.method_GET(key);
                    break;
                }             
            }
            App_form_input.Dom_FormInput(response_callback);
            $('#Preview').removeClass('hide');

        },

        method_GET : function(dt){
            for(key in dt){
                switch(key) {
                  case "EMP":
                    App_form_input.Dom_EMP(dt[key]);
                    break;
                  case "MHS":
                  App_form_input.Dom_MHS(dt[key]);
                    break;

                }
            }
        },

        Dom_MHS : function(dt){
            var selector = $('#Page_GET');
            var html = '<div class = "thumbnail" style = "margin-top:5px;">';
            for (var i = 0; i < dt.length; i++) {
                var Choose = dt[i].Choose;
                var keyNumber = dt[i].number;
                switch(Choose) {
                  case "NPM":
                    html += '<div class = "row GET" keyindex = "'+i+'" keynumber="'+keyNumber+'">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:5px;">'+
                                        '<h3><u><b>Students '+keyNumber+' </b></u></h3>'+
                                    '</div>'+
                                    '<div class = "form-group">'+
                                        '<label>Choose Students</label>'+
                                        '<div class="input-group">'+
                                            '<input type="text" class="form-control Input" readonly name="'+key+'" key="GET" field="'+key+'">'+
                                            '<span class="input-group-btn">'+
                                                '<button class="btn btn-default SearchNPMSTD" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
                                            '</span>'+
                                        '</div>'+
                                        '<label for="Name"></label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'        

                                ;
                    break;

                }
            }

            html += '</div>';
            selector.html(html);
        },

        Dom_EMP : function(dt){
            var selector = $('#Page_GET');
            var html = '<div class = "thumbnail" style = "margin-top:5px;">';
            // console.log(dt);
            for (var i = 0; i < dt.length; i++) {
                var Choose = dt[i].Choose;
                var keyNumber = dt[i].number;
                switch(Choose) {
                  case "NIP":
                    html += '<div class = "row GET" keyindex = "'+i+'" keynumber="'+keyNumber+'" >'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:5px;">'+
                                        '<h3><u><b>Employees '+keyNumber+' </b></u></h3>'+
                                    '</div>'+
                                    '<div class = "form-group">'+
                                        '<label>Choose Employees</label>'+
                                        '<div class="input-group">'+
                                            '<input type="text" class="form-control Input" readonly name="'+key+'" key="GET" field="'+key+'">'+
                                            '<span class="input-group-btn">'+
                                                '<button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
                                            '</span>'+
                                        '</div>'+
                                        '<label for="Name"></label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'        

                                ;
                    break;

                }
            }

            html += '</div>';
            selector.html(html);
        },

        SearchNIPEMP : function(selector){
              var html = '';
              html ='<div class = "row">'+
                      '<div class = "col-md-12">'+
                        '<div class="table-responsive">'+
                          '<table id="datatable_employees" class="table table-bordered display select" cellspacing="0" width="100%">'+
                 '<thead>'+
                    '<tr>'+
                       '<th style = "width:5%;">No</th>'+
                       '<th>NIP - Name</th>'+
                       '<th>Division</th>'+
                       '<th>Position</th>'+
                    '</tr>'+
                 '</thead>'+
                 '<tbody></tbody>'+
            '</table></div></div></div>';

              $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Employees'+'</h4>');
              $('#GlobalModalLarge .modal-body').html(html);
              $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
              $('#GlobalModalLarge').modal({
                  'show' : true,
                  'backdrop' : 'static'
              });

              var table = $('#datatable_employees').DataTable({
                  "fixedHeader": true,
                  "processing": true,
                  "destroy": true,
                  "serverSide": true,
                  "lengthMenu": [
                      [10, 25],
                      [10, 25]
                  ],
                  "iDisplayLength": 10,
                  "ordering": false,
                  "language": {
                      "searchPlaceholder": "Search",
                  },
                  "ajax": {
                      url: base_url_js + "rest3/__LoadEmployees_server_side", // json datasource
                      ordering: false,
                      type: "post", // method  , by default get
                      data: function(token) {
                          var data = {
                              auth: 's3Cr3T-G4N',
                          };
                          var get_token = jwt_encode(data, "UAP)(*");
                          token.token = get_token;
                      },
                      error: function() { // error handling
                          $(".datatable_employees-grid-error").html("");
                          $("#datatable_employees-grid").append(
                              '<tbody class="datatable_employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                          );
                          $("#datatable_employees-grid_processing").css("display", "none");
                      }
                  },
                  'createdRow': function(row, data, dataIndex) {
                      var dt = jwt_decode(data.data);
                      $(row).attr('datatoken',data.data);
                      $(row).find('td:eq(1)').html(dt['NIP']+' - '+dt['Name']);
                      $(row).find('td:eq(2)').html(dt['DepartmentName']);
                      $(row).find('td:eq(3)').html(dt['PositionName']);
                  },
                  dom: 'l<"toolbar">frtip',
                  "initComplete": function(settings, json) {

                  }
              });

              S_Table_example_emp = table;

              S_Table_example_emp.on( 'click', 'tr', function (e) {
                  var row = $(this);
                  var datatoken = jwt_decode(row.attr('datatoken'));
                  selector.closest('.input-group').find('.Input').val(datatoken['NIP']);
                  selector.closest('.input-group').find('.Input').attr('datatoken',row.attr('datatoken'));
                  selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
                  $('#GlobalModalLarge').modal('hide');
              });

        },

        SearchNPMSTD : function(selector){
              var html = '';
              html ='<div class = "row">'+
                      '<div class = "col-md-12">'+
                        '<div class="table-responsive">'+
                          '<table id="datatable_STD" class="table table-bordered display select" cellspacing="0" width="100%">'+
                 '<thead>'+
                    '<tr>'+
                       '<th style = "width:5%;">No</th>'+
                       '<th>NPM - Name</th>'+
                       '<th>Prodi</th>'+
                    '</tr>'+
                 '</thead>'+
                 '<tbody></tbody>'+
            '</table></div></div></div>';

              $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Students'+'</h4>');
              $('#GlobalModalLarge .modal-body').html(html);
              $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
              $('#GlobalModalLarge').modal({
                  'show' : true,
                  'backdrop' : 'static'
              });

              var table = $('#datatable_STD').DataTable({
                  "fixedHeader": true,
                  "processing": true,
                  "destroy": true,
                  "serverSide": true,
                  "lengthMenu": [
                      [10, 25],
                      [10, 25]
                  ],
                  "iDisplayLength": 10,
                  "ordering": false,
                  "language": {
                      "searchPlaceholder": "Search",
                  },
                  "ajax": {
                      url: base_url_js + "rest3/__LoadStudents_server_side", // json datasource
                      ordering: false,
                      type: "post", // method  , by default get
                      data: function(token) {
                          var data = {
                              auth: 's3Cr3T-G4N',
                          };
                          var get_token = jwt_encode(data, "UAP)(*");
                          token.token = get_token;
                      },
                      error: function() { // error handling
                          $(".datatable_STD-grid-error").html("");
                          $("#datatable_STD-grid").append(
                              '<tbody class="datatable_STD-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                          );
                          $("#datatable_STD-grid_processing").css("display", "none");
                      }
                  },
                  'createdRow': function(row, data, dataIndex) {
                      var dt = jwt_decode(data.data);
                      $(row).attr('datatoken',data.data);
                      $(row).find('td:eq(1)').html(dt['NPM']+' - '+dt['Name']);
                      $(row).find('td:eq(2)').html(dt['ProdiName']);
                  },
                  dom: 'l<"toolbar">frtip',
                  "initComplete": function(settings, json) {

                  }
              });

              S_Table_example_mhs = table;

              S_Table_example_mhs.on( 'click', 'tr', function (e) {
                  var row = $(this);
                  var datatoken = jwt_decode(row.attr('datatoken'));
                  selector.closest('.input-group').find('.Input').val(datatoken['NPM']);
                  selector.closest('.input-group').find('.Input').attr('datatoken',row.attr('datatoken'));
                  selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
                  $('#GlobalModalLarge').modal('hide');
              });

        },

        method_SET : function(dt){
            for (key in dt){
                if (key == 'PolaNoSurat') {
                    App_form_input.Dom_PolaNoSurat(dt[key]);
                }
                else if(key == 'Signature'){
                    App_form_input.Dom_Signature(dt[key]);
                }

            }   
        },

        Dom_PolaNoSurat : function(data){
            var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
                            '<div class = "row">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:15px;">'+
                                        '<h3><u><b>Pola Nomor Surat</b></u></h3>'+
                                    '</div>'+
                                    '<p style ="color : red;">Sample : 041/UAP/R/SKU/X/2019</p>'+
                                    '<p>'+
                                        'Keterangan : <br/>'+
                                        '* 041 : Increment, 3 character <br/>'+    
                                        '* UAP : Prefix <br/>'+    
                                        '* R : Prefix <br/>'+    
                                        '* SKU : Prefix <br/>'+    
                                        '* X : Bulan Romawi <br/>'+    
                                        '* 2019 : Tahun <br/>'+
                                        '* / : delimiter <br/>'+
                                    '</p>'+
                                    '<br/>'+
                                    '<div class = "form-group">'+
                                        '<label>Prefix</label>'+
                                        '<input type = "text" class="form-control Input" value = "UAP/R/SKU"  field="PolaNoSurat" name = "prefix" key ="SET" />'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
            $('#Page_SET').append(html);       
                                            
        },

        Dom_Signature : function(data){
            var html = '<div class = "thumbnail" style = "margin-top:5px;">';
            for (var i = 0; i < data.length; i++) {
                var select =  data[i].select;
                var data_selected_default = 2;
                var keyNumber = data[i].number;
                var htmlOP = App_form_input.SelectOP(select,data_selected_default);
                html +=    '<div class = "row Approval" keyindex = "'+i+'" keyNumber = "'+keyNumber+'">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:15px;">'+
                                        '<h3><u><b>Approval '+keyNumber+' </b></u></h3>'+
                                    '</div>'+
                                    '<div class = "form-group">'+
                                        '<label>Approval</label>'+
                                        htmlOP+
                                    '</div>'+
                                    '<div class = "form-group">'+
                                        '<label>Verify</label>'+
                                        '<select class = "form-control Input" field="Signature" name = "verify" key = "SET">'+
                                            '<option value = "0">Manual</option>'+
                                            '<option value = "1">Auto</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div class = "form-group">'+
                                        '<label>CAP</label>'+
                                        '<select class = "form-control Input" field="Signature" name = "cap" key = "SET">'+
                                            '<option value = "0">Manual</option>'+
                                            '<option value = "1">Auto</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';      

            }

            $('#Page_SET').append(html);  
        },

        SelectOP : function(data,data_selected = '',name='user',key ="SET",field='Signature'){
            var html =  '<select class = "form-control Input" name = "'+name+'" key="'+key+'" field ="'+field+'">';
            for (var i = 0; i < data.length; i++) {
               var selected = (data_selected ==  data[i].ID) ? 'selected' : '';
               html +=  '<option value = "'+data[i].ID+'" '+selected+' >'+data[i].Value+'</option>';
            }

            html  += '</select>';

            return html;

        },


        // -- //

        method_INPUT : function(dt){
            var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
                            '<div class = "row">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:15px;">'+
                                        '<h3><u><b>Input by Request</b></u></h3>'+
                                    '</div>';
            for (var i = 0; i < dt.length; i++) {
                html  +=   '<div class = "form-group">'+
                                '<label>'+dt[i]+'</label>'+
                                '<input type = "text" class="form-control Input" name="'+i+'" field= "'+dt[i]+'"  key ="INPUT" value = "Free Text by Request"  style = "color:red;" />'+
                            '</div>';
            }

            html  += '</div></div></div>';
            $('#Page_INPUT').append(html);
        },

        // -- //

        method_USER : function(dt){
            var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
                            '<div class = "row">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:15px;">'+
                                        '<h3><u><b>User Data Request</b></u></h3>'+
                                    '</div>';
            for (var i = 0; i < dt.length; i++) {
                var v = dt[i];
                var _split = v.split('.');

                html  +=   '<div class = "form-group">'+
                                '<label>'+'Auto by System to get '+_split[1]+'</label>'+
                                '<input type = "hidden" class="form-control Input" name="'+_split[1]+'" field= "'+_split[0]+'" key ="USER " value = "get '+_split[1]+'" readonly style = "color:red;" />'+
                            '</div>';
            }

            html  += '</div></div></div>';
            $('#Page_USER').append(html);
            

        },

        // -- //

        method_GRAB : function(dt){
            for (key in dt){
                if (key == 'Date') {
                     App_form_input.Dom_Date(dt[key]);
                }
            }   
        },

        // -- //

        method_TABLE : function(dt){
            // console.log(dt);
            if (dt['API'] != undefined) {
                var htmlOP = App_form_input.SelectAPIOP(dt['API']['select']);
                var html = '<div class="thumbnail" style = "margin-top:5px;">'+
                                '<div class = "row">'+
                                    '<div class = "col-md-12">'+
                                        '<div style = "padding:15px;">'+
                                            '<h3><u><b>SET TABLE </b></u></h3>'+
                                        '</div>'+
                                        '<div class = "form-group">'+
                                            '<label>Choose API</label>'+
                                            htmlOP+
                                        '</div>'+
                                        '<div id = "DOMAPI"></div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';

                $('#Page_TABLE').append(html);
            }

        },

        DOMSelectedAPI : function(selector){
            var html = '';
            var params = jQuery.parseJSON( jwt_decode(selector.find('option:selected').attr('params')) );
            if (typeof params !== 'undefined') {
              var DataCallback =  jwt_decode($('#FormDocument').attr('datacallback'));
              for(key in params){
                if (params[key] == '#NIP' || params[key] == '$NIP') {
                  var dtRowEmp = DataCallback['TABLE']['API']['selectEmployees'];
                  var htmlOPEMP = App_form_input.SelectAPIOPEMP(dtRowEmp,params[key]);
                  html  += '<div class = "form-group">'+
                              '<label>Choose Parameter '+params[key]+'</label>'+
                              htmlOPEMP+
                          '</div>';
                }
                else if(params[key] == '#NPM'){
                  html  += '<div class = "form-group">'+
                              '<label>Choose Parameter '+params[key]+'</label>'+
                              '<div class="input-group">'+
                                  '<input type="text" class="form-control Input" readonly field="PARAMS" name="'+params[key]+'" key="TABLE">'+
                                  '<span class="input-group-btn">'+
                                      '<button class="btn btn-default SearchNPMSTD" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
                                  '</span>'+
                              '</div>'+
                              '<label for="Name"></label>'+
                          '</div>';
                }
                else
                {
                  var dtRow = DataCallback['TABLE']['API']['paramsChoose'][params[key]];
                  var htmlOP = App_form_input.SelectAPIOPByParams(dtRow,params[key]);
                  
                  html += '<div class = "form-group">'+
                              '<label>Choose Parameter '+params[key]+'</label>'+
                              htmlOP+
                          '</div>';  
                }


              }

                if (html != '') {
                     html +=  '<div style = "text-align:right;padding:10px;">'+
                                '<button class = "btn btn-default" id = "setTable">Set Table</button>'+
                              '</div>'+
                              '<div id = "pageSetTable"></div>';  
                }
               

            }
            
            $('#DOMAPI').html(html);

            $('.Input[field="employees"][tabindex!="-1"]').removeClass('form-control');
            $('.Input[field="employees"][tabindex!="-1"]').addClass('select2-select-00 full-width-fix');
            $('.Input[field="employees"][tabindex!="-1"]').select2({
                //allowClear: true
            });
        },

        setTableDesign : function(selector){
            var selectorPage = $('#pageSetTable');
            var data = {};
            // var querySQL = jwt_decode($('.Input[field="TABLE"][name="API"] option:selected').attr('query'));
            var ID_api = $('.Input[field="TABLE"][name="API"] option:selected').val();
            var count = 0;
            $('.Input[key="TABLE"][field="PARAMS"]').not('div').each(function(e){
                data[count] = {};
                var nm = $(this).attr('name');
                // nm = nm.replace("#", "");
                var v = $(this).find('option:selected').val();
                if (v == undefined) {
                  v = $(this).val();
                }
                data[count][nm] = v;
                count++;
            })
            // data['querySQL'] = querySQL;
            data['ID_api'] = ID_api;
            // data['NIP'] = $('.Input[field="employees"] option:selected').val();
            data['action'] = 'sample';
            // console.log(data);return;
            $('#Preview').prop('disabled',true);
            loading_button2(selector);
            var url = base_url_js+"document-generator-action/__run_set_table";
            var token = jwt_encode(data,'UAP)(*');
            AjaxSubmitTemplate(url,token).then(function(response){
                if (response.status == 1) {
                    App_form_input.MapTable(response.callback);
                    $('#Preview').prop('disabled',false);
                }
                end_loading_button2(selector,'Set Table');
            }).fail(function(response){
               toastr.error('No Result Data,please try again');
               end_loading_button2(selector,'Set Table');
            })
            
        },

        MapTable : function(dt){
            var selector = $('#pageSetTable');
            var DataCallback =  jwt_decode($('#FormDocument').attr('datacallback'));
            var keyTable = DataCallback['TABLE']['KEY'];
            var html = '<div class = "row" style="padding:15px;"><h3><u><b>Header</b></u></h3>';
            for (var i = 0; i < keyTable.length; i++) {
                html += '<div class = "col-md-3">'+
                            '<div class = "form-group">'+
                                '<label>'+keyTable[i]+'</label>'+
                                '<input type = "text" class = "form-control Input" field="'+keyTable[i]+'" name="MappingTable" parent="header" key = "TABLE" >'+
                            '</div>'+
                        '</div>';        
            }

            html += '</div>'

            html +=  '<div class = "row" style="padding:15px;"><h3><u><b>Value</b></u></h3>';

            for (var i = 0; i < keyTable.length; i++) {
                var htmlOPChooseField = App_form_input.SelectAPIOPChooseField(dt[0],keyTable[i],i);
                html += '<div class = "col-md-3">'+
                            '<div class = "form-group">'+
                                '<label>'+keyTable[i]+'</label>'+
                                htmlOPChooseField+
                            '</div>'+
                        '</div>';        
            }
            selector.html(html);
        },

        SelectAPIOPChooseField : function(data,keyTable,number){
            var selected = (number == 0) ? 'selected' : '';
            var html =  '<select class = "form-control Input" field="'+keyTable+'" name="'+'MappingTable'+'" parent="Value" key = "TABLE">';
                         html +=  '<option value = "'+'Increment'+'" '+selected+' >'+'Increment'+'</option>';
            var run = 1 ;
            for (key in data){
                selected = (number ==run ) ? 'selected' : '';
                html +=  '<option value = "'+key+'" '+selected+' >'+key+'</option>';
                run++;
            }
            html  += '</select>';
            return html;
        },

        SelectAPIOPByParams : function(data,paramsChoose){
            var html =  '<select class = "form-control Input" field="PARAMS" name="'+paramsChoose+'" key = "TABLE">';
            for (var i = 0; i < data.length; i++) {
               var selected = (data[i].Selected == 1) ? 'selected'  : ''; 
               html +=  '<option value = "'+data[i].ID+'" '+selected+' >'+data[i].Value+'</option>';
            }

            html  += '</select>';

            return html;
        },

        SelectAPIOPEMP : function(data,paramsChoose){
            var html =  '<select class = "form-control Input" field="PARAMS" name="'+paramsChoose+'" key = "TABLE">';
            for (var i = 0; i < data.length; i++) {
               html +=  '<option value = "'+data[i].NIP+'">'+data[i].Name+'</option>';
            }

            html  += '</select>';

            return html;
        },

        SelectAPIOP : function(data){
            var html =  '<select class = "form-control Input" field="TABLE" name="API" key = "TABLE">';
            html += '<option value = "-" selected disabled>--No Choose API--</option>';
            // console.log(data);
            for (var i = 0; i < data.length; i++) {

               html +=  '<option value = "'+data[i].ID+'" '+''+' params = "'+jwt_encode(data[i].Params,'UAP)(*')+'" query= "'+jwt_encode(data[i].Query,'UAP)(*')+'" >'+data[i].ApiNameTable+'</option>';
            }

            html  += '</select>';

            return html;
        },

        set_TABLE : function(attrname,attrva,attrkey,attrfield,el){
            // console.log(settingTemplate[attrkey]);
            // select API Choose
            settingTemplate[attrkey]['API']['Choose'] = $('.Input[field="TABLE"][name="API"] option:selected').val();
            // var count = 0;
            var data = {};
            var count = 0;
            $('.Input[key="TABLE"][field="PARAMS"]').not('div').each(function(e){
                data[count] = {};
                var nm = $(this).attr('name');
                // nm = nm.replace("#", "");
                var v = $(this).find('option:selected').val();
                if (v == undefined) {
                  v = $(this).val();
                }
                data[count][nm] = v;
                count++;
            })
            settingTemplate[attrkey]['paramsUser'] = data;
            // settingTemplate[attrkey]['paramsUser']['NIP'] = $('.Input[field="employees"] option:selected').val();    // only preview for sample

            settingTemplate[attrkey]['MapTable'] = {};
            settingTemplate[attrkey]['MapTable']['Header'] = {};
            settingTemplate[attrkey]['MapTable']['Value'] = {};
            // header
            $('.Input[key="TABLE"][name="MappingTable"]').each(function(e){
                var parent = $(this).attr('parent');
                var field = $(this).attr('field');
                if (parent == 'header') {
                    settingTemplate[attrkey]['MapTable']['Header'][field] = $(this).val();
                }

                if (parent == 'Value') {
                    settingTemplate[attrkey]['MapTable']['Value'][field] = $(this).find('option:selected').val();
                }

            });


        },

        // -- //

        Dom_Date : function(data){
            var html = '<div class = "thumbnail" style = "margin-top:5px;">';
            var select =  data.select;
            var data_selected_default = 1;
            var htmlOP = App_form_input.SelectOP(select,data_selected_default,'user','GRAB','Date');
            html +=    '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<div style = "padding:15px;">'+
                                    '<h3><u><b>GRAB '+' </b></u></h3>'+
                                '</div>'+
                                '<div class = "form-group">'+
                                    '<label>Date Choose</label>'+
                                    htmlOP+
                                '</div>'+
                            '</div>'+
                        '</div>';   

            $('#Page_GRAB').append(html);  
        },

        Dom_FormInput : function(response_callback){
            var DataCallback = jwt_encode(response_callback,'UAP)(*');
            var html = '<div class = "thumbnail" style = "margin-top:5px;" id = "FormDocument" DataCallback ="'+DataCallback+'" >';
            var page = $('#Page_form_input');
            html += '<div class="row">'+
                        '<div class = "col-md-12">'+
                            '<div style = "padding:15px;">'+
                                '<h3><u><b>Identify Document'+' </b></u></h3>'+
                            '</div>'+
                            '<div class="form-group">'+ 
                                '<label>Name</label>'+
                                '<input type ="text" class="form-control Input" field ="DocumentName" name ="DocumentName" value = "DocumentName" key="DOCUMENT" />'+
                            '</div>'+
                            '<div class="form-group">'+ 
                                '<label>Alias</label>'+
                                '<input type ="text" class="form-control Input" field ="DocumentAlias" name ="DocumentAlias" value = "DocumentAlias" key="DOCUMENT" />'+
                            '</div>'+
                        '</div>'+
                    '</div>';
            
            html += '</div>';
            page.html(html);            

        },

        // -- //

        SubmitPreviewPDF : function(selector){
            var data = [];
            var DataCallback =  jwt_decode($('#FormDocument').attr('datacallback'));
            settingTemplate = DataCallback;
            $('.Input').not('div').each(function(){
                var el = $(this);
                var attrname = el.attr('name').trim();
                if (!$(this).is("select")) {
                    var attrva = el.val();
                }
                else
                {
                    var attrva = el.find('option:selected').val();
                }
                
                var attrkey = el.attr('key').trim();
                var attrfield = el.attr('field').trim();
                for (variable in settingTemplate){
                    switch(variable) {
                      case "SET":
                        if (variable == attrkey) {
                            App_form_input.set_SET(attrname,attrva,attrkey,attrfield,el);
                        }
                        break;
                      case "USER":
                        if (variable == attrkey) {
                            /*
                                USER get from session by Request
                            */
                        }
                           
                        break;
                      case "INPUT":
                        if (variable == attrkey) {
                            App_form_input.set_INPUT(attrname,attrva,attrkey,attrfield,el);
                        }
                        break;
                      case "GRAB":
                        if (variable == attrkey) {
                            App_form_input.set_GRAB(attrname,attrva,attrkey,attrfield,el);
                        }
                        break;
                      case "TABLE":
                          if (variable == attrkey) {
                            App_form_input.set_TABLE(attrname,attrva,attrkey,attrfield,el);
                          }
                        break;
                      case "GET":
                        if (variable == attrkey) {
                          App_form_input.set_GET(attrname,attrva,attrkey,attrfield,el);
                        }
                        break;
                      case "DOCUMENT":
                        
                        break;
                    }             
                }


            })
            // console.log(settingTemplate);
            // return;
            var ArrUploadFilesSelector = [];
            var url = base_url_js+"document-generator-action/__preview_template";
            var token = jwt_encode(settingTemplate,'UAP)(*');
            var UploadFile = $('#UploadFile');
            var valUploadFile = UploadFile.val();
            if (valUploadFile) { // if upload file
                 var NameField = UploadFile.attr('name');
                 var temp = {
                     NameField : NameField,
                     Selector : UploadFile,
                 };
                 ArrUploadFilesSelector.push(temp);
            }
            loading_button2(selector);
            AjaxSubmitTemplate(url,token,ArrUploadFilesSelector).then(function(response){
                if (response.status == 1) {
                    window.open(response.callback, '_blank');
                    $('#btnSave').prop('disabled',false);
                }
                else
                {
                    toastr.error('Something error,please try again');
                }
                end_loading_button2(selector,'Preview');
            }).fail(function(response){
               toastr.error('Connection error,please try again');
               end_loading_button2(selector,'Preview');
            })

        },

        SaveTemplate : function(selector){
            if (typeof settingTemplate !== 'undefined') {
                var ArrUploadFilesSelector = [];
                var url = base_url_js+"document-generator-action/__save_template";
                
                var DepartmentArr = [];
                S_Table_example_.$('input[type="checkbox"]:checked').each(function(){
                  var v = $(this).val();
                  var n = $(this).attr('dt');
                  var temp = {
                    Code : v,
                    Name : n,
                  };

                  DepartmentArr.push(temp);
                }); // exit each function

                var data = {
                    settingTemplate : settingTemplate,
                    DocumentName : $('.Input[name="DocumentName"]').val() ,
                    DocumentAlias : $('.Input[name="DocumentAlias"]').val() ,
                    DepartmentArr : DepartmentArr,
                };
                
                var token = jwt_encode(data,'UAP)(*');
                var UploadFile = $('#UploadFile');
                var valUploadFile = UploadFile.val();
                if (valUploadFile) { // if upload file
                     var NameField = UploadFile.attr('name');
                     var temp = {
                         NameField : NameField,
                         Selector : UploadFile,
                     };
                     ArrUploadFilesSelector.push(temp);
                }

                loading_button2(selector);
                AjaxSubmitTemplate(url,token,ArrUploadFilesSelector).then(function(response){
                    if (response.status == 1) {
                       toastr.success('Saved'); 
                       location.reload();
                    }
                    else
                    {
                        toastr.error('Something error,please try again');
                        end_loading_button2(selector,'Save');
                    }
                }).fail(function(response){
                   toastr.error('Connection error,please try again');
                   end_loading_button2(selector,'Save');
                })

            }
        },

        ShowModalDepartment : function(selector){
                        
            var html = '';
            html ='<div class = "row">'+
                    '<div class = "col-md-12">'+
                        '<table id="example_budget" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>Select &nbsp <input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                     '<th>Code</th>'+
                     '<th>Departement</th>'+
                  '</tr>'+
               '</thead>'+
          '</table></div></div>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Department'+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
                '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
            var url = base_url_js+'api/__getAllDepartementPU';
            $.get( url, function( dt ) {
                var table = $('#example_budget').DataTable({
                      "processing": true,
                      "serverSide": false,
                      "data" : dt,
                      'columnDefs': [
                          {
                             'targets': 0,
                             'searchable': false,
                             'orderable': false,
                             'className': 'dt-body-center',
                             'render': function (data, type, full, meta){
                                 var checked = '';
                                 if (full.Code == DepartmentID) {
                                     checked = 'checked';
                                 }
                                 return '<input type="checkbox" name="id[]" value="' + full.Code + '" dt = "'+full.Abbr+'" '+checked+'>';
                             }
                          },
                          {
                             'targets': 1,
                             'render': function (data, type, full, meta){
                                 return full.Abbr;
                             }
                          },
                          {
                             'targets': 2,
                             'render': function (data, type, full, meta){
                                 return full.Name2;
                             }
                          },
                      ],
                      'createdRow': function( row, data, dataIndex ) {
                            // console.log(data);
                      },
                      // 'order': [[1, 'asc']]
                });

                S_Table_example_ = table;
            });

        },

        // -- //

        set_SET : function(attrname,attrva,attrkey,attrfield,el){
            if (attrname == 'prefix') {
               settingTemplate[attrkey]['PolaNoSurat']['setting']['prefix'] = attrva;
            }
            else if(attrname == 'user'){
                var keyindex = parseInt(el.closest('.Approval').attr('keyindex'));
                settingTemplate[attrkey]['Signature'][keyindex]['user'] = attrva;
            }
            else if(attrname == 'verify'){
                var keyindex = parseInt(el.closest('.Approval').attr('keyindex'));
                settingTemplate[attrkey]['Signature'][keyindex]['verify'] = attrva;

                var keyNumber = parseInt(el.closest('.Approval').attr('keynumber'));
                settingTemplate[attrkey]['Signature'][keyindex]['number'] = keyNumber;
            }
            else if(attrname == 'cap'){
                var keyindex = parseInt(el.closest('.Approval').attr('keyindex'));
                settingTemplate[attrkey]['Signature'][keyindex]['cap'] = attrva;
            }
        },

        // -- //

        set_INPUT : function(attrname,attrva,attrkey,attrfield,el){
            for (var i = 0; i < settingTemplate['INPUT'].length; i++) {
               if (i==attrname) {
                var keyInput = i + 1;
                settingTemplate['INPUT'][i] = {
                    field : attrfield,
                    value : attrva,
                    mapping : 'Input'+keyInput,
                }
                break;
               }
            }
        },

        // -- //

        set_GRAB : function(attrname,attrva,attrkey,attrfield,el){
            for (variable in settingTemplate[attrkey]){
                if (variable == 'Date') {
                    settingTemplate[attrkey][variable]['user'] = attrva;
                }
            }
            
        },

        set_GET : function(attrname,attrva,attrkey,attrfield,el){
            var keynumber = el.closest('.GET').attr('keynumber');
            var keyindex = parseInt(el.closest('.GET').attr('keyindex'));
            var dt = jwt_decode(el.attr('datatoken'));
            settingTemplate[attrkey][attrname][keyindex]['user'] = {};
            settingTemplate[attrkey][attrname][keyindex]['user'] = dt;
        },


    }

    $(document).off('change', '#UploadFile').on('change', '#UploadFile',function(e) {
       var itsme = $(this);
       App_form_input.UploadChangeFunction(itsme);
    })

    $(document).off('click', '#Preview').on('click', '#Preview',function(e) {
       var itsme = $(this);
       App_form_input.SubmitPreviewPDF(itsme);
    })

    $(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
       var itsme = $(this);
       App_form_input.SaveTemplate(itsme);
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var itsme = $(this);
       App_form_input.ShowModalDepartment(itsme);
    })

    $(document).off('click', '.SearchNIPEMP').on('click', '.SearchNIPEMP',function(e) {
       var itsme = $(this);
       App_form_input.SearchNIPEMP(itsme);
    })

    $(document).off('click', '.SearchNPMSTD').on('click', '.SearchNPMSTD',function(e) {
       var itsme = $(this);
       App_form_input.SearchNPMSTD(itsme);
    })

    // Handle click on "Select all" control
    $(document).off('click', '#example-select-all').on('click', '#example-select-all',function(e) {
       // Get all rows with search applied
       var rows = S_Table_example_.rows({ 'search': 'applied' }).nodes();
       // Check/uncheck checkboxes for all rows in the table
       $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $(document).off('change', '.Input[field="TABLE"][name="API"]').on('change', '.Input[field="TABLE"][name="API"]',function(e) {
       var itsme = $(this);
       App_form_input.DOMSelectedAPI(itsme);
    })

    $(document).off('click', '#setTable').on('click', '#setTable',function(e) {
       var itsme = $(this);
       App_form_input.setTableDesign(itsme);

    })

    $(document).off('change', '.Input[field="Signature"][name="verify"][key="SET"]').on('change', '.Input[field="Signature"][name="verify"][key="SET"]',function(e) {
       var v = $(this).find('option:selected').val();
       $('.Input[field="Signature"][name="verify"][key="SET"] option').filter(function() {
          //may want to use $.trim in here
          return $(this).val() == v; 
       }).prop("selected", true);
    })

</script>