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
</style> 

<style>
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
            if (response.length > 0) {
                // console.log(response);return;
                for (var i = 0; i < response.length; i++) {
                    var IDselector = (response[i]['TypeAcademic'] == 'S1') ? '#loadtableNow' : '#loadtableS2' ;
                    var Dt = response[i]['Dt'];

                    for (var j = 0; j < Dt.length; j++) {
                        var NameUniversity = Dt[j]['Name_University'];
                        var Major1 = Dt[j]['Major'];
                        var Idlist1 = Dt[j]['ID'];
                        var Idlist2 = Dt[j]['ID2'];

                        if (Dt[j]['LinkFiles_st'] == 1) { // file exist
                            var Ijazah = '<iframe src="'+base_url_js+'uploads/files/'+Dt[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+Dt[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                        }
                        else {
                            var Ijazah = '<img src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="width:200px; height:100px;">';
                        }

                        if (Dt[j]['LinkFiles_tr_st'] == 1) { // file exist
                            var Transcript = '<iframe src="'+base_url_js+'uploads/files/'+Dt[j]['LinkFiles_tr']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-round btn-primary btnviewlistsrata" filesub ="'+Dt[j]['LinkFiles_tr']+'"><i class="fa fa-eye"></i> View </button></center>';
                        }
                        else {
                            var Transcript = '<img src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="width:200px; height:100px;">';
                        }

                        var strdate = Dt[j]['DateIjazah'];
                        var dates = moment(strdate).format('DD-MM-YYYY');
                        //console.log(Dt[j]);
                        $(IDselector).append('<div class="panel panel-default"> '+
                            '<div class="panel-body" style="padding: 0px;"> '+
                            '<h3 class="heading-small">Academic '+response[i]['TypeAcademic']+'  <div class="pull-right"><button class="btn btn-danger btn-circle btndelist" data-toggle="tooltip" data-placement="top" title="Delete" listid_ijazah ="'+Idlist1+'" listid_transcript ="'+Idlist2+'"><i class="fa fa-trash"></i></button></div></h3>'+
                            '<table id="dataPersonal" class="table table-bordered table-striped table-data" style="margin-bottom: 0px;"> '+
                            '<tr> '+
                            '<td style="width: 25%;">Name University</td> '+
                            '   <th style="width: 30%;"><span style="color: darkblue;">'+NameUniversity+'</span></th> '+
                            '     <td style="width: 25%;">No. Ijazah</td> '+
                            '       <th style="width: 30%;"><span style="color: darkblue;">'+Dt[j]['NoIjazah']+'</span></th> '+
                            '</tr> '+
                            '<tr> '+
                            '   <td style="width: 25%;">Major</td> '+
                            '       <th style="width: 30%;"><span style="color: darkblue;">'+Dt[j]['NamaJurusan']+'</span></th> '+
                            '           <td style="width: 25%;">Program Study</td> '+
                            '               <th style="width: 30%;"><span style="color: darkblue;">'+Dt[j]['NamaProgramStudi']+' </span></th> '+
                            '</tr> '+
                            '<tr> '+
                            '    <td style="width: 25%;"> Ijazah Date</td> '+
                            '       <th style="width: 30%;"> '+ dates +' </th> '+
                            '           <td style="width: 25%;"> Grade/ IPK </td> '+
                            '               <th style="width: 30%;">'+Dt[j]['Grade']+'</th> '+
                            '</tr> '+
                            '<tr> '+
                            '    <td style="width: 25%;"> Total Credit (SKS) </td> '+
                            '       <th style="width: 30%;">'+Dt[j]['TotalCredit']+'</th> '+
                            '           <td style="width: 25%;"> Total Semester </td> '+
                            '               <th style="width: 30%;">'+Dt[j]['TotalSemester']+'</th> '+
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


<script>
     $(document).on('click','.btndelist',function () {
        if (window.confirm('Are you sure to delete data?')) {
            //loading_button('#btndelist');

            var acaid1 = $(this).attr('listid1');
            var acaid2 = $(this).attr('listid2');
            var data = {
                action : 'deleteacademic',
                ID1 : acaid1,
                ID2 : acaid2,
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__delistacaemploy";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete Data!','Success'); 
                setTimeout(function () {
                    window.location.href = '';
                },1000);
            });
        }
    });
</script>


