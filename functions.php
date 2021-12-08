<?php
if($_GET['download']==null){
$function = $_GET['function'];
$handler = curl_init("http://esprezza2.no-ip.org/Capacitacion/EncuestaNom035/" . $function);
$_POST['url'] = 'encuestanom035.esprezza.com/';
if (isset($_POST['user_id'])) {
	if ($_POST['user_id'] == 1) {
		unset($_POST['user_id']);
	}
}
$_POST['cliente_id'] = '1';
$options = [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($_POST)];
curl_setopt_array($handler, $options);
$response = curl_exec($handler);

curl_close($handler);

return $response;
}else{
    //$handler = curl_init();
    $CurlConnect = curl_init();
	curl_setopt($CurlConnect, CURLOPT_URL, "http://esprezza2.no-ip.org/Capacitacion/EncuestaNom035/get_encuesta_pdf/" .  $_GET['download']);
	curl_setopt($CurlConnect, CURLOPT_POST, 1);
	curl_setopt($CurlConnect, CURLOPT_RETURNTRANSFER, 1);
	$Result = curl_exec($CurlConnect);

	header('Cache-Control: public');
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="' . $_GET['name'] . '"');
	header('Content-Length: ' . strlen($Result));
	echo $Result;
    return true;
}



?>