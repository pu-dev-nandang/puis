<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-attd").addClass("active");
    });
</script>
<form id="form-attendance" action="" method="post" autocomplete="off">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
            <div class="my-attendance">
                <table id="table-list-attendance" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="15%">Date</th>
                            <th width="15%">Pattern</th>
                            <th width="5%">IN</th>
                            <th width="5%">OUT</th>
                            <th width="25%">Status</th>
                            <th width="20%"></th>
                            <th>Notes to HR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</form>