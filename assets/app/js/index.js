var app = {
  // Application Constructor

  initialize: function () {
  },

  // Update DOM on a Received Event
  receivedEvent: function (id) {
    var parentElement = document.getElementById(id);
    var listeningElement = parentElement.querySelector(".listening");
    var receivedElement = parentElement.querySelector(".received");
    listeningElement.setAttribute("style", "display:none;");
    receivedElement.setAttribute("style", "display:block;");
    console.log("Received Event: " + id);
  },
};

app.initialize();
