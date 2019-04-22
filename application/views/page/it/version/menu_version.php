
<style>
    #tableEmployees tr th{
        text-align: center;
    }
</style>

<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 17px;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}

</style> 

<div class="col-md-12" style="margin-bottom: 15px;">
    <div class="btn-group">
          <button type="button" class="btn btn-primary btn-round btn-action" data-action="addGroupModule"> <i class="fa fa-plus-circle"></i>Add Group</button>
          <button type="button" class="btn btn-success btn-round btn-addgroup"> <i class="fa fa-plus-circle"></i> Add Module</button>
    </div>
</div>

<div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0)" class="menuVersion" data-page="version_data" data-toggle="tab"><i class="fa fa-industry"></i> List data Version </a></li>
                <li class=""><a href="javascript:void(0)" class="menuVersion" data-page="list_groupmodule" data-toggle="tab"><i class="fa fa-object-group"></i> List data Module</a></li>
                <li class=""><a href="javascript:void(0)" class="menuVersion" data-page="list_module" data-toggle="tab"><i class="fa fa-object-group"></i>  List data Group </a></li> 
            </ul>
            <div class="tab-content">
                <hr/>
                <div id="divPage"></div>
            </div>
        </div>
</div>


<script>
    $(document).ready(function () {
        
        var data = {
            page : 'version_data'
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);
        //window.Lecturer_NIP = 0;

    });

    $(document).on('click','.menuVersion',function () {
        var page = $(this).attr('data-page');
        var data = {
            //NIP : NIP,
            page : page
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);
    });


    function loadPage(token) {
        var url = base_url_js+'it/loadpageversion';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }

</script>

<script>
     $(document).on('click','.btndeletegroup',function () {
        if (window.confirm('Are you sure to delete module data ?')) {
            
            var versionid = $(this).attr('versionid');
            
            var data = {
                action : 'deletegroupmod',
                versionid : versionid
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__deleteversion";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete Module Data!','Success'); 
                setTimeout(function () {
                    window.location.href = '';
                },1000);
            });
        }
    });
</script>

<script>
     $(document).on('click','.btndeleteversion',function () {
        if (window.confirm('Are you sure to delete version data ?')) {

            var versionid = $(this).attr('versionid');
            var data = {
                action : 'deleteversion',
                versionid : versionid
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__deleteversion";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete Version Data!','Success'); 
                setTimeout(function () {
                    window.location.href = '';
                },1000);
            });
        }
    });
</script>

<script>
    $(document).on('click','.btneditsavegroups',function () {
        saveeditgroups();
    });

    function saveeditgroups() {
        
        var iddivision = $('#filterDivisiom option:selected').attr('id');
        var Namegroup = $('#editNameGroups').val();
        var IDGroupedit = $('#IDGroupedit').val();
        
        if(iddivision!=null && iddivision!=''
            && Namegroup!='' && Namegroup!=null
            && IDGroupedit!='' && IDGroupedit!=null
            )
        { 
    
            var data = {
                action : 'EditGroups',
                formInsert : {
                    iddivision : iddivision,
                    IDGroupedit : IDGroupedit,
                    Namegroup : Namegroup
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Edit Group Module Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>



<script>
    $(document).on('click','.btneditsavegroupmod',function () {
        saveeditgroupmodule();
    });

    function saveeditgroupmodule() {
        
        var idnameegroup = $('#filtereditgroupmodule option:selected').attr('id');
        var idmodule = $('#filtereditgroupname option:selected').attr('id');
        var idmodule = $('#filtereditgroupname').val();
        var Descriptiongroup = $('#editdescriptiongroup').val();
        var IDGroupedit = $('#IDModuledit').val();
        
        if(idnameegroup!=null && idnameegroup!=''
            && idmodule!='' && idmodule!=null
            && Descriptiongroup!='' && Descriptiongroup!=null
            && IDGroupedit!='' && IDGroupedit!=null
            )
        { 
    
            var data = {
                action : 'EditGroupModule',
                formInsert : {
                    idnameegroup : idnameegroup,
                    idmodule : idmodule,
                    Descriptiongroup : Descriptiongroup,
                    IDGroupedit : IDGroupedit
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Edit Group Module Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>

<script>
    $(document).on('click','.btneditSaveVersion',function () {
        saveeditversion();
    });

    function saveeditversion() {

        var selectmodule = $('#filtereditmodule option:selected').attr('id');
        var selectpic = $('#selectpicversion').val();
        var Descriptionversion = $('#descriptionversion').val();
        var VersionID = $('#Idversion').val();
        var Noversion = $('#Noeditversion').val();
        
        if(selectmodule!=null && selectmodule!=''
            && selectpic!='' && selectpic!=null
            && VersionID!='' && VersionID!=null
            && Noversion!='' && Noversion!=null
            && Descriptionversion!='' && Descriptionversion!=null)
        { 
    
            var data = {
                action : 'EditVersion',
                formInsert : {
                    selectmodule : selectmodule,
                    noversion : Noversion,
                    selectpic : selectpic,
                    Descriptionversion : Descriptionversion,
                    VersionID : VersionID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Edit Version Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>

<script>
    // Add save Group
    $(document).on('click','.btnSaveGroup',function () {
        savegroupmodule();
    });

    function savegroupmodule() {

        var selectdivision = $('.filterStatusDivision option:selected').attr('id');
        var Namegroup = $('#Namegroup').val();
        
        if(selectdivision!=null && selectdivision!=''
                    && Namegroup!='' && Namegroup!=null)
        { 
        
            var data = {
                action : 'AddGroupModule',
                formInsert : {
                    division : selectdivision,
                    Namegroup : Namegroup
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
                    toastr.error('Name Division or Name Group already is exist!','Error');
                } else {  
                    toastr.success('New Group Module Saved','Success');
                    setTimeout(function () {
                        $('#GlobalModal').modal('hide');
                            window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }

     //-------------------------------------------------------

    /// add Module
    $(document).on('click','.btnaddSaveGroup',function () {
        saveaddgroupmodule();
    });

    function saveaddgroupmodule() {

        var selectdivision = $('.filterStatusDivision2 option:selected').attr('id');
        var IDGroups = $('.filaddnamegroup option:selected').attr('id');
        var Namegroup = $('.filaddnamegroup').val();
        var Namemodule = $('#Namemodule').val();
        var Descriptiongroup = $('#Descriptiongroup').val();

        if(selectdivision!=null && selectdivision!=''
                && IDGroups!='' && IDGroups!=null
                && Namegroup!='' && Namegroup!=null
                && Namemodule!='' && Namemodule!=null
                && Descriptiongroup!='' && Descriptiongroup!=null)
        { 
    
        var data = {
            action : 'AddModule',
            formInsert : {
                division : selectdivision,
                IDGroups : IDGroups,
                Namemodule : Namemodule,
                Descriptiongroup : Descriptiongroup
            }
        };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
                    //toastr.error('Name division or module already is exist!','Error');
                } else {  
                    toastr.success('Add Module Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }



</script>

<script>
    $(document).on('click','.btnSaveVersion',function () {
        savedataversion();
    });

    function savedataversion() {

        var filternamepic = $('#filternamepic').val();
        var filterStatusModule = $('.filterStatusModule option:selected').attr('id');
        var Noversion = $('#Noversion').val();
        var Descriptionversion = $('#descriptionversion').val();
        
        if(filternamepic!=null && filternamepic!=''
                    && filterStatusModule!='' && filterStatusModule!=null
                    && Noversion!='' && Noversion!=null
                    && Descriptionversion!='' && Descriptionversion!=null)
        { 
        
        var data = {
            action : 'AddVersion',
            formInsert : {
                filternamepic : filternamepic,
                filterStatusModule : filterStatusModule,
                Noversion : Noversion,
                Descriptionversion : Descriptionversion
            }
        };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
                    toastr.error('Version already is exist!','Error');
                } else {  //if success save data
                    toastr.success('Version Data Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>


