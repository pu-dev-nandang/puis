<?php $message = $this->session->flashdata('message');
    if(!empty($message)){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
    });
    </script>
<?php } ?>

<style type="text/css">
    #form-employee .tabulasi-emp > ul > li.active > a{background:#428bca;color:#fff;border:1px solid #428bca;}
    #form-employee .cursor{cursor: pointer;}
    #form-employee .cursor-disable{cursor: no-drop;}
    #form-employee .profile-info > h3{margin:0px;padding: 5px}
    #form-employee .profile-info > h3:first-child{font-weight: bold;text-transform: uppercase; }
</style>
<div id="form-employee">
    <div class="row">
        <div class="col-sm-12" style="margin-bottom:20px">
            <a class="btn btn-warning" href="<?=site_url('human-resources/employees')?>"><i class="fa fa-angle-double-left"></i> Back to list</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-1">
                            <?php $imgPr = (!empty($employee->Photo) && file_exists('./uploads/employees/'.$employee->Photo)) ? base_url('uploads/employees/'.$employee->Photo) : base_url('images/icon/userfalse.png'); ?>
                            <img id="imgThumbnail" src="<?php echo $imgPr; ?>" style="max-width: 100px;width: 100%;">                            
                        </div>
                        <div class="col-sm-10">
                            <div class="profile-info">
                                <h3><?=$employee->Name?></h3>
                                <h3><?=$employee->NIP?></h3>
                                <h3><?=$employee->EmailPU?></h3>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-primary btn-print btn-sm" type="button"><i class="fa fa-print"></i> Print ID Card</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="tabulasi-emp">
              <ul class="nav nav-tabs" role="tablist">
                <li class="nv-personal" ><a href="<?=site_url('human-resources/employees/edit-employees/'.$NIP)?>" ><i class="fa fa-user"></i> Personal Data</a></li>
                <li class="nv-career" ><a href="<?=site_url('human-resources/employees/career-level/'.$NIP)?>"><i class="fa fa-suitcase"></i> Career Level</a></li>
                <li class="nv-additional" ><a href="<?=site_url('human-resources/employees/additional-info/'.$NIP)?>"><i class="fa fa-user-plus"></i> Additional Information</a></li>
                <li class="nv-family" ><a href="<?=site_url('human-resources/employees/family/'.$NIP)?>"><i class="fa fa-users"></i> Family</a></li>
                <li class="nv-edu" ><a href="<?=site_url('human-resources/employees/educations/'.$NIP)?>"><i class="fa fa-graduation-cap"></i> Educations</a></li>
                <li class="nv-experience" ><a href="<?=site_url('human-resources/employees/work-experience/'.$NIP)?>"><i class="fa fa-briefcase"></i> Work Experience</a></li>
                <li class="nv-attd" ><a href="<?=site_url('human-resources/employees/attendance/'.$NIP)?>"><i class="fa fa-calendar-check-o"></i> Attendance</a></li>
              </ul>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12"> <?php echo $page; ?> </div>
    </div>
</div>


<script type="text/javascript">
    function fetchAdditionalData(NIP) {
        var data = {
          NIP : NIP,
        };
        var token = jwt_encode(data,'UAP)(*');
        var result = null;
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/employees/detail",
            data : {token:token},
            dataType : 'json',
            async: false,
            beforeSend :function(){
                //loading_modal_show();
                $(".mailing-list .detailMail").addClass("hidden");
            },error : function(jqXHR){
                //loading_modal_hide();
                $("body #modalGlobal .modal-body").html(jqXHR.responseText);
                $("body #modalGlobal").modal("show");
            },success : function(response){
                result = response;
            }
        });

        return result;
    }
    $("#form-employee").on("click","#multiple-field .btn-add",function(){
        var itsme = $(this);
        var parent = itsme.parent().parent().parent().parent();
        var fieldName = parent.data("source");
        var cloneRow = parent.find("#table-list-"+fieldName+" tbody tr:first").clone();
        var totalRow = parent.find("#table-list-"+fieldName+" tbody tr").length;
        var num = totalRow+1;

        var hasAttr = cloneRow.attr("data-table");
        if(typeof hasAttr !== typeof undefined && hasAttr !== false){
            cloneRow.removeAttr("data-table").removeAttr("data-id").removeAttr("data-name");
        }
        
        /*DIVISION*/
        /*var cloneDivition = cloneRow.find("select#divisionID");
        cloneRow.find("#s2id_divisionID").remove();
        cloneDivition.attr("id","divisionID-"+num).select2({width:'100%'});*/

        /*POSITION*/
        /*var cloneDivition = cloneRow.find("select#positionID");
        cloneRow.find("#s2id_positionID").remove();
        cloneDivition.attr("id","positionID-"+num).select2({width:'100%'});*/  

        cloneRow.find("td input[type=text].datepicker-tmp").removeClass("hasDatepicker").attr("id","datePicker-"+fieldName+"-"+num);
        cloneRow.find("td input[type=text].datepicker-sd").removeClass("hasDatepicker").attr("id","datePickerSD-"+fieldName+"-"+num);
        cloneRow.find("td select.select2-tmp").attr("id","select2-"+fieldName+"-"+num);
        cloneRow.find("td select.select2-sd").attr("id","select2SD-"+fieldName+"-"+num);


        cloneRow.find("td:first").text(num);
        cloneRow.find(".form-control").val("");
        parent.find("#table-list-"+fieldName+" tbody").append(cloneRow);
        
        /*DATEPICKER*/
        parent.find("#table-list-"+fieldName+" tbody tr > td #datePicker-"+fieldName+"-"+num).datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });;
        parent.find("#table-list-"+fieldName+" tbody tr > td #datePickerSD-"+fieldName+"-"+num).datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });;
        /*SELECT2*/
        var select2 = parent.find("#table-list-"+fieldName+" tbody tr > td #select2-"+fieldName+"-"+num);
        select2.prev().remove();
        select2.select2({width:'100%'});
        var select2 = parent.find("#table-list-"+fieldName+" tbody tr > td #select2SD-"+fieldName+"-"+num);
        select2.prev().remove();
        select2.select2({width:'100%'});


    });
    
    $("#form-employee").on("click","#multiple-field .btn-remove",function(){
        var itsme = $(this);
        var parent = itsme.parent().parent().parent().parent();
        var fieldName = parent.data("source");
        var totalRow = parent.find("#table-list-"+fieldName+" tbody tr").length;
        if(totalRow > 1){
            var lastRow = parent.find("#table-list-"+fieldName+" tbody tr:last");
            var hasAttr = lastRow.attr("data-table");
            if(typeof hasAttr !== typeof undefined && hasAttr !== false){
                if(confirm("Are you sure wants to remove this bank "+lastRow.data("name")+"?")){
                    var data = {
                      ID : lastRow.data("id"),
                      TABLE : lastRow.data("table")
                    };
                    var token = jwt_encode(data,'UAP)(*');
                    $.ajax({
                        type : 'POST',
                        url : base_url_js+"human-resources/employees/remove-additional",
                        data : {token:token},
                        dataType : 'json',
                        beforeSend :function(){loading_modal_show()},
                        error : function(jqXHR){
                            loading_modal_hide();
                            $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                            $("body #GlobalModal").modal("show");
                        },success : function(response){
                            loading_modal_hide();
                            if(jQuery.isEmptyObject(response)){
                                alert("Data not founded. Try again.");
                            }else{
                                lastRow.remove();
                                toastr.info(""+response.message,'Info!');
                            }
                        }
                    });
                }
            }else{lastRow.remove();}
        }
    });
</script>