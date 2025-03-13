<?php
require_once '../../../wp-blog-header.php';
require_once './payu-latam.php';
get_header('shop');

if(isset($_REQUEST['signature'])){
	$signature = $_REQUEST['signature'];
} else {
	$signature = $_REQUEST['firma'];
}

if(isset($_REQUEST['merchantId'])){
	$merchantId = $_REQUEST['merchantId'];
} else {
	$merchantId = $_REQUEST['usuario_id'];
}
if(isset($_REQUEST['referenceCode'])){
	$referenceCode = $_REQUEST['referenceCode'];
} else {
	$referenceCode = $_REQUEST['ref_venta'];
}
if(isset($_REQUEST['TX_VALUE'])){
	$value = $_REQUEST['TX_VALUE'];
} else {
	$value = $_REQUEST['valor'];
}
if(isset($_REQUEST['currency'])){
	$currency = $_REQUEST['currency'];
} else {
	$currency = $_REQUEST['moneda'];
}
if(isset($_REQUEST['transactionState'])){
	$transactionState = $_REQUEST['transactionState'];
} else {
	$transactionState = $_REQUEST['estado'];
}

$value = number_format($value, 1, '.', '');

$payu = new WC_Payu_Latam;
$api_key = $payu->get_api_key();
$signature_local = $api_key . '~' . $merchantId . '~' . $referenceCode . '~' . $value . '~' . $currency . '~' . $transactionState;
$signature_md5 = md5($signature_local);

if(isset($_REQUEST['polResponseCode'])){
	$polResponseCode = $_REQUEST['polResponseCode'];
} else {
	$polResponseCode = $_REQUEST['codigo_respuesta_pol'];
}

$agradecimiento = '';
$order = new WC_Order($referenceCode);
if($transactionState == 6 && $polResponseCode == 5){
	$estadoTx = "Transacci&oacute;n fallida";
} else if($transactionState == 6 && $polResponseCode == 4){
	$estadoTx = "Transacci&oacute;n rechazada";
} else if($transactionState == 12 && $polResponseCode == 9994){
	$estadoTx = "Pendiente, Por favor revisar si el d&eacute;bito fue realizado en el Banco";
} else if($transactionState == 4 && $polResponseCode == 1){
	$estadoTx = "Transacci&oacute;n aprobada";
	$agradecimiento = '¡Gracias por tu compra!';
} else{
	if(isset($_REQUEST['message'])){
		$estadoTx=$_REQUEST['message'];
	} else {
		$estadoTx=$_REQUEST['mensaje'];
	}
}

if(isset($_REQUEST['transactionId'])){
	$transactionId = $_REQUEST['transactionId'];
} else {
	$transactionId = $_REQUEST['transaccion_id'];
}
if(isset($_REQUEST['reference_pol'])){
	$reference_pol = $_REQUEST['reference_pol'];
} else {
	$reference_pol = $_REQUEST['ref_pol'];
}
if(isset($_REQUEST['pseBank'])){
	$pseBank = $_REQUEST['pseBank'];
} else {
	$pseBank = $_REQUEST['banco_pse'];
}
$cus = $_REQUEST['cus'];
if(isset($_REQUEST['description'])){
	$description = $_REQUEST['description'];
} else {
	$description = $_REQUEST['descripcion'];
}
if(isset($_REQUEST['lapPaymentMethod'])){
	$lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
} else {
	$lapPaymentMethod = $_REQUEST['medio_pago_lap'];
}

if (strtoupper($signature) == strtoupper($signature_md5)) {

    if ($_SERVER['HTTPS'] == "on"){
        $pr = "https://".$_SERVER['SERVER_NAME'];
    }
    else{
        $pr = "http://".$_SERVER['SERVER_NAME'];
    }
?>
	<center>
		<table style="width: 42%; margin-top: 100px;">
			<tr align="center">
				<th colspan="2">DATOS DE LA COMPRA</th>
			</tr>
			<tr align="right">
				<td>Estado de la transacci&oacute;n</td>
				<td><?php echo $estadoTx; ?></td>
			</tr>
			<tr align="right">
				<td>ID de la transacci&oacute;n</td>
				<td><?php echo $transactionId; ?></td>
			</tr>		
			<tr align="right">
				<td>Referencia de la venta</td>
				<td><?php echo $reference_pol; ?></td>
			</tr>		
			<tr align="right">
				<td>Referencia de la transacci&oacute;n</td>
				<td><?php echo $referenceCode; ?></td>
			</tr>	
			<?php
				if($pseBank!=null){
			?>
				<tr align="right">
					<td>CUS</td>
					<td><?php echo $cus; ?> </td>
				</tr>
				<tr align="right">
					<td>Banco</td>
					<td><?php echo $pseBank; ?> </td>
				</tr>
			<?php
				}
			?>
			<tr align="right">
				<td>Valor total</td>
				<td>$<?php echo $value; ?> </td>
			</tr>
			<tr align="right">
				<td>Moneda</td>
				<td><?php echo $currency; ?></td>
			</tr>
			<tr align="right">
				<td>Descripción</td>
				<td><?php echo $description; ?></td>
			</tr>
			<tr align="right">
				<td>Entidad</td>
				<td><?php echo $lapPaymentMethod; ?></td>
			</tr>
		</table>
		<p/>
		<h1><?php echo $agradecimiento ?></h1>
	</center>
<?php
} else {
	echo '<h1><center>La petici&oacute;n es incorrecta! Hay un error en la firma digital.</center></h1>';
}
?>
<script type='text/javascript' src='<?php echo $pr;?>/wp-content/plugins/elementor/assets/js/frontend-modules.js?ver=2.5.9'></script>

<script type='text/javascript'>
    var ElementorProFrontendConfig = {"ajaxurl":"<?php echo $pr;?>\/wp-admin\/admin-ajax.php","nonce":"691f9dbcab","shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"google":{"title":"Google+","has_counter":true},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"delicious":{"title":"Delicious"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"es_ES","app_id":""}};
</script>
<script type='text/javascript' src='<?php echo $pr;?>/wp-content/plugins/elementor-pro/assets/js/frontend.min.js?ver=2.5.1'></script>

<script type='text/javascript'>
    var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"2.5.9","urls":{"assets":"<?php echo $pr;?>\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"general":{"elementor_global_image_lightbox":"yes","elementor_enable_lightbox_in_editor":"yes"}},"post":{"id":159,"title":"Inicio","excerpt":""},"user":{"roles":["administrator"]}};
</script>
<script type='text/javascript' src='<?php echo $pr;?>/wp-content/plugins/elementor/assets/js/frontend.min.js?ver=2.5.9'></script>
<?php
get_footer('shop');
?>