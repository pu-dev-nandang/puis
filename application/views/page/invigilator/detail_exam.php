

<?php if($viewPageExam==1 || $viewPageExam=='1'){ ?>

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-database.js"></script>

<style>
    #examHelp {
        border-left: 1px solid #CCCCCC;
    }

    #examHelp .chat
    {
        list-style: none;
        margin: 0;
        padding: 0;
        background: #ffffff;
    }

    #examHelp .chat li
    {
        margin-bottom: 10px;
        padding-bottom: 0px;
        border-bottom: 1px dotted #B3A9A9;
    }

    #examHelp .chat li.left .chat-body
    {
        /*margin-left: 60px;*/
    }

    #examHelp .chat li.right .chat-body
    {
        /*margin-right: 60px;*/
    }


    #examHelp .chat li .chat-body p
    {
        margin: 0;
        color: #777777;
    }

    #examHelp .panel .slidedown .glyphicon, .chat .glyphicon
    {
        margin-right: 5px;
    }

    #examHelp .panel-body
    {
        overflow-y: scroll;
        height: 550px;
    }

    #examHelp .label {
        position: relative;
        left: unset;
    }

    #examHelp .panel-chat {
        margin-top: 10px;
        margin-bottom: 10px;
        background: #f5f5f5;
        padding: 5px 10px 5px 10px;
        border-radius: 7px;
    }
    .table-centre th, .table-centre td {
        text-align: center !important;
    }

    #examCountDown {
        font-size: 19px;
        padding: 15px;
        background: #ffffff;
        margin-bottom: 10px;
        text-align: center;
    }
</style>

<div class="container" style="margin-top: 30px;">
    <div class="col-md-10 col-md-offset-1">



        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-8">
                        <a href="<?= base_url('invigilator'); ?>" class="btn btn-lg btn-warning">Back to Home</a>
                    </div>
                    <div class="col-md-4">
                        <div id="examCountDown">Countdown <span id="viewExountDown">00:00:00</span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div style="text-align: center;">
                                    <h1><?= $dataToken['ClassGroup'].' - '.$dataToken['CourseEng'] ?></h1>
                                    <hr/>
                                </div>

                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-md-8">
<!--                                        <div>Refresh the table in <span id="loadTimeTable">00:00:10</span></div>-->
                                        <div class="alert alert-info" role="alert">Use the buttons to refresh the students present</div>
                                    </div>
                                    <div class="col-md-4" style="text-align: right;">
                                        <button class="btn btn-info btn-sm" id="btnRefreshTable"><i class="fa fa-refresh"></i> Table</button>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped table-centre">
                                    <thead>
                                    <tr style="background: #eaeaea;">
                                        <th style="width: 1%;">No</th>
                                        <th>Student</th>
                                        <th style="width: 20%;">Attendance</th>
                                        <th style="width: 20%;">Submit Exam</th>
                                    </tr>
                                    </thead>
                                    <tbody id="listStd"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4" id="examHelp">
                <div class="panel panel-primary">
                    <div class="panel-heading" id="accordion">
                        <span class="fa fa-comment margin-right"></span> Live Chat With Student
                    </div>
                    <div class="panel-collapse collapse in" aria-expanded="true" id="collapseOne">
                        <div class="panel-body">
                            <ul class="chat" id="viewChat"></ul>
                        </div>
                        <div class="panel-footer">
                            <div class="input-group">
                                <input id="inputMessage" type="text" class="form-control input-sm" placeholder="Type your message here..." maxlength="200" />
                                <span class="input-group-btn">
                                            <button class="btn btn-warning btn-sm" id="btnSubmitChat">
                                                Send</button>
                                        </span>
                            </div>
                            <p class="help-block"><span id="viewChar">0</span> of 200 character</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.ExamID = "<?= $dataToken['ExamID']; ?>";
        //getCountdw('#showCountdown',"<?//= $dataExamOnline['ExamEnd'] ?>//");
        loadTableExam();

        // loadCoutDownChatTable();

        loadCoutDown('#viewExountDown',"<?= $ExamOnline['ExamEnd']; ?>",1);
    });

    // fire base
    var firebaseConfig = {
        apiKey: "AIzaSyCj6Wf2ARn_N3Nqsa1YGY5HcRHKoCFQaNA",
        authDomain: "my-test-6976a.firebaseapp.com",
        databaseURL: "https://my-test-6976a.firebaseio.com",
        projectId: "my-test-6976a",
        storageBucket: "my-test-6976a.appspot.com",
        messagingSenderId: "964038080180",
        appId: "1:964038080180:web:ca3fd88deb491a8c7d09e1"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);


    function loadTableExam(){
        var data = {
            action : 'loadDataExamOnline',
            ExamID : ExamID
        };
        var token = jwt_encode(data,'s3Cr3T-G4N');
        var url = base_url_js+'api4/__crudExamOnline';

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

            var Loading = '<tr>' +
                '<td colspan="4"><h4><i class="fa fa-refresh fa-spin"></i> Loading...</h4></td>' +
                '</tr>';

            $('#listStd').html(Loading);

            if(jsonResult.length>0){
                var tr = '';
                $.each(jsonResult,function (i,v) {
                    var viewStartWorking = moment(v.StartWorking).format('D MMM H:mm:ss');
                    var viewSavedAt = (v.SavedAt!='' && v.SavedAt!=null) ? moment(v.SavedAt).format('D MMM H:mm:ss') : '';

                    var viewDescAnsw = (v.Description!='' && v.Description!=null)
                        ? '<div><textarea class="form-control" readonly>'+v.Description+'</textarea></div>'
                        : '';

                    var viewFileAnsw = (v.File!='' && v.File!=null)
                        ? '<div style="margin-bottom: 10px;margin-top: 10px;"><a href="'+base_url_js+'uploads/task-exam/'+v.File+'" target="_blank" class="btn btn-sm btn-default">Download file</a></div>'
                        : '';

                    tr = tr+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left !important;"><b>'+v.Name+'</b><br/>'+
                        v.NPM+
                        viewFileAnsw+viewDescAnsw+
                        '</td>' +
                        '<td>'+viewStartWorking+'</td>' +
                        '<td>'+viewSavedAt+'</td>' +
                        '</tr>';
                });
                setTimeout(function () {
                    $('#listStd').html(tr);
                },500);
            }
            else {
                setTimeout(function () {
                    var Loading = '<tr>' +
                        '<td colspan="4"><h4>-- No data --</h4></td>' +
                        '</tr>';

                    $('#listStd').html(Loading);
                },500);
            }

            // loadCoutDownChatTable();



        });
    }



    function loadCoutDownChatTable(){

        var EndSessions = moment().add(3,'minutes').format('H:mm:ss');
        var ens = EndSessions.split(':');
        var start = moment();
        var end   = moment().hours(ens[0]).minutes(ens[1]).seconds(ens[2]);

        var en = moment().valueOf();
        var d = end.diff(start);
        var fiveSeconds = parseInt(en) + parseInt(d);


        $('#loadTimeTable')
            .countdown(fiveSeconds, function(event) {
                $(this).text(
                    // event.strftime('%D days %H:%M:%S')
                    event.strftime('%H:%M:%S')
                );
            })
            .on('finish.countdown', function() {

                loadTableExam();

            });
    }

    $('#btnRefreshTable').click(function () {
        loadTableExam();
    });

    $('#inputMessage').keyup(function (e) {
        var inputMessage = $('#inputMessage').val();
        $('#viewChar').html(inputMessage.length);


        if (e.keyCode === 13) {
            var inputMessage = $('#inputMessage').val();
            if(inputMessage!=''){
                sendMessage(inputMessage);
                $('#inputMessage').val('');
                $('#viewChar').html('0');
            }
        }

    });


    $('#btnSubmitChat').click(function () {
        var inputMessage = $('#inputMessage').val();
        if(inputMessage!=''){
            sendMessage(inputMessage);
            $('#inputMessage').val('');
        }
    });

    function sendMessage(Message) {

        // Save to DB
        firebase.database().ref("msg_"+ExamID).push().set({
            "UserID" : sessionNIP,
            "Name" : ucwords(sessionName),
            "Type" : 'emp',
            "Message" : Message,
            "EntredAt" : getDateTimeNow()
        });


    }

    // listen a from incoming message
    firebase.database().ref("msg_<?= $dataToken['ExamID']; ?>").on("child_added",function (snapshot) {


        var isMe = (snapshot.val().UserID == sessionNIP)
            ? '<small class="text-muted label label-warning">Me</small> | '
            : '';

        var isASC = (snapshot.val().Type == 'asc')
            ? '<small class="text-muted label label-danger">ASC</small> | ' : ''

        var chatOn = moment(snapshot.val().EntredAt).format('d MMM HH:mm');

        var divChat = '<li class="clearfix">' +
            '                                            <div class="chat-body clearfix">' +
            '                                                <div class="header">' +
            '                                                    <strong class="primary-font">'+isMe+isASC+snapshot.val().Name+' <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>'+chatOn+'</small></strong>' +
            '                                                        '+
            '                                                </div>' +
            '                                                <div class="panel-chat">'+snapshot.val().Message+'</div>' +
            '                                            </div>' +
            '                                        </li>';


        document.getElementById("viewChat").innerHTML += divChat;
    });

</script>

<?php } else { ?>

    <div class="container" style="margin-top: 30px;margin-bottom: 150px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
                <div class="alert alert-danger" role="alert">
                    <h3 style="margin-top: 10px;"><b>The exam has been reached the time duration.</b></h3>
                </div>
                <hr/>
                <a href="<?= base_url('invigilator'); ?>" class="btn btn-lg btn-primary">Back to Home</a>
            </div>
        </div>
    </div>

<?php } ?>