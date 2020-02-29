<div class="row">
    <div class="col-sm-12">
        <div style="padding:5px">
            <a class="btn btn-warning" href="<?=site_url('student-life/master/company/list')?>"><i class="fa fa-chevron-left"></i> Back to list</a>
        </div>
    </div>
    <div class="col-sm-12">
        <form id="form-post-master" action="" method="post" autocomplete="off">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-edit"></i>
                        <span>Form Master Company</span>
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="hidden" name="ID" class="com-ID" id="ID">
                                <input type="text" name="Name" class="form-control required com-Name" id="Name">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="Brand" class="form-control required com-Brand" id="Brand">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Industry</label>
                                <select class="select2-select-00 select2-req com-IndustryTypeID" style="width: 100%;" size="5" id="IndustryTypeID" name="IndustryTypeID">
                                    <option value=""></option>
                                </select>
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2 oth-industry hidden">
                            <div class="form-group">
                                <label>Other Industry</label>
                                <input type="text" class="form-control com-Industry" name="Industry" id="Industry" placeholder="Type industry name">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Range Employees</label>
                                <select class="form-control com-EmployeeMemberRangeID required " id="EmployeeMemberRangeID" name="EmployeeMemberRangeID"></select>
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Number Of Branches</label>
                                <input class="form-control required number com-NumberOfBranchef" type="text" id="NumberOfBranchef" name="NumberOfBranchef" />
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Gross Revenue</label>
                                <select class="form-control required com-GrossRevenueID" id="GrossRevenueID" name="GrossRevenueID"></select>
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>                        
                    </div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control required com-Address" id="Address" name="Address" rows="5"></textarea>
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Country</label>
                                <select class="com-CountryID select2-req" id="CountryID" name="CountryID"></select>
                                <small class="text-danger text-message"></small>
                            </div>
                            <div class="form-group">
                                <label>Postcode</label>
                                <input class="form-control required number com-Postcode" type="text" id="Postcode" name="Postcode" maxlength="5">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Province</label>
                                <select class="form-control isrequire com-ProvinceID" id="ProvinceID" name="ProvinceID">
                                    <option value="" disabled selected>-- Select Province --</option>
                                    <option value="" disabled>------------</option>
                                </select>
                                <small class="text-danger text-message"></small>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control com-Phone number" type="text" id="Phone" name="Phone">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label>Region</label>
                            <select class="form-control isrequire com-RegionID" id="RegionID" name="RegionID"></select>
                            <small class="text-danger text-message"></small>
                        </div>
                        <div class="col-sm-2">
                            <label>District</label>
                            <select class="form-control isrequire com-DistrictID" id="DistrictID" name="DistrictID"></select>
                            <small class="text-danger text-message"></small>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Website official</label>
                                <input class="form-control com-Website" id="Website" name="Website">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Facebook</label>
                                <input class="form-control com-Facebook" id="Facebook" name="Facebook">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Instagram</label>
                                <input class="form-control com-Instagram" id="Instagram" name="Instagram">
                                <small class="text-danger text-message"></small>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="alert alert-info" style="padding:5px;margin:0px"><b><i class="fa fa-exclamation-triangle"></i> Please fill up this form with correctly data</b></p>                            
                        </div>
                        <div class="col-sm-8 text-right">
                            <a class="btn btn-default" href="<?=site_url('student-life/master/company/list')?>">Cancel</a>
                            <button class="btn btn-success" id="btnSave" type="button">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<textarea class="hide" id="dataUpdate"><?= json_encode($detailCompany); ?></textarea>


<script>
    function checkUpdate(){
        var dataUpdate = $('#dataUpdate').val();
        var d = (dataUpdate!='') ? JSON.parse(dataUpdate) : [];

        if(d.length>0){
            d = d[0];

            /*UPDATED BY FEBRI @ FEB 2020*/
            var DistrictID = 0; var ProvinceID = 0; var RegionID = 0;
            $.each(d,function(k,v){
                console.log(k);
                $("#form-post-master #"+k).val(v);
                if(k == "IndustryTypeID"){
                    var keyval = 60;
                    if(v != null){
                        keyval = v;
                    }
                    loadSelectOptionCompanyType('#IndustryTypeID',keyval);
                }
                if(k == "EmployeeMemberRangeID"){
                    loadSelectOptionRangeEmployees('#EmployeeMemberRangeID',v);
                }
                if(k == "GrossRevenueID"){
                    loadSelectOptionGrossRevenue('#GrossRevenueID',v);
                }
                if(k == "CountryID"){
                    loadSelectOptionCountry('#CountryID',v);
                }

                if(k == "ProvinceID"){
                    ProvinceID = v;
                    loadSelectOptionLoc_Province('#ProvinceID', v);
                }
                if(k == "RegionID"){
                    RegionID = v;
                    loadSelectOptionLoc_Regions(ProvinceID,'#RegionID',v);
                }
                if(k == "DistrictID"){
                    DistrictID = v;
                    loadSelectOptionLoc_District(RegionID,'#DistrictID',v);
                }

            });
            /*END UPDATED BY FEBRI @ FEB 2020*/

            /*
            #OLD SCRIPT BY NANDANG#

            $('#ID').val(d.ID);
            $('#Name').val(d.Name);
            $('#Brand').val(d.Brand);

            var IndustryTypeID = (d.IndustryTypeID!=null && d.IndustryTypeID!=''
                && d.IndustryTypeID!=0 && d.IndustryTypeID!='0') ? d.IndustryTypeID : '';
            loadSelectOptionCompanyType('#IndustryTypeID',IndustryTypeID);

            if(IndustryTypeID==''){
                $('#LastIndustry').html(d.Industry);
            } else {
                $('#LastIndustry').remove();
            }

            var ProvinceID = (d.ProvinceID!=null && ProvinceID!='') ? d.ProvinceID : '';
            var RegionID = (d.RegionID!=null && d.RegionID!='') ? d.RegionID : '';
            var DistrictID = (d.DistrictID!=null && d.DistrictID!='') ? d.DistrictID : '';
            loadSelectOptionLoc_Province('#ProvinceID',ProvinceID);
            if(ProvinceID!='') {

                loadSelectOptionLoc_Regions(d.ProvinceID,'#RegionID',RegionID);
            }
            if(RegionID!=''){
                loadSelectOptionLoc_District(d.RegionID,'#DistrictID',DistrictID);
            }
            loadSelectOptionRangeEmployees('#EmployeeMemberRangeID',d.EmployeeMemberRangeID);
            loadSelectOptionGrossRevenue('#GrossRevenueID',d.GrossRevenueID);
            $('#Phone').val(d.Phone);
            $('#Address').val(d.Address);

            $('#Website').val(d.Website);
            $('#Facebook').val(d.Facebook);
            $('#Instagram').val(d.Instagram);
            $('#NumberOfBranchef').val(d.NumberOfBranchef);

            #END OF NANDANG CODE#
            */

        } else {
            loadSelectOptionCountry("#CountryID",'');
            loadSelectOptionCompanyType('#IndustryTypeID','');
            //loadSelectOptionLoc_Province('#ProvinceID','');

            loadSelectOptionRangeEmployees('#EmployeeMemberRangeID','');
            loadSelectOptionGrossRevenue('#GrossRevenueID','');

            $('#IndustryTypeID').select2({width:'100%'});
        }
    }

    /*ADDED BY FEBRI @ FEB 2020*/
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
    /*END ADDED BY FEBRI @ FEB 2020*/


    $(document).ready(function () {
        checkUpdate();

        /*ADDED BY FEBRI @ FEB 2020*/
        $("#form-post-master").on("keyup keydown",".number",function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

        $("#form-post-master").on("change","#IndustryTypeID",function(){
            var value = $(this).val();
            if($.isNumeric(value)){
                if(value == 60){
                    $("#form-post-master .oth-industry").removeClass('hidden');
                    $("#form-post-master .oth-industry .com-Industry").addClass("required");
                }else{
                    $("#form-post-master .oth-industry .com-Industry").val("").removeClass("required");
                    $("#form-post-master .oth-industry").addClass('hidden');
                }
            }
        });
        
        
        $("#form-post-master").on("change","#CountryID",function(){
            var value = $(this).val();
            if($.isNumeric(value)){
                if(value == '001'){
                    $("#form-post-master .isrequire").addClass("required").prop("disabled",false);;
                    loadSelectOptionLoc_Province('#ProvinceID','');
                }else{
                    $("#form-post-master .isrequire").val("").removeClass("required").prop("disabled",true);
                    $("#form-post-master .isrequire").next().text("");
                }
            }
        });
        /*END ADDED BY FEBRI @ FEB 2020*/
        
        $('#ProvinceID').change(function () {
            var ProvinceID = $('#ProvinceID').val();
            $('#RegionID').html('<option value="" disabled selected>-- Select Region --</option>');
            $('#DistrictID').html('<option value="" disabled selected>-- Select District --</option>');
            if(ProvinceID!='' && ProvinceID!=null){
                loadSelectOptionLoc_Regions(ProvinceID,'#RegionID','');
            }
        });

        $('#RegionID').change(function () {
            var RegionID = $('#RegionID').val();
            $('#DistrictID').html('<option value="" disabled selected>-- Select District --</option>');
            if(RegionID!='' && RegionID!=null){
                loadSelectOptionLoc_District(RegionID,'#DistrictID','');
            }
        });


        /*UPDATED BY FEBRI @ FEB 2020*/
        $("#form-post-master").on("click","#btnSave",function(){
            var error = false;
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent().parent().parent();
            itsform.find(".required,.select2-req").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;                    
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            
            var totalError = itsform.find(".error").length;
            if(error && totalError == 0 ){
                var dataFormArr = itsform.serializeArray();
                var dataForm = {};
                if(!jQuery.isEmptyObject(dataFormArr)) {
                    $.each(dataFormArr,function(k,v){
                        if(v.name != 'ID'){
                            dataForm[v.name] = v.value;
                        }
                    });
                }
                
                var ID = itsform.find("input[name=ID]").val();

                var data = {
                    action : 'saveMasterCompany',
                    ID : (ID!='' && ID!=null) ? ID : '',
                    dataForm : dataForm
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudTracerAlumni';

                $.post(url,{token:token},function (result) {
                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        window.location.replace("<?=site_url('student-life/master/company/list')?>");
                    },500);
                });


            }else{
                alert("Please fill out the field.");
            }
        });
        /*END UPDATED BY FEBRI @ FEB 2020*/

        /*
        #OLD SCRIPT BY NANDANG#
        $('#btnSave').click(function () {
            var elm = $('#dataForm .form-control');
            var dataForm = '';

            var sumt = true;
            elm.each(function (i,v) {

                var koma = ((i+1)<elm.length) ? ',' : '';
                dataForm = dataForm+'"'+v.id+'":"'+v.value+'"'+koma;


                if(v.value!='' && v.value!=null){
                    $('#'+v.id).css('border','1px solid green');
                } else {
                    $('#'+v.id).css('border','1px solid red');
                    sumt = false;
                }

            });

            var IndustryTypeID = $('#IndustryTypeID').val();


            setTimeout(function () {
                $('.form-master-company').css('border','1px solid #ccc');
            },3000);




            if(sumt && IndustryTypeID!='' && IndustryTypeID!=null){

                dataForm = JSON.parse('{'+dataForm+',"IndustryTypeID":"'+IndustryTypeID+'"}');

                loading_modal_show();

                var ID = $('#ID').val();

                var data = {
                    action : 'saveMasterCompany',
                    ID : (ID!='' && ID!=null) ? ID : '',
                    dataForm : dataForm
                };

                console.log(dataForm);

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudTracerAlumni';

                $.post(url,{token:token},function (result) {
                    toastr.success('Data saved','Success');
                    // loadDataCompany();

                    setTimeout(function () {
                        window.location.href="";
                    },500);
                });

            }
            else {
                toastr.error('All form are required');
            }

        });
        #END OF OLD SCRIPT BY NANDANG
        */
    });

</script>
