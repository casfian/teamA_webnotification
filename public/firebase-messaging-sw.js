importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCMM-yLnjJh-yfUkSb2FXknOVPJivoO1Z8",
    authDomain: "laravelnotifications1.firebaseapp.com",
    projectId: "laravelnotifications1",
    storageBucket: "laravelnotifications1.appspot.com",
    messagingSenderId: "211220431628",
    appId: "1:211220431628:web:58a98eb3abe320aed87f29"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});