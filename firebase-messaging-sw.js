importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyDNEjQGVH_D0gdXJ17IB_AUs1Z2uCdzWJs",
    authDomain: "pals-194015.firebaseapp.com",
    databaseURL: "https://pals-194015.firebaseio.com",
    projectId: "pals-194015",
    storageBucket: "pals-194015.appspot.com",
    messagingSenderId: "652219708720",
    appId: "1:652219708720:web:cea24855ef4135ce9584f5",
    measurementId: "G-WKXRKEJ5XD"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    // body...
    const title = payload.notification.title;
    const options = {
        body : payload.notification.body
    };
    return self.registration.showNotification(title,options);
})