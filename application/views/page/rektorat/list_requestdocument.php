
<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 17px;
    text-align: center;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}

</style> 


<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> List Request Document</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <table class="table table-bordered table-striped" id="tablerequestdoc">
                    <thead>
                        <tr style="background: #3968c6;color: #FFFFFF;">
                            <th style="width: 1%;">No</th>
                            <th class="th-center" style="width: 10%;">NIP & Name</th>
                            <th class="th-center" style="width: 10%;">Type Request</th>
                            <th class="th-center" style="width: 8%;">Date Request</th>
                            <th class="th-center" style="width: 12%;">For Request</th>
                            <th class="th-center" style="width: 8%;">Start Date</th>
                            <th class="th-center" style="width: 8%;">End Date</th>
                            <th class="th-center" style="width: 20%;">Description Event</th>
                            <th class="th-center" style="width: 14%;">Confirmation</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        load_documentrequestlist('');
    });

    function load_documentrequestlist(status) {
        var dataTable = $('#tablerequestdoc').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getreqdocument?s="+status, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>

<script>
    
    $(document).on('click','.btnapproved',function () {

        if (confirm('Are you sure Approved Request?')) {

            var requestID = $(this).attr('requestid');
            if(requestID!=null && requestID!='') { 

                var data = {
                action : 'Approved',
                formInsert : {
                    requestID : requestID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__confirmrequest';
                $.post(url,{token:token},function (result) {
                    
                    if(result==0 || result=='0'){ 
                    } 
                    else { 
                        toastr.success('Success Approved Request','Success');
                        load_documentrequestlist();
                    }
                });
            }  
            else {
                toastr.error('Confirmation Error!','Error');
                return;
            }
        } 
    else { 
        return;
    }
});

</script>


<script>
$(document).on('click','.btnrejected',function () {
        
    if (confirm('Are you sure Rejected Request?')) {

        var requestID = $(this).attr('requestid'); 
        
        if(requestID!=null && requestID!='') { 
    
            var data = {
                action : 'Rejected',
                formInsert : {
                    requestID : requestID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__confirmrequest';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Success Rejected Request!','Success');
                    load_documentrequestlist();
                }
            });
        }
        else {
            toastr.error('Confirmation Error!!','Error');
            return;
        }

    } else {
        return;
    }
});

</script>




