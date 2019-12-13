<?php 
$field = [
    'Input1' => 'Agenda',
    'Input2' => 'Hari',
    'Input3' => 'Tanggal',
    'Input4' => 'Waktu',
    'Input5' => 'Tempat ',
];

$PolaSurat = [
    'method' => [
            'Usually' => [
                'Increment' => 'Yes',
                'Max' => 3,
            ]
        ],
];


echo json_encode($PolaSurat);
echo json_encode($field);


 ?>

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
        <br/>

        <div id = "FormGenerate"></div>

    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" id="btnSave" disabled>Save</button>
    </div>
</div>

<script type="text/javascript">
    var App_form_input = {
        UploadChangeFunction : function(selector){
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
                        $('#btnSave').prop('disabled',false);
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
            console.log(response_callback);
        },
    }

    $(document).off('change', '#UploadFile').on('change', '#UploadFile',function(e) {
       var itsme = $(this);
       App_form_input.UploadChangeFunction(itsme);
    })
</script>