<form id="form-additional-info" action="<?=base_url('human-resources/employees/additional-info-save')?>" method="post" autocomplete="off">
<input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="pull-right">
                                <label><input type="checkbox" class="samedata-check" value="<?=$NIP?>"> same data as IDCard</label>
                            </div>
                            <h4 class="panel-title">Current Address</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <label class="col-sm-12">Address</label>
                                <div class="col-sm-12">                                                
                                    <textarea class="form-control required samedata samedata-Address" required name="CurrAddress" rows="6"><?=(!empty($detail) ? $detail->CurrAddress : null)?></textarea>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control samedata samedata-Postcode required number" required name="CurrPostCode" value="<?=(!empty($detail) ? $detail->CurrPostCode : null)?>" maxlength="5">
                                    <small class="text-danger text-message"></small>
                                </div>
                                <div class="col-sm-5">
                                    <label>Phone</label>
                                    <input type="text" class="form-control samedata samedata-Phone number" name="CurrPhone" value="<?=(!empty($detail) ? $detail->CurrPhone : null)?>" maxlength="12">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">External Card Number</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Family Card</label>
                                        <input type="text" class="form-control required number" required name="IDFamilyCard" value="<?=(!empty($detail) ? $detail->IDFamilyCard : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>NPWP</label>
                                        <input type="text" class="form-control required number" required name="IDNPWP" value="<?=(!empty($detail) ? $detail->IDNPWP : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Passport</label>
                                        <input type="text" class="form-control required number" required name="IDPassport" value="<?=(!empty($detail) ? $detail->IDPassport : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>BPJS Tenaga Kerja</label>
                                        <input type="text" class="form-control required number" required name="IDBpjstk" value="<?=(!empty($detail) ? $detail->IDBpjstk : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>BPJS Pensiun</label>
                                        <input type="text" class="form-control number" name="IDBpjspensiun" value="<?=(!empty($detail) ? $detail->IDBpjspensiun : null)?>">
                                    </div>
                                    <div class="form-group">
                                        <label>BPJS Kesehatan</label>
                                        <input type="text" class="form-control required number" required name="IDBpjskesehatan" value="<?=(!empty($detail) ? $detail->IDBpjskesehatan : null)?>">
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-additional").addClass("active");

        $(".samedata-check").change(function(){
            var data = {
              NIP : "<?=$NIP?>",
            };
            var token = jwt_encode(data,'UAP)(*');
            if($(this).is(':checked')){
                $.ajax({
                    type : 'POST',
                    url : base_url_js+"human-resources/employees/detail",
                    data : {token:token},
                    dataType : 'json',
                    beforeSend :function(){
                        //loading_modal_show();
                        $(".mailing-list .detailMail").addClass("hidden");
                    },error : function(jqXHR){
                        //loading_modal_hide();
                        $("body #modalGlobal .modal-body").html(jqXHR.responseText);
                        $("body #modalGlobal").modal("show");
                    },success : function(response){
                        if(!jQuery.isEmptyObject(response)){
                            $.each(response,function(k,v){
                                $("#form-additional-info").find(".samedata-"+k).val(v);
                            });
                        }
                    }
                });
            }else{
                $("#form-additional-info").find(".samedata").val("");
            }
        });

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
                loading_modal_show();
                $("#form-additional-info")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        var mybank = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(mybank)){
            if(!jQuery.isEmptyObject(mybank.MyBank)){
                $tablename = $("#table-list-bank"); var num = 1;
                $.each(mybank.MyBank,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_educations").attr("data-id",value.ID).attr("data-name",value.bank);
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
    });
</script>