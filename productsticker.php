<?php

if (!defined('_PS_VERSION_'))
    exit();

require_once (dirname(__FILE__).'/clases/productstickerObj.php');

class Productsticker extends Module
{
    public function __construct()
    {
        $this->name = 'productsticker';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Delvis Tovar';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('productsticker', 'productsticker');
        $this->description = $this->l('This module is developed to productsticker.', 'productsticker');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?', 'productsticker');
    }

    public function install()
    {
        if (!parent::install())
            return false;

        $this->registerHook('displayProductPriceBlock');
        $this->registerHook('displayHeader');
        $this->installDB();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall())
            return false;

        $this->uninstallDB();
        return true;
    }

    public function installDB(){
        return Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."sticker` (
            `id_sticker` int(11)NOT NULL AUTO_INCREMENT,
            `id_category` int(11) NOT NULL,
            `sticker` VARCHAR(255) NOT NULL,
            `sticker_class` text NOT NULL,
            `sticker_date` timestamp NOT NULL,
            `sticker_status` int(11) NULL,
            PRIMARY KEY(`id_sticker`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
    }

    public function uninstallDB(){
        return Db::getInstance()->execute(
            "DROP TABLE `"._DB_PREFIX_."sticker`;"
        );
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet('modules-productsticker', 'modules/'.$this->name.'/css/productsticker.css', ['media' => 'all', 'priority' => 150]);
    }

    public function hookDisplayProductPriceBlock(array $params)
    {
        if ($params['type'] == 'weight') {
            $product = $params['product'];
            $categories = Product::getProductCategories($product->id);
            $idCompaniesArray = implode(",",$categories);
            
            $stickers = ProductstickerObj::getProductSticker($idCompaniesArray);
            $this->context->smarty->assign('stickers', $stickers);
            return $this->display(__FILE__,'views/templates/hook/hookDisplayProductPriceBlock.tpl');
        }
        return false;
    }

    public function getContent()
    {
        if(Tools::isSubmit('sticker_env')){
            if (isset($_FILES["sticker_IMG"])) {
                $sticker = $_FILES["sticker_IMG"];
                if ($sticker['name']!="") {
                    $allowed = array('image/gif','image/jpeg','image/jpg','image/png');
                    if (in_array($sticker['type'], $allowed)) {
                        $ext = substr($_FILES['sticker_IMG']['name'], strrpos($_FILES['sticker_IMG']['name'], '.') + 1);
                        $file_name = md5($_FILES['sticker_IMG']['name']).'.'.$ext;

                        $copy = move_uploaded_file($_FILES['sticker_IMG']['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'images/upload/'.DIRECTORY_SEPARATOR.$file_name);
                        if (!$copy) {
                            $this->context->smarty->assign('errorForm', $this->l('Error moviendo la imagen: '));
                        } else {
                            $id_category = Tools::getValue('id_category');
                            $pathsticker = "upload/".$file_name;
                            $sticker_class = Tools::getValue('sticker_class');
                            $sticker_status = Tools::getValue('sticker_status');

                            $stickerObj = new ProductstickerObj();
                            $stickerObj->id_category = (int)$id_category;
                            $stickerObj->sticker = $pathsticker;
                            $stickerObj->sticker_class = pSQL($sticker_class);
                            $stickerObj->sticker_status = (int)$sticker_status;
                            $stickerObj->sticker_date = date('Y-m-d H:i:s');
                            $result = $stickerObj->add();
                            if ($result) {
                                $this->context->smarty->assign('saveForm','1');
                            } else {
                                $this->context->smarty->assign('errorForm',$this->l('No se ha podido grabar la foto en la DDBB'));
                            }
                        }
                        
                    } else{
                        $this->context->smarty->assign('errorForm',$this->l('Formato de imagen no válido'));
                    }
                }
            }
        }

        /* assing data and return view*/
        $stickers = ProductstickerObj::getAll();
        $this->context->smarty->assign('stickers', $stickers);
        $categories = ProductstickerObj::getAllCategories();
        $this->context->smarty->assign('categories', $categories);
        $this->context->smarty->assign("uri", $this->getPathUri());
        return $this->display(__FILE__,"getContent.tpl");
    }
}

?>