<div class="row" style="margin-left: 0px;margin-right: 0px;">
    <div class="col-md-12">
        <div class="pull-right">
            <span data-smt="" class="btn btn-add btn-write btn-add-t_template">
                <i class="icon-plus"></i> Add
           </span>
        </div>
        <br><br>
        <div id = "loadTableTemplateTransaksi">

        </div>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
            LoadTableTemlateTransaksi();
    }); // exit document Function

    function LoadTableTemlateTransaksi()
    {
        $("#loadTableTemplateTransaksi").empty();
        var TableGenerate = '<table class="table table-bordered tableData" id ="tableDataTemplateTransaksi">'+
                            '<thead>'+
                            '<tr>'+
                                '<th width = "3%">No</th>'+
                                '<th>Name</th>'+
                                '<th>Start Date</th>'+
                                '<th>End Date</th>'+
                                '<th style = "text-align: center;">Approval</th>'+
                                '<th style = "min-width:120px !important;">Action</th>'+
                            '</tr></thead>' 
                            ;
        TableGenerate += '<tbody>';

        var dataForTable = [];
        var url = base_url_js+'budgeting/_GetTemplateTransaksi';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            dataForTable = response;
            // console.log(dataForTable);
            for (var i = 0; i < dataForTable.length; i++) {
                var btn_edit = '<button type="button" class="btn btn-warning btn-edit btn-edit-t_template btn-write" code = "'+dataForTable[i].ID+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button>';
                var btn_del = ' <button type="button" class="btn btn-danger btn-delete btn-delete-t_template btn-write"  code = "'+dataForTable[i].ID+'"> <i class="fa fa-trash" aria-hidden="true"></i> </button>';
                var Approval = jQuery.parseJSON(dataForTable[i].JsonStatusDefault);
                var htmlApproval = '';
                for (var j = 0; j < Approval.length; j++) {
                    var No =parseInt(j) + 1;
                    htmlApproval += '<li>'+No+'. '+Approval[j].Name+'</li>';
                }
                // console.log(Approval);
                TableGenerate += '<tr>'+
                                    '<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
                                    '<td>'+ dataForTable[i].Name+'</td>'+
                                    '<td>'+ dataForTable[i].StartDate+'</td>'+
                                    '<td>'+ dataForTable[i].EndDate+'</td>'+
                                    '<td>'+ htmlApproval+'</td>'+
                                    '<td>'+ btn_edit + ' '+' &nbsp' + btn_del+'</td>'+
                                 '</tr>'    
            }

            TableGenerate += '</tbody></table>';
            $("#loadTableTemplateTransaksi").html(TableGenerate);
            LoaddataTable("#tableDataTemplateTransaksi");
        });
    }

    $(document).off('click', '.btn-add-t_template').on('click', '.btn-add-t_template',function(e) {
        modal_generate_transaksi_template('add','Form Transaksi Template');
    })

    
    $(document).off('click', '.btn-edit-t_template').on('click', '.btn-edit-t_template',function(e) {
        var ID = $(this).attr('code');
        modal_generate_transaksi_template('edit','Form Transaksi Template',ID);
    })

    function modal_generate_transaksi_template(action,title,ID='') {
        var url = base_url_js+"budgeting/form_template_transaksi";
        var data = {
            Action : action,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(' ');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }

    $(document).off('click', '.btn-delete-t_template').on('click', '.btn-delete-t_template',function(e) {
        var ID = $(this).attr('code');
        if (confirm('Are you sure ?')) {
            var action = 'delete';
            var data = {
                action : action,
                ID : ID,
            }
            var url = base_url_js+'budgeting/template_transaksi_save';
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{ token:token },function (resultJson) {
                LoadTableTemlateTransaksi();
                toastr.success('Saved');
            }).fail(function() {
              toastr.info('No Result Data'); 
            }).always(function() {
                            
            });
        }
        
    })
</script>