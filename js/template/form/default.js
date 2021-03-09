var config_validate = {
    ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
    errorClass: 'validation-invalid-label',
    successClass: 'validation-valid-label',
    validClass: 'validation-valid-label',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        $(element).closest('.form-group').removeClass('has-error');
    },
    // Different components require proper error label placement
    errorPlacement: function (error, element) {
        //            console.log(error);
        //            console.log(element);
        //            error_message('Please')
        // Unstyled checkboxes, radios
        if (element.parents().hasClass('form-check')) {
            error.appendTo(element.parents('.form-check').parent());
        }

        // Input with icons and Select2
        else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
            error.appendTo(element.parent());
        }

        // Input group, styled file input
        else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
            error.appendTo(element.parent().parent());
        }

        // Other elements
        else {
            error.insertAfter(element);
        }
    },
    //        showErrors: function (errorMap, errorList) {
    //            console.log(errorMap);
    //            console.log(errorList);
    //            console.log('error');
    ////            $("#summary").html("Your form contains "
    ////                    + this.numberOfInvalids()
    ////                    + " errors, see details below.");
    ////            this.defaultShowErrors();
    //        },
    success: function (label) {
        label.remove();
        label.closest('.form-group').removeClass('has-error');
    },
    //        rules: rules_form,
    //        messages: messages_form,
    submitHandler: async function (form) {
        if ($(form).hasClass('no-ajax')) {
            if ($(form).hasClass('target-blank')) {
                $(form).attr('target', "javascript:window.open('','targetNew')")
            }
            $('#form')[0].submit();
        }
        else if($(form).hasClass('ajax-token'))
        {
            var dataPost = $(form).serializeArray();

            var ArrUploadFilesSelector = [];
            var UploadFile = $('input[type="file"]');
            var valUploadFile = UploadFile.val();
            if (valUploadFile) {
                var NameField = UploadFile.attr('name');
                var temp = {
                    NameField : NameField,
                    Selector : UploadFile,
                };
                ArrUploadFilesSelector.push(temp);
            }

            const token = jwt_encode(dataPost,'UAP)(*');
            const url = $(form).attr('action');
            loading_modal_show();
            try {
              var response =  await AjaxSubmitFormPromises(url,token,ArrUploadFilesSelector);
              response_form(response);

                
            }
            catch(err) {
              toastr.error('something wrong, please contact IT','!Error');
            }

            loading_modal_hide();
        } 
        else {
            loading_modal_show();
            $(form).ajaxSubmit({
                success: async function (data) {
                    try{
                        response_form(data);
                    }
                    catch(err){
                        toastr.error('something wrong, please contact IT','!Error');
                    }

                    await timeout(1000);
                    loading_modal_hide(); 
                },
                error : function(){
                    loading_modal_hide();
                }
            });
            return false;
        }
    }
};

var btnPasteHere = function (context) {
    var ui = $.summernote.ui;

    // create button
    var button = ui.button({
        contents: '<i class="fa fa-clipboard"/> Paste text',
        tooltip: 'Paste text',
        click: function () {
            // invoke insertText method with 'hello' on editor module.

            let html = 
                      '<div class = "row">'+
                        '<div class = "col-md-12">'+
                          '<div class = "well">'+
                            '<label>Paste here</label>'+
                            '<textarea id="fillModalPaste" class="form-control" rows="10" placeholder="Paste here..."></textarea>' +
                            '<hr/>' +
                            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
                            ' | <button type="button" class="btn btn-success" id="btnSaveModalPaste">Save</button> ' +
                          '</div>'+
                        '</div>'+
                      '</div>'  
              ;

            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Paste Here</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            $('#fillModalPaste').focus()

            $('#btnSaveModalPaste').click(function () {
                var fillModalPaste = $('#fillModalPaste').val();
                context.invoke('editor.insertText', fillModalPaste);
               $('#GlobalModal').modal('hide');
            });
        }
    });

    return button.render();   // return button as jquery object
};  
    

$(document).ready(function () {
    //uniform
    $('.styled').uniform();

    //select2
    $('.select2').select2({
        closeOnSelect: true
    });
    $('.select2-with-clear').select2({
        placeholder: '',
        allowClear: true,
        debug: true,
        closeOnSelect: true
    });

    $('.datetimepicker').datetimepicker({
      format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
    });

    $('.formTemplateMessage').summernote({
        placeholder: 'Text your question...',
        height: 250,
        disableDragAndDrop : true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'help']],
            ['mybutton', ['PasteHere']]
        ],
        buttons: {
            PasteHere: btnPasteHere
        },
        callbacks: {
            onPaste: function(e) {
                    alert('Disabled cut copy and paste');
                    e.preventDefault();
            }
        }
    });

    $("#form").validate(config_validate);
});
