<?php
class ProductstickerObj extends ObjectModel{
	
	public $id_sticker;
	public $id_category;
	public $sticker;
	public $sticker_class;
	public $sticker_date;
	public $sticker_status;

	/**
	 *@see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'sticker', 'primary'=>'id_sticker', 'multilang'=> false,
		'fields' => array(
			'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'sticker' => array('type' => self::TYPE_STRING, 'required' => true),
			'sticker_class' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
			'sticker_date' => array('type' => self::TYPE_DATE, 'require' => true),
			'sticker_status' => array('type' => self::TYPE_INT, 'required' => true),
		),
	);

	public static function getProductSticker($id_categories){
		$sticker = Db::getInstance()->executeS('
			SELECT sticker, sticker_class FROM `'._DB_PREFIX_.'sticker`
			WHERE `id_category` IN ('.$id_categories.')
			ORDER BY `id_sticker` DESC
		');

		return $sticker;
	}

	public static function getAll(){
		$sticker = Db::getInstance()->executeS('
			SELECT ST.*, CAL.name
			FROM `'._DB_PREFIX_.'sticker` AS ST
			INNER JOIN `'._DB_PREFIX_.'category` AS CA ON ST.id_category = CA.id_category
			INNER JOIN `'._DB_PREFIX_.'category_lang` AS CAL ON CA.id_category = CAL.id_category AND CAL.id_lang = 1
			ORDER BY `id_sticker` DESC
		');

		return $sticker;
	}

	public static function getAllCategories(){
		$categories = Db::getInstance()->executeS('
			SELECT CAL.name, CA.id_category
			FROM `'._DB_PREFIX_.'category` AS CA 
			INNER JOIN `'._DB_PREFIX_.'category_lang` AS CAL ON CA.id_category = CAL.id_category AND CAL.id_lang = 1
			ORDER BY `name` DESC
		');

		return $categories;
	}

	public static function deleteSticker($id){
		$sticker= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'sticker`
            WHERE id_sticker = '.$id.'
        ');

		return $sticker;
	}
}
?>