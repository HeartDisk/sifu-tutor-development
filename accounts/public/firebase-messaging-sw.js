importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyA5KDujI41Fjat2JjFdDyUahSRfVkx3Aro",
    projectId: "sifututor-af80c",
    messagingSenderId: "725388372527",
    appId: "1:725388372527:web:557e266c9441d6e37c74da"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});