<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-financial").addClass("active");        
    });
</script>

<form id="form-training" action="<?=base_url('human-resources/employees/credential-financial-save')?>" method="post" autocomplete="off">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
            s
        </div>
    </div>
</form>