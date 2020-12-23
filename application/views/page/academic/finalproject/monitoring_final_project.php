
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
    .btn-upload {
        padding: 3px 5px 3px 5px;
        font-size: 10px !important;
        /*font-weight: bold;*/
    }

    #tableData td {
        vertical-align: middle;
    }
    #tableData td:nth-child(1), #tableData td:nth-child(2), #tableData td:nth-child(3){
        vertical-align: top !important;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1" style="text-align: right;margin-top: 30px;">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-md-6">
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewData"></div>
    </div>
</div>

<div class="modal" id="editdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Edit Final Project</h4>
      </div>
      <div class="modal-body">
      <input type="hidden" name="id" id="id">
        <div class="form-group">
            <label class="col-md-3 control-label">Tittle Indonesia</label>
            <div class="col-md-12">
            	<textarea type="text" name="tittleindo" id="tittleindo" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Tittle Inggris</label>
            <div class="col-md-12">
            	<textarea type="text" name="tittleing" id="tittleing" class="form-control"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12">
            <right>
              <button type="button" class="btn btn-primary waves-effect text-left" onclick="confirmEdit();">
                Save
              </button>
              <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal" aria-hidden="true">
                Cancel
              </button>
            </right>
          </div>
        </div>
      </div>
    </div>
  </div> 
</div> 

<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);

        $('#editdata').on('show.bs.modal',function (event) {
        	var div = $(event.relatedTarget);
        	var modal = $(this);

        	modal.find('#id').attr("value",div.data('id'));
        	modal.find('#tittleindo').html(div.data('tittleindo'));
        	modal.find('#tittleing').html(div.data('tittleing'));
        	modal.find('#tittleindo').attr("value",div.data('tittleindo'));
        	modal.find('#tittleing').attr("value",div.data('tittleing'));
        	
        })
    });

    $('#filterSemester,#filterBaseProdi').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadData();
        }
    });

    function loadData() {
        var filterSemester = $('#filterSemester').val();
        var filterBaseProdi = $('#filterBaseProdi').val();


        if(filterSemester!='' && filterSemester!=null){

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
                ? filterBaseProdi.split('.')[0] : '';

            var SemesterID = filterSemester.split('.')[0];

            $('#viewData').html('<table class="table table-striped table-bordered" id="tableData" style="width: 100%;">' +
                '            <thead>' +
                '            <tr>' +
                '               <th style="width: 5%;" >No</th>' +
                '               <th style="width: 15%;">Student</th>' +
                '               <th style="width: 25%;">Tittle Indonesia</th>' +
                '               <th style="width: 20%;">Tittle Inggris</th>' +
                '               <th style="width: 15%;">Update By</th>' +
                '               <th style="width: 15%;">Update At</th>' +
                '               <th style="width: 5%;">Action</th>' +
                '            </tr>' +
                '            </thead>' +
                '        </table>');


            var token = jwt_encode({action : 'viewList',SemesterID:SemesterID,ProdiID : ProdiID},'UAP)(*');
            var url = base_url_js+'academic/loadFinalProject';

            // window.dataTable.ajax.reload(null, false);
            window.dataTable = $('#tableData').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Tittle"
                },
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }
    }



       async function confirmEdit()
     {
       var boolValidation = true;
       var datarequest = {
         ID : $("#id").val(),
         tittleIndo : $("#tittleindo").val(),
         tittleIng : $("#tittleing").val(),
       };

       var dataAjax = {
         action : 'editTittle',
         datarequest : datarequest,
       }
       var token = jwt_encode(dataAjax,'UAP)(*');
       var url = base_url_js+'academic/loadFinalProject';
       try{
         var ajax = await AjaxSubmitFormPromises(url,token);
        
         if(ajax['status'] == 1){
           $("#editdata").modal("hide");
           toastr.success('change data is successful','Success');
           window.location = '';
         }
         else
         {
           alert(ajax['msg']);
         }
       }
       catch(err){
         toastr.info('Something error');
       }
     }
  
</script>