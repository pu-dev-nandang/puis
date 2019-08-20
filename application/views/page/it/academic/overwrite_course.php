


<div class="row">

    <div class="col-md-9">


        <div class="row">
            <div class="col-md-6">
                <div class="well">
                    <input class="form-control" id="searchStudent" placeholder="Search Student.." >

                    <hr/>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 2%;">No</th>
                            <th style="width: 20%;">NIM</th>
                            <th>Student</th>
                            <th style="width: 2%;"><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3" style="border-left: 1px solid #CCCCCC;">
        <h3>Log Update</h3>
    </div>


</div>

<script>
    $('#searchStudent').keyup(function () {
        var searchStudent = $('#searchStudent').val();

        if(searchStudent!='' && searchStudent!=null){

        }

    });
</script>