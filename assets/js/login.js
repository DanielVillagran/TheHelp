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
firebase.initializeApp(firebaseConfig);
firebase.analytics();
// const messaging = firebase.messaging();
$(document).ready(function () {
  $("#loginbtn").click(function () {
    login();
  });
  $("#revisarHistorial").click(function () {
    revisarHistorial();
  });

});
function revisarHistorial() {
  $("#demo-form").hide();
  var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, qrbox: 150 });
  objetoScanner = html5QrcodeScanner
  html5QrcodeScanner.render(onScanSuccess);
  function onScanSuccess(decodedText, decodedResult) {
    //objetoScanner.stop();
    if (decodedText.includes("vehiculos...")) {
      let id = decodedText.split("vehiculos...")[1];
      location.replace("/ClienteVehiculos/view/" + id)
      // $('[name="users[vehiculoId]"]').val(id);
      html5QrcodeScanner.clear();
    }
  }
}
function login() {
  if ($("#login").val().length == "") {
    alert("Escribe tu nombre de usuario");
    return;
  }

  var data = {
    CorreoUser: $("#login").val().trim(),
    NameUser: Sha256.hash($("#password").val().trim()),
  };

  $.ajax({
    type: "post",
    url: "/user/login",
    async: false,
    cache: false,
    data: data,
    dataType: "json",
    beforeSend: function (Response) {
      swal({
        title: "Loading",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif",
      });
    },
    success: function (Response) {
      //swal.close();
      if (Response == true) {
        //get_token();
        location.reload();
      } else {
        swal("Error", "El usuario no existe", "error");
      }
    },
    error: function (Response, Error) {
      //alert("Error Interno: " + Error);
    },
  });
}
function saveTokenForUser(currentToken) {
  $.ajax({
    type: "post",
    url: "/User/add_token_to_user",
    data: { token: currentToken },
    dataType: "json",
    beforeSend: function () { },
    success: function (data) {
      location.reload();
    },
  });
}
function get_token() {
  messaging
    .getToken()
    .then((currentToken) => {
      if (currentToken) {
        console.log(currentToken);
        sendTokenToServer(currentToken);
        updateUIForPushEnabled(currentToken);
        saveTokenForUser(currentToken);
      } else {
        // Show permission request.
        console.log(
          "No Instance ID token available. Request permission to generate one."
        );
        // Show permission UI.
        updateUIForPushPermissionRequired();
        setTokenSentToServer(false);
      }
    })
    .catch((err) => {
      console.log("An error occurred while retrieving token. ", err);
    });
}
messaging.onTokenRefresh(() => {
  messaging
    .getToken()
    .then((refreshedToken) => {
      console.log("Token refreshed.");
      setTokenSentToServer(false);
      sendTokenToServer(refreshedToken);
      resetUI();
      // [END_EXCLUDE]
    })
    .catch((err) => {
      console.log("Unable to retrieve refreshed token ", err);
      showToken("Unable to retrieve refreshed token ", err);
    });
});
messaging.onMessage((payload) => {
  console.log("Message received. ", payload);
  appendMessage(payload);
  // [END_EXCLUDE]
});

function resetUI() {
  clearMessages();
  showToken("loading...");
  messaging
    .getToken()
    .then((currentToken) => {
      if (currentToken) {
        sendTokenToServer(currentToken);
        updateUIForPushEnabled(currentToken);
      } else {
        console.log(
          "No Instance ID token available. Request permission to generate one."
        );
        updateUIForPushPermissionRequired();
        setTokenSentToServer(false);
      }
    })
    .catch((err) => {
      console.log("An error occurred while retrieving token. ", err);
      showToken("Error retrieving Instance ID token. ", err);
      setTokenSentToServer(false);
    });
  // [END get_token]
}

function showToken(currentToken) {
  // Show token in console and UI.
  const tokenElement = document.querySelector("#token");
}
function sendTokenToServer(currentToken) {
  if (!isTokenSentToServer()) {
    console.log("Sending token to server...");
    setTokenSentToServer(true);
  } else {
    console.log(
      "Token already sent to server so won't send it again " +
      "unless it changes"
    );
  }
}

function isTokenSentToServer() {
  return window.localStorage.getItem("sentToServer") === "1";
}

function setTokenSentToServer(sent) {
  window.localStorage.setItem("sentToServer", sent ? "1" : "0");
}

function showHideDiv(divId, show) {
  const div = document.querySelector("#" + divId);
  if (show) {
    div.style = "display: visible";
  } else {
    div.style = "display: none";
  }
}

function requestPermission() {
  console.log("Requesting permission...");
  Notification.requestPermission().then((permission) => {
    if (permission === "granted") {
      console.log("Notification permission granted.");
      resetUI();
    } else {
      console.log("Unable to get permission to notify.");
    }
  });
}

function deleteToken() {
  messaging
    .getToken()
    .then((currentToken) => {
      messaging
        .deleteToken(currentToken)
        .then(() => {
          console.log("Token deleted.");
          setTokenSentToServer(false);

          resetUI();
        })
        .catch((err) => {
          console.log("Unable to delete token. ", err);
        });
      // [END delete_token]
    })
    .catch((err) => {
      console.log("Error retrieving Instance ID token. ", err);
      showToken("Error retrieving Instance ID token. ", err);
    });
}

function appendMessage(payload) {
  const messagesElement = document.querySelector("#messages");
  const dataHeaderELement = document.createElement("h5");
  const dataElement = document.createElement("pre");
  dataElement.style = "overflow-x:hidden;";
  dataHeaderELement.textContent = "Received message:";
  dataElement.textContent = JSON.stringify(payload, null, 2);
}

function clearMessages() {
  const messagesElement = document.querySelector("#messages");
  while (messagesElement.hasChildNodes()) {
    messagesElement.removeChild(messagesElement.lastChild);
  }
}

function updateUIForPushEnabled(currentToken) {
  showToken(currentToken);
}

function updateUIForPushPermissionRequired() { }
