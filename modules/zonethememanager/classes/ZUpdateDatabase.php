<?php
/**
 * 2007-2019 PrestaShop and Contributors.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA and Contributors
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

include_once _PS_MODULE_DIR_.'zoneslideshow/classes/ZSlideshow.php';

class ZUpdateDatabase
{
    private function sqlCheckColumnexists($table_name, $field_name)
    {
        $sql = 'DESCRIBE `'._DB_PREFIX_.$table_name.'`';
        $columns = Db::getInstance()->executeS($sql);
        $found = false;
        foreach ($columns as $col) {
            if ($col['Field'] == $field_name) {
                $found = true;
                break;
            }
        }
        
        return $found;
    }

    private function sqlAlterTableAddColumn($table_name, $field_name, $column_attribute)
    {
        $found = $this->sqlCheckColumnexists($table_name, $field_name);

        if (!$found) {
            $sql = 'ALTER TABLE `'._DB_PREFIX_.$table_name.'` ADD `'.$field_name.'` '.$column_attribute;
            Db::getInstance()->execute($sql);
        }
    }

    public function updateDatabase()
    {
        $this->updateDatabase_zonecolumnblocks();
        $this->updateDatabase_zonehomeblocks();
        $this->updateDatabase_zonemegamenu();
        $this->updateDatabase_zonepopupnewsletter();
        $this->updateDatabase_zoneproductadditional();
        $this->updateDatabase_zoneslideshow();
        $this->updateDatabase_zonethememanager();
        $this->updateHookPositions();
    }

    protected function updateHookPositions()
    {
        $ps_languageselector = ModuleCore::getInstanceByName('ps_languageselector');
        $ps_currencyselector = ModuleCore::getInstanceByName('ps_currencyselector');
        $context = Context::getContext();
        if (!Hook::isModuleRegisteredOnHook($ps_languageselector, 'displayNav2', $context->shop->id)) {
            $ps_languageselector->unregisterHook('displayNav1');
            $ps_languageselector->registerHook('displayNav2');
        }
        if (!Hook::isModuleRegisteredOnHook($ps_currencyselector, 'displayNav2', $context->shop->id)) {
            $ps_currencyselector->unregisterHook('displayNav1');
            $ps_currencyselector->registerHook('displayNav2');
        }
    }

    protected function updateDatabase_zonecolumnblocks()
    {
        $this->sqlAlterTableAddColumn('zcolumnblock', 'active_mobile', 'TINYINT(1) NULL DEFAULT \'1\' AFTER `active`');
    }

    protected function updateDatabase_zonehomeblocks()
    {
        $this->sqlAlterTableAddColumn('zhomeblock', 'active_mobile', 'TINYINT(1) NULL DEFAULT \'1\' AFTER `active`');
        $this->sqlAlterTableAddColumn('zhometab', 'active_mobile', 'TINYINT(1) NULL DEFAULT \'1\' AFTER `active`');
    }

    protected function updateDatabase_zonemegamenu()
    {
        $this->sqlAlterTableAddColumn('zdropdown', 'custom_class', 'VARCHAR(254) NULL AFTER `column`');
        $this->sqlAlterTableAddColumn('zdropdown', 'category_options', 'text NULL DEFAULT NULL AFTER `manufacturers`');
        $this->sqlAlterTableAddColumn('zdropdown', 'manufacturer_options', 'text NULL DEFAULT NULL AFTER `manufacturers`');
        $this->sqlAlterTableAddColumn('zmenu', 'link_newtab', 'TINYINT NULL DEFAULT \'0\' AFTER `position`');
        $this->sqlAlterTableAddColumn('zmenu', 'title_image', 'VARCHAR(128) DEFAULT NULL AFTER `position`');
        $this->sqlAlterTableAddColumn('zmenu', 'custom_class', 'VARCHAR(254) NULL AFTER `drop_column`');

        $title_image_dir = _PS_MODULE_DIR_.'zonemegamenu/views/img/title_images';
        if (!is_dir($title_image_dir)) {
            mkdir($title_image_dir);
        }
    }

    protected function updateDatabase_zonepopupnewsletter()
    {
        $this->sqlAlterTableAddColumn('zpopupnewsletter', 'subscribe_form', 'TINYINT(1) NULL DEFAULT \'1\' AFTER `cookie_time`');
    }

    protected function updateDatabase_zoneproductadditional()
    {
        $this->sqlAlterTableAddColumn('zproduct_extra_field', 'manufacturers', 'text NULL DEFAULT \'\' AFTER `products`');
        $this->sqlAlterTableAddColumn('zproduct_extra_field', 'hook', 'VARCHAR(128) NULL DEFAULT \'ProductExtraContent\' AFTER `id_shop`');
        $this->sqlAlterTableAddColumn('zproduct_extra_field', 'popup', 'TINYINT(1) NULL DEFAULT \'0\' AFTER `id_shop`');
        $this->sqlAlterTableAddColumn('zproduct_extra_field', 'popup_width', 'VARCHAR(50) NULL DEFAULT \'560\' AFTER `id_shop`');
        $this->sqlAlterTableAddColumn('zproduct_extra_field', 'title_image', 'VARCHAR(255) NULL DEFAULT NULL AFTER `id_shop`');

        $zoneproductadditional = ModuleCore::getInstanceByName('zoneproductadditional');
        $context = Context::getContext();
        if (!Hook::isModuleRegisteredOnHook($zoneproductadditional, 'displayProduct3rdColumn', $context->shop->id)) {
            $zoneproductadditional->registerHook('displayProduct3rdColumn');
        }
        if (!Hook::isModuleRegisteredOnHook($zoneproductadditional, 'displayAfterProductThumbs', $context->shop->id)) {
            $zoneproductadditional->registerHook('displayAfterProductThumbs');
        }
    }

    protected function updateDatabase_zoneslideshow()
    {
        $this->sqlAlterTableAddColumn('zslideshow_lang', 'image_name', 'VARCHAR(100) NULL DEFAULT \'\' AFTER `title`');
        $this->sqlAlterTableAddColumn('zslideshow', 'active_mobile', 'TINYINT(1) NULL DEFAULT \'1\' AFTER `active`');

        $found = $this->sqlCheckColumnexists('zslideshow_lang', 'slide_link');
        if (!$found) {
            $sql = 'ALTER TABLE `'._DB_PREFIX_.'zslideshow_lang` CHANGE `link` `slide_link` VARCHAR(254)';
            Db::getInstance()->execute($sql);
        }

        $query = 'SELECT `id_zslideshow` FROM `'._DB_PREFIX_.'zslideshow`';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($result) {
            $languages = Language::getLanguages(false);
            foreach ($result as $row) {
                $slide = new ZSlideshow($row['id_zslideshow']);
                $temp = array();
                foreach ($languages as $lang) {
                    $temp[$lang['id_lang']] = $slide->image;
                }
                $slide->image_name = $temp;
                $slide->save();
            }
        }
    }

    protected function updateDatabase_zonethememanager()
    {
        $this->sqlAlterTableAddColumn('zthememanager_lang', 'cookie_message', 'TEXT NULL AFTER `footer_bottom`');

        $zonethememanager = ModuleCore::getInstanceByName('zonethememanager');
        $context = Context::getContext();
        if (!Hook::isModuleRegisteredOnHook($zonethememanager, 'displayProductCombinationsBlock', $context->shop->id)) {
            $zonethememanager->registerHook('displayProductCombinationsBlock');
        }
    }
}
