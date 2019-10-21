<div class="row">
    <div class="col-md-12">
        <div class="well">
            <div class = "row">
                <div class="col-md-12">
                <div style = "padding : 10px;">
                    <h2>Developer Mode</h2>
                </div>
                <div class="pull-left">
                    <button class="btn btn-default btn-tambah">Add</button>
                </div>
                    <table class="table" id = "tbl_config">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Apps</th>
                                <th>Global Password</th>
                                <th>Develop Mode</th>
                                <th>Maintenance Mode</th> 
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var AppJQ = {
        TableViewInput : function(){
                            var data = {
                                mode : 'read',
                                auth : 's3Cr3T-G4N',
                            };

                            var token = jwt_encode(data,'UAP)(*');
                            var url = base_url_js+'rest3/__submit_console_developer';

                            $.post(url,{token:token},function (jsonResult) {
                                AppJQ.MakeIsiTable(jsonResult);                           
                            });
                        },
        MakeIsiTable : function(jsonResult){
                        var selector = $('#tbl_config tbody');
                        selector.empty();
                        for (let i = 0; i < jsonResult.length; i++) {
                            var InputApps = '<input type ="text" class = "form-control InputApps" value = "'+jsonResult[i].Apps+'" >  ';
                            var InputPassword = '<input type ="text" class = "form-control InputPassword" value = "'+jsonResult[i].GlobalPassword+'" >  ';
                            var SelectDevelopMode = '<select class ="form-control InputDevelopMode"> ';
                            SelectDevelopMode += AppJQ.OPMode(jsonResult[i].DevelopMode);
                            SelectDevelopMode += '</select>';
                            var SelectMaintenanceMode = '<select class ="form-control InputMaintenanceMode"> '+ 
                                                            AppJQ.OPMode(jsonResult[i].MaintenanceMode)+
                                                        '</select>'; 
                            var Action = '<button class="btn btn-primary btn-sm  btnsave" data-id="'+jsonResult[i].ID+'" action = "edit"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
                            selector.append(
                                '<tr>'+
                                        '<td>'+(i+1)+'</td>'+
                                        '<td>'+InputApps+'</td>'+
                                        '<td>'+InputPassword+'</td>'+
                                        '<td>'+SelectDevelopMode+'</td>'+
                                        '<td>'+SelectMaintenanceMode+'</td>'+
                                        '<td>'+Action+'</td>'+
                                '</tr>'        
                            );
                            
                        }
                    },
        SaveData : function(Selector){
                        var Tr = Selector.closest('tr');
                        var DataForm = {
                             'Apps' : Tr.find('.InputApps').val(),
                             'GlobalPassword' : Tr.find('.InputPassword').val(),
                             'DevelopMode' : Tr.find('.InputDevelopMode option:selected').val(),
                             'MaintenanceMode' : Tr.find('.InputMaintenanceMode option:selected').val(),   
                        };
                        var mode = (Selector.attr('action') == 'edit' ) ? 'update' : 'insert';
                        var Data = {
                            ID : Selector.attr('data-id'),
                            DataForm : DataForm,
                            mode : mode,
                            auth : 's3Cr3T-G4N',
                        }

                        if (confirm('Are you sure ?')) {
                            Selector.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
                            Selector.prop('disabled',true);

                            var token = jwt_encode(Data,'UAP)(*');
                            var url = base_url_js+'rest3/__submit_console_developer';
                            $.post(url,{token:token},function (jsonResult) {
                                toastr.success('Data Saved');
                                AppJQ.TableViewInput();                          
                            });
                        }

                   },
        AddData : function(){
            var InputApps = '<input type ="text" class = "form-control InputApps" value = "" >  ';
            var InputPassword = '<input type ="text" class = "form-control InputPassword" value = "" >  ';
            var SelectDevelopMode = '<select class ="form-control InputDevelopMode"> ';
            SelectDevelopMode += AppJQ.OPMode();
            SelectDevelopMode += '</select>';
            var SelectMaintenanceMode = '<select class ="form-control InputMaintenanceMode"> '+ 
                                            AppJQ.OPMode()+
                                        '</select>'; 
            var Action = '<button class="btn btn-primary btn-sm  btnsave" data-id="" action = "add"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
            var No = $('#tbl_config tbody tr:last').find('td:eq(0)').html();
            No = parseInt(No)+1;
            html =  '<tr>'+
                        '<td>'+No+'</td>'+
                        '<td>'+InputApps+'</td>'+
                        '<td>'+InputPassword+'</td>'+
                        '<td>'+SelectDevelopMode+'</td>'+
                        '<td>'+SelectMaintenanceMode+'</td>'+
                        '<td>'+Action+'</td>'+
                '</tr>' ;
            return html;    
                           
        }  ,                          
        OPMode : function(Selected = null){
                            Selected = (Selected == null ) ? '1' : Selected;                                
                            var arr = [
                                {
                                    Name : 'True',
                                    Value : '1',
                                },
                                {
                                    Name : 'False',
                                    Value : '0',
                                }
                                
                            ];

                           var html = '';
                           for (let i = 0; i < arr.length; i++) {
                               var ss = (Selected == arr[i].Value) ? 'selected' : '';
                               html += '<option value = "'+arr[i].Value+'" '+ss+' >'+arr[i].Name+'</option>';
                           }

                           return html;
                        },               
        
        loaded : function(){
                    AppJQ.TableViewInput();
                },

    };

    $(document).ready(function() {
        AppJQ.loaded();
	})

    $(document).off('click', '.btnsave').on('click', '.btnsave',function(e) {
       var GetSelector = $(this);
       AppJQ.SaveData(GetSelector);
    })

    $(document).off('click', '.btn-tambah').on('click', '.btn-tambah',function(e) {
       var GetSelector = $(this);
       var html = AppJQ.AddData();
       $('#tbl_config tbody').append(html);
    })
   
</script>