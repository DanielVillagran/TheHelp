$(document).ready(function () {
	var urlParams = new URLSearchParams(window.location.search);
	email=urlParams.get('email'); 
    $("#correo").empty().append(email);

});