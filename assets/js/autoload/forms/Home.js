$(document).ready(function () {
  var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, qrbox: 150 });
  objetoScanner = html5QrcodeScanner
  html5QrcodeScanner.render(onScanSuccess);
  function onScanSuccess(decodedText, decodedResult) {
    //objetoScanner.stop();
    if (decodedText.includes("vehiculos...")) {
      let id = decodedText.split("vehiculos...")[1];
      location.replace("/Vehiculos/edit/" + id)
      // $('[name="users[vehiculoId]"]').val(id);
      html5QrcodeScanner.clear();
    }
  }

});