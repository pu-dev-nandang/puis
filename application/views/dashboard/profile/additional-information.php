<form id="form-additional-info" action="<?=base_url('profile/save-changes')?>" method="post" autocomplete="off" style="margin:0px">
<input type="hidden" name="NIP" value="<?=$NIP?>">
<input class="form-control" name="action" type="hidden" value="additional-info" />
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">External Card Number</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Family Card</label>
                                        <input type="text" class="form-control required number profile-IDFamilyCard" required name="IDFamilyCard" value="<?=(!empty($detail) ? $detail->IDFamilyCard : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>NPWP</label>
                                        <input type="text" class="form-control required number profile-IDNPWP" required name="IDNPWP" value="<?=(!empty($detail) ? $detail->IDNPWP : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Passport</label>
                                        <input type="text" class="form-control required number profile-IDPassport" required name="IDPassport" value="<?=(!empty($detail) ? $detail->IDPassport : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default" id="multiple-field" data-source="bank">
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
                            <h4 class="panel-title">Bank Account</h4>
                        </div>
                        <div class="panel-body">                                        
                            <table class="table table-bordered" id="table-list-bank">
                                <thead>
                                    <tr>
                                        <th width="2%">No</th>
                                        <th>Name of Bank</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control bank-ID" name="bankID[]">
                                            <input type="text" class="form-control required bank-bank autocomplete" required name="bankName[]" id="autocomplete-bank">
                                            <small class="text-danger text-message"></small>
                                        </td>
                                        <td><input type="text" class="form-control required bank-accountName" required name="bankAccName[]"><small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required bank-accountNumber number" required name="bankAccNum[]"><small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-success btn-submit" type="button">Save changes</button>
        </div>
    </div>
</form>

<script type="text/javascript">
   
    $(document).ready(function(){
        $("#form-employee .navigation-tabs ul > li").removeClass("active");
        $("#form-employee .navigation-tabs ul > li.nv-additional").addClass("active");

        $("#form-additional-info .btn-submit").click(function(){
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
                loading_page_modal();
                $("#form-additional-info")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            $.each(myData,function(key,value){
                $("#form-additional-info").find(".profile-"+key).val(value);
            });
            if(!jQuery.isEmptyObject(myData.MyBank)){
                $tablename = $("#table-list-bank"); var num = 1;
                $.each(myData.MyBank,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_bank_account").attr("data-id",value.ID).attr("data-name",value.bank);
                    $cloneRow.find("td:first").text(num);
                    $.each(value,function(k,v){
                        $cloneRow.find(".bank-"+k).val(v);                        
                    });
                    
                    $tablename.find("tbody").append($cloneRow);
                    num++;
                });
                $tablename.find("tbody tr:first").remove();
            }
        }

        var companyBankTags = bankName();
        $( "#autocomplete-bank" ).autocomplete({
          source: companyBankTags
        });

        $("#InsuranceInternalEndDate,#InsuranceInternalStartDate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
    });
</script>