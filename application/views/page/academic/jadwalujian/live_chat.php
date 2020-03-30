
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-database.js"></script>

<style>

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
</style>

<div class="col-md-4 col-md-offset-4" id="examHelp">
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




<script>

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
        firebase.database().ref("msg_<?= $ExamID; ?>").push().set({
            "UserID" : sessionNIP,
            "Name" : ucwords(sessionName),
            "Type" : 'asc',
            "Message" : Message,
            "EntredAt" : getDateTimeNow()
        });


    }

    // listen a from incoming message
    firebase.database().ref("msg_<?= $ExamID; ?>").on("child_added",function (snapshot) {

        var isInv = (snapshot.val().Type=='emp' && snapshot.val().UserID != sessionNIP)
            ? '<small class="text-muted label label-success">Invigilator</small> | '
            : '';

        var isMe = (snapshot.val().UserID == sessionNIP)
            ? '<small class="text-muted label label-warning">Me</small> | '
            : '';

        var chatOn = moment(snapshot.val().EntredAt).format('d MMM H:m');

        var divChat = '<li class="clearfix">' +
            '                                            <div class="chat-body clearfix">' +
            '                                                <div class="header">' +
            '                                                    <strong class="primary-font">'+isMe+isInv+snapshot.val().Name+' <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>'+chatOn+'</small></strong>' +
            '                                                        '+
            '                                                </div>' +
            '                                                <div class="panel-chat">'+snapshot.val().Message+'</div>' +
            '                                            </div>' +
            '                                        </li>';


        document.getElementById("viewChat").innerHTML += divChat;
    });

</script>