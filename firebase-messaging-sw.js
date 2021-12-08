
importScripts('https://www.gstatic.com/firebasejs/7.8.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.8.2/firebase-messaging.js');
 // Your web app's Firebase configuration
 var firebaseConfig = {
    apiKey: "AIzaSyBiPoie2riDmI5KzZqG8YeMNRUBhZfHwzs",
    authDomain: "zumpango-8a42d.firebaseapp.com",
    databaseURL: "https://zumpango-8a42d.firebaseio.com",
    projectId: "zumpango-8a42d",
    storageBucket: "zumpango-8a42d.appspot.com",
    messagingSenderId: "481264006171",
    appId: "1:481264006171:web:8936edeb2e8407e679b4be",
    measurementId: "G-XP532P678P"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  const messaging = firebase.messaging();
    messaging.usePublicVapidKey("BOupuUzDsBymZXXOmAVLS7cvKQTqSCxVKh3tDtZa8CJU8sHsUC3j2UnU6il8kmhHShHooGanBIbfspadRDuRhmk");
  function showNotification() {
    Notification.requestPermission(function(result) {
      if (result === 'granted') {
        navigator.serviceWorker.ready.then(function(registration) {
          registration.showNotification('Vibration Sample', {
            body: 'Buzz! Buzz!',
            icon: '../images/touch/chrome-touch-icon-192x192.png',
            vibrate: [200, 100, 200, 100, 200, 100, 200],
            tag: 'vibration-sample'
          });
        });
      }
    });
  }

  function get_token(){
   messaging.getToken().then((currentToken) => {
    if (currentToken) {
      sendTokenToServer(currentToken);
      updateUIForPushEnabled(currentToken);
    } else {
    // Show permission request.
    console.log('No Instance ID token available. Request permission to generate one.');
    // Show permission UI.
    updateUIForPushPermissionRequired();
    setTokenSentToServer(false);
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
  //showToken('Error retrieving Instance ID token. ', err);
  //setTokenSentToServer(false);
});
}

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
    notificationOptions);

});