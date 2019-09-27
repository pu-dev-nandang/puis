<h3>This is the page : penggunaan_dana.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
                    
    <style>
        #dataDanaTable tr th, #dataDanaTable tr td {
            text-align: center;
        }
        #tablePD tr th, #tablePD tr td {
            text-align: center;
        }
    </style>

    <div class="well">
        <div class="row">

            <div class="col-md-3">

                <div class="form-group">
                    <label>Jenis Penggunaan</label>
                    <input id="formID" class="hide">
                    <select class="form-control" id="formJPID"></select>
                    <a style="float: right;" href="javascript:void(0);" class="" id="btnCrud_JP"><i class="fa fa-edit margin-right"></i> Jenis Penggunaan</a>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <input class="form-control" id="formYear" />
                </div>
                <div class="form-group">
                    <label>Jumlah Dana</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" id="formPrice">
                    </div>
                </div>
                <div class="form-group" style="text-align: right;">
                    <button class="btn btn-primary" id="btnSave">Save</button>
                </div>

            </div>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="well">
                            <select class="form-control" id="filterYear"></select>
                        </div>
                    </div>
                    <div class="col-md-4" style="text-align: right;margin-bottom: 20px;">
                        <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered  dataTable2Excel table2excel_with_colors" data-name="Penggunaan-Dana"  id="tablePD">
                            <thead>
                            <tr>
                                <th rowspan="2" style="width: 1%;">No</th>
                                <th rowspan="2">Jenis Penggunaan</th>
                                <th colspan="3">Unit Pengelolaan Program Studi (Rupiah)</th>
                                <th rowspan="2" style="width: 10%;">Rata - Rata</th>
                                <th colspan="3">Program Studi (Rupiah)</th>
                                <th rowspan="2" style="width: 10%;">Rata - Rata</th>
                            </tr>
                            <tr>
                                <th style="width: 10%;">TS-2 <span id="viewTS2"></span></th>
                                <th style="width: 10%;">TS-1 <span id="viewTS1"></span></th>
                                <th style="width: 10%;">TS <span id="viewTS"></span></th>
                                <th style="width: 10%;">TS-2 <span id="viewTS2"></span></th>
                                <th style="width: 10%;">TS-1 <span id="viewTS1"></span></th>
                                <th style="width: 10%;">TS <span id="viewTS"></span></th>
                            </tr>
                            </thead>
                            <tbody id="loadListDana"></tbody>
                        </table>
                    </div>
                </div>

                <div id="viewData2"></div>

            </div>

        </div>
    </div>

                
                    <script>
                $(document).ready(function () {
                    var firstLoad = setInterval(function () {
                        var filterProdi = $('#filterProdi').val();
                        if(filterProdi!='' && filterProdi!=null){
                            loadPage();
                            clearInterval(firstLoad);
                        }
                    },1000);
                    setTimeout(function () {
                        clearInterval(firstLoad);
                    },5000);
            
                });
                $('#filterProdi').change(function () {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        loadPage();
                    }
                });
                function loadPage() {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        $('#viewProdiID').html(filterProdi);
                        $('#viewProdiName').html($('#filterProdi option:selected').text());
                    }
                }
            </script>