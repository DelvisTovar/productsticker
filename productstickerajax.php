<?php 
	global $cookie;
	include(dirname(__FILE__).'/../../config/config.inc.php');
	require_once(dirname(__FILE__).'/../../init.php');
	require_once(dirname(__FILE__).'/../../classes/ObjectModel.php');
	require_once (dirname(__FILE__).'/clases/productstickerObj.php');

	if($_POST['action'] == 'deleteSticker'){
		if(isset($_POST['id'])){
			$stickers = ProductstickerObj::deleteSticker($_POST['id']);
			if ($stickers) {
				@unlink(dirname(__FILE__).'/images/'.$_POST['img']);
			} 
			
			echo $stickers;
		}
		else
			echo 'El id no existe';
	}

?>