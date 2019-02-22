
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
</div>

<script>
    $(document).ready(function () {
        loadAcademicDetails();
    });

    $(document).on('click','#btnviewIjazahS1', function () {

        var filesub = $(this).attr('filesub');
       
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center> '+
            '<iframe src="'+base_url_js+'uploads/files/'+filesub+'" frameborder="0" style="width:745px; height:550px;"></iframe> '+
            '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary" data-dismiss="modal">Close</button>' +
            '</center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
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
            if(response.length>0){
            var no = 1;

            for (var i = 0; i < response.length; i++) {

                if (response[i]['TypeAcademic'] == 'S1' ) {

                    var strdate = response[i]['DateIjazah'];
                    var dates = moment(strdate).format('DD-MM-YYYY');

                    if (response[i]['IjazahFile'] != null) {
                        var filesx = '<iframe src="'+base_url_js+'uploads/files/'+response[i]['IjazahFile']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button id="btnviewIjazahS1" class="btn btn-sm btn-primary" filesub ="'+response[i]['IjazahFile']+'"><i class="fa fa-eye"></i> View </button></center>';
                        } else {
                        var filesx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">'
                    }

                    if (response[i]['TranscriptFile'] != null) {
                        var filestrans = '<iframe src="'+base_url_js+'uploads/files/'+response[i]['TranscriptFile']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button id="btnviewIjazahS1" class="btn btn-sm btn-primary" filesub ="'+response[i]['TranscriptFile']+'"><i class="fa fa-eye"></i> View </button></center>';
                        } else {
                        var filestrans = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">'
                    }

                $("#loadtableNow").append(
                    ' <div class="panel panel-default"> '+
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
                            '       <th style="width: 30%;"><div class="container"> '+filesx+' </th>'+
                            '           <td style="width: 25%;"> File Transcript </td> '+
                            '               <th style="width: 30%;"><div class="container"> '+filestrans+' </th>'+
                            '</tr> ' +
                            '</table>'+
                            '</div> '+
                            '</div> '
                    );  
                 }

                 if ((response[i]['TypeAcademic'] == 'S2') || (response[i]['TypeAcademic'] == 'S3')) {

                    $("#loadtableS2").append(
                    ' <div class="panel panel-default"> '+
                        '<div class="panel-body" style="padding: 0px;"> '+
                            '<h3 class="heading-small">Academic '+response[i]['TypeAcademic']+'</h3> '+
                            '<table id="dataPersonal2" class="table table-bordered table-striped table-data" style="margin-bottom: 0px;"> '+
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
                            '       <th style="width: 30%;"><div class="container"> '+filesx+' </th>'+
                            '           <td style="width: 25%;"> File Transcript </td> '+
                            '               <th style="width: 30%;"><div class="container"> '+filestrans+' </th>'+
                            '</tr> ' +
                            '</table>'+
                            '</div> '+
                            '</div> '
                        );  
                    }

                } //end for

            }
        }).done(function() {
        })
    };
</script>