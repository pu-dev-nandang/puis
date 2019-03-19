
<style type="text/css">
    @media screen and (min-width: 768px) {
        .modal-content {
          width: 785px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }
    @media screen and (min-width: 992px) {
        .modal-lg {
          width: 950px; /* New width for large modal */
        }
    }
</style>

<div class="row">
    <div class="col-md-6" style="border-right: 1px solid #afafafb5;">
        <span id = "loadtableNow"></span>             
    </div>
 <!-- <span id="bodyAddSesi"></span> -->
    <div class="col-md-6">
        <div id = "loadtableS2"></div>
    </div>
   <!--  <div id = "pagecontent"></div> -->
</div>

<script>
    $(document).ready(function () {
        loadAcademicDetails();
    });

    function loadAcademicDetails() {
        var NIP = '<?php echo $NIP; ?>';
        var url = base_url_js+'api/__reviewacademic?NIP='+NIP;
        var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            //console.log(resultJson);
            var response = resultJson;
            response.sort(function(a, b){
                var keyA = new Date(a.TypeAcademic),
                    keyB = new Date(b.TypeAcademic);
                // Compare the 2 dates
                if(keyA < keyB) return -1;
                if(keyA > keyB) return 1;
                return 0;
            });
            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                       
                    if (response[i]['TypeAcademic'] == 'S1' ) {
                        //var NameUniversity1 = response[i]['NameUniversity'].toLowerCase();
                        var NameUniversity1 = response[i]['NameUniversity'];

                            if (response[i]['LinkFiles'] != null) {
                                var Ijazah = '<iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                            } else {
                                var Ijazah = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">';
                            }

                            if (response[i]['DateIjazah'] != null) {
                                var strdate = response[i]['DateIjazah'];
                                var dates = moment(strdate).format('DD-MM-YYYY');
                            } else {
                                var dates = '00-00-0000';

                            }

                        for (var j = i+1; j < response.length; j++) {
                            //var NameUniversity2 = response[j]['NameUniversity'].toLowerCase();
                            var NameUniversity2 = response[i]['NameUniversity'];
                            if (NameUniversity1 == NameUniversity2) {
                                //var Transcript = response[j]['LinkFiles'];
                                if (response[j]['LinkFiles'] != null) {
                                    var Transcript = '<iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                                } else {
                                    var Transcript = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">';
                                }

                                $("#loadtableNow").append(  ' <div class="panel panel-default"> '+
                                '<div class="panel-body" style="padding: 0px;"> '+
                                '<h3 class="heading-small">Academic '+response[i]['TypeAcademic']+'</h3> '+
                                '<table id="dataPersonal" class="table table-bordered table-striped table-data" style="margin-bottom: 0px;"> '+
                                '<tr> '+
                                '<td style="width: 25%;">Name University</td> '+
                                '   <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['NameUniversity']+'</span></th> '+
                                '     <td style="width: 25%;">No. Ijazah</td> '+
                                '       <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['NoIjazah']+'</span></th> '+
                                '</tr> '+
                                '<tr> '+
                                '   <td style="width: 25%;">Major</td> '+
                                '       <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['Major']+'</span></th> '+
                                '           <td style="width: 25%;">Program Study</td> '+
                                '               <th style="width: 30%;">'+response[i]['ProgramStudy']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> Ijazah Date</td> '+
                                '       <th style="width: 30%;"> '+ dates +' </th> '+
                                '           <td style="width: 25%;"> Grade/ IPK </td> '+
                                '               <th style="width: 30%;">'+response[i]['Grade']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> Total Credit (SKS) </td> '+
                                '       <th style="width: 30%;">'+response[i]['TotalCredit']+'</th> '+
                                '           <td style="width: 25%;"> Total Semester </td> '+
                                '               <th style="width: 30%;">'+response[i]['TotalSemester']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> File Ijazah </td> '+
                                '       <th style="width: 30%;"><div class="container"> '+Ijazah+' </th>'+
                                '           <td style="width: 25%;"> File Transcript </td> '+
                                '               <th style="width: 30%;"><div class="container"> '+Transcript+' </th>'+
                                '</tr> ' +
                                '</table>'+
                                '</div> '+
                                '</div> ')
                                i = j;
                                break;
                            }
                        }

                    }
                    else
                    {
                        //var NameUniversity1 = response[i]['NameUniversity'].toLowerCase();
                        var NameUniversity1 = response[i]['NameUniversity'];
                        if (response[i]['DateIjazah'] != null) {
                                var strdate = response[i]['DateIjazah'];
                                var dates = moment(strdate).format('DD-MM-YYYY');
                            } else {
                                var dates = '00-00-0000';
                        }
            
                        if (response[i]['LinkFiles'] != null) {
                            var Ijazah = '<iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                        } else {
                            var Ijazah = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">';
                        }
                        for (var j = i+1; j < response.length; j++) {
                            //var NameUniversity2 = response[j]['NameUniversity'].toLowerCase();
                            var NameUniversity2 = response[j]['NameUniversity'];
                            if (NameUniversity1 == NameUniversity2) {
                                //var Transcript = response[j]['LinkFiles'];
                                    if (response[j]['LinkFiles'] != null) {
                                        var Transcript = '<iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                                    } else {
                                        var Transcript = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">';
                                    }
                        $("#loadtableS2").append( '<div class="panel panel-default"> '+
                            '<div class="panel-body" style="padding: 0px;"> '+
                                '<h3 class="heading-small">Academic '+response[i]['TypeAcademic']+'</h3> '+
                                '<table id="dataPersonal" class="table table-bordered table-striped table-data" style="margin-bottom: 0px;"> '+
                                '<tr> '+
                                '<td style="width: 25%;">Name University</td> '+
                                '   <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['NameUniversity']+'</span></th> '+
                                '     <td style="width: 25%;">No. Ijazah</td> '+
                                '       <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['NoIjazah']+'</span></th> '+
                                '</tr> '+
                                '<tr> '+
                                '   <td style="width: 25%;">Major</td> '+
                                '       <th style="width: 30%;"><span style="color: darkblue;">'+response[i]['Major']+'</span></th> '+
                                '           <td style="width: 25%;">Program Study</td> '+
                                '               <th style="width: 30%;">'+response[i]['ProgramStudy']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> Ijazah Date</td> '+
                                '       <th style="width: 30%;"> '+ dates +' </th> '+
                                '           <td style="width: 25%;"> Grade/ IPK </td> '+
                                '               <th style="width: 30%;">'+response[i]['Grade']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> Total Credit (SKS) </td> '+
                                '       <th style="width: 30%;">'+response[i]['TotalCredit']+'</th> '+
                                '           <td style="width: 25%;"> Total Semester </td> '+
                                '               <th style="width: 30%;">'+response[i]['TotalSemester']+'</th> '+
                                '</tr> '+
                                '<tr> '+
                                '    <td style="width: 25%;"> File Ijazah </td> '+
                                '       <th style="width: 30%;"><div class="container"> '+Ijazah+' </th>'+
                                '           <td style="width: 25%;"> File Transcript </td> '+
                                '               <th style="width: 30%;"><div class="container"> '+Transcript+' </th>'+
                                '</tr> ' +
                                '</table>'+
                                '</div> '+
                                '</div> ')
                                i = j;
                                break;
                            }
                        }
                    }
                    
                }
                
            }

        }).done(function() {
        })
    
        setTimeout(function () {
                //$('#loadtablefiles1').html(resultJson);
            },500)
    };
</script>



