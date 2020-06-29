<div class="row">
    <div class="col-md-3 col-md-offset-4">
        <div class="well">
            <div class="form-group">
                <label for="">Filter  Year</label>
                <select class = "form-control" id="SelectTA">

                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="widget box">
           <div class="widget-header">
            <h4>Select Data</h4>
           </div> 
           <div class="widget-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id = "ViewTable">
                            
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="widget box">
            <div class="widget-header">
                <h4>Status Progress</h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id = "viewTblStat">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <button class="btn btn-block btn-success btn-save">Roolback To Be Intake</button>
        </div>
    </div>
</div>
<script>
    var S_Table = '';
    var DataSelected = [];
    var DBTA = <?php echo json_encode($DBTA)  ?>;
    $(document).ready(function() {
        LoadYearDB();
		LoadDataForTable();
	})

    function LoadYearDB()
    {
        var selector = $('#SelectTA');
        selector.empty();
        for (let index = 0; index < DBTA.length; index++) {
          var t = DBTA[index];
          var t_ = t.split('_');
          var db = t_[0];
          var y = t_[1];
          selector.append('<option value = "'+y+'" db="'+db+'" >'+y+'</option>')
        }
    }

    function LoadDataForTable(MakeDataSelected="")
    {
        DataSelected = [];
        // make table

        var htmlTable = '<table class = "table table-bordered" id = "tblToBeMHS">'+
                        '<thead>'+
                          '  <tr>'+
                          '      <th style = "width:2%;"></th>'+
                          '      <th>NPM & Nama</th>'+
                          '      <th>Formulir Code</th>'+
                          '      <th>Prodi</th>'+
                          '  </tr> '+               
                        '</thead>'+ 
                        '<tbody>'+
                       ' </tbody> '+  
                    '</table>';
        $('#ViewTable').html(htmlTable);

        var ta = $('#SelectTA option:selected').val();
        var data = {
            action : 'read',
            auth : 's3Cr3T-G4N',
            ta : ta,
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest3/__get_roolback_door_to_be_mhs_admission';

        $.post(url,{token:token},function (jsonResult) {
            MakeDataTable(jsonResult);
            if (MakeDataSelected == "") {
                MakeDataSelected();
            }
             
            
        });
    }

    $(document).off('change', '#SelectTA').on('change', '#SelectTA',function(e) {
        LoadDataForTable();
    })

    function MakeDataTable(jsonResult)
    {
        var dt = jsonResult;
        var table = $('#tblToBeMHS').DataTable({
		      "data" : dt,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '<input type="checkbox" name="id[]" value="' + full.NPM + '" class = "chkGet" dt-name = "'+full.Name+'" >';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return full.NPM+'</br>'+full.Name;
			         }
			      },
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
                        return full.FormulirCode+' / '+full.No_Ref;
			         }
			      },
                  {
			         'targets': 3,
			         'render': function (data, type, full, meta){
                        return full.NameProdi;
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		
		      },
		      'order': [[1, 'asc']]
		});

        S_Table = table;
    }

    function MakeDataSelected()
    {
        var htmlTable = '<table class = "table table-bordered" id = "tblStatMHS">'+
                        '<thead>'+
                          '  <tr>'+
                          '      <th style = "width:40%;" >NPM & Nama</th>'+
                          '      <th>Status</th>'+
                          '  </tr> '+               
                        '</thead>'+ 
                        '<tbody>'+
                       ' </tbody> '+  
                    '</table>';
        $('#viewTblStat').html(htmlTable);
        if (DataSelected.length > 0) {
            $('#tblStatMHS tbody').empty();
            for (let index = 0; index < DataSelected.length; index++) {
                var NPM = DataSelected[index].NPM;
                var Name = DataSelected[index].Name;
                var Status = DataSelected[index].Status;
                $('#tblStatMHS tbody').append('<tr>'+
                                                    '<td>'+NPM+'</br>'+Name+'</td>'+
                                                    '<td style = "color:red">'+Status+'</td>'+
                                               '</tr>'     
                )
            }
        }            
    }

    $(document).off('click', '.chkGet').on('click', '.chkGet',function(e) {
        DataSelected = [];
        S_Table.$('input[type="checkbox"]').each(function(){
			if(this.checked){
				var v = $(this).val();
				var n = $(this).attr('dt-name');
				var temp = {
					NPM : v,
					Name : n,
                    Status : 'Selected',
				};

				DataSelected.push(temp);
			}
		}); // exit each function

        MakeDataSelected();
    })

    $(document).off('click', '.btn-save').on('click', '.btn-save',function(e) {
            if (confirm('Are you sure ?')) {
                    if (DataSelected.length > 0) {
                         S_Table.$('input[type="checkbox"]').attr('disabled',true);
                        var url = base_url_js+'rest3/__get_roolback_door_to_be_mhs_admission';
                        var ta = $('#SelectTA option:selected').val();
                        var data = {
                                    action : 'roolback',
                                    auth : 's3Cr3T-G4N',
                                    ta : ta,
                                    DataSelected : DataSelected,
                                };
                        var token = jwt_encode(data,'UAP)(*');
                        loading_button('.btn-save');
                        $.post(url,{token:token},function (resultJson) {
                            DataSelected = resultJson.Dt;
                            MakeDataSelected();
                            LoadDataForTable("1");  // not load dataselected
                            S_Table.$('input[type="checkbox"]').each(function(){
			                    if(this.checked){
                                    $(this).closest('tr').remove();
	                            }
		                    });
                            $('.btn-save').prop('disabled',false).html('Roolback To Be Intake');
                        }).fail(function() {
                            toastr.info('No Result Data'); 
                        }).always(function() {
                            S_Table.$('input[type="checkbox"]').attr('disabled',false);              
                        });
                    } 
            }
    })
</script>