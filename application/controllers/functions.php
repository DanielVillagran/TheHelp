<?php
$function = $_GET['function'];
$handler = curl_init("http://esprezza2.no-ip.org/Capacitacion/EncuestaNom035/" . $function);
$_POST['url'] = 'encuestanom035.esprezza.com/';
$_POST['cliente_id'] = '1';
$options = [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($_POST)];
curl_setopt_array($handler, $options);
$response = curl_exec($handler);
if (!curl_errno($handler)) {
 var_dump(curl_getinfo($handler));
}
curl_close($handler);

return $response;

?>