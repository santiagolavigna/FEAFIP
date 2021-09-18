<?php

include 'src/Afip.php';

$afip = new Afip(array('CUIT' => 20372284103));

/**
 * Numero del punto de venta
 **/ 
$punto_de_venta = 1;

/**
 * Tipo de factura
 **/
$tipo_de_comprobante = 11; // 11 = Factura C

/**
 * Número de la ultima Factura C
 **/
$last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_comprobante);

/**
 * Concepto de la factura
 *
 * Opciones:
 *
 * 1 = Productos 
 * 2 = Servicios 
 * 3 = Productos y Servicios
 **/
$concepto = 1;

/**
 * Tipo de documento del comprador
 *
 * Opciones:
 *
 * 80 = CUIT 
 * 86 = CUIL 
 * 96 = DNI
 * 99 = Consumidor Final 
 **/
$tipo_de_documento = 80;

/**
 * Numero de documento del comprador (0 para consumidor final)
 **/
$numero_de_documento = 33693450239;

/**
 * Numero de comprobante
 **/
$numero_de_factura = $last_voucher + 1;

/**
 * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
 **/
$fecha = date('Y-m-d');

/**
 * Importe de la Factura
 **/
$importe_total = 100;

/**
 * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
 **/
if ($concepto === 2 || $concepto === 3) {
	/**
	 * Fecha de inicio de servicio en formato aaaammdd
	 **/
	$fecha_servicio_desde = intval(date('Ymd'));

	/**
	 * Fecha de fin de servicio en formato aaaammdd
	 **/
	$fecha_servicio_hasta = intval(date('Ymd'));

	/**
	 * Fecha de vencimiento del pago en formato aaaammdd
	 **/
	$fecha_vencimiento_pago = intval(date('Ymd'));
} else {
	$fecha_servicio_desde = null;
	$fecha_servicio_hasta = null;
	$fecha_vencimiento_pago = null;
}


$data = array(
	'CantReg' 	=> 1, // Cantidad de facturas a registrar
	'PtoVta' 	=> $punto_de_venta,
	'CbteTipo' 	=> $tipo_de_comprobante,
	'Concepto' 	=> $concepto,
	'DocTipo' 	=> $tipo_de_documento,
	'DocNro' 	=> $numero_de_documento,
	'CbteDesde' => $numero_de_factura,
	'CbteHasta' => $numero_de_factura,
	'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
	'FchServDesde'  => $fecha_servicio_desde,
	'FchServHasta'  => $fecha_servicio_hasta,
	'FchVtoPago'    => $fecha_vencimiento_pago,
	'ImpTotal' 	=> $importe_total,
	'ImpTotConc' => 0, // Importe neto no gravado
	'ImpNeto' 	=> $importe_total, // Importe neto
	'ImpOpEx' 	=> 0, // Importe exento al IVA
	'ImpIVA' 	=> 0, // Importe de IVA
	'ImpTrib' 	=> 0, //Importe total de tributos
	'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
	'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
);

/** 
 * Creamos la Factura 
 **/
$res = $afip->ElectronicBilling->CreateVoucher($data);

/**
 * Mostramos por pantalla los datos de la nueva Factura 
 **/
var_dump(array(
	'cae' => $res['CAE'], //CAE asignado a la Factura
	'vencimiento' => $res['CAEFchVto'] //Fecha de vencimiento del CAE
));
