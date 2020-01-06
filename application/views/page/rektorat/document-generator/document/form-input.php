<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Form</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="well">
            <div class="form-group">
                <label>Upload Template</label>
                <input type="file" name = "PathTemplate" id = "UploadFile">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id = "Page_form_input">
                
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
                    
                    break;
                  case "DOCUMENT":
                    
                    break;
                }             
            }
            App_form_input.Dom_FormInput(response_callback);
            $('#Preview').removeClass('hide');

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
                var htmlOP = App_form_input.SelectOP(select,data_selected_default);
                html +=    '<div class = "row Approval" keyindex = "'+i+'">'+
                                '<div class = "col-md-12">'+
                                    '<div style = "padding:15px;">'+
                                        '<h3><u><b>Approval '+(i+1)+' </b></u></h3>'+
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
            $('.Input').each(function(){
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
                        
                        break;
                      case "DOCUMENT":
                        
                        break;
                    }             
                }


            })
            
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
                var data = {
                    settingTemplate : settingTemplate,
                    DocumentName : $('.Input[name="DocumentName"]').val() ,
                    DocumentAlias : $('.Input[name="DocumentAlias"]').val() ,
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


    }

    $(document).off('change', '#UploadFile').on('change', '#UploadFile',function(e) {
       var itsme = $(this);
       App_form_input.UploadChangeFunction(itsme);
    })

    $(document).off('click', '#Preview').on('click', '#Preview',function(e) {
       var itsme = $(this);
       App_form_input.SubmitPreviewPDF(itsme);
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var itsme = $(this);
       App_form_input.SaveTemplate(itsme);

    })
</script>