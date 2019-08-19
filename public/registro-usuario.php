<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
include_once  plugin_dir_path( dirname( __FILE__ ) ).'includes/settings.php';

if(isset($_POST['regisForm'])):
	$tipdoc = $_POST['tipdoc'];
	$doc = $_POST['doc'];
	$nmbr = $_POST['nmbr'];
	$aplld = $_POST['aplld'];
	$cel = $_POST['cel'];
	$mail = $_POST['mail'];
	$fto = $_FILES['fto'];
	$feed = $_POST['feed'];

	if($_FILES['fto']['tmp_name'] == ''){
		echo 'Error1';
		exit;
	}

	$nombre_imagen = RG_FECHA.$_FILES['fto']['name'];
	$tipo_imagen = $_FILES['fto']['type'];
	$original = $_FILES['fto']['tmp_name'];
	$maxAncho = 800; $maxAlto = 1300;
	if($tipo_imagen == 'image/png'){
		$imgOrig = imagecreatefrompng($original);
	}else if($tipo_imagen == 'image/jpg' || $tipo_imagen == 'image/jpeg'){
		$imgOrig = imagecreatefromjpeg($original);
	}
	list($ancho,$alto)=getimagesize($original);
	$ratioX = $maxAncho / $ancho;
	$ratioY = $maxAlto / $alto;
	if(($ancho <= $maxAncho) && ($alto <= $maxAlto)){
		$finalX = $ancho; $finalY = $alto;
	}else if(($ratioX * $alto) < $maxAlto){
		$finalX = $maxAncho; $finalY = ceil($ratioX * $alto);
	}else{
		$finalX = ceil($ratioY * $ancho); $finalY = $maxAlto;
	}
	$lienzo = imagecreatetruecolor($finalX,$finalY);
	imagecopyresampled($lienzo,$imgOrig,0,0,0,0,$finalX, $finalY,$ancho,$alto);
	imagedestroy($imgOrig);
	$carpeta_destino = RG_EDIT_SERV.'/photos/';
	if($tipo_imagen == 'image/png'){
		imagepng($lienzo,$carpeta_destino.$nombre_imagen);
	}else if($tipo_imagen == 'image/jpg' || $tipo_imagen == 'image/jpeg'){
		imagejpeg($lienzo,$carpeta_destino.$nombre_imagen);
	}


	$regisUsr = new RG_Usuario($tipdoc,$doc,$nmbr,$aplld,$cel,$mail,$fto,$feed);
	if($regisUsr->crear($tipdoc,$doc,$nmbr,$aplld,$cel,$mail,$nombre_imagen,$feed)==true){
		echo 'Exito';
	}else{
		echo 'Error';
	}
	
endif;

class RG_Usuario{

	public function crear($tipdoc,$doc,$nmbr,$aplld,$cel,$mail,$nombre_imagen,$feed){
		global $wpdb;
		$sql = ("INSERT INTO registro_usuarios (
					rg_ip_usr,
					rg_tipdoc_usr,
					rg_doc_usr,
					rg_nmbr_usr,
					rg_aplld_usr,
					rg_cel_usr,
					rg_mail_usr,
					rg_fto_usr,
					rg_feed_usr,
					rg_actv_usr,
					rg_fch
				) values (
					'".RG_USER_IP."',
					'{$tipdoc}',
					'{$doc}',
					'{$nmbr}',
					'{$aplld}',
					'{$cel}',
					'{$mail}',
					'{$nombre_imagen}',
					'{$feed}',
					1,
					'".RG_FECHA."'
				)");
		$wpdb->query($sql);

		return true;

	}
}