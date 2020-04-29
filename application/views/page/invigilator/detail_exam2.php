

<?php if($viewPageExam==1 || $viewPageExam=='1'){ ?>

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
        height: 400px;
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
                    <div class="col-md-4 col-md-offset-8">
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
                                        <div>Refresh the table in <span id="loadTimeTable">00:00:10</span></div>
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
                <div class="alert alert-warning" role="alert">
                    <b style="color: red;">Attention, please!</b>
                    <br/>
                    At this time the chat is not processed automatically,
                    you must wait for <b>3 minutes</b> to refresh the chat or use
                    the refresh button to refresh the chat manually.
                    <br/>
                    <b style="color: green;">For the future our IT team will continue to develop this well</b>
                </div>
                <p style="color: #ffffff;">Refresh the chat in <span id="viewChatCountdown"></span></p>
                <div class="panel panel-primary">
                    <div class="panel-heading" id="accordion">
                        <span class="fa fa-comment margin-right"></span> Chat With Invigilator
                        <div class="btn-group pull-right">
                            <a type="button" href="javascript:void(0);" id="btnRefreshChat" class="btn btn-default btn-xs">
                                <span class="fa fa-refresh margin-right"></span> Chat
                            </a>
                        </div>
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
        loadChat();
        loadCoutDownChatTable();

        loadCoutDown('#viewExountDown',"<?= $ExamOnline['ExamEnd']; ?>",1);
    });

    function loadTableExam(){
        var data = {
            action : 'loadDataExamOnline',
            ExamID : ExamID
        };
        var token = jwt_encode(data,'s3Cr3T-G4N');
        var url = base_url_js+'api4/__crudExamOnline';

        $.post(url,{token:token},function (jsonResult) {

            var Loading = '<tr>' +
                '<td colspan="4"><h4><i class="fa fa-refresh fa-spin"></i> Loading...</h4></td>' +
                '</tr>';

            $('#listStd').html(Loading);

            if(jsonResult.length>0){
                var tr = '';
                $.each(jsonResult,function (i,v) {
                    var viewStartWorking = moment(v.StartWorking).format('D MMM H:mm:ss');
                    var viewSavedAt = (v.SavedAt!='' && v.SavedAt!=null) ? moment(v.SavedAt).format('D MMM H:mm:ss') : '';
                    tr = tr+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left !important;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
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

            loadCoutDownChatTable();



        });
    }

    function loadChat() {
        var data = {
            action : 'loadChatExamOnline',
            ExamID : ExamID
        };
        var token = jwt_encode(data,'s3Cr3T-G4N');
        var url = base_url_js+'api4/__crudExamOnline';
        $.post(url,{token:token},function (jsonResult) {

            var loading = '<li class="clearfix" style="text-align: center;"><h4><i class="fa fa-refresh fa-spin margin-right"></i> Loading...</h4></li>';
            $('#viewChat').html(loading);


            if(jsonResult.length>0){

                var divChat = '';
                $.each(jsonResult,function (i,v) {


                    var isMe = (v.UserID == sessionNIP)
                        ? '<small class="text-muted label label-warning">Me</small> | ' : '';

                    var chatOn = moment(v.EntredAt).format('d MMM H:m');

                    divChat = divChat+'<li class="clearfix">' +
                        '                                            <div class="chat-body clearfix">' +
                        '                                                <div class="header">' +
                        '                                                    <strong class="primary-font">'+isMe+''+v.Name+' <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>'+chatOn+'</small></strong>' +
                        '                                                        '+
                        '                                                </div>' +
                        '                                                <div class="panel-chat">'+v.Message+'</div>' +
                        '                                            </div>' +
                        '                                        </li>';


                });

                setTimeout(function () {
                    $('#viewChat').html(divChat);
                },500);
            }
            else {
                var noData = '<li class="clearfix" style="text-align: center;"><h4>-- No data --</h4></li>';

                setTimeout(function () {
                    $('#viewChat').html(noData);
                },500);
            }

            loadCoutDownChat('#viewChatCountdown',moment().add(3,'minutes').format('H:mm:ss'));


        });

    }

    function loadCoutDownChat(element,EndSessions){

        var ens = EndSessions.split(':');
        var start = moment();
        var end   = moment().hours(ens[0]).minutes(ens[1]).seconds(ens[2]);

        var en = moment().valueOf();
        var d = end.diff(start);
        var fiveSeconds = parseInt(en) + parseInt(d);


        $(element)
            .countdown(fiveSeconds, function(event) {
                $(this).text(
                    // event.strftime('%D days %H:%M:%S')
                    event.strftime('%H:%M:%S')
                );
            })
            .on('finish.countdown', function() {

                loadChat();

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

    $('#inputMessage').keyup(function () {
        var inputMessage = $('#inputMessage').val();
        $('#viewChar').html(inputMessage.length);
    });

    $('#btnRefreshChat').click(function () {
        loadChat();
    });

    $('#btnSubmitChat').click(function () {
        var inputMessage = $('#inputMessage').val();
        if(inputMessage!=''){
            var data = {
                action : 'insertChatExamOnline',
                dataForm : {
                    ExamID : ExamID,
                    UserID : sessionNIP,
                    TypeUser : 'emp',
                    Message : inputMessage
                }
            };
            var token = jwt_encode(data,'s3Cr3T-G4N');
            var url = base_url_js+'api4/__crudExamOnline';
            $.post(url,{token:token},function (jsonResult) {
                $('#inputMessage').val('');
                $('#viewChar').html('0');
                loadChat();

            });
        }
    });
</script>

<?php } else { ?>

    <div class="container" style="margin-top: 30px;margin-bottom: 150px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
                <div class="alert alert-danger" role="alert">
                    <h3 style="margin-top: 10px;"><b>I'm sorry the exam time has expired</b></h3>
                </div>
                <hr/>
                <a href="<?= base_url('invigilator'); ?>" class="btn btn-lg btn-primary">Back to Home</a>
            </div>
        </div>
    </div>

<?php } ?>