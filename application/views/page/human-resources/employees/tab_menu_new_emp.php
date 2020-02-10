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
    $("#form-employee").on("click","#multiple-field .btn-add",function(){
        var itsme = $(this);
        var parent = itsme.parent().parent().parent().parent();
        var fieldName = parent.data("source");
        var cloneRow = parent.find("#table-list-"+fieldName+" tbody tr:first").clone();
        var totalRow = parent.find("#table-list-"+fieldName+" tbody tr").length;
        var num = totalRow+1;
        
        /*DATEPICKER*/
        var birthDate = cloneRow.find("#birthdate").removeClass("hasDatepicker");
        birthDate.attr("id","#birthdate-"+num).datepicker({
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });

        /*DIVISION*/
        var cloneDivition = cloneRow.find("select#divisionID");
        cloneRow.find("#s2id_divisionID").remove();
        cloneDivition.attr("id","divisionID-"+num).select2({width:'100%'});

        /*POSITION*/
        var cloneDivition = cloneRow.find("select#positionID");
        cloneRow.find("#s2id_positionID").remove();
        cloneDivition.attr("id","positionID-"+num).select2({width:'100%'});        


        cloneRow.find("td:first").text(num);
        cloneRow.find(".form-control").val("");
        parent.find("#table-list-"+fieldName+" tbody").append(cloneRow);
    });
    
    $("#form-employee").on("click","#multiple-field .btn-remove",function(){
        var itsme = $(this);
        var parent = itsme.parent().parent().parent().parent();
        var fieldName = parent.data("source");
        var totalRow = parent.find("#table-list-"+fieldName+" tbody tr").length;
        if(totalRow > 1){
            parent.find("#table-list-"+fieldName+" tbody tr:last").remove();                
        }
    });
</script>