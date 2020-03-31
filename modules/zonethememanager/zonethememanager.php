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

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

include_once dirname(__FILE__).'/classes/ZManager.php';
include_once dirname(__FILE__).'/classes/ZUpdateDatabase.php';

class ZOneThemeManager extends Module
{
    protected $html = '';
    protected $action;
    protected $currentIndex;
    protected $image_folder = 'views/img/front/';
    protected $static_pages;
    protected $zonetheme_version = '2.4.8';

    public function __construct()
    {
        $this->name = 'zonethememanager';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Theme Manager',
            array(),
            'Modules.ZoneThememanager.Admin'
        );
        $this->description = $this->trans(
            'Configure the main elements of ZOne theme.',
            array(),
            'Modules.ZoneThememanager.Admin'
        );

        $this->static_pages = array(
            'stores' => $this->trans(
                'Our Stores',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'prices-drop' => $this->trans(
                'Price Drop',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'new-products' => $this->trans(
                'New Products',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'best-sales' => $this->trans(
                'Best Sales',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'contact' => $this->trans(
                'Contact us',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'sitemap' => $this->trans(
                'Sitemap',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
        );

        $this->action = Tools::getValue('action', 'general');
        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&action='.$this->action;
    }

    public function install()
    {
        if (!file_exists(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        }
        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                return false;
            }
        }

        Configuration::updateGlobalValue('ZONETHEME_VERSION', $this->zonetheme_version);

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('displayFooterLeft')
            && $this->registerHook('displayFooterRight')
            && $this->registerHook('displayFooterAfter')
            && $this->registerHook('displayBanner')
            && $this->registerHook('displayNav1')
            && $this->registerHook('displaySidebarNavigation')
            && $this->registerHook('displayOutsideMainPage')
            && $this->registerHook('displayProductCombinationsBlock')
            && $this->registerHook('actionCategoryAdd')
            && $this->registerHook('actionCategoryUpdate')
            && $this->registerHook('actionCategoryDelete')
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && $this->registerHook('actionAttributeDelete')
            && $this->registerHook('actionAttributeSave')
            && $this->registerHook('actionAttributeGroupDelete')
            && $this->registerHook('actionAttributeGroupSave')
            && $this->registerHook('actionAttributeCombinationDelete')
            && $this->registerHook('actionAttributeCombinationSave')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zthememanager`,
            `'._DB_PREFIX_.'zthememanager_lang`';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        Configuration::deleteByName('ZONETHEME_VERSION');

        $this->_clearCache('*');

        return parent::uninstall();
    }

    private function upgradeThemeVersion()
    {
        $theme_version = Configuration::getGlobalValue('ZONETHEME_VERSION');
        if (!$theme_version) {
            $theme_version = '1.0.0';
        }
        if (version_compare($theme_version, $this->zonetheme_version) == -1) {
            $update_db = new ZUpdateDatabase();
            $update_db->updateDatabase();
            Configuration::updateGlobalValue('ZONETHEME_VERSION', $this->zonetheme_version);

            foreach (glob(_PS_CONFIG_DIR_ . 'themes/ZOneTheme/*.json') as $filename) {
                Tools::deleteFile($filename);
            }
        }
    }

    public function getContent()
    {
        $this->upgradeThemeVersion();
        
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addJS($this->_path.'views/js/back.js');

        if (Tools::isSubmit('submitGeneralSettings')) {
            $this->processSaveGeneralSettings();
        } elseif (Tools::isSubmit('deleteBoxedBackgroundImage')) {
            $this->processDeleteBoxedBackgroundImage();
        } elseif (Tools::isSubmit('deleteSVGLogo')) {
            $this->processDeleteSVGLogo();
        } elseif (Tools::isSubmit('submitHeaderSettings')) {
            $this->processSaveHeaderSettings();
        } elseif (Tools::isSubmit('submitFooterSettings')) {
            $this->processSaveFooterSettings();
        } elseif (Tools::isSubmit('submitCategorySettings')) {
            $this->processSaveCategorySettings();
        } elseif (Tools::isSubmit('submitProductSettings')) {
            $this->processSaveProductSettings();
        } elseif (Tools::isSubmit('submitConfigureZOne')) {
            if (Tools::getValue('overwrite_zone_settings', 0)) {
                $this->processImportZOneTables();
            }
        }

        $this->smarty->assign(array(
            'alert' => $this->html,
            'action' => Tools::getValue('action', 'general'),
            'panel_href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'doc_url' => $this->_path.'documentation.pdf',
            'settings_form' => $this->renderSettingsForm(),
            'theme_version' => $this->zonetheme_version,
        ));

        return $this->display(__FILE__, 'views/templates/admin/settings_form.tpl');
    }

    protected function processDeleteBoxedBackgroundImage()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        if ($general_settings['boxed_bg_img']) {
            $image_path = $this->local_path.$this->image_folder.$general_settings['boxed_bg_img'];

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $general_settings['boxed_bg_img'] = false;
            $settings->general_settings = $general_settings;
            $settings->save();
            $this->_clearCache('*');
        }

        Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
    }

    protected function processDeleteSVGLogo()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        if ($general_settings['svg_logo']) {
            $image_path = _PS_IMG_DIR_.$general_settings['svg_logo'];

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $general_settings['svg_logo'] = false;
            $settings->general_settings = $general_settings;
            $settings->save();
            $this->_clearCache('*');
        }

        Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
    }

    protected function processSaveGeneralSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $general_settings = array(
            'layout' => Tools::getValue('layout'),
            'boxed_bg_color' => Tools::getValue('boxed_bg_color', '#bdbdbd'),
            'boxed_bg_img_style' => Tools::getValue('boxed_bg_img_style'),
            'lazy_loading' => (int) Tools::getValue('lazy_loading'),
            'progress_bar' => (int) Tools::getValue('progress_bar'),
        );

        if (isset($_FILES['boxed_bg_img']) && !empty($_FILES['boxed_bg_img']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['boxed_bg_img'], Tools::getMaxUploadSize())) {
                $this->html .= $this->displayError($error);
            } else {
                $file_name = $_FILES['boxed_bg_img']['name'];
                if (move_uploaded_file($_FILES['boxed_bg_img']['tmp_name'], $this->local_path.$this->image_folder.$file_name)) {
                    $general_settings['boxed_bg_img'] = $file_name;
                } else {
                    $this->html .= $this->displayError($this->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                }
            }
        }

        $settings->general_settings = array_merge($settings->general_settings, $general_settings);

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->trans(
                'General Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveHeaderSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $general_settings = array(
            'svg_width' => (int) Tools::getValue('svg_width'),
            'sticky_menu' => (int) Tools::getValue('sticky_menu'),
            'sticky_mobile' => (int) Tools::getValue('sticky_mobile'),
            'sidebar_cart' => (int) Tools::getValue('sidebar_cart'),
            'sidebar_navigation' => (int) Tools::getValue('sidebar_navigation'),
            'sidebar_categories' => Tools::getValue('sidebar_categories', array()),
            'mobile_menu' => Tools::getValue('mobile_menu'),
        );

        $home_categories = Category::getHomeCategories(Configuration::get('PS_LANG_DEFAULT'), true, false);
        if (count($home_categories) == count($general_settings['sidebar_categories'])) {
            $general_settings['sidebar_categories'] = 'ALL';
        }

        if (isset($_FILES['svg_logo']) && !empty($_FILES['svg_logo']['tmp_name'])) {
            if ($_FILES['svg_logo']['size'] > Tools::getMaxUploadSize()) {
                $this->html .= $this->displayError($this->trans('The uploaded file exceeds the post_max_size directive in php.ini', array(), 'Admin.Notifications.Error'));
            } else {
                $file_name = strtotime('now').'.'.pathinfo($_FILES['svg_logo']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['svg_logo']['tmp_name'], _PS_IMG_DIR_.$file_name)) {
                    $general_settings['svg_logo'] = $file_name;
                } else {
                    $this->html .= $this->displayError($this->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                }
            }
        }
        $settings->general_settings = array_merge($settings->general_settings, $general_settings);

        $settings->header_top_bg_color = Tools::getValue('header_top_bg_color');

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $header_top = array();
        $header_phone = array();
        foreach ($languages as $lang) {
            $header_top[$lang['id_lang']] = Tools::getValue('header_top_'.$lang['id_lang']);
            if (!$header_top[$lang['id_lang']]) {
                $header_top[$lang['id_lang']] = Tools::getValue('header_top_'.$id_lang_default);
            }
            $header_phone[$lang['id_lang']] = Tools::getValue('header_phone_'.$lang['id_lang']);
            if (!$header_phone[$lang['id_lang']]) {
                $header_phone[$lang['id_lang']] = Tools::getValue('header_phone_'.$id_lang_default);
            }
        }
        $settings->header_top = $header_top;
        $settings->header_phone = $header_phone;
        $settings->header_save_date = strtotime('now');

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->trans(
                'Header Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveFooterSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $general_settings = array(
            'scroll_top' => (int) Tools::getValue('scroll_top'),
        );
        $settings->general_settings = array_merge($settings->general_settings, $general_settings);

        $settings->footer_cms_links = Tools::getValue('footer_cms_links', array());

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $footer_about_us = array();
        $footer_static_links = array();
        $footer_bottom = array();
        $cookie_message = array();
        foreach ($languages as $lang) {
            $footer_about_us[$lang['id_lang']] = Tools::getValue('footer_about_us_'.$lang['id_lang']);
            if (!$footer_about_us[$lang['id_lang']]) {
                $footer_about_us[$lang['id_lang']] = Tools::getValue('footer_about_us_'.$id_lang_default);
            }
            $footer_static_links[$lang['id_lang']] = Tools::getValue('footer_static_links_'.$lang['id_lang']);
            if (!$footer_static_links[$lang['id_lang']]) {
                $footer_static_links[$lang['id_lang']] = Tools::getValue('footer_static_links_'.$id_lang_default);
            }
            $footer_bottom[$lang['id_lang']] = Tools::getValue('footer_bottom_'.$lang['id_lang']);
            if (!$footer_bottom[$lang['id_lang']]) {
                $footer_bottom[$lang['id_lang']] = Tools::getValue('footer_bottom_'.$id_lang_default);
            }
            $cookie_message[$lang['id_lang']] = Tools::getValue('cookie_message_'.$lang['id_lang']);
            if (!$cookie_message[$lang['id_lang']]) {
                $cookie_message[$lang['id_lang']] = Tools::getValue('cookie_message_'.$id_lang_default);
            }
        }
        $settings->footer_about_us = $footer_about_us;
        $settings->footer_static_links = $footer_static_links;
        $settings->footer_bottom = $footer_bottom;
        $settings->cookie_message = $cookie_message;

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);
        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->trans(
                'Footer Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveCategorySettings()
    {
        $settings = ZManager::getSettingsByShop();

        $category_settings = array(
            'show_image' => (int) Tools::getValue('show_image'),
            'show_description' => (int) Tools::getValue('show_description'),
            'expand_description' => (int) Tools::getValue('expand_description'),
            'show_subcategories' => (int) Tools::getValue('show_subcategories'),
            'product_grid_columns' => (int) Tools::getValue('product_grid_columns'),
            'buy_in_new_line' => (int) Tools::getValue('buy_in_new_line'),
            'default_product_view' => Tools::getValue('default_product_view'),
        );
        $settings->category_settings = array_merge($settings->category_settings, $category_settings);

        $general_settings = array(
            'quickview' => (int) Tools::getValue('quickview'),
        );
        $settings->general_settings = array_merge($settings->general_settings, $general_settings);

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->trans(
                'Category Page Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveProductSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $new_settings = array(
            'product_info_layout' => Tools::getValue('product_info_layout'),
            'product_add_to_cart_layout' => Tools::getValue('product_add_to_cart_layout'),
            'product_actions_position' => Tools::getValue('product_actions_position'),
            'product_image_zoom' => (int) Tools::getValue('product_image_zoom'),
            'product_countdown' => (int) Tools::getValue('product_countdown'),
            'product_attributes_layout' => Tools::getValue('product_attributes_layout'),
            'combination_price' => (int) Tools::getValue('combination_price'),
            'combination_separator' => Tools::getValue('combination_separator'),
        );

        $settings->product_settings = $new_settings;

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->trans(
                'Product Page Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processImportZOneTables()
    {
        $sql_file = $this->local_path.'sql/zone_modules.sql';
        $result = true;
        if (!file_exists($sql_file)) {
            $result = false;
        } elseif (!$sql = Tools::file_get_contents($sql_file)) {
            $result = false;
        }

        $sql = str_replace(array('PREFIX_', 'BASE_URL'), array(_DB_PREFIX_, $this->context->shop->getBaseURL(true)), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
        foreach ($sql as $query) {
            $result &= Db::getInstance()->execute($query);
        }

        $img_folder = $this->local_path.'views/img/cms/';
        $cms_folder = _PS_IMG_DIR_.'cms/';
        $cms_imgs = glob($img_folder.'*.{jpg,png}', GLOB_BRACE);
        foreach ($cms_imgs as $img) {
            $file_to_go = str_replace($img_folder, $cms_folder, $img);
            Tools::copy($img, $file_to_go);
        }
        
        $tables_with_id_shop = array(
            'zcolorsfonts',
            'zcolumnblock',
            'zhomeblock',
            'zthememanager',
            'zmenu',
            'zpopupnewsletter',
            'zslideshow',
            'zproduct_extra_field',
        );
        $sql = 'UPDATE ';
        foreach ($tables_with_id_shop as &$t) {
            $t = _DB_PREFIX_.$t;
        }
        $sql .= implode(', ', $tables_with_id_shop).' SET ';
        foreach ($tables_with_id_shop as &$t) {
            $t = $t.'.`id_shop` = '.(int) $this->context->shop->id;
        }
        $sql .= implode(', ', $tables_with_id_shop);
        $result &= Db::getInstance()->execute($sql);

        $sql = 'UPDATE '._DB_PREFIX_.'zmenu_lang SET link = \'#\' WHERE 1';
        $result &= Db::getInstance()->execute($sql);

        $sql = 'UPDATE '._DB_PREFIX_.'zthememanager SET svg_logo = \'\' WHERE 1';
        $result &= Db::getInstance()->execute($sql);

        $sql_cats = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT *
            FROM `'._DB_PREFIX_.'category` c
            '.Shop::addSqlAssociation('category', 'c').'
            WHERE c.`id_parent` != 0 AND c.`id_parent` != 1
            AND `active` = 1
            ORDER BY c.`id_parent` ASC
            LIMIT 3'
        );
        if ($sql_cats) {
            $array_cats = array();
            foreach ($sql_cats as $row) {
                $array_cats[] = $row['id_category'];
            }
            $afc_settings = Tools::unSerialize(Configuration::get('ZONEFEATUREDCATEGORIES_SETTINGS', null, null, null, 'a:0:{}'));
            $afc_settings['featuredCategories'] = $array_cats;
            Configuration::updateValue('ZONEFEATUREDCATEGORIES_SETTINGS', serialize($afc_settings));
        }

        $tables_with_id_lang = array(
            'zcolumnblock_lang',
            'zdropdown_lang',
            'zhomeblock_lang',
            'zhometab_lang',
            'zmenu_lang',
            'zpopupnewsletter_lang',
            'zproduct_extra_field_lang',
            'zslideshow_lang',
            'zthememanager_lang',
        );
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');

        $langTables = array();
        foreach ($tables_with_id_lang as $t) {
            $langTables[] = _DB_PREFIX_.$t;
        }

        $sql = 'UPDATE ';
        $sql .= implode(', ', $langTables).' SET ';
        $set_sql = array();
        foreach ($langTables as $table) {
            $set_sql[] = '`'.$table.'`.`id_lang` = '.(int) $id_lang_default;
        }
        $sql .= implode(', ', $set_sql);
        $result &= Db::getInstance()->execute($sql);
        
        foreach ($langTables as $name) {
            preg_match('#^'.preg_quote(_DB_PREFIX_).'(.+)_lang$#i', $name, $m);
            $identifier = 'id_'.$m[1];

            $fields = '';
            $primary_key_exists = false;
            $columns = Db::getInstance()->executeS('SHOW COLUMNS FROM `'.pSQL($name).'`');
            foreach ($columns as $column) {
                $fields .= '`'.$column['Field'].'`, ';
                if ($column['Field'] == $identifier) {
                    $primary_key_exists = true;
                }
            }
            $fields = rtrim($fields, ', ');

            if (!$primary_key_exists) {
                continue;
            }

            $sql = 'INSERT IGNORE INTO `'.pSQL($name).'` ('.pSQL($fields).') (SELECT ';

            reset($columns);
            foreach ($columns as $column) {
                if ($identifier != $column['Field'] && $column['Field'] != 'id_lang') {
                    $sql .= '(
                        SELECT `'.bqSQL($column['Field']).'`
                        FROM `'.bqSQL($name).'` tl
                        WHERE tl.`id_lang` = '.(int) $id_lang_default.'
                        AND tl.`'.bqSQL($identifier).'` = `'.bqSQL(str_replace('_lang', '', $name)).'`.`'.bqSQL($identifier).'`
                    ),';
                } else {
                    $sql .= '`'.bqSQL($column['Field']).'`,';
                }
            }

            $sql = rtrim($sql, ', ');
            $sql .= ' FROM `'._DB_PREFIX_.'lang` CROSS JOIN `'.bqSQL(str_replace('_lang', '', $name)).'` ';
            $sql .= ' WHERE `'.bqSQL($identifier).'` IN (SELECT `'.bqSQL($identifier).'` FROM `'.bqSQL($name).'`) )';

            $result &= Db::getInstance()->execute($sql);
        }

        if ($result) {
            $this->html .= $this->displayConfirmation($this->trans(
                'Import sample data successfully. Please go to "Advanced Parameters > Performance" menu and click on "Clear cache" button.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        } else {
            $this->html .= $this->displayError($this->trans(
                'A error occurred during import data.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }
    }

    protected function renderSettingsForm()
    {
        $action = Tools::getValue('action');
        if ($action == 'header') {
            $result = $this->renderHeaderForm();
        } elseif ($action == 'footer') {
            $result = $this->renderFooterForm();
        } elseif ($action == 'category') {
            $result = $this->renderCategoryForm();
        } elseif ($action == 'product') {
            $result = $this->renderProductForm();
        } elseif ($action == 'configure_zone') {
            $result = $this->renderConfigureZOneForm();
        } else {
            $result = $this->renderGeneralForm();
        }

        return $result;
    }

    // General
    protected function renderGeneralForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGeneralSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getGeneralFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getGeneralForm()));
    }

    protected function getGeneralForm()
    {
        $layout_options = array(
            'query' => array(
                array('id' => 'wide', 'name' => 'Wide'),
                array('id' => 'boxed', 'name' => 'Boxed'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $boxed_bg_img_style_options = array(
            'query' => array(
                array('id' => 'repeat', 'name' => 'Repeat'),
                array('id' => 'stretch', 'name' => 'Stretch'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        $bg_image_url = false;
        $bg_image_size = false;
        if ($general_settings['boxed_bg_img']) {
            $bg_image_url = $this->_path.$this->image_folder.$general_settings['boxed_bg_img'];
            $bg_image_size = filesize($this->local_path.$this->image_folder.$general_settings['boxed_bg_img']) / 1000;
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'General',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Page Layout',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'layout',
                        'options' => $layout_options,
                        'desc' => 'Set wide or boxed layout to your site.',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->trans(
                            'Boxed Background Color',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_color',
                        'form_group_class' => 'odd',
                        'desc' => $this->trans(
                            'Set the background color to boxed layout.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->trans(
                            'Boxed Background Image',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_img',
                        'display_image' => true,
                        'image' => $bg_image_url ? '<img src="'.$bg_image_url.'" alt="" class="img-thumbnail" style="max-width: 100px;" />' : false,
                        'size' => $bg_image_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteBoxedBackgroundImage',
                        'form_group_class' => 'odd',
                        'desc' => $this->trans(
                            'Set the background image to boxed layout.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Background Image Style',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_img_style',
                        'options' => $boxed_bg_img_style_options,
                        'form_group_class' => 'odd',
                        'desc' => $this->trans(
                            'How a background image will be displayed.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Lazy Loading Images',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'lazy_loading',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'lazy_loading_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'lazy_loading_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Enable the Lazy Load Effect for Product Images',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Progress Bar',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'progress_bar',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'progress_bar_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'progress_bar_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Page load progress bar.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save General Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getGeneralFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;

        $fields_value = array(
            'layout' => Tools::getValue('layout', $general_settings['layout']),
            'boxed_bg_color' => Tools::getValue('boxed_bg_color', $general_settings['boxed_bg_color']),
            'boxed_bg_img_style' => Tools::getValue('boxed_bg_img_style', $general_settings['boxed_bg_img_style']),
            'lazy_loading' => Tools::getValue('lazy_loading', $general_settings['lazy_loading']),
            'progress_bar' => Tools::getValue('progress_bar', $general_settings['progress_bar']),
        );

        return $fields_value;
    }

    // Header
    protected function renderHeaderForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitHeaderSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getHeaderFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getHeaderForm()));
    }

    protected function getHeaderForm()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        $logo_url = false;
        $logo_size = false;
        if ($general_settings['svg_logo']) {
            $logo_url = _PS_IMG_.$general_settings['svg_logo'];
            $logo_size = filesize(_PS_IMG_DIR_.$general_settings['svg_logo']) / 1000;
        }

        $mobile_menu_values = array(
            array('id' => 'categorytree', 'value' => 'categorytree', 'label' => $this->trans(
                'Category Tree',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'megamenu', 'value' => 'megamenu', 'label' => $this->trans(
                'Mega Menu',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $home_categories = Category::getHomeCategories(Configuration::get('PS_LANG_DEFAULT'), true, false);
        if ($general_settings['sidebar_categories'] == 'ALL') {
            $selected_categories = array();
            foreach ($home_categories as $cat) {
                $selected_categories[] = $cat['id_category'];
            }
        } else {
            $selected_categories = $general_settings['sidebar_categories'];
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Header',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->trans(
                            'Header Top Background Color',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_top_bg_color',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Header Top',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_top',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'Displays a event at the top of page',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Header Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_phone',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'Displays some custom links on Header.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->trans(
                            'SVG Logo',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'svg_logo',
                        'display_image' => true,
                        'image' => $logo_url ? '<img src="'.$logo_url.'" alt="" class="img-thumbnail" style="max-width: 100px;" />' : false,
                        'size' => $logo_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteSVGLogo',
                        'desc' => $this->trans(
                            'Using SVG logo instead of image logo for your site',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'SVG Width',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'svg_width',
                        'col' => 2,
                        'suffix' => 'px',
                        'desc' => $this->trans(
                            'Set width of SVG Logo',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Sticky Menu',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sticky_menu',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'sticky_menu_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'sticky_menu_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Make the menu "sticky" as soon as it hits the top of the page when you scroll down.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Sticky Menu on Mobile',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sticky_mobile',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'sticky_mobile_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'sticky_mobile_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Enable the sticky menu on mobile device.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Sidebar Mini Cart',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sidebar_cart',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'sidebar_cart_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'sidebar_cart_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Enable the Sidebar Mini Cart instead of the Dropdown Cart on the header.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Sidebar Navigation',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sidebar_navigation',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'sidebar_navigation_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'sidebar_navigation_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Enable the Sidebar Navigation on desktop version.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->trans(
                            'Sidebar Category Tree',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sidebar_categories',
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'sidebar_category_tree',
                            'use_checkbox' => true,
                            'selected_categories' => $selected_categories,
                            'set_data' => $home_categories
                        ),
                        'desc' => $this->trans(
                            'Choose categories you want to display in the Sidebar Navigation.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'sidebar-category-tree',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Mobile Menu',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'mobile_menu',
                        'is_bool' => true,
                        'values' => $mobile_menu_values,
                        'desc' => 'Choose a menu type on mobile version.',
                        'form_group_class' => 'odd',
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save Header Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getHeaderFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;

        $fields_value = array(
            'header_top_bg_color' => Tools::getValue('header_top_bg_color', $settings->header_top_bg_color),
            'svg_width' => (int) Tools::getValue('svg_width', $general_settings['svg_width']),
            'sticky_menu' => Tools::getValue('sticky_menu', $general_settings['sticky_menu']),
            'sticky_mobile' => Tools::getValue('sticky_mobile', $general_settings['sticky_mobile']),
            'sidebar_cart' => Tools::getValue('sidebar_cart', $general_settings['sidebar_cart']),
            'sidebar_navigation' => Tools::getValue('sidebar_navigation', $general_settings['sidebar_navigation']),
            'mobile_menu' => Tools::getValue('mobile_menu', $general_settings['mobile_menu']),
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_header_top = isset($settings->header_top[$lang['id_lang']]) ?
            $settings->header_top[$lang['id_lang']] : '';
            $fields_value['header_top'][$lang['id_lang']] = Tools::getValue('header_top_'.(int) $lang['id_lang'], $default_header_top);

            $default_header_phone = isset($settings->header_phone[$lang['id_lang']]) ?
            $settings->header_phone[$lang['id_lang']] : '';
            $fields_value['header_phone'][$lang['id_lang']] = Tools::getValue('header_phone_'.(int) $lang['id_lang'], $default_header_phone);
        }

        return $fields_value;
    }

    // Footer
    protected function renderFooterForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFooterSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getFooterFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getFooterForm()));
    }

    protected function getFooterForm()
    {
        $footer_cms_links_values = array();
        $cms_pages = CMS::listCms(null, false, true);
        if ($cms_pages) {
            foreach ($cms_pages as $cms) {
                $footer_cms_links_values[] = array(
                    'id' => $cms['id_cms'],
                    'name' => $this->trans(
                        'CMS Page: ',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ).$cms['meta_title'],
                    'val' => $cms['id_cms'],
                );
            }
        }
        foreach ($this->static_pages as $controller => $title) {
            $footer_cms_links_values[] = array(
                'id' => $controller,
                'name' => $title,
                'val' => $controller,
            );
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Footer',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Footer About Us',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_about_us',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'About your store and contact information',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'checkbox_array',
                        'label' => $this->trans(
                            'Footer CMS Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_cms_links',
                        'values' => $footer_cms_links_values,
                        'desc' => $this->trans(
                            'CMS Pages and some useful for your Store',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Footer Static Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_static_links',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'Use the List format (ul & li HTML tag) for this field',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Footer Bottom',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_bottom',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'CopyRight, Payment,...',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Scroll to Top Button',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'scroll_top',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'scroll_top_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'scroll_top_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Allow your visitors to easily scroll back to the top of your page.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Cookie Message',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'cookie_message',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->trans(
                            'Cookie Compliance Acceptance Messages',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save Footer Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getFooterFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;

        $fields_value = array(
            'footer_cms_links' => Tools::getValue('footer_cms_links', $settings->footer_cms_links),
            'scroll_top' => Tools::getValue('scroll_top', $general_settings['scroll_top']),
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_footer_about_us = '';
            if (isset($settings->footer_about_us[$lang['id_lang']])) {
                $default_footer_about_us = $settings->footer_about_us[$lang['id_lang']];
            }
            $fields_value['footer_about_us'][$lang['id_lang']] = Tools::getValue('footer_about_us_'.(int) $lang['id_lang'], $default_footer_about_us);

            $default_footer_static_links = '';
            if (isset($settings->footer_static_links[$lang['id_lang']])) {
                $default_footer_static_links = $settings->footer_static_links[$lang['id_lang']];
            }
            $fields_value['footer_static_links'][$lang['id_lang']] = Tools::getValue('footer_static_links_'.(int) $lang['id_lang'], $default_footer_static_links);

            $default_footer_bottom = '';
            if (isset($settings->footer_bottom[$lang['id_lang']])) {
                $default_footer_bottom = $settings->footer_bottom[$lang['id_lang']];
            }
            $fields_value['footer_bottom'][$lang['id_lang']] = Tools::getValue('footer_bottom_'.(int) $lang['id_lang'], $default_footer_bottom);

            $default_cookie_message = '';
            if (isset($settings->cookie_message[$lang['id_lang']])) {
                $default_cookie_message = $settings->cookie_message[$lang['id_lang']];
            }
            $fields_value['cookie_message'][$lang['id_lang']] = Tools::getValue('cookie_message_'.(int) $lang['id_lang'], $default_cookie_message);
        }

        return $fields_value;
    }

    // Category
    protected function renderCategoryForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCategorySettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getCategoryFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getCategoryForm()));
    }

    protected function getCategoryForm()
    {
        $product_grid_columns_values = array(
            array('id' => '2', 'value' => '2', 'label' => $this->trans(
                '2 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '3', 'value' => '3', 'label' => $this->trans(
                '3 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '4', 'value' => '4', 'label' => $this->trans(
                '4 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '5', 'value' => '5', 'label' => $this->trans(
                '5 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $default_product_view_values = array(
            array('id' => 'grid', 'value' => 'grid', 'label' => $this->trans(
                'Grid View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'list', 'value' => 'list', 'label' => $this->trans(
                'List View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'table', 'value' => 'table', 'label' => $this->trans(
                'Table View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Category Page',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Category Image',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_image',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'show_image_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'show_image_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => ' ',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Category Description',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_description',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'show_description_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'show_description_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => ' ',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Expand Description',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'expand_description',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'expand_description_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'expand_description_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => 'Display Category Description with Expand Effect',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Subcategories',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_subcategories',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'show_subcategories_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'show_subcategories_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => ' ',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Product Grid Columns',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_grid_columns',
                        'is_bool' => true,
                        'values' => $product_grid_columns_values,
                        'desc' => 'Number of columns in Product Grid view.',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'BUY button in new line',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'buy_in_new_line',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'buy_in_new_line_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'buy_in_new_line_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => 'In Product Grid view, Show the "BUY" button in a new line.',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Default Product View',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'default_product_view',
                        'is_bool' => true,
                        'values' => $default_product_view_values,
                        'desc' => 'Default product list view in the category page.',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Quick View Window',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'quickview',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'quickview_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'quickview_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => $this->trans(
                            'Display quick view window on homepage and category pages',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save Category Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getCategoryFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $category_settings = $settings->category_settings;
        $general_settings = $settings->general_settings;

        $fields_value = array(
            'show_image' => Tools::getValue('show_image', $category_settings['show_image']),
            'show_description' => Tools::getValue('show_description', $category_settings['show_description']),
            'expand_description' => Tools::getValue('expand_description', $category_settings['expand_description']),
            'show_subcategories' => Tools::getValue('show_subcategories', $category_settings['show_subcategories']),
            'product_grid_columns' => Tools::getValue('product_grid_columns', $category_settings['product_grid_columns']),
            'buy_in_new_line' => Tools::getValue('buy_in_new_line', $category_settings['buy_in_new_line']),
            'default_product_view' => Tools::getValue('default_product_view', $category_settings['default_product_view']),
            'quickview' => Tools::getValue('quickview', $general_settings['quickview']),
        );

        return $fields_value;
    }

    // Product
    protected function renderProductForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProductSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getProductFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getProductForm()));
    }

    protected function getProductForm()
    {
        $product_info_layout_values = array(
            array('id' => 'product_info_normal', 'value' => 'normal', 'label' => $this->trans(
                'Normal',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'product_info_tabs', 'value' => 'tabs', 'label' => $this->trans(
                'Tabs',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'product_info_accordions', 'value' => 'accordions', 'label' => $this->trans(
                'Accordion',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );
        $product_add_to_cart_layout_values = array(
            array('id' => 'addtocart_normal', 'value' => 'normal', 'label' => $this->trans(
                'Normal',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'addtocart_inline', 'value' => 'inline', 'label' => $this->trans(
                'Inline',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );
        $product_actions_position_values = array(
            array('id' => 'left', 'value' => 'left', 'label' => $this->trans(
                'Left',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'right', 'value' => 'right', 'label' => $this->trans(
                'Right',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );
        $product_attributes_layout_values = array(
            array(
                'id' => 'attributes_layout_default',
                'value' => 'default',
                'label' => $this->trans(
                    'Default',
                    array(),
                    'Modules.ZoneThememanager.Admin'
                )
            ),
            array(
                'id' => 'attributes_layout_swatches',
                'value' => 'swatches',
                'label' => $this->trans(
                    'Swatches',
                    array(),
                    'Modules.ZoneThememanager.Admin'
                )
            ),
            array(
                'id' => 'attributes_layout_combinations',
                'value' => 'combinations',
                'label' => $this->trans(
                    'Combinations',
                    array(),
                    'Modules.ZoneThememanager.Admin'
                )
            ),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Product Page',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Product Image Zoom',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_image_zoom',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'product_image_zoom_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'product_image_zoom_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => 'Show a bigger size product image on mouseover',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Product Countdown',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_countdown',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'product_countdown_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'product_countdown_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'desc' => 'Enable a countdown box for Product Special Price.',
                        'form_group_class' => 'odd',
                    ),
                    /*array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Product Actions Position',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_actions_position',
                        'is_bool' => true,
                        'values' => $product_actions_position_values,
                        'desc' => 'Position of the "Product actions" box',
                    ),*/
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            '"Add to cart" button',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_add_to_cart_layout',
                        'is_bool' => true,
                        'values' => $product_add_to_cart_layout_values,
                        'desc' => 'Quantity box and "add to cart" button layout',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Product Details Layout',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_info_layout',
                        'is_bool' => true,
                        'values' => $product_info_layout_values,
                        'desc' => 'Select a product informations layout',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Product Attributes Layout',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_attributes_layout',
                        'values' => $product_attributes_layout_values,
                        'desc' => ' ',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Combinations Price',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'combination_price',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'combination_price_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'combination_price_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                        'desc' => $this->trans(
                            'Display Product Price in Combinations.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'product_attributes_combinations',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Combinations Separator',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'combination_separator',
                        'col' => 3,
                        'desc' => $this->trans(
                            'Attributes Separator in Combinations.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'product_attributes_combinations',
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save Product Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getProductFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $product_settings = $settings->product_settings;

        $fields_value = array(
            'product_info_layout' => Tools::getValue('product_info_layout', $product_settings['product_info_layout']),
            'product_add_to_cart_layout' => Tools::getValue('product_add_to_cart_layout', $product_settings['product_add_to_cart_layout']),
            'product_actions_position' => Tools::getValue('product_actions_position', $product_settings['product_actions_position']),
            'product_image_zoom' => Tools::getValue('product_image_zoom', $product_settings['product_image_zoom']),
            'product_countdown' => Tools::getValue('product_countdown', $product_settings['product_countdown']),
            'product_attributes_layout' => Tools::getValue('product_attributes_layout', $product_settings['product_attributes_layout']),
            'combination_price' => Tools::getValue('combination_price', $product_settings['combination_price']),
            'combination_separator' => Tools::getValue('combination_separator', $product_settings['combination_separator']),
        );

        return $fields_value;
    }
    
    // Configure ZOne Module
    protected function renderConfigureZOneForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfigureZOne';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigureZOneValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigureZOneForm()));
    }

    protected function getConfigureZOneForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Import Module Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'html',
                        'label' => $this->trans(
                            'Please note:',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => '<p style="padding-top: 8px;">This action will remove all the settings of the Z.One modules and add the new settings like the demo page.
                            <br/>Please make sure you have a database backup.</p>',
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'overwrite',
                        'values' => array(
                            'query' => array(
                                array(
                                    'id' => 'zone_settings',
                                    'name' => $this->trans(
                                        'Overwrite the Z.One module settings',
                                        array(),
                                        'Modules.ZoneThememanager.Admin'
                                    ),
                                    'val' => 1
                                ),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Overwrite',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getConfigureZOneValues()
    {
        $fields_value = array();

        return $fields_value;
    }

    // Hook process

    public function hookActionCategoryAdd($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionCategoryDelete($params)
    {
        $this->_clearCache('*');
    }
    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }
    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }
    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeDelete($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeSave($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeGroupDelete($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeGroupSave($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeCombinationDelete($params)
    {
        $this->_clearCache('*');
    }
    public function hookActionAttributeCombinationSave($params)
    {
        $this->_clearCache('*');
    }

    public function getBoxedBackgroundCSS($settings = null, $force = false)
    {
        if (!$settings) {
            $settings = ZManager::getSettingsByShop();
        }
        
        $general_settings = $settings->general_settings;
        $boxed_bg_css = false;
        if ($general_settings['layout'] == 'boxed' || $force) {
            $boxed_bg_css = 'body { background-color: '.$general_settings['boxed_bg_color'].';';
            if ($general_settings['boxed_bg_img']) {
                $bg_img_url = $this->_path.$this->image_folder.$general_settings['boxed_bg_img'];
                $bg_img_url = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($bg_img_url).$bg_img_url;
                $boxed_bg_css .= 'background-image: url('.$bg_img_url.');';
                if ($general_settings['boxed_bg_img_style'] == 'repeat') {
                    $boxed_bg_css .= 'background-repeat: repeat;';
                } elseif ($general_settings['boxed_bg_img_style'] == 'stretch') {
                    $boxed_bg_css .= 'background-repeat: no-repeat;background-attachment: fixed;background-position: center;background-size: cover;';
                }
            }
            $boxed_bg_css .= '}';
        }

        return $boxed_bg_css;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerJavascript(
            'modules-shoppingcart',
            'modules/ps_shoppingcart/ps_shoppingcart.js',
            array('position' => 'bottom', 'priority' => 150)
        );

        $is_mobile = ($this->context->isMobile() && !$this->context->isTablet());
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        $category_settings = $settings->category_settings;
        $product_settings = $settings->product_settings;
        $body_classes = ZManager::getStyleClassesByShop();

        $zoneLogo = array();
        $logo = (Configuration::get('PS_LOGO')) ? Configuration::get('PS_LOGO') : false;
        if ($logo) {
            $logo_name = _PS_IMG_.$logo;
            $zoneLogo['url'] = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($logo_name).$logo_name;
            list($tmpWidth, $tmpHeight, $type) = getimagesize(_PS_IMG_DIR_.$logo);
            $zoneLogo['width'] = $tmpWidth;
            $zoneLogo['height'] = $tmpHeight;
        }

        if ($is_mobile) {
            $general_settings['sidebar_navigation'] = 1;
            $general_settings['progress_bar'] = 0;
            $general_settings['sticky_menu'] = 0;
            $product_settings['product_image_zoom'] = 0;
            $body_classes .= ' a-mobile-device';
            $category_settings['default_product_view'] = 'grid';
        } else {
            $general_settings['sticky_mobile'] = 0;
        }

        if ($category_settings['buy_in_new_line']) {
            $body_classes .= ' product-small-style';
        }
        if ($general_settings['layout'] == 'boxed') {
            $body_classes .= ' boxed-layout';
        }
        if ($this->context->customer->isLogged(true)) {
            $body_classes .= ' customer-has-logged';
        }
        if (Configuration::isCatalogMode()) {
            $body_classes .= ' catalog-mode';
        }
        if ($this->context->isMobile()) {
            $body_classes .= ' touch-screen';
        }
        $svg_logo = false;
        if ($general_settings['svg_logo'] && file_exists(_PS_IMG_DIR_.$general_settings['svg_logo'])) {
            $path = _PS_IMG_.$general_settings['svg_logo'];
            $svg_logo = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($path).$path;
        }
        
        $this->context->smarty->assign(array(
            'zoneLayout' => $general_settings['layout'],
            'zoneLazyLoading' => ($general_settings['lazy_loading'] && !$this->context->controller->ajax),
            'zoneSidebarCart' => $general_settings['sidebar_cart'],
            'zoneSidebarNavigation' => $general_settings['sidebar_navigation'],
            'zoneQuickView' => $general_settings['quickview'],
            'svgLogo' => $svg_logo,
            'svgWidth' => $general_settings['svg_width'].'px',
            'mobileMenuType' => $general_settings['mobile_menu'],
            'productGridColumns' => $category_settings['product_grid_columns'],
            'cat_showImage' => $category_settings['show_image'],
            'cat_showDescription' => $category_settings['show_description'],
            'cat_expandDescription' => $category_settings['expand_description'],
            'cat_showSubcategories' => $category_settings['show_subcategories'],
            'cat_productView' => $category_settings['default_product_view'],
            'product_infoLayout' => $product_settings['product_info_layout'],
            'product_AddToCartLayout' => $product_settings['product_add_to_cart_layout'],
            'product_ActionPosition' => $product_settings['product_actions_position'],
            'product_imageZoom' => $product_settings['product_image_zoom'],
            'product_enableCountdown' => $product_settings['product_countdown'],
            'product_attributesLayout' => $product_settings['product_attributes_layout'],
            'psDimensionUnit' => Configuration::get('PS_DIMENSION_UNIT'),
            //'current_customer_group' => (int)Group::getCurrent()->id,
            'zoneLogo' => $zoneLogo,
            'zoneBodyClasses' => $body_classes,
            'zoneIsMobile' => $is_mobile,
            'zoneNotCatalogMode' => !Configuration::isCatalogMode(),
            'ps_legalcompliance_spl' => (Module::isEnabled('ps_legalcompliance') && Configuration::get('AEUC_LABEL_SPECIFIC_PRICE')),
            'enabled_pm_advancedsearch4' => (Module::isInstalled('pm_advancedsearch4') && Module::isEnabled('pm_advancedsearch4')),
        ));

        Media::addJsDef(array(
            'varPageProgressBar' => $general_settings['progress_bar'],
            'varSidebarCart' => $general_settings['sidebar_cart'],
            'varStickyMenu' => $general_settings['sticky_menu'],
            'varMobileStickyMenu' => $general_settings['sticky_mobile'],
            'varPSAjaxCart' => (int) Configuration::get('PS_BLOCK_CART_AJAX', null, null, null, 1),
            'varCustomActionAddVoucher' => 1,
            'varCustomActionAddToCart' => 1,
            'varProductPendingRefreshIcon' => 1,
            'varGetFinalDateMiniatureController' => $this->context->link->getModuleLink($this->name, 'getFinalDateMiniature', array(), true),
            'varGetFinalDateController' => $this->context->link->getModuleLink($this->name, 'getFinalDate', array(), true),
            'varProductCommentGradeController' => $this->context->link->getModuleLink($this->name, 'CommentGrade', array(), true),
        ));

        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_header.tpl';
        $cacheId = $this->name.'|header';

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $boxed_bg_css = $this->getBoxedBackgroundCSS($settings, false);
            $this->smarty->assign(array(
                'boxed_bg_css' => $boxed_bg_css,
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayNav1()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_nav1.tpl';
        $cacheId = $this->name.'|nav1';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'header_phone' => $settings->header_phone,
            ));
        }
        
        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayBanner()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_banner.tpl';
        $cacheId = $this->name.'|banner';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'header_top' => $settings->header_top,
                'header_top_bg_color' => (Tools::strtolower($settings->header_top_bg_color) != "#f9f2e8") ? $settings->header_top_bg_color : false,
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayFooterLeft()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_footerleft.tpl';
        $cacheId = $this->name.'|footerleft';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'aboutUs' => $settings->footer_about_us,
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayFooterRight()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_footerright.tpl';
        $cacheId = $this->name.'|footerright';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);
            $cms_links = array();
            $page_links = array();

            $cms_pages = CMS::listCms($id_lang, false, true);
            if ($cms_pages) {
                foreach ($cms_pages as $cms) {
                    if (in_array($cms['id_cms'], $settings->footer_cms_links)) {
                        $cms_links[] = array(
                            'link' => $this->context->link->getCMSLink($cms['id_cms']),
                            'title' => $cms['meta_title'],
                        );
                    }
                }
            }

            foreach ($this->static_pages as $controller => $title) {
                if (in_array($controller, $settings->footer_cms_links)) {
                    $page_links[] = array(
                        'link' => $this->context->link->getPageLink($controller),
                        'id' => $controller,
                    );
                }
            }

            $this->smarty->assign(array(
                'cmsLinks' => $cms_links,
                'pageLinks' => $page_links,
                'staticLinks' => $settings->footer_static_links,
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayFooterAfter()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_footerafter.tpl';
        $cacheId = $this->name.'|footerafter';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'footerBottom' => $settings->footer_bottom,
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplaySidebarNavigation()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_sidebar_navigation.tpl';
        $cacheId = $this->name.'|navigation';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->smarty->assign(array(
                'category_tree_controller' => $this->context->link->getModuleLink($this->name, 'categoryTree', array(), true),
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayOutsideMainPage()
    {
        $templateFile = 'module:zonethememanager/views/templates/hook/zonethememanager_outsidemainpage.tpl';
        $cacheId = $this->name.'|outsidemainpage';
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'cookieMessage' => $settings->cookie_message,
                'enableScrollTop' => $settings->general_settings['scroll_top'],
            ));
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    protected function preProcessProductCombinations($combinations, $groups, $id_product, $id_product_attribute, $minimal_quantity)
    {
        $result_combinations = array();
        $settings = ZManager::getSettingsByShop();
        $product_settings = $settings->product_settings;
        $disp_unvailable_attr = Configuration::get('PS_DISP_UNAVAILABLE_ATTR');
        $out_of_stock = StockAvailable::outOfStock($id_product);
        $allow_oosp = Product::isAvailableWhenOutOfStock($out_of_stock);

        if ($product_settings['product_attributes_layout'] == 'combinations') {
            $priceFormatter = new PriceFormatter();
            $usetax = false;
            $tax_calc = Product::getTaxCalculationMethod();
            if ($tax_calc == 0 || $tax_calc == 2) {
                $usetax = true;
            }

            foreach ($combinations as $id_pro_attr => $combination) {
                if (!$disp_unvailable_attr && $combination['quantity'] < 1) {
                    continue;
                }
                $exist = true;
                foreach ($combination['attributes_values'] as $k => $value) {
                    if (empty($groups[$k]) || empty($groups[$k]['attributes'])) {
                        $exist = false;
                    }
                }

                if ($exist) {
                    $combination['title'] = implode("<span>".$product_settings['combination_separator']."</span>", $combination['attributes_values']);

                    if ($product_settings['combination_price']) {
                        $price = Product::getPriceStatic(
                            (int) $id_product,
                            $usetax,
                            $id_pro_attr,
                            6,
                            null,
                            false,
                            true,
                            $minimal_quantity
                        );
                        $combination['price'] = $priceFormatter->format($price);
                    } else {
                        $combination['price'] = false;
                    }

                    $combination['disable'] = false;
                    if (!$combination['quantity'] && !$allow_oosp) {
                        $combination['disable'] = true;
                    }

                    if ($product_settings['combination_quantity']) {
                        $combination['quantity_label'] = ($combination['quantity'] > 1) ? $this->trans('Items', array(), 'Shop.Theme.Catalog') : $this->trans('Item', array(), 'Shop.Theme.Catalog');
                    } else {
                        $combination['quantity'] = false;
                    }
                    
                    $combination['groups'] = array();
                    $i = 0;
                    foreach ($combination['attributes_values'] as $k => $value) {
                        $combination['groups'][$k] = $combination['attributes'][$i++];
                    }

                    $result_combinations[$id_pro_attr] = $combination;
                }
            }
        }

        $this->smarty->assign(array(
            'combinations' => $result_combinations,
            'id_product_attribute' => $id_product_attribute,
        ));
    }

    public function hookDisplayProductCombinationsBlock($param)
    {
        $combinations = $param['combinations'];
        $groups = $param['groups'];
        $id_product = (int) $param['id_product'];
        $id_product_attribute = (int) $param['id_product_attribute'];
        $minimal_quantity = $param['minimal_quantity'];

        if (empty($combinations) || empty($groups)) {
            return;
        }

        $templateFile = 'module:zonethememanager/views/templates/hook/product_combinations.tpl';
        $cacheId = $this->name.'|combinations|'.$id_product.'|'.$id_product_attribute;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcessProductCombinations($combinations, $groups, $id_product, $id_product_attribute, $minimal_quantity);
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function displayCategoryTree()
    {
        $templateFile = 'module:zonethememanager/views/templates/front/category-tree.tpl';
        $cacheId = $this->name.'|categorytree';
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $settings = ZManager::getSettingsByShop();
            $general_settings = $settings->general_settings;
            $sidebar_menus = $this->getHomeCategoryTree($general_settings['sidebar_categories']);

            $this->smarty->assign(array(
                'sidebarMenus' => $sidebar_menus,
            ));
        }
        
        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    protected function getHomeCategoryTree($id_sidebar_categories)
    {
        $id_root_category = Configuration::get('PS_HOME_CATEGORY');
        $root_category = new Category((int) $id_root_category, $this->context->language->id);
        $range = '';

        $maxdepth = 5;
        if (Validate::isLoadedObject($root_category)) {
            $maxdepth += $root_category->level_depth;
            $range = 'AND nleft >= '.(int) $root_category->nleft.' AND nright <= '.(int) $root_category->nright;
        }

        $treeIds = array();
        $treeParents = array();
        $category_row = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT c.`id_parent`, c.`id_category`, cl.`name`, cl.`link_rewrite`
            FROM `'._DB_PREFIX_.'category` c
            INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int) $this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
            INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int) $this->context->shop->id.')
            WHERE (c.`active` = 1 OR c.`id_category` = '.(int) $id_root_category.')
            AND c.`id_category` != '.(int) Configuration::get('PS_ROOT_CATEGORY').'
            '.((int) $maxdepth != 0 ? ' AND `level_depth` <= '.(int) $maxdepth : '').'
            '.pSQL($range).'
            AND c.`id_category` IN (
                SELECT id_category
                FROM `'._DB_PREFIX_.'category_group`
                WHERE `id_group` IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int) $this->context->customer->id))).')
            )
            ORDER BY `level_depth` ASC, cs.`position` ASC'
        );
        foreach ($category_row as $row) {
            $treeParents[$row['id_parent']][] = $row;
            $treeIds[$row['id_category']] = $row;
        }

        $results = array();
        if ($id_sidebar_categories == 'ALL') {
            $tree = $this->getTree($treeParents, $treeIds, $maxdepth, $id_root_category);
            $results = $tree['children'];
        } else {
            foreach ($id_sidebar_categories as $id) {
                $results[] = $this->getTree($treeParents, $treeIds, $maxdepth, $id);
            }
        }

        return $results;
    }

    protected function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        $children = array();

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        $menu_thumb = false;
        $link = $name = '';
        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];

            $thumb = false;
            for ($i = 0; $i < 3; ++$i) {
                if (file_exists(_PS_CAT_IMG_DIR_.$id_category.'-'.$i.'_thumb.jpg')) {
                    $thumb = $i;
                    break;
                }
            }
            if ($thumb !== false) {
                list($tmpWidth, $tmpHeight, $type) = getimagesize(_PS_CAT_IMG_DIR_.$id_category.'-'.$thumb.'_thumb.jpg');
                $menu_thumb = array(
                    'url' => $this->context->link->getCatImageLink($resultIds[$id_category]['link_rewrite'], $id_category, $thumb.'_thumb'),
                    'width' => $tmpWidth,
                    'height' => $tmpHeight,
                );
            }
        }

        return array(
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'menu_thumb' => $menu_thumb,
            'children' => $children
        );
    }
}
