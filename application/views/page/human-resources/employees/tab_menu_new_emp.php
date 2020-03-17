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
    .half-circle{border-radius: 10px;background: #eee;font-weight: bold;font-size: 12px;padding: 2px 5px;margin: 0px 8px }
    .half-circle.blue{background: #2fa4e7;color: #fff}
    .half-circle.orange{background: #dd5600;color: #fff}
    .bgx{border:1px solid #ddd;padding: 6px 13px;font-weight: normal;border: 1px solid rgba(0, 0, 0, 0.13);}
    .bgx.green{background-color: #51a351;color:#fff;}
    .bgx.red{background-color: #bd362f;color:#fff;}
    .bgx.blue{background-color: #3968c6;color:#fff;}

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
                        <?php 
                        $today = date("Y-m-d");
                        $birthDate = $employee->DateOfBirth;
                        $diff = date_diff(date_create($birthDate), date_create($today));
                        $myAge = $diff->format('%y');
                        ?>
                        <div class="col-sm-1">
                            <?php $imgPr = (!empty($employee->Photo) && file_exists('./uploads/employees/'.$employee->Photo)) ? base_url('uploads/employees/'.$employee->Photo) : base_url('images/icon/userfalse.png'); ?>
                            <img class="img-thumbnail" id="imgThumbnail" src="<?php echo $imgPr; ?>" style="max-width: 100px;width: 100%;">                            
                        </div>
                        <div class="col-sm-4">
                            <div class="profile-info">
                                <h3><?=(!empty($employee->TitleAhead) ? $employee->TitleAhead.' ' : '').$employee->Name.(!empty($employee->TitleBehind) ? ', '.$employee->TitleBehind : '')?></h3>
                                <h3><?=$employee->NIP?></h3>
                                <h3><?=$employee->EmailPU?></h3>
                                <h3><?=(!empty($employee->PlaceOfBirth) ? $employee->PlaceOfBirth.', ' : '').date("d F Y",strtotime($employee->DateOfBirth))?><span class="half-circle blue"><?=$myAge?> years old</span></h3>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="profile-info">
                                <h3>Division <?=$employee->DivisionMain?></h3>
                                <h3><?=$employee->PositionMain?></h3>
                                <h3>Join Date 
                                <?php if(!empty($employee->HistoricalJoin)){
                                $firstJoin = $employee->HistoricalJoin->JoinDate;
                                $diffJ = date_diff(date_create($firstJoin), date_create($today));
                                $myJobYear = $diffJ->format('%y');
                                $myJobMonth = $diffJ->format('%m');
                                $myJobDay = $diffJ->format('%d'); ?>
                                <?=date("d F Y",strtotime($employee->HistoricalJoin->JoinDate))?> <span class="half-circle orange"><?=(!empty($myJobYear) ? $myJobYear.' years '.$myJobMonth.' months' : ( !empty($myJobMonth) ? $myJobMonth.' months '.$myJobDay.' days' : (!empty($myJobDay) ? $myJobDay.' days' : '0 month') ) ) ?></span>
                                <?php }else{echo "-";} ?>
                                </h3>
                                <?php if(!empty($employee->ResignDate)){ ?> 
                                <h3>Resign Date <?=date("d F Y",strtotime($employee->ResignDate))?></h3>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-right">
                                <span class="bgx <?=(($employee->StatusEmployeeID == 2) ? 'green': ( ($employee->StatusEmployeeID == 1) ? 'blue':'red' ) )?>">
                                <i class="fa fa-handshake-o"></i> <?=strtoupper($employee->EmpStatus)?>
                                </span>
                            </div>
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
                <li class="nv-training" ><a href="<?=site_url('human-resources/employees/training/'.$NIP)?>"><i class="fa fa-list-alt"></i> Training</a></li>
                <li class="nv-experience" ><a href="<?=site_url('human-resources/employees/work-experience/'.$NIP)?>"><i class="fa fa-briefcase"></i> Work Experience</a></li>
                <li class="nv-attd" ><a href="<?=site_url('human-resources/employees/attendance/'.$NIP)?>"><i class="fa fa-calendar-check-o"></i> Attendance</a></li>
                <li class="nv-benefit" ><a href="<?=site_url('human-resources/employees/credential-benefit/'.$NIP)?>"><i class="fa fa-credit-card"></i> Credential Benefit</a></li>
              </ul>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12"> <?=$page; ?> </div>
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


    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa            = split[0].length % 3,
        rupiah          = split[0].substr(0, sisa),
        ribuan          = split[0].substr(sisa).match(/\d{3}/gi);
     
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
     
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }

    function employees() {
        var result = [];
        var filtering = "sortby=em.ID&orderby=desc";        
        var token = jwt_encode({Filter : filtering},'UAP)(*');
        $.ajax({
            type : 'POST',
            url : base_url_js+"api/database/__fetchEmployees",
            data : {token:token},
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                console.log(jqXHR);
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    if(!jQuery.isEmptyObject(response.data)){
                        $.each(response.data,function(k,v){
                            result.push(v.NIP+"/"+v.Name);                    
                        });
                    }
                }
            }
        });

        return result;
    }

    
    function companyName() {
        var result = [];
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/master-aphris/get-company",
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    $.each(response,function(k,v){
                        result.push(v.Name);                    
                    });
                }
            }
        });

        return result;
    }

    
    function bankName() {
        var result = [];
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/master-aphris/get-company-bank",
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    $.each(response,function(k,v){
                        result.push(v.Name);                    
                    });
                }
            }
        });

        return result;
    }


    function UniversityName() {
        var result = [];
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/master-aphris/get-univ",
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    $.each(response,function(k,v){
                        result.push(v.Name_University);                    
                    });
                }
            }
        });

        return result;
    }
    

    function MajorName() {
        var result = [];
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/master-aphris/get-major",
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    $.each(response,function(k,v){
                        result.push(v.Name_MajorProgramstudy);
                    });
                }
            }
        });

        return result;
    }


    function timeList() {
        var time = [];
        for (var i = 0; i < 24; i++) {
            var h=i;
            if(i < 10){ h= "0"+i; }
            for (var j = 0; j < 6; j++) {
                var m = j;
                if(j < 10){m=j+"0";}
                time.push(h+":"+m);
            };
        }
        return time;
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
        
        cloneRow.find("td input[type=text].datepicker-tmp").removeClass("hasDatepicker").attr("id","datePicker-"+fieldName+"-"+num);
        cloneRow.find("td input[type=text].datepicker-sd").removeClass("hasDatepicker").attr("id","datePickerSD-"+fieldName+"-"+num);
        
        cloneRow.find("td select.select2-tmp").attr("id","select2-"+fieldName+"-"+num).prop("disabled",false);
        cloneRow.find("td select.select2-sd").attr("id","select2SD-"+fieldName+"-"+num).prop("disabled",true);
        
        /*cloneRow.find("td .select2-term-ft").attr("id","select2-term-ft-"+fieldName+"-"+num).removeClass("select2-offscreen");
        cloneRow.find("td .select2-term-sd").attr("id","select2-term-sd-"+fieldName+"-"+num).removeClass("select2-offscreen");*/

        cloneRow.find("td input.autocomplete").attr("id","autocomplete-"+fieldName+"-"+num).removeClass("ui-autocomplete-input").removeAttr("autocomplete");
        
        cloneRow.find("td:first").text(num);
        cloneRow.find(".form-control").val("");
        parent.find("#table-list-"+fieldName+" tbody").append(cloneRow);

        cloneRow.find("td input.autocomplete").attr("id","autocomplete-start-"+fieldName+"-"+num).removeClass("ui-autocomplete-input").removeAttr("autocomplete").val("00:00");
        cloneRow.find("td input.autocomplete").attr("id","autocomplete-end-"+fieldName+"-"+num).removeClass("ui-autocomplete-input").removeAttr("autocomplete").val("00:00");
        
        /*DATEPICKER*/
        parent.find("#table-list-"+fieldName+" tbody tr > td #datePicker-"+fieldName+"-"+num).datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
        parent.find("#table-list-"+fieldName+" tbody tr > td #datePickerSD-"+fieldName+"-"+num).datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });

        /*SELECT2*/
        var select2 = parent.find("#table-list-"+fieldName+" tbody tr > td #select2-"+fieldName+"-"+num);
        select2.prev().remove();
        select2.select2({width:'100%'});
        var select2SD = parent.find("#table-list-"+fieldName+" tbody tr > td #select2SD-"+fieldName+"-"+num);
        select2SD.prev().remove();
        select2SD.select2({width:'100%'});
        
        /*AUTOCOMPLETE*/
        var companyTags = companyName();
        var autocomplete_company = parent.find("#table-list-"+fieldName+" tbody tr > td #autocomplete-experience-"+num);
        autocomplete_company.autocomplete({
          source: companyTags
        });
        
        var companyBankTags = bankName();
        var autocomplete_bank = parent.find("#table-list-"+fieldName+" tbody tr > td #autocomplete-bank-"+num);
        autocomplete_bank.autocomplete({
          source: companyBankTags
        });
        
        var employeeTags = employees();
        var autocomplete_employee = parent.find("#table-list-"+fieldName+" tbody tr > td #autocomplete-career-"+num);
        autocomplete_employee.autocomplete({
          source: employeeTags
        });
        
        var timeTags = timeList();
        var autocomplete_time = parent.find("#table-list-"+fieldName+" tbody tr > td #autocomplete-start-training-"+num);
        autocomplete_time.autocomplete({
          source: timeTags
        });
        
        var timeTags = timeList();
        var autocomplete_time = parent.find("#table-list-"+fieldName+" tbody tr > td #autocomplete-end-training-"+num);
        autocomplete_time.autocomplete({
          source: timeTags
        });



    });
    
    $("#form-employee").on("click","#multiple-field .btn-remove",function(){
        var itsme = $(this);
        var parent = itsme.parent().parent().parent().parent();
        var fieldName = parent.data("source");
        var totalRow = parent.find("#table-list-"+fieldName+" tbody tr").length;
        var lastRow = parent.find("#table-list-"+fieldName+" tbody tr:last");
        var hasAttr = lastRow.attr("data-table");
        if(typeof hasAttr !== typeof undefined && hasAttr !== false){
            if(confirm("Are you sure wants to remove this "+lastRow.data("name")+"?")){
                //if(totalRow > 1){
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
                /*}else if(totalRow == 1){
                    lastRow.find("input,select,textarea, .form-control").val("");
                }*/
                if(totalRow == 1){
                    location.reload();
                }
            }
        }else{
            if(totalRow == 1){
                lastRow.find(".form-control, input, select,textarea").val("");
            }else{
                lastRow.remove();
            }
        }
    });
    
    $("body #form-employee").on("keyup keydown",".number",function(){
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
</script>