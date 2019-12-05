
<div class="row" style="margin-top: 30px;">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-8">
                    <label>Programme Study</label>
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Judiciums Year</label>
                    <select class="form-control" id="filterJudiciumsYear"></select>
                </div>
            </div>

        </div>
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-centre table-bordered">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th style="width: 15%;">Student</th>
                <th>Title</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionJudiciumsYear('filterJudiciumsYear','');
    });

</script>