<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-benefit").addClass("active");        

        $("#credential-benfit").on("keyup",".rupiah",function(){
            var value = $(this).val();
            if($.trim(value).length > 0){
                var money = formatRupiah(value,"");
                $(this).val(money);                
            }
        });

        $("#datePicker-parking,#datePickerSD-parking").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });

        var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            //BPJS NUMBER
            $("#salary-emp").find(".salary-price").val((jQuery.isEmptyObject(myData.Salary)) ? 0 : myData.Salary);
            $("#bpjs-permit").find(".bpjs-IDBpjstk").val(myData.IDBpjstk);
            $("#bpjs-permit").find(".bpjs-IDBpjspensiun").val(myData.IDBpjspensiun);
            $("#bpjs-permit").find(".bpjs-IDBpjskesehatan").val(myData.IDBpjskesehatan);
        }

        $("#credential-benfit").on("click","#form-multiple-pane .btn-submit",function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            itsform.find(".required").each(function(){
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
                loading_modal_show();
                itsform[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });
    });
</script>
<div class="panel panel-primary" id="credential-benfit">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-5">
                <div id="salary-emp">
                    <form id="form-multiple-pane" action="<?=base_url('human-resources/employees/additional-info-save')?>" method="post" >
                        <input type="hidden" name="action" value="credential-benefit">
                        <input type="hidden" name="NIP" value="<?=$NIP?>">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Salary Employee
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Salary</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">RP</div>
                                        <input type="text" class="form-control required number rupiah salary-price" name="salary">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="bpjs-permit">
                    <form id="form-multiple-pane" action="<?=base_url('human-resources/employees/credential-benefit-bpjs-save')?>" method="post" >
                        <input type="hidden" name="NIP" value="<?=$NIP?>">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    BPJS
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-data-list">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Type</th>
                                                <th>Card Number</th>
                                                <th width="20%">Salary cuts for employer</th>
                                                <th width="20%">Salary cuts for employee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Ketenagajerjaan</td>
                                                <td><input type="hidden" name="Type[]" class="form-control required number bpjs-Type" value="1">
                                                    <input type="text" name="CardNumber[]" class="form-control required number bpjs-IDBpjstk" readonly></td>
                                                <td><div class="input-group">
                                                        <input type="text" name="CutsEmployer[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="CutsEmployee[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Pensiun</td>
                                                <td><input type="hidden" name="Type[]" class="form-control required number bpjs-Type" value="2">
                                                    <input type="text" name="CardNumber[]" class="form-control required number bpjs-IDBpjspensiun" readonly></td>
                                                <td><div class="input-group">
                                                        <input type="text" name="CutsEmployer[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="CutsEmployee[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Kesehatan</td>
                                                <td><input type="hidden" name="Type[]" class="form-control required number bpjs-Type" value="3">
                                                    <input type="text" name="CardNumber[]" class="form-control required number bpjs-IDBpjskesehatan" readonly></td>
                                                <td><div class="input-group">
                                                        <input type="text" name="CutsEmployer[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="CutsEmployee[]" class="form-control required number" maxlength="3">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>               

            </div>
            <div class="col-sm-7">
                <div id="parking-permit">
                    <form id="form-multiple-pane" action="<?=base_url('human-resources/employees/credential-benefit-parking-save')?>" method="post" >
                        <input type="hidden" name="NIP" value="<?=$NIP?>">
                        <div class="panel panel-default" id="multiple-field" data-source="parking">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-xs btn-add" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-default btn-xs btn-remove" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <h4 class="panel-title">
                                    Parking Permit
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-list-data">
                                    <table class="table table-bordered" id="table-list-parking">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Transportation</th>
                                                <th>License Plat</th>
                                                <th colspan="2">Validity Period</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><input type="hidden" class="form-control required parking-ID" name="ID[]" >
                                                <select class="form-control required parking-transportationType" name="transportationType[]">
                                                    <option value="">Choose one</option>
                                                    <option value="1">Motorcycle</option>
                                                    <option value="2">Car</option>
                                                </select></td>
                                                <td><input type="text" class="form-control required parking-licensePlat" name="licensePlat[]" ></td>
                                                <td><input type="text" class="form-control required parking-startDate datepicker-tmp" id="datePicker-parking" name="startDate[]" placeholder="Start Date"></td>
                                                <td><input type="text" class="form-control required parking-endDate datepicker-sd" id="datePickerSD-parking" name="endDate[]" placeholder="End Date" ></td>
                                                <td><select class="form-control required parking-status" name="status[]">
                                                    <option value="">Choose one</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Non Active</option>
                                                </select></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="allowance-permit">
                    <form id="form-multiple-pane" action="<?=base_url('human-resources/employees/credential-benefit-allowance-save')?>" method="post" >
                        <input type="hidden" name="NIP" value="<?=$NIP?>">
                        <div class="panel panel-default" id="multiple-field" data-source="allowance">
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-xs btn-add" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-default btn-xs btn-remove" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <h4 class="panel-title">
                                    Allowance
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-list-data">
                                    <table class="table table-bordered" id="table-list-allowance">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><input type="hidden" class="form-control required allowance-ID" name="ID[]" >
                                                <input type="text" name="name[]" class="form-control required allowance-name"></td>
                                                <td><div class="input-group">
                                                      <div class="input-group-addon">RP</div>
                                                        <input type="text" name="price[]" class="form-control required allowance-price number rupiah"></td>
                                                    </div>
                                                <td><textarea class="form-control allowance-note" rows="1" name="note[]"></textarea></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tax-permit">
                    <form id="form-multiple-pane" action="<?=base_url('human-resources/employees/credential-benefit-tax-save')?>" method="post" >
                        <input type="hidden" name="NIP" value="<?=$NIP?>">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    TAX PPH 21
                                </h4>
                            </div>
                            <div class="panel-body"></div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            
        </div>

    </div>
</div>