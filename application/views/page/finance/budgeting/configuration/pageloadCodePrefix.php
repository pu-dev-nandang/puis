<style type="text/css">
	#tableData thead th,#tableData tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}
</style>
<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Code Prefix</h4>
        </div>
        <div class="panel-body">
            <p><b>* Please Enter to save data</b></p>
            <div class="table-responsive" id = "loadTable">

            </div>	
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    loadTable();

    $(".btn-add").click(function(){
	    modal_generate('add','Add');
    });
    
}); // exit document Function


function loadTable()
{
	$("#loadTable").empty();
	var TableGenerate = '<table class="table table-bordered" id ="tableData">'+
						'<thead>'+
						'<tr>'+
							'<th>Post</th>'+
                            '<th>Post Realization</th>'+
                            '<th>Post Budgeting</th>'+
                            '<th>Catalog</th>'+
							'<th>Supplier</th>'+
						'</tr></thead>'	
						;
	TableGenerate += '<tbody>';
	var dataForTable = '<?php echo $loadData ?>';
    dataForTable = jQuery.parseJSON(dataForTable);
    // console.log(dataForTable);
	for (var i = 0; i < dataForTable.length; i++) {
        var CodePostInput = '<div class="row">'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Prefix</label>'+
                                        '<input class="form-control CodePost" style="background-color: #fff;color: #333;" value = "'+dataForTable[i]['CodePost']+'" max-length = "5"><br>'+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Length</label>'+
                                        '<input type="number" class="form-control LengthCodePost" value="'+dataForTable[i]['LengthCodePost']+'" min = "6" max = "9">' +
                                    '</div>'+
                                '</div>'+
                            '</div>'            
                                        ;
        var CodePostRealisasi = '<div class="row">'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Prefix</label>'+
                                        '<input class="form-control CodePostRealisasi" style="background-color: #fff;color: #333;" value = "'+dataForTable[i]['CodePostRealisasi']+'" max-length = "5"><br>'+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Length</label>'+
                                        '<input type="number" class="form-control LengthCodePostRealisasi" value="'+dataForTable[i]['LengthCodePostRealisasi']+'" min = "6" max = "9">' +
                                    '</div>'+
                                '</div>'+
                            '</div>'            
                                        ;
        var selectOptionYear = '<select class = "form-control YearCodePostBudget">';
        for (var j = 0; j < 2; j++) {
            var selected = (j == dataForTable[i]['YearCodePostBudget']) ? 'selected' : '';
            var valuee = (j == 0) ? 'No' : 'Yes';
            selectOptionYear += '<option value = "'+j+'" '+selected+'>'+valuee+'</option>'
        }
        selectOptionYear += '</select>';  
        // console.log(selectOptionYear);
                                

        var CodePostBudget = '<div class="row">'+
                                '<div class = "col-xs-4">'+
                                    '<div class="form-group"><label>Prefix</label>'+
                                        '<input class="form-control CodePostBudget" style="background-color: #fff;color: #333;" value = "'+dataForTable[i]['CodePostBudget']+'" max-length = "5"><br>'+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-4">'+
                                    '<div class="form-group"><label>Add Year Code</label>'+
                                       selectOptionYear+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-4">'+
                                    '<div class="form-group"><label>Length</label>'+
                                        '<input type="number" class="form-control LengthCodePostBudget" value="'+dataForTable[i]['LengthCodePostBudget']+'" min = "6" max = "9">' +
                                    '</div>'+
                                '</div>'+
                            '</div>'            
                                        ;
        var CodeCatalog = '<div class="row">'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Prefix</label>'+
                                        '<input class="form-control CodeCatalog" style="background-color: #fff;color: #333;" value = "'+dataForTable[i]['CodeCatalog']+'" max-length = "5"><br>'+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Length</label>'+
                                        '<input type="number" class="form-control LengthCodeCatalog" value="'+dataForTable[i]['LengthCodeCatalog']+'" min = "6" max = "9">' +
                                    '</div>'+
                                '</div>'+
                            '</div>'            
                                        ; 
                                        
        var CodeSupplier = '<div class="row">'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Prefix</label>'+
                                        '<input class="form-control CodeSupplier" style="background-color: #fff;color: #333;" value = "'+dataForTable[i]['CodeSupplier']+'" max-length = "5"><br>'+
                                    '</div>'+
                                '</div>'+
                                '<div class = "col-xs-6">'+
                                    '<div class="form-group"><label>Length</label>'+
                                        '<input type="number" class="form-control LengthCodeSupplier" value="'+dataForTable[i]['LengthCodeSupplier']+'" min = "6" max = "9">' +
                                    '</div>'+
                                '</div>'+
                            '</div>'            
                                        ;                                                                                               

        TableGenerate += '<tr>'+
                            '<td>'+ CodePostInput+'</td>'+
                            '<td>'+ CodePostRealisasi+'</td>'+
                            '<td>'+ CodePostBudget+'</td>'+
                            '<td>'+ CodeCatalog+'</td>'+
                            '<td>'+ CodeSupplier+'</td>'+
                         '</tr>'    
    }

    TableGenerate += '</tbody></table>';
    $("#loadTable").html(TableGenerate);
    var url = base_url_js+'budgeting/save_codeprefix';  
    $(".CodePost").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                CodePost : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })
    
    $(".LengthCodePost").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                LengthCodePost : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".CodePostRealisasi").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                CodePostRealisasi : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".LengthCodePostRealisasi").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                LengthCodePostRealisasi : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".CodePostBudget").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                CodePostBudget : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".YearCodePostBudget").change(function(){
        var val = $(this).val();
        var data = {
            YearCodePostBudget : val,
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (resultJson) {
            toastr.success('Data berhasil disimpan', 'Success!');
        }).fail(function() {
          toastr.info('No Action...'); 
          // toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        }); 
    })

    $(".LengthCodePostBudget").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                LengthCodePostBudget : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".CodeCatalog").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                CodeCatalog : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })
    
    $(".LengthCodeCatalog").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                LengthCodeCatalog : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })
    
    $(".CodeSupplier").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                CodeSupplier : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })

    $(".LengthCodeSupplier").keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13) {
            var val = $(this).val();
            var data = {
                LengthCodeSupplier : val,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
                toastr.success('Data berhasil disimpan', 'Success!');
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
        }
    })
					
}
</script>