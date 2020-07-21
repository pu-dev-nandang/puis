<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php $message = $this->session->flashdata('message');
if(!empty($message)){ ?>
<script type="text/javascript">
$(document).ready(function(){
    toastr.success("<?= $this->session->flashdata('message');?>",'Info!');
});
</script>
<?php } ?>
<script type="text/javascript">
    function fetchAdditionalData(NIP) {
        var data = {
          NIP : NIP,
        };
        var token = jwt_encode(data,'UAP)(*');
        var result = null;
        $.ajax({
            type : 'POST',
            url : base_url_js +"human-resources/employees/detail",
            data : {token:token},
            dataType : 'json',
            async: false,
            beforeSend :function(){
                loading_modal_show();
            },error : function(jqXHR){
                loading_modal_hide();                
                $('#globalModalLarge .modal-header').html("Failed Error");
                $('#globalModalLarge .modal-body').html(jqXHR.responseText);
                $('#globalModalLarge').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });
            },success : function(response){
                loading_modal_hide();
                result = response;
            }
        });

        var tempDataEmp = {};
        <?php if(!empty($param)){ ?>
        tempDataEmp = jQuery.parseJSON('<?=$Logs?>');
        <?php } ?>

        //compare
        if(!jQuery.isEmptyObject(tempDataEmp)){
            var result = getDifferences(result,tempDataEmp);
            /*console.log(result);
            console.log(tempDataEmp);
            console.log(result);*/
        }

        return result;
    }

    function getDifferences(oldObj, newObj) {
       var diff = {};

       for (var k in oldObj) {
            if(k in newObj){
                diff[k] = newObj[k];
            }else{
                diff[k] = oldObj[k];
            }
       }

       for (k in newObj) {
          if (!(k in oldObj)){
             diff[k] = newObj[k]; // property is new
          }
       }

       return diff;
    }

    function loadYearOfBirth(element,selected){
        $(element).empty();
        var thisYear = (new Date()).getFullYear();
        var startTahun = parseInt(thisYear) - parseInt(120);
        var selisih = parseInt(thisYear) - parseInt(startTahun);
        for (var i = 0; i <= selisih; i++) {
            var valTh = ( parseInt(startTahun) + parseInt(i));
            var sc = (selected!='' && typeof selected !== "undefined" && valTh == selected ) ? 'selected' : '';
            $(element).append('<option value="'+valTh+'" '+sc+'>'+valTh+'</option>');
        }
    }

    function loadMonthBirth(element,selected){
        $(element).empty();
        var month = {
            01 : 'Jan',
            02 : 'Feb',
            03 : 'Mar',
            04 : 'April',
            05 : 'Mei',
            06 : 'Jun',
            07 : 'Jul',
            08 : 'Aug',
            09 : 'Sep',
            10 : 'Okt',
            11 : 'Nov',
            12 : 'Des'
        };

        for(var key in month) {
            var getKey = key.toString();
            var value = (getKey.length == 1) ? '0' + getKey : key;

            var sc = (selected!='' && selected!=null && typeof selected !== "undefined" && value==selected)? 'selected' : '';
            $(element).append('<option value="'+ value +'" '+sc+'>'+month[key]+'</option>');
        }

    }

    function loadCountDays(Year,Month,element,selected){

        $(element).empty();
        var countDays = moment(Year+"-"+Month, "YYYY-MM").daysInMonth()
        // get dd
        for (var i = 1; i <= countDays ; i++) {

            var getKey = i.toString();
            var value =  (getKey.length == 1) ? '0' + getKey : value = i;

            var sc = (selected!='' && typeof selected !== "undefined" && value == selected) ? 'selected' : '';

            $(element).append('<option value="'+ value +'" '+sc+'>'+value+'</option>');
        }
    }


    function loadSelectOptionMaritalStatus(element,selected) {
        var url = base_url_js+'api/__getMaritalStatus';

        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = (selected!='' && typeof selected !== "undefined" && d.ID == selected) ? 'selected' : '';
                $(element).append('<option value="'+d.ID+'" '+sc+'>'+d.name+'</option>');
            }
        });
    }


    function loadSelectOptionReligi(element,selected) {
        var url = base_url_js+'api/__getAgama';

        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = (selected!='' && typeof selected !== "undefined" && d.IDReligion == selected) ? 'selected' : '';
                $(element).append('<option value="'+d.IDReligion+'" '+sc+'>'+d.Religion+'</option>');
            }
        });
    }

    function loadSelectOptionCountry(element,selected) {
        var url = base_url_js+'api/__getCountry';
        $.getJSON(url,function (jsonResult) {
            $(element).append('<option>-- Select Country --</option>');
            $.each(jsonResult,function (i,v) {
                var sc = (selected==v.ctr_code) ? 'selected' : '';
                $(element).append('<option value="'+v.ctr_code+'" '+sc+'>'+v.ctr_name+'</option>');
            })
            $(element).select2({'width':'100%'});
        });
    }

    function loadSelectOptionLoc_Province(element,selected){
        var url = base_url_js+'api/__getProvince';
        $.getJSON(url,function (jsonResult) {
            $.each(jsonResult,function (i,v) {

                var sc = (selected==v.ProvinceID) ? 'selected' : '';
                $(element).append('<option value="'+v.ProvinceID+'" '+sc+'>'+v.ProvinceName+'</option>');

            })
        });
    }

    function loadSelectOptionLoc_Regions(ProvinceID,element,selected){
        var url = base_url_js+'api/__getRegions/'+ProvinceID;
        $.getJSON(url,function (jsonResult) {
            $.each(jsonResult,function (i,v) {

                var sc = (selected==v.RegionID) ? 'selected' : '';
                $(element).append('<option value="'+v.RegionID+'" '+sc+'>'+v.RegionName+'</option>');

            })
        });
    }

    function loadSelectOptionLoc_District(RegionID,element,selected){
        var url = base_url_js+'api/__getDistric/'+RegionID;
        $.getJSON(url,function (jsonResult) {
            $.each(jsonResult,function (i,v) {

                var sc = (selected==v.DistrictID) ? 'selected' : '';
                $(element).append('<option value="'+v.DistrictID+'" '+sc+'>'+v.DistrictName+'</option>');

            })
        });
    }

    function loadSelectOptionLevelEducation(element,selected) {
        var url = base_url_js+"api/__getLevelEducation";
        $.get(url,function (jsonResult) {

            $.each(jsonResult,function (i,v) {

                var sc = (selected!='' && typeof selected !== 'undefined' && parseInt(selected) == parseInt(v.ID))
                    ? 'selected' : '';
                $(element).append('<option value="'+v.ID+'" '+sc+'>'+v.Level+' - '+v.Description+'</option>');
            });

        });
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

    $(document).ready(function(){
        $("body #container.fixed-header").addClass("sidebar-closed");
        $("body").on("keyup keydown",".number",function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
        <?php if($employee->isApproved == 1){ ?>
        $("#form-employee > .middle form > .panel > .panel-footer > button").hide(); 
        <?php } ?>
        <?php if($employee->isApproved != 0){ ?>
        $("#form-employee").on("click","#btn-detail-req",function(){
            var itsme = $(this);
            var NIP = itsme.data("nip");
            var data = {
              NIP : NIP,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.ajax({
                type : 'POST',
                url : base_url_js+"profile/detail-request/"+NIP,
                data : {token:token},
                dataType : 'html',
                beforeSend :function(){
                    loading_modal_show();
                    $(".mailing-list .detailMail").addClass("hidden");
                },error : function(jqXHR){
                    loading_modal_hide();
                    $('#GlobalModalLarge .modal-header').html("Failed Error");
                    $('#GlobalModalLarge .modal-body').html(jqXHR.responseText);
                    $('#GlobalModalLarge').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                },success : function(response){
                    loading_modal_hide();
                    $(".load-request").html(response);
                }
            });
        });
        <?php } ?>
        $("#ProfilePicture,#imgThumbnail").attr("src",window.sessionUrlPhoto);


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
            cloneRow.find("input[type=file]").val("");
            cloneRow.find(".fetch-file").remove();
            parent.find("#table-list-"+fieldName+" tbody").append(cloneRow);

            /*cloneRow.find("td input.autocomplete").attr("id","autocomplete-start-"+fieldName+"-"+num).removeClass("ui-autocomplete-input").removeAttr("autocomplete").val("00:00");
            cloneRow.find("td input.autocomplete").attr("id","autocomplete-end-"+fieldName+"-"+num).removeClass("ui-autocomplete-input").removeAttr("autocomplete").val("00:00");*/
            
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
                            beforeSend :function(){loading_modal_show();},
                            error : function(jqXHR){
                                loading_modal_hide();
                                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                                $("body #GlobalModal").modal("show");
                            },success : function(response){
                                console.log(response);
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

    });
</script>

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
    <div class="top">
        <div class="info-general">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <?php 
                        $today = date("Y-m-d");
                        $birthDate = $employee->DateOfBirth;
                        $diff = date_diff(date_create($birthDate), date_create($today));
                        $myAge = $diff->format('%y');
                        ?>
                        <div class="col-sm-1 text-center">
                            <img class="img-thumbnail" id="ProfilePicture" style="max-width: 100px;width: 100%;" src="data:image/png;base64,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fzkCKieEB5dt8Lp19+h/Vm0YUjPHghUAAkAACAASeuhMxcn88f6NBkDBfStWef264YEdGyxXASAABIAAICkXwuAt0256+RcM+3Sx1/AWPPrRiTCg9W3LVQAIAAEgAEjoC2grV93y8i+4c11F/t4BXs8bFD1e9o7T8yxWASAABIAAIBmj5h/Mf57f3QCIDdk0PfpMu8bregMe3LzFUhUAAkAACACSutvf+egGP6/1yPIvGLz1rfD47DNe3+t932JBdRjYPM1SFQACQAAIABJ64tyni3p0+V++a+D21/JfKvQad+3OE4ssVAEgAASAACChJ859tKsoy/8vdw18Kf8wIa/15z28fqdlKgAEgAAQACRjzOzTod/uF4saALG+uyeFkQuqvOaF133emTCw8T3LVAAIAAEgAEjGHRtnFH35X7518N5no9OG3V73+COXI0stUgEgAASAALAQEvr2+bL1JVv+V941ML7LYFl/5LJmryUqAASAABAAAiChG8/MPZb/F3mpA+DPxocHlq8tzwcsVZwLgxqmW6ICQAAIAAEgAJL4yV9tGLT1Twkt/7+4Z/Wysnvt76laYYEKAAEgAASAAEhoCUWLN+nlX3DX2gX5O+GVw+s+csUBy1MACAABIAAEQEJLaOG+8LXK8akJgNgdGz7M34go06cus8+HwRc+tDwFgAAQAAJAACTw+fOsc6H/zimpWv4Ft2+elv/zZfW1v3fvpxanABAAAkAACICE7joXPaQnjcu/YOC2N8KYOaey94yFZYdD/7a3LE4BIAAEgAAQAAncde7jrale/gX9d7wS/ULheHaO/mfVhiE1sy1NASAABIAAEAAJPHBmTnXou+f5XhEA+VsHR3cmHDX/UCZe+/t3rrcwBYAAEAACQAAk4/bN7/Wa5X/5roF7ngsjFlX27nstLD4eBrS+bWEKAAEgAASAAEjgX6Ar1vS65X/5roF7J4bhS7b3ztc++mnjHWfmWZYCQAAIAAEgAEpv9PzD0a13n+m1AZAX/WTxwWUbet9tlrdssSgFgAAQAAJAACRxt7/zYeD213v38r/CfStX9p7vXCysDgObp1mUAkAACAABIABKb9inH2Vm+RfE/z/1ip9bnlxoSQoAASAABIAASOBpc9Ejd7O2/AuGrp+Tf5ZBan9uuWGHBSkABIAAEAACoPTGzD4T+u2anNkAiA3ZND36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ANy1doHFm6aj//pXLRAEgAAQAAKguAEwfPEOSzdNR/8nJ1keCAABIAAEQHEDYMycU6Hv7hcs3rQ4MCE6+vekPwSAABAAAqDIATBk03RLN023+73wisWBABAAAkAAFDcAHli+1tJN09H/8ectDQSAABAAAqC4ATB63pHQZ+9EizctqqIn/bU4+kcACAABIACKGABjZ9aGgdvesHTTdPR/foqFgQAQAAJAABQ3AIat+djSTdNv/o89Z1kgAASAABAAxQ2AEYv2hq9Vjrd4U/Okv+jov2mqZYEAEAACQAAULwAen3029Nv1kqWbpqP/cy9ZFAgAASAABEBxA2Do+jmWbpqO/o88a0kgAASAABAAxQ2Ahz7eYumm7ei/8XVLAgEgAASAACheADxWcTLctud5SzdNv/k/M9mCQAAIAAEgAIoZABfC4C3TLN00Hf0fnhj6t3nSHwJAAAgAAVDEALhv5SpLN21P+rv4muWAABAAAkAAFC8ARs0/GPpUTrB003T0f+pFiwEBIAAEgAAoXgCMnVUTBux4zdJN09H/wWcc/SMABIAAEADFDYC71y60dNN29F//qqWAABAAAkAAFC8Ahi/eaeGm7ei/epKFgAAQAAJAABQvAMbMOR367n7R0k2TAxNC/1ZH/wgAASAABEARA2DIxg8s3LTd7vfCK5YBAkAACAABULwAeOCTdRZu2o7+TzxvESAABIAAEADFC4DR846G2/ZOtHTTpGp86N/yhkWAABAAAkAAFCcAxs6sDYO2/snCTdvRf+3LlgACQAAIAAFQvAC4Z/UyCzdtv/k/9pwFgAAQAAJAAHQ/AN7f19rl8h+5cF/4WuV4SzdlR//9mqdaAJS933SsFgACQAB0NwCWHm27avk/Putc6L9zioWbtqP/mpdc/CHyh85NAkAACIDuBkBNcy48MfPzAXDnugoLN21H/0eedeGHS1blTggAASAAuhsA8fx+fdPl5f/wx1st3LTZHx39N77uwg+R/9QxN3SGnAAQAAKgJwLgQksuPLmwITxWcTL03fO8hZu2o/+zk134IXJn+7SwM1fTreudABAAAuALU93YGb67Z5GFm7aj/8MTPekPIo+0fxA25c50+1onAASAAOhi2nOdYWb97vCTEzPD8INTwl1Vk0jYvY1vh/va34OyNDxa+k92fBRe79wdGkN7j1znBIAAEADGGFOG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">
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
                                <h3>Department <?=$employee->DivisionMain?></h3>
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
                                <p><span class="bgx <?=(($employee->StatusEmployeeID == 2) ? 'green': ( ($employee->StatusEmployeeID == 1) ? 'blue':'red' ) )?>">
                                <i class="fa fa-handshake-o"></i> <?=strtoupper($employee->EmpStatus)?>
                                </span></p>
                                <?php if($employee->isApproved != 0 && empty($param) ){ ?>
                                <div class="btn-group" style="margin-top:10px;">
                                    <button class="btn btn-sm btn-<?=($employee->isApproved == 2 ) ? 'danger':'default'?>" type="button" id="btn-detail-req" data-nip="<?=$employee->NIP?>"><i class="fa fa-<?=($employee->isApproved == 2 ) ? 'user-times fa-3x':'hourglass-end fa-spin'?>"></i><br>
                                        <?=(($employee->isApproved == 3) ? "You've made a data change.":(($employee->isApproved == 1) ? "Your request has been processed successfully <br>and waiting for verification by HR.":"Your request has been rejected.") )?><br><b>Click here to see the detail.</b>
                                    </button>
                                </div>
                                <?php }
                                if(!empty($param)){ ?>
                                <div class="btn-group" style="margin-top:10px;">
                                    <button class="btn info-custome" type="button">
                                    <i class="fa fa-edit fa-2x"></i><br>
                                    <span>Your in custome mode by your requested</span></button>
                                </div>
                                <?php } ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navigation-tabs">
            <div class="row">
                <div class="col-sm-12">
                    <div class="tabulasi-emp">
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nv-personal active" ><a href="<?=site_url('profile/'.$NIP.(!empty($param) ? $param:''))?>" ><i class="fa fa-user"></i> Personal Data</a></li>
                        <li class="nv-career" ><a href="<?=site_url('profile/career-level/'.$NIP)?>"><i class="fa fa-suitcase"></i> Career Level</a></li>
                        <li class="nv-additional" ><a href="<?=site_url('profile/additional-info/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-user-plus"></i> Additional Information</a></li>
                        <li class="nv-family" ><a href="<?=site_url('profile/family/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-users"></i> Family</a></li>
                        <li class="nv-edu" ><a href="<?=site_url('profile/educations/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-graduation-cap"></i> Educations</a></li>
                        <li class="nv-training" ><a href="<?=site_url('profile/training/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-list-alt"></i> Training</a></li>
                        <li class="nv-experience" ><a href="<?=site_url('profile/work-experience/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-briefcase"></i> Work Experience</a></li>
                        <li class="nv-department" ><a href="<?=site_url('profile/department/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-building"></i> Department Member</a></li>
                        <li class="nv-signature" ><a href="<?=site_url('profile/signature/'.$NIP.(!empty($param) ? $param:''))?>"><i class="fa fa-pencil-square-o"></i> Signature</a></li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="load-request"></div>
    </div>
    <div class="middle">
        <?=$page?>
    </div>
</div>