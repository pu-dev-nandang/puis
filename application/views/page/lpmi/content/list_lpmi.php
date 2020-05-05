<div class="tab-pane fade active in" id="list" role="tabpanel" aria-labelledby="list-tab">
					<div class="table-responsive">
					  <table class="table table-condensed">
					      <thead>
					        <tr>
					          <th>#</th>
					          <th>Title</th>
					          <th>Text</th>
					          <th>Publish</th>
					          <th>Date Update</th>
					        </tr>
					      </thead>
					      <tbody id="viewDatalist">
					        
					      </tbody>
					    </table>
					</div>
				</div>


				<script>
    $(document).ready(function () {

        window.G_Type = '3';

        loadSelectOptionLanguageProdi('#LangID','');

        $('#Description').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                  onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                    e.preventDefault();
                    var div = $('<div />');
                    div.append(bufferText);
                    div.find('*').removeAttr('style');
                    setTimeout(function () {
                      document.execCommand('insertHtml', false, div.html());
                    }, 10);
                  }
                }
        });

        loadData();

        var firsLoad = setInterval(function () {

            var LangID = $('#LangID').val();
            if(LangID!='' && LangID!=null){
                loadDataOption();
                clearInterval(firsLoad);
            }

        },1000);

    });
 

    $('#LangID').change(function () {
        var LangID = $('#LangID').val();
        if(LangID!='' && LangID!=null){
            loadDataOption();
        }
    });

    function loadData() {
        var data = {
            action : 'read',
            Type : G_Type
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api_lpmi/__crudlpmi';

        $.post(url,{token:token},function (jsonResult) {
        	var response = jQuery.parseJSON(jsonResult);
        	
            $('#viewDatalist').empty();
            if(response.length>0){
            	var no=1;
                $.each(response,function (i,v) {
                    $('#viewDatalist').append('<tr>'+
							          '<th scope="row">'+no+'</th>'+
							          '<td>'+v.Title+'</td>'+
							          '<td>'+v.Description+'</td>'+
							          '<td>'+v.Status+'</td>'+
							          '<td>'+v.CreateAt+'</td>'+
							          '<td><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button> <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>'+
							        '</tr>');
                });

            } else {
                $('#viewDatalist').html('<div class="well">Data not yet</div>');
            }

        });
    }

    function loadDataOption() {
        var LangID = $('#LangID').val();
        if(LangID!='' && LangID!=null){
            var data = {
                action : 'readDataProdiTexting',
                Type : G_Type,
                LangID : LangID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api-prodi/__crudDataProdi';
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.length>0){
                    $('#Description').summernote('code', jsonResult[0].Description);
                } else {
                    $('#Description').summernote('code', '');
                }

            });
        }
    }

    $('#btnSave').click(function () {

        var LangID = $('#LangID').val();
        var Description = $('#Description').val();

        if(LangID!='' && LangID!=null &&
            Description!='' && Description!=null){

            var data = {
                action : 'updateProdiTexting',
                dataForm : {
                    Type : G_Type,
                    LangID : LangID,
                    Description : Description,
                    UpdatedBy : sessionNIP
                }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api-prodi/__crudDataProdi';
            $.post(url,{token:token},function (jsonResult) {
                toastr.success('Data saved','Success');
                loadData();
            })

        }

    });

</script>