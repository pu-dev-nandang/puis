<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.13.0/firebase-database.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->

<script>
    // Your web app's Firebase configuration
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

    var myName = prompt("Enter your name...");

    function sendMessage() {
        var message = document.getElementById("message").value;

        // Save to DB
        firebase.database().ref("message").push().set({
            "sender" : myName,
            "message" : message
        });

        return false;
    }

    // listen a from incoming message
    firebase.database().ref("message").on("child_added",function (snapshot) {

        var btn = '<button data-id="'+snapshot.key+'" onclick="deleteMessage(this);">Del</button>';

        var html = "";
        html += '<li id="ms_'+snapshot.key+'">';
        html += snapshot.val().sender + ": "+snapshot.val().message + btn;
        html += "</li>";

        document.getElementById("messages").innerHTML += html;
    });

    function deleteMessage(self) {

        var messageId = self.getAttribute("data-id");

        // Remove
        firebase.database().ref("message").child(messageId).remove();
    }

    // Attc
    firebase.database().ref("message").on("child_removed",function(snapshot){
        document.getElementById("ms_"+snapshot.key).innerHTML= "This message has been removed";
    });

</script>

<form onsubmit="return sendMessage();">
    <input type="text" id="message" placeholder="Enter message..." autocomplete="off" />

    <input type="submit"/>
</form>


<ul id="messages">

</ul>
