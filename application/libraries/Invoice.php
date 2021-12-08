<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Invoice
 *
 * Facturacion ANT.
 *
 * @package		Invoice
 * @author		Martin Mora
 * @version		1.0.0
 */
class Invoice {

    private $error = array();
    public $erp_invoice_id;
    public $uuid;
    public $ant_account_id;


    function __construct() {
        $this->ci = & get_instance();

        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('Erp_Invoice_Model');
    }

    public function setEmision() {
        $invoice = Erp_Invoice_Model::get_invoice_emision($this->erp_invoice_id);
        $logdato['createdby'] = null;
        $logdato['type'] = 'info';
        $logdato['logtime'] = date('H:i:s');
        $logdato['logdate'] = date('Y-m-d');
        $logdato['message'] = 'Timbre invoice ' . $this->erp_invoice_id . ' QUERY:  ' . json_encode($invoice);
        $logdato['level'] = '2';
        $logs = Log_Model::Insert($logdato);
        $type = '';
        $valores = '';
        $type_product = "";
        $metodo_pago = "";
        $complemento = "";
        $saldoAnterior = 0;
        $saldoInsoluto = 0;
        $formaP = "";
        //$ant_account_id = $this->session->userdata('ant_account_id');
        $invoice->discount = '0.00';
        if ($invoice->colonia_texto == '' || $invoice->colonia_texto == null) {
            $invoice->colonia = $invoice->colonia;
        } else {
            $invoice->colonia = $invoice->colonia_texto;
        }
        if ($invoice->municipio_texto == '' || $invoice->municipio_texto == null) {
            $invoice->municipio = $invoice->municipio;
        } else {
            $invoice->municipio = $invoice->municipio_texto;
        }
        if ($invoice->pais == '' || $invoice->pais == null) {
            $invoice->pais = 'Mexico';
        }
        if ($invoice->nota_credito == 't') {
            $type = 'E';
            $valores = '<tiporelacion>01</tiporelacion>
                        <uuid_relacion>' . $invoice->uuid_nota_credito . '</uuid_relacion>';
            $invoice->subtotal = number_format($invoice->subtotal, 2, '.', '');
            $invoice->total = number_format($invoice->total, 2, '.', '');
            $invoice->moneda = $invoice->moneda;
            $formaP = '<formaDePago>' . $invoice->metodo_pago . '</formaDePago>';
            $metodo_pago = '<metodoDePago>' . $invoice->forma_pago . '</metodoDePago>';
        } else if ($invoice->complemento == 't') {
            $invoice->uuid_complemento = isset($invoice->uuid_complemento) || $invoice->uuid_complemento != '' ? $invoice->uuid_complemento : $invoice->uuid_remplazo;
            $facturaPrincipal = Erp_Invoice_Model::get_factura_principal_complementos($invoice->uuid_complemento);
            $type = 'P';
            $valores = '';
            $metodo_pago = '<metodoDePago>' . $invoice->forma_pago . '</metodoDePago>';
            $remplazo = "";
            $optionst['select'] = "sum(total) as total";
            $optionst['where'] = "uuid_complemento='{$invoice->uuid_complemento}' and status=1";
            $optionst['result'] = "1row";
            $saldo_complementos = Erp_Invoice_Model::Load($optionst);
            if ($facturaPrincipal) {
                if ($invoice->num_parcialidad == 1) {
                    $saldoAnterior = $facturaPrincipal->total;
                } else {
                    $saldoAnterior = floatval($facturaPrincipal->total) - floatval($saldo_complementos->total);
                    $saldoAnterior = abs($saldoAnterior);
                }
            }
            $saldoInsoluto = abs(number_format($saldoAnterior - $invoice->total, 2));
            if (!is_null($invoice->uuid_remplazo) && $invoice->uuid_remplazo != '') {
                $facturaPrincipal = Erp_Invoice_Model::get_factura_principal_complementos($invoice->uuid_complemento);
                $xx = Erp_Invoice_Model::get_factura_principal_complementos($facturaPrincipal->uuid_complemento);
                $optionst['select'] = "sum(total) as total";
                $optionst['where'] = "uuid_complemento='{$facturaPrincipal->uuid_complemento}' and status=1";
                //$optionst['where'] = "uuid_complemento='{$facturaPrincipal->uuid_complemento}' and status=1 and num_parcialidad<$facturaPrincipal->num_parcialidad";
                $optionst['result'] = "1row";
                $saldo_complementos = Erp_Invoice_Model::Load($optionst);
                $valores = '<tiporelacion>04</tiporelacion>
                        <uuid_relacion>' . $invoice->uuid_remplazo . '</uuid_relacion>';
                $saldoAnterior = floatval($xx->total) - floatval($saldo_complementos->total);
                $saldoInsoluto = $saldoAnterior - floatval($invoice->total);
            }
            $complemento = '<complemento>
                <pago10>
                    <pago>
                        <fechaPago>' . $invoice->fecha_expedicion . '</fechaPago>
                        <monedaP>' . $invoice->moneda . '</monedaP>
                        <formaDePagoP>' . $invoice->metodo_pago . '</formaDePagoP>
                        <monto>' . number_format($invoice->total, 2, '.', '') . '</monto>
                        ';
            if (!is_null($invoice->rfc_cta_ord) && $invoice->rfc_cta_ord != '') {
                $complemento = $complemento . '<rfcCtaOrd>' . $invoice->rfc_cta_ord . '</rfcCtaOrd>
                             ';
            }
            if (!is_null($invoice->cta_ord) && $invoice->cta_ord != '') {
                $complemento = $complemento . '<ctaOrd>' . $invoice->cta_ord . '</ctaOrd>
                             ';
            }
            if (!is_null($invoice->num_operacion) && $invoice->num_operacion != '') {
                $complemento = $complemento . '<numOperacion>' . $invoice->num_operacion . '</numOperacion>
                             ';
            }
            if (!is_null($invoice->rfc_cta_ben) && $invoice->rfc_cta_ben != '') {
                $complemento = $complemento . '<rfcCtaBen>' . $invoice->rfc_cta_ben . '</rfcCtaBen>
                             ';
            }
            if (!is_null($invoice->cta_ben) && $invoice->cta_ben != '') {
                $complemento = $complemento . '<ctaBen>' . $invoice->cta_ben . '</ctaBen>
                             ';
            }
            if (!is_null($invoice->tipo_cade_pago) && $invoice->tipo_cade_pago != '') {
                $complemento = $complemento . '<tipoCadPago>' . $invoice->tipo_cade_pago . '</tipoCadPago>' .
                    '<certPago>' . $invoice->cert_pago . '</certPago>' .
                    '<cadPago>' . $invoice->cad_pago . '</cadPago>' .
                    '<selloPago>' . $invoice->sello_pago . '</selloPago>';
            }
            $complemento = $remplazo . $complemento . '</pago>
                    <relacion>
                        <uuid>' . $invoice->uuid_complemento . '</uuid>
                        <serie>' . $invoice->serie . '</serie>
                        <folio>' . $facturaPrincipal->folio . '</folio>
                        <metodoPago>' . $invoice->forma_pago . '</metodoPago>
                        <numParcialidad>' . $invoice->num_parcialidad . '</numParcialidad>    
                        <moneda>' . $invoice->moneda . '</moneda>
                        <saldoAnterior>' . number_format($saldoAnterior, 2, '.', '') . '</saldoAnterior>
                        <saldoinsoluto>' . $saldoInsoluto . '</saldoinsoluto>
                    </relacion>
                </pago10>
                     </complemento>';
            $invoice->subtotal = 0;
            $invoice->total = 0;
            $invoice->moneda = 'XXX';
            $formaP = '';
            //$invoice->fecha_expedicion = $facturaPrincipal->fecha_expedicion;
        } else if ($invoice->forma_pago == 'PPD') {
            $type = 'I';
            $valores = '';
            $metodo_pago = '<metodoDePago>' . $invoice->forma_pago . '</metodoDePago>';
            $invoice->subtotal = number_format($invoice->subtotal, 2, '.', '');
            $invoice->total = number_format($invoice->total, 2, '.', '');
            $invoice->moneda = $invoice->moneda;
            $formaP = '<formaDePago>' . $invoice->metodo_pago . '</formaDePago>';
        } else {
            $metodo_pago = '<metodoDePago>' . $invoice->forma_pago . '</metodoDePago>';
            $type = 'I';
            $valores = '';
            $invoice->subtotal = number_format($invoice->subtotal, 2, '.', '');
            $invoice->total = number_format($invoice->total, 2, '.', '');
            $invoice->moneda = $invoice->moneda;
            $formaP = '<formaDePago>' . $invoice->metodo_pago . '</formaDePago>';
        }
        $invoice->rfc = preg_replace("[\s+]", "", $invoice->rfc);
        $invoice->rfc = str_replace("&", "&amp;", $invoice->rfc);
        //$invoice->razon_social = preg_replace("[\s+]", "", $invoice->razon_social);
        $invoice->razon_social = str_replace("&", "&amp;", $invoice->razon_social);
        $claveProdServ = "";
        $claveUnidad = "";
        $datosReceptor = '';
        $claveProdServd = "";

        $invoice->uso_cfdi = (isset($invoice->uso_cfdi) || $invoice->uso_cfdi != 0 || $invoice->uso_cfdi != null) ? $invoice->uso_cfdi : 'G03';
        if ($invoice->rfc == 'XAXX010101000') {
            $claveProdServd = "01010101";
            $claveUnidad = "ACT";
            //$invoice->razon_social = "";
            $datosReceptor = '<rfc>' . $invoice->rfc . '</rfc>';
            $datosReceptor .= '<nombre>' . $invoice->razon_social . '</nombre>';
            $datosReceptor .= '<usoCFDI>' . $invoice->uso_cfdi . '</usoCFDI>';
            $type_product = "Actividad";
        } else {
            $claveProdServd = "80161504";
            $claveUnidad = "E48";
            $datosReceptor = '<rfc>' . $invoice->rfc . '</rfc>';
            $datosReceptor .= '<nombre>' . $invoice->razon_social . '</nombre>';
            $datosReceptor .= '<usoCFDI>' . $invoice->uso_cfdi . '</usoCFDI>';
            $type_product = "Unidad de servicio";
        }

        //DATOS DIRECCION RECEPTOR
        $calle = $invoice->calle;
        $nExterior = $invoice->noexterior;
        $nInterior = $invoice->nointerior;
        $colonia = $invoice->colonia;
        $localidad = $invoice->localidad;
        $municipio = $invoice->municipio;
        $estado = $invoice->estado;
        $pais = $invoice->pais;
        $cp = '44610';
        $descuento = "";
        /*if ($invoice->ant_account_id == '1000') {
            $cp = '44600';
            $invoice->serie='C';
        }*/
        if ($invoice->total > 0) {
            //$descuento = '<descuento>' . $invoice->discount . '</descuento>';
        } else {
            $descuento = "";
        }
        $emision = '<emision>
		<cliente>50</cliente>
		<factura>
			<data>
					<serie>' . $invoice->serie . '</serie>
					<folio>' . $invoice->folio . '</folio>
					<fecha>' . $invoice->fecha_expedicion . '</fecha>';
        $emision = $emision . $formaP;
        $emision = $emision . '<subtotal>' . $invoice->subtotal . '</subtotal>';
        $emision = $emision . $descuento;
        $emision = $emision . '<total>' . $invoice->total . '</total>';
        $emision = $emision . $metodo_pago;
        $emision = $emision . '<tipoDeComprobante>' . $type . '</tipoDeComprobante>
					<lugarDeExpedicion>' . $cp . '</lugarDeExpedicion>
                    <moneda>' . $invoice->moneda . '</moneda>
					<condPago>' . $invoice->condiciones_pago . '</condPago>';
        $emision = $emision . $valores;
        $emision = $emision . '</data>
		<receptor>' . $datosReceptor . '</receptor>
			<conceptos>';
        $invoice_detail = Erp_Invoice_Detail_Model::load(array(
            'select' => '
						erp_invoice_detail.inv_products_id,
						erp_invoice_detail.cantidad,
						erp_invoice_detail.costo,
						erp_invoice_detail.importe,
						erp_invoice_detail.product_name,
                                                erp_invoice_detail.erp_clave_producto,
                                                erp_invoice_detail.inv_product_unity_type_id',
            'where' => array('erp_invoice_detail.erp_invoice_id' => $this->erp_invoice_id)
        ));

        $factura_tax = Erp_Invoice_Tax_Model::load(
            array(
                'select' => 'impuesto, tasa, importe, tipo_impuesto',
                'where' => "erp_invoice_id = {$this->erp_invoice_id} AND importe > 0",
            )
        );
        $totalRetenidos = 0;
        $totalTraslados = 0;
        if ($factura_tax) {
            foreach ($factura_tax as $key => $value) {
                if ($factura_tax[$key]->tipo_impuesto == 'Traslados') {
                    $totalTraslados = $totalTraslados + $factura_tax[$key]->importe;
                } else {
                    $totalRetenidos = $totalRetenidos + $factura_tax[$key]->importe;
                }
            }
        }

        if ($invoice_detail) {
            foreach ($invoice_detail as $key => $value) {
                $name_product = '';
                $description_product = '';
                $unidad = "";
                $valorUni = 0;
                $inv_product = Inv_Products_Model::load(array('select' => 'name, inv_product_type_id, description', 'where' => array('id' => $invoice_detail[$key]->inv_products_id)));
                //$unity_type=
                if ($inv_product) {
                    $name_product = $invoice_detail[$key]->product_name;
                    $inv_product_type = Inv_Product_Type_Model::load(array('select' => 'name', 'where' => array('id' => $inv_product[0]->inv_product_type_id)));
                    //$type_product = 'Unidad de servicio';
                    $description_product = $invoice_detail[$key]->product_name;
                } else {
                    $name_product = $invoice_detail[$key]->product_name;
                    //$type_product = "No aplica";
                    $description_product = $invoice_detail[$key]->product_name;
                }
                $name_product = trim(html_entity_decode($name_product), " \t\n\r\0\x0B\xC2\xA0");
                $claveProdServ = (isset($invoice_detail[$key]->erp_clave_producto) && $invoice_detail[$key]->erp_clave_producto != '' || $invoice_detail[$key]->erp_clave_producto != null) ? $invoice_detail[$key]->erp_clave_producto : $claveProdServd;
                $importe_iva = $invoice_detail[$key]->importe * 0.16;
                if ($invoice->complemento == 't') {
                    $invoice_detail[$key]->costo = 0;
                    $invoice_detail[$key]->importe = 0;
                    $importe_iva = 0;
                    $totalTraslados = 0;
                    $claveProdServ = '84111506';
                    $unidad = '';
                    $claveUnidad = 'ACT';
                    $invoice_detail[$key]->costo = 0;
                    $invoice_detail[$key]->importe = 0;
                } else {
                    $unidad = '<unidad>' . $type_product . '</unidad>';
                    $invoice_detail[$key]->costo = number_format($invoice_detail[$key]->costo, 2, '.', '');
                    $invoice_detail[$key]->importe = number_format($invoice_detail[$key]->importe, 2, '.', '');
                }
                $emision = $emision . '
			        <concepto>
						<cantidad>' . $invoice_detail[$key]->cantidad . '</cantidad>';
                $emision = $emision . $unidad;
                $emision = $emision . '<claveUnidad>' . $claveUnidad . '</claveUnidad>
                        <claveProdServ>' . $claveProdServ . '</claveProdServ>
						<descripcion>' . $name_product . '</descripcion>
						<valorUnitario>' . $invoice_detail[$key]->costo . '</valorUnitario>
						<importe>' . $invoice_detail[$key]->importe . '</importe>';
                if ($totalTraslados != 0) {
                    $emision = $emision . '<impuestos>
                                <traslados>
                                    <traslado>
                                        <base>' . number_format($invoice_detail[$key]->importe, 2, '.', '') . '</base>
                                        <impuesto>002</impuesto>
                                        <tipofactor>Tasa</tipofactor>
                                        <tasa>0.160000</tasa>
                                        <importe>' . number_format($importe_iva, 2, '.', '') . '</importe>
                                    </traslado>
                                </traslados>
                            </impuestos>';
                }
                $emision = $emision . '</concepto>';
            }
        }
        $emision = $emision . '
			</conceptos>';
        $emision = $emision . $complemento;
        if ($totalTraslados != 0) {
            $emision = $emision . '<impuestos>';
            $emision = $emision . '<traslados>';

            /* if ($factura_tax) {
              foreach ($factura_tax as $key => $value) {
              if ($factura_tax[$key]->tipo_impuesto == 'Traslados') {
              $emision = $emision .
              '<traslado>
              <tipofactor>Tasa</tipofactor>
              <impuesto>002</impuesto>
              <tasa>' . $factura_tax[$key]->tasa . '</tasa>
              <importe>' . number_format($factura_tax[$key]->importe, 2, '.', '') . '</importe>
              </traslado>';
              } else {
              $emision = $emision .
              '<retenido>
              <impuesto>' . $factura_tax[$key]->impuesto . '</impuesto>
              <tasa>' . $factura_tax[$key]->tasa . '</tasa>
              <importe>' . number_format($factura_tax[$key]->importe, 2, '.', '') . '</importe>
              </retenido>';
              }
              }
              } */


            $emision = $emision . '
                    <traslado>
                        <impuesto>002</impuesto>
                        <tipofactor>Tasa</tipofactor>
                        <tasa>0.160000</tasa>
                        <importe>' . number_format($totalTraslados, 2, '.', '') . '</importe>
                    </traslado>';

            $emision = $emision . '</traslados>';

            $emision = $emision . '<totalImpuestosTrasladados>' . number_format($totalTraslados, 2, '.', '') . '</totalImpuestosTrasladados>';
            if ($totalRetenidos != 0) {
                $emision = $emision . '<totalImpuestosRetenidos>' . number_format($totalRetenidos, 2, '.', '') . '</totalImpuestosRetenidos>';
            }

            $emision = $emision . '</impuestos>';
        }
        $emision = $emision . '</factura>
	</emision>';
        $emision = $this->limpiar_caracteres_especiales($emision);
        //echo var_dump($emision);
        $result=false;
        $result = $this->sendEmision($emision, $this->erp_invoice_id);
        $options['status'] = 4;
        $options['id_request'] = $result->uuid;
        $options['uuid'] = $result->uuid;
        $options['message'] = '';
        $where = 'id = ' . $this->erp_invoice_id;
        $response = Erp_Invoice_Model::Update($options, $where);
        $logdato['createdby'] = null;
        $logdato['type'] = 'info';
        $logdato['logtime'] = date('H:i:s');
        $logdato['logdate'] = date('Y-m-d');
        $logdato['message'] = 'Timbre invoice ' . $this->erp_invoice_id . ' EMISION:  ' . $emision;
        $logdato['level'] = '2';
        $logs = Log_Model::Insert($logdato);
        return $result;
    }

    private function sendEmision($emision, $id) {
        $service_url = $_SERVER['SERVER_NAME'] == 'dexfit.antfarm.mx' ? 'http://batuta.antfarm.mx/api/get_emision' : 'http://batuta.beta.antfarm.mx/api/get_emision';
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'data' => $emision,
            'filename' => 'emisionDexfit_' . $id . '.xml',
            'flavour' => 'Facturacion',
            'scheduled' => '',
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        } else {
            return json_decode($curl_response);
        }
    }

    /*
    uuid D2224658-0F1B-4224-9560-19069A9B5A0F
    id request 85b6f3b8-03df-460d-8c6c-c1ee7cbef88b
    uuid cancel 327d6553-bf88-46d0-b31e-388240e66ac8
    uuid done cancel d2224658-0f1b-4224-9560-19069a9b5a0f
    */

    public function setCancel() {
        $CancelTimbrado = '<cancelarTimbrado>
					       <client>50</client>
					       <uuid>' . $this->uuid . '</uuid>
					       <type>cancelarTimbrado</type>
		                   </cancelarTimbrado>';
        $service_url = $_SERVER['SERVER_NAME'] == 'dexfit.antfarm.mx' ? 'http://batuta.antfarm.mx/api/get_emision' : 'http://batuta.beta.antfarm.mx/api/get_emision';
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'data' => $CancelTimbrado,
            'filename' => 'ctimbrado_' . $this->uuid . '.xml',
            'flavour' => 'CancelarFactura',
            'scheduled' => '',
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        } else {
            return json_decode($curl_response);
        }
    }

    public function limpiar_caracteres_especiales($s) {
        $s = str_replace("[áàâãª]", "a", $s);
        $s = str_replace("[ÁÀÂÃ]", "A", $s);
        $s = str_replace("[éèê]", "e", $s);
        $s = str_replace("[&]", "&amp;", $s);
        $s = str_replace("[ÉÈÊ]", "E", $s);
        $s = str_replace("[íìî]", "i", $s);
        $s = str_replace("[ÍÌÎ]", "I", $s);
        $s = str_replace("[óòôõº]", "o", $s);
        $s = str_replace("[ÓÒÔÕ]", "O", $s);
        $s = str_replace("[úùû]", "u", $s);
        $s = str_replace("[ÚÙÛ]", "U", $s);
        $s = str_replace("ñ", "n", $s);
        $s = str_replace("Ñ", "N", $s);
        //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
        //$s = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$s);
        return $s;
    }

    function clean($str) {
        $str = utf8_decode($str);
        $str = str_replace("&nbsp;", " ", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = trim($str);
        return $str;
    }

}

/* End of file Invoice.php */
/* Location: ./application/libraries/Invoice.php */