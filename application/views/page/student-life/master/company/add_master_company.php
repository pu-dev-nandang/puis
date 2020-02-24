
<div class="row">
    <div class="col-md-8 col-md-offset-2" style="margin-bottom: 77px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Add Master Company <sup><span style="color: red;">(All form required)</span></sup></h4>
            </div>
            <div class="panel-body" id="dataForm">
                <div class="form-group">
                    <label>Company Name</label>
                    <input class="hide" id="ID">
                    <input class="form-control form-master-company" id="Name">
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input class="form-control form-master-company" id="Brand">
                </div>
                <div class="form-group">
                    <label>Industry</label>
                    <div id="LastIndustry" class="alert alert-warning" role="alert">

                    </div>
                    <div>
                        <select class="select2-select-00 form-master-company" style="width: 100%;" size="5" id="IndustryTypeID">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input class="form-control form-master-company" type="number" id="Phone">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control form-master-company" id="Address" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Province</label>
                            <select class="form-control form-master-company" id="ProvinceID">
                                <option value="" disabled selected>-- Select Province --</option>
                                <option value="" disabled>------------</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Region</label>
                            <select class="form-control form-master-company" id="RegionID"></select>
                        </div>
                        <div class="col-md-4">
                            <label>District</label>
                            <select class="form-control form-master-company" id="DistrictID"></select>
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <label>Website</label>
                    <input class="form-control form-master-company" id="Website">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook</label>
                            <input class="form-control form-master-company" id="Facebook">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Instagram</label>
                            <input class="form-control form-master-company" id="Instagram">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Range Employees</label>
                            <select class="form-control form-master-company" id="EmployeeMemberRangeID"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Number Of Branches</label>
                            <input class="form-control form-master-company" type="number" id="NumberOfBranchef" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gross Revenue</label>
                            <select class="form-control form-master-company" id="GrossRevenueID"></select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-success" id="btnSave">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<textarea class="hide" id="dataUpdate"><?= json_encode($detailCompany); ?></textarea>


<script>

    $(document).ready(function () {

        checkUpdate();
    });

    function checkUpdate(){
        var dataUpdate = $('#dataUpdate').val();
        var d = (dataUpdate!='') ? JSON.parse(dataUpdate) : [];

        if(d.length>0){
            d = d[0];

            console.log(d);

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

        } else {
            loadSelectOptionCompanyType('#IndustryTypeID','');
            loadSelectOptionLoc_Province('#ProvinceID','');

            loadSelectOptionRangeEmployees('#EmployeeMemberRangeID','');
            loadSelectOptionGrossRevenue('#GrossRevenueID','');

            $('#IndustryTypeID').select2({allowClear: true});
        }
    }

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

</script>
