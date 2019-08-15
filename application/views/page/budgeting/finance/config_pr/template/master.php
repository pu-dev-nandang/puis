<div class="row" style="margin-left: 0px;margin-right: 0px;">
    <div class="col-md-12">
        <div class="pull-right">
            <span data-smt="" class="btn btn-add btn-write btn-add-m_template">
                <i class="icon-plus"></i> Add
           </span>
        </div>
        <br><br>
        <div id = "loadTableTemplateMaster">

        </div>  
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
            LoadTableTemlateMaster(); 
    }); // exit document Function

    function LoadTableTemlateMaster()
    {
        $("#loadTableTemplateMaster").empty();
        var TableGenerate = '<table class="table table-bordered tableData" id ="tableDataTemplateMaster">'+
                            '<thead>'+
                            '<tr>'+
                                '<th width = "3%">No</th>'+
                                '<th>Name</th>'+
                                '<th style = "text-align: center;">Approval</th>'+
                                '<th style = "min-width:120px !important;">Action</th>'+
                            '</tr></thead>' 
                            ;
        TableGenerate += '<tbody>';

        var dataForTable = [];
        var url = base_url_js+'budgeting/_GetTemplate';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            dataForTable = response;
            // console.log(dataForTable);
            for (var i = 0; i < dataForTable.length; i++) {
                var btn_edit = '<button type="button" class="btn btn-warning btn-edit btn-edit-m_template btn-write" code = "'+dataForTable[i].ID+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button>';
                var btn_del = ' <button type="button" class="btn btn-danger btn-delete btn-delete-m_template btn-write"  code = "'+dataForTable[i].ID+'"> <i class="fa fa-trash" aria-hidden="true"></i> </button>';
                var Approval = jQuery.parseJSON(dataForTable[i].JsonStatusDefault);
                var htmlApproval = '';
                for (var j = 0; j < Approval.length; j++) {
                    var No =parseInt(j) + 1;
                    htmlApproval += '<li>'+No+'. '+Approval[j].NIP+' : '+Approval[j].Name+'</li>';
                }
                // console.log(Approval);
                TableGenerate += '<tr>'+
                                    '<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
                                    '<td>'+ dataForTable[i].Name+'</td>'+
                                    '<td>'+ htmlApproval+'</td>'+
                                    '<td>'+ btn_edit + ' '+' &nbsp' + btn_del+'</td>'+
                                 '</tr>'    
            }

            TableGenerate += '</tbody></table>';
            $("#loadTableTemplateMaster").html(TableGenerate);
            LoaddataTable("#tableDataTemplateMaster");
        });
    }

    $(document).off('click', '.btn-add-m_template').on('click', '.btn-add-m_template',function(e) {
        modal_generate_master_template('add','Form Master Template');
    })
    $(document).off('click', '.btn-edit-m_template').on('click', '.btn-edit-m_template',function(e) {
        var ID = $(this).attr('code');
        modal_generate_master_template('edit','Form Master Template',ID);
    })  

    function modal_generate_master_template(action,title,ID='') {
        var url = base_url_js+"budgeting/form_template_master";
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
</script>