<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menuEBudget">
    <ul class="nav nav-tabs">
       <!--  <li role="presentation">
            <a href="<?php echo base_url().'/budgeting' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li> -->
        <li class="active">
            <a href="javascript:void(0)" class="pageAnchor" page = "data">Data</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">CRM</h4>
                </div>
                <div class="panel-body">
                    <div class="row" id = "pageContent">
                       
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // $("#container").attr('class','fixed-header sidebar-closed');
        // $("#sidebar").remove();
        LoadPage('data');

        $(".pageAnchor").click(function(){
            var Page = $(this).attr('page');
            $(".menuEBudget li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageContent").empty();
            LoadPage(Page);
        });
        
    }); // exit document Function

    function LoadPage(page)
    {
        loading_page("#pageContent");
        var url = base_url_js+'admisssion/crm/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            setTimeout(function () {
                $("#pageContent").empty();
                $("#pageContent").html(html);
            },1000);
            
        }); // exit spost
    }

    $(document).on('change', '.file-upload',function(e) {
        loadingStart();
        var files = $(this)[0].files;
        files = files[0];
        var validation_file = validation_files(files);
        if (validation_file) {
            var form_data = new FormData();
            var url = base_url_js + "admission/crm/import";
            form_data.append("fileData", files);
            $.ajax({
              type:"POST",
              url:url,
              data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData:false,
              dataType: "json",
              success:function(data)
              {
                if (data != '') {
                    toastr.error(data,'!!!Failed')
                }
                else
                {
                    loadtable();
                }

                loadingEnd(1000);
              },
              error: function (data) {
                toastr.error(data.msg, 'Connection error, please try again!!');
              }
            })
        }
        else
        {
             loadingEnd(1);
        }
    })

    function validation_files(files)
    {
        var name = files.name;
        var extension = name.split('.').pop().toLowerCase();
        var msgStr = '';
        if(jQuery.inArray(extension, ['xls','xlsx']) == -1)
        {
         msgStr += 'File Number Invalid Type File<br>';
        }

        var oFReader = new FileReader();
        oFReader.readAsDataURL(files);
        var f = files;
        var fsize = f.size||f.fileSize;

        if(fsize > 2000000) // 2mb
        {
         msgStr += 'File Number '+ no + ' Image File Size is very big<br>';
        }

        if (msgStr != '') {
          toastr.error(msgStr, 'Failed!!');
          return false;
        }
        else
        {
          return true;
        }
    }

    function loadtable()
    {
        $("#tableData3 tbody").empty();
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

        var table = $('#tableData3').DataTable( {
            "fixedHeader": true,
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 25,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"admission/crm/showdata", // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                // data : {length : $("select[name='tableData4_length']").val()},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                  var btndel = '<button type="button" class="btn btn-danger btn-delete btn-delete-data" data = "'+data[13]+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
                  $( row ).find('td:eq(12)').html(btndel);
            },
        } );


        table.on('click', '.btn-delete-data',function(e) {
            var IDTable = $(this).attr('data');
            if (confirm("Are you sure ?") == true) {
                var url = base_url_js+"admission/crm/delete/byid";
                var data = {
                    ID : IDTable
                }
                var token = jwt_encode(data,'UAP)(*');
                $.post(url,{token : token},function(a,b,c){
                    loadtable();
                })
            }
        })
    }    
</script>