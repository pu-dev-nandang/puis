<style>
    #dataTableAc tr th, #dataTableAc tr td {
        text-align: center;
    }
    .tableStd tr th, .tableStd tr td {
        text-align: center;
    }
    .tableStd tr td:first-child {
        text-align: left;
    }
</style>


<div id="generate-edom">
     <div class="row">
        <div class="col-md-12">
            <div id="filter-panel" class="collapse">
                <div class="panel panel-info">
                    <div class="panel-heading"><h5 style="margin:0px"><i class="fa fa-filter"></i> Form Filter</h5></div>
                    <div class="panel-body">
                        <form class="form-filter"> 
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <input type="text" id="FNPM" name="FNPM" class="form-control" placeholder="NPM or Name" >
                                    </div>
                                </div>
                            
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Event Name</label>
                                        <input type="text" id="FEvent" name="FEvent" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label>Category</label>
                                    <select class="form-control" name="FCategID" id="FCategID">
                                        <option value="">-Choose One-</option>
                                        <?php if(!empty($categories)){ 
                                        foreach ($categories as $c) { ?>
                                        <option value="<?=$c->ID?>"><?=$c->Name?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Level</label>
                                        <select class="form-control required Level" id="FLevel" required name="FLevel" >
                                            <option value="">Choose one</option>
                                            <option value="Provinsi">Provinsi/Wilayah</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Internasional">Internasional</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control required Level" id="FType" required name="FType" >
                                            <option value="">Choose one</option>
                                            <option value="1">Academic</option>
                                            <option value="0">Non Academic</option>
                                        </select>
                                    </div>
                                </div>
                                                
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-6">                                          
                                            <div class="form-group">
                                                <label>Start Date</label>
                                                 <input class="form-control form-update-data" id="StartDate"  style="color: #333333;background: #ffffff;" readonly />

                                            </div>                                          
                                        </div>
                                        <div class="col-sm-6">                                          
                                            <div class="form-group">
                                               <label>End Date</label>
                                                <input class="form-control form-update-data" id="EndDate"  style="color: #333333;background: #ffffff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Status Approval</label>
                                        <select class="form-control required FTIsApproved" id="FTIsApproved" required name="FTIsApproved" >
                                            <option value="">Choose one</option>
                                            <option value="1">Need Approval</option>
                                            <option value="2">Approved</option>
                                            <option value="3">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                                
                                <!-- <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>SKPI</label>
                                        <select class="form-control required FTIsSKPI" id="FTIsSKPI" required name="FTIsSKPI" >
                                            <option value="">Choose one</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div> -->

                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-default" id="clearBtn">Clear</a>
                                        <button class="btn btn-sm btn-info btn-search" type="button" onclick="loadDataRequest()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default">                                
                <div class="panel-body">
                    <div class="row" style="margin-bottom:10px">
                        <div class="col-sm-12">
                            <button id="filterbtn" class="btn btn-sm btn-info btn-open-filter" type="button" data-toggle="collapse" data-target="#filter-panel" aria-expanded="false" aria-controls="filter-panel">
                                <i class="fa fa-filter"> <span>Filter</span></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">                                        
                        <div id="loadTable"></div>
                    </div>
                </div>
            </div>
                      
        </div>
    </div> 
</div>

<script>
    $('#clearBtn').click(function () {
        $('.form-filter')[0].reset();

        loadDataRequest();
    });

    $(document).ready(function(){
       loadDataRequest();

        $(".btn-open-filter").click(function(){
            var isOpen = $(this).attr("aria-expanded");
            if(isOpen == "false"){
                $("button").attr("aria-expanded","true");
                document.getElementById("filterbtn").style.backgroundColor = "#bd362f"; 
                $(this).find("i.fa").toggleClass("fa-filter fa-times");
                $(this).find("span").text("Close Filter");
            }else{
                $("button").attr("aria-expanded","false");
                document.getElementById("filterbtn").style.backgroundColor = "#34a7c8"; 
                $(this).find("i.fa").toggleClass("fa-times fa-filter");
                $(this).find("span").text("Filter");                
            }
        });
        

        $( "#StartDate,#EndDate" )
            .datepicker({
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true
            });
    });

    function loadDataRequest() {
         var EventName = $("#FEvent").val();
            var std = $("#FNPM").val();
            var categ = $("#FCategID").val();
            var lvl = $("#FLevel").val();
            var type = $("#FType").val();
            var sDate = $('#StartDate').val();
            var eDate = $('#EndDate').val();
            var isAppr = $("#FTIsApproved").val();
        $('#loadTable').html('<table id="tableDataSA" class="table table-bordered table-striped table-centre" style="width:100%";>' +
            '               <thead>' +
            '                <tr style="background: #337ab7; color:#fff;">' +
            '                   <th style="width: 1%;">No</th>'+
            '                    <th style="width: 25%;">Event Name</th>'+
            '                    <th style="width: 10%;">Event Date</th>'+
            '                    <th style="width: 5%;">Category</th>'+
            '                    <th style="width: 5%;">Level</th>'+
            '                    <th style="width: 5%;">Type</th>'+
            '                    <th style="width: 10%;">Achievement</th>'+
            '                    <th style="width: 5%;">Certificate</th>'+
            '                    <th style="width: 10%;">Status Approval</th>'+
            '                    <th style="width: 5%;"><i class="fa fa-cog"></i></th>'+
            '                    <th style="width: 15%;">Member</th>'+
            '                </tr>' +
            '                </thead>' +
            '           </table>');

           
          
        var data = {
            action : 'viewData',
            EventName : EventName,
            std : std,
            categ : categ,
            lvl : lvl,
            type : type,
            sDate : sDate,
            eDate : eDate,
            isAppr : isAppr,
        };
           
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+"student-life/student-achievement/fetchData";
        var dataTable = $('#tableDataSA').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Event, Member"
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    // loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
    
        

        
    

</script>
