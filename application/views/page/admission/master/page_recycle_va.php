<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Recycle VA Register Online</h4>
            </div>
            <div class="widget-content">
                <div class = "row"> 
                    <div  class="col-xs-12" align="right" id="pagination_link"></div>
                </div>
                <div id="pageData">
                    
                </div>
                <br>
                <div class="col-xs-12" align = "right">
                   <button class="btn btn-inverse btn-notification btn-recycle" id="btn-recycle">Recycle</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        loadTbl_VA(1);
    });

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).data("ci-pagination-page");
      loadTbl_VA(page)
      // loadData_register_document(page);
    });

    function getValueChecbox()
    {
         var allVals = [];
         $('.tableData :checked').each(function() {
            if($(this).val() != 'nothing')
            {
                allVals.push($(this).val());
            }
           
         });
         return allVals;
    }

    function loadTbl_VA(page)
    {
        loading_page('#pageData');
        var url = base_url_js+'admission/master-registration/loadDataVA-deleted/'+page;
        $.post(url,function (data_json) {
            // jsonData = data_json;
            var obj = JSON.parse(data_json); 
            // console.log(obj);
            setTimeout(function () {
                $("#pageData").html(obj.register_deleted);
                $("#pagination_link").html(obj.pagination_link);
            },500);
        }).done(function() {
          
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
          // $('#btn-dwnformulir').prop('disabled',false).html('Formulir');
        });
    }

    $(document).on('click','#btn-recycle', function () {
        loading_button('#btn-recycle');
        var chkValue = getValueChecbox();
        // console.log(test);
        if (chkValue.length == 0) {
            toastr.error("Silahkan checked dahulu", 'Failed!!');
            $('#btn-recycle').prop('disabled',false).html('Recycle');
        }
        else
        {
            var url = base_url_js+'admission/master-registration/virtual-account/page-recycle-va/submit_recycle_va';
            var data = {
                                chkValue : chkValue,
                            };
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token},function (data_json) {
                var obj = JSON.parse(data_json);
                if(obj != 'Success')
                {
                    toastr.error(obj, 'Failed!!');
                }
                $('#btn-recycle').prop('disabled',false).html('Recycle');
            }).done(function() {
                setTimeout(function () {
                    loadTbl_VA(1);
                },500);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');;
              $('#btn-recycle').prop('disabled',false).html('Recycle');
            }).always(function() {
                $('#btn-recycle').prop('disabled',false).html('Recycle');
            });
        }
    });
</script>