<?php
/**
 * 2007-2019 PrestaShop and Contributors.
 *
 * PHP version 5
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

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

include_once dirname(__FILE__).'/classes/ZHomeBlock.php';
include_once dirname(__FILE__).'/classes/ZHomeTab.php';

class ZOneHomeBlocks extends Module
{
    protected $html = '';
    protected $currentIndex;
    protected $btproduct = 'blocktype_product';
    protected $bthtml = 'blocktype_html';
    protected $bttabs = 'blocktype_tabs';
    protected $ptfeatures = 'products_featured';
    protected $ptnew = 'products_new';
    protected $ptspecial = 'products_special';
    protected $ptseller = 'products_seller';
    protected $ptselected = 'products_selected';
    protected $ptcategory = 'products_category';
    protected $order_by_values = array(
        0 => 'name',
        1 => 'price',
        2 => 'date_add',
        3 => 'date_upd',
        4 => 'position',
        5 => 'manufacturer_name',
        6 => 'quantity',
        7 => 'reference'
    );
    protected $order_way_values = array(
        0 => 'asc',
        1 => 'desc'
    );
    protected $hooks;
    protected $product_types;
    protected $is_mobile;

    public function __construct()
    {
        $this->name = 'zonehomeblocks';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Homepage Blocks',
            array(),
            'Modules.ZoneHomeblocks.Admin'
        );
        $this->description = $this->trans(
            'Flexible Products and Banners on the Homepage',
            array(),
            'Modules.ZoneHomeblocks.Admin'
        );

        $this->hooks = array(
            'home_top' => $this->trans(
                'Top of Homepage',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            'home_middle' => $this->trans(
                'Main Homepage',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            'home_bottom' => $this->trans(
                'Bottom of Homepage',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            )
        );
        $this->product_types = array(
            $this->ptfeatures => $this->trans(
                'Featured Products',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->ptnew => $this->trans(
                'New Products',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->ptspecial => $this->trans(
                'Special Products',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->ptseller => $this->trans(
                'Best Seller Products',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->ptselected => $this->trans(
                'Selected Products',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->ptcategory => $this->trans(
                'Products from Category',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            )
        );
        $this->is_mobile = ($this->context->isMobile()  && !$this->context->isTablet());

        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
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

        return parent::install()
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && $this->registerHook('actionCategoryAdd')
            && $this->registerHook('actionCategoryUpdate')
            && $this->registerHook('actionCategoryDelete')
            && $this->registerHook('updateOrderStatus')
            && $this->registerHook('displayHome')
            && $this->registerHook('displayTopColumn')
            && $this->registerHook('displayBottomColumn')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zhomeblock`,
            `'._DB_PREFIX_.'zhomeblock_lang`,
            `'._DB_PREFIX_.'zhometab`,
            `'._DB_PREFIX_.'zhometab_lang`';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function backOfficeHeader()
    {
        Media::addJsDef(array(
            'blocktype_product' => $this->btproduct,
            'blocktype_html' => $this->bthtml,
            'blocktype_tabs' => $this->bttabs,
            'products_selected' => $this->ptselected,
            'products_category' => $this->ptcategory,
        ));

        $this->context->controller->addJqueryPlugin('tablednd');
        $this->context->controller->addJS($this->_path.'views/js/position.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        if (Tools::isSubmit('savezhometab')) {
            if ($this->processSaveHomeTab()) {
                $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
                Tools::redirectAdmin($this->currentIndex.'&updatezhomeblock&id_zhomeblock='.$id_zhomeblock.'&token='.Tools::getAdminTokenLite('AdminModules'));
            } else {
                return $this->html.$this->renderHomeTabForm();
            }
        } elseif (Tools::isSubmit('addzhometab') || Tools::isSubmit('updatezhometab')) {
            return $this->renderHomeTabForm();
        } elseif (Tools::isSubmit('deletezhometab')) {
            $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
            $id_zhometab = (int) Tools::getValue('id_zhometab');
            $zhometab = new ZHomeTab($id_zhomeblock, $id_zhometab);
            $zhometab->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&updatezhomeblock&id_zhomeblock='.$id_zhomeblock.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszhometab')) {
            $this->ajaxStatusHomeTab('active');
        } elseif (Tools::isSubmit('statusmobilezhometab')) {
            $this->ajaxStatusHomeTab('active_mobile');
        } elseif (Tools::getValue('updatePositions') == 'zhometab') {
            $this->ajaxPositionsHomeTab();
        } elseif (Tools::isSubmit('savezhomeblock')) {
            if ($this->processSaveHomeBlock()) {
                return $this->renderHomeBlockList();
            } else {
                return $this->html.$this->renderHomeBlockForm();
            }
        } elseif (Tools::isSubmit('addzhomeblock') || Tools::isSubmit('updatezhomeblock')) {
            return $this->renderHomeBlockForm();
        } elseif (Tools::isSubmit('deletezhomeblock')) {
            $zhomeblock = new ZHomeBlock((int) Tools::getValue('id_zhomeblock'));
            $zhomeblock->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszhomeblock')) {
            $this->ajaxStatusHomeBlock('active');
        } elseif (Tools::isSubmit('statusmobilezhomeblock')) {
            $this->ajaxStatusHomeBlock('active_mobile');
        } elseif (Tools::getValue('updatePositions') == 'zhomeblock') {
            $this->ajaxPositionsHomeBlock();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } else {
            return $this->renderHomeBlockList();
        }
    }

    protected function ajaxProductsList()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die();
        }

        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                ON (pl.id_product = p.id_product
                AND pl.id_lang = '.(int) Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
            WHERE (pl.name LIKE \'%'.pSQL($query).'%\'
                OR p.reference LIKE \'%'.pSQL($query).'%\'
                OR p.id_product = '.(int) $query.')
            GROUP BY p.`id_product`';

        $items = Db::getInstance()->executeS($sql);

        if ($items) {
            foreach ($items as $item) {
                $item['name'] = str_replace('|', '-', $item['name']);
                echo trim($item['name']).(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '').'|'.(int) $item['id_product']."\n";
            }
        } else {
            Tools::jsonEncode(new stdClass());
        }
    }

    protected function ajaxStatusHomeBlock($field = 'active')
    {
        $id_zhomeblock = (int)Tools::getValue('id_zhomeblock');
        if (!$id_zhomeblock) {
            die(Tools::jsonEncode(array(
                'success' => false,
                'error' => true,
                'text' => $this->trans(
                    'Failed to update the status',
                    array(),
                    'Admin.Notifications.Error'
                )
            )));
        } else {
            $zhomeblock = new ZHomeBlock($id_zhomeblock);
            $zhomeblock->$field = !(int)$zhomeblock->$field;
            if ($zhomeblock->save()) {
                $this->_clearCache('*');
                die(Tools::jsonEncode(array(
                    'success' => true,
                    'text' => $this->trans(
                        'The status has been updated successfully',
                        array(),
                        'Admin.Notifications.Success'
                    )
                )));
            } else {
                die(Tools::jsonEncode(array(
                    'success' => false,
                    'error' => true,
                    'text' => $this->trans(
                        'Failed to update the status',
                        array(),
                        'Admin.Notifications.Error'
                    )
                )));
            }
        }
    }

    protected function ajaxPositionsHomeBlock()
    {
        $positions = Tools::getValue('zhomeblock');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZHomeBlock::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function processSaveHomeBlock()
    {
        $zhomeblock = new ZHomeBlock();
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        if ($id_zhomeblock) {
            $zhomeblock = new ZHomeBlock($id_zhomeblock);
        }

        $zhomeblock->position = (int) Tools::getValue('position');
        $zhomeblock->active = (int) Tools::getValue('active');
        $zhomeblock->active_mobile = (int) Tools::getValue('active_mobile');
        $zhomeblock->block_type = Tools::getValue('block_type');
        $zhomeblock->hook = Tools::getValue('hook');
        $zhomeblock->custom_class = Tools::getValue('custom_class');
        $zhomeblock->product_filter = Tools::getValue('product_filter');

        $product_options = array();
        $product_options['limit'] = Tools::getValue('limit');
        $product_options['mobile_limit'] = Tools::getValue('mobile_limit');
        $product_options['enable_slider'] = Tools::getValue('enable_slider');
        $product_options['mobile_enable_slider'] = Tools::getValue('mobile_enable_slider');
        $product_options['auto_scroll'] = Tools::getValue('auto_scroll');
        $product_options['number_column'] = Tools::getValue('number_column');
        $product_options['sort_order'] = Tools::getValue('sort_order');
        $product_options['selected_products'] = Tools::getValue('selected_products');
        $product_options['selected_category'] = Tools::getValue('selected_category');
        $zhomeblock->product_options = $product_options;

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $static_html = array();
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$lang['id_lang']);
            if (!$static_html[$lang['id_lang']]) {
                $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$id_lang_default);
            }
        }
        $zhomeblock->title = $title;
        $zhomeblock->static_html = $static_html;

        $result = $zhomeblock->validateFields(false) && $zhomeblock->validateFieldsLang(false);

        if ($result) {
            $zhomeblock->save();

            if ($id_zhomeblock) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Block Content has been updated.',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Block Content has been created successfully.',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayConfirmation($this->trans(
                'An error occurred while attempting to save Block Content.',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ));
        }

        return $result;
    }

    protected function ajaxStatusHomeTab($field = 'active')
    {
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $id_zhometab = (int) Tools::getValue('id_zhometab');
        if (!$id_zhometab) {
            die(Tools::jsonEncode(array(
                'success' => false,
                'error' => true,
                'text' => $this->trans(
                    'Failed to update the status',
                    array(),
                    'Admin.Notifications.Error'
                )
            )));
        } else {
            $zhometab = new ZHomeTab($id_zhomeblock, $id_zhometab);
            $zhometab->$field = !(int)$zhometab->$field;
            if ($zhometab->save()) {
                $this->_clearCache('*');
                die(Tools::jsonEncode(array(
                    'success' => true,
                    'text' => $this->trans(
                        'The status has been updated successfully',
                        array(),
                        'Admin.Notifications.Success'
                    )
                )));
            } else {
                die(Tools::jsonEncode(array(
                    'success' => false,
                    'error' => true,
                    'text' => $this->trans(
                        'Failed to update the status',
                        array(),
                        'Admin.Notifications.Error'
                    )
                )));
            }
        }
    }

    protected function ajaxPositionsHomeTab()
    {
        $positions = Tools::getValue('zhometab');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZHomeTab::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function processSaveHomeTab()
    {
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $id_zhometab = (int) Tools::getValue('id_zhometab');
        $zhometab = new ZHomeTab($id_zhomeblock);
        if ($id_zhometab) {
            $zhometab = new ZHomeTab($id_zhomeblock, $id_zhometab);
        }

        $zhometab->position = (int) Tools::getValue('position');
        $zhometab->active = (int) Tools::getValue('active');
        $zhometab->active_mobile = (int) Tools::getValue('active_mobile');
        $zhometab->block_type = Tools::getValue('block_type');
        $zhometab->product_filter = Tools::getValue('product_filter');

        $product_options = array();
        $product_options['limit'] = Tools::getValue('limit');
        $product_options['mobile_limit'] = Tools::getValue('mobile_limit');
        $product_options['enable_slider'] = Tools::getValue('enable_slider');
        $product_options['mobile_enable_slider'] = Tools::getValue('mobile_enable_slider');
        $product_options['auto_scroll'] = Tools::getValue('auto_scroll');
        $product_options['number_column'] = Tools::getValue('number_column');
        $product_options['sort_order'] = Tools::getValue('sort_order');
        $product_options['selected_products'] = Tools::getValue('selected_products');
        $product_options['selected_category'] = Tools::getValue('selected_category');
        $zhometab->product_options = $product_options;

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $static_html = array();
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$lang['id_lang']);
            if (!$static_html[$lang['id_lang']]) {
                $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$id_lang_default);
            }
        }
        $zhometab->title = $title;
        $zhometab->static_html = $static_html;

        $result = $zhometab->validateFields(false) && $zhometab->validateFieldsLang(false);

        if ($result) {
            $zhometab->save();

            if ($id_zhometab) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Tab Content has been updated.',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Tab Content has been created successfully.',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayConfirmation($this->trans(
                'An error occurred while attempting to save Tab Content.',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ));
        }

        return $result;
    }

    protected function renderHomeBlockList()
    {
        $hook = Tools::getValue('hook', 'home_middle');

        $this->smarty->assign(array(
            'alert' => $this->html,
            'current_hook' => $hook,
            'hooks' => $this->hooks,
            'panel_href' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'doc_url' => $this->_path.'documentation.pdf',
            'home_block_list' => $this->renderHomeBlockListByHook($hook),
        ));
        
        return $this->display(__FILE__, 'views/templates/admin/home-block-list.tpl');
    }

    protected function renderHomeBlockListByHook($hook)
    {
        $blocks = ZHomeBlock::getList((int) $this->context->language->id, $hook);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzhomeblock&hook='.$hook.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($blocks);
        //$helper->table_id = 'zonehomeblock_'.$hook;
        //$helper->list_id = 'zonehomeblock_'.$hook;
        $helper->identifier = 'id_zhomeblock';
        $helper->table = 'zhomeblock';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->hooks[$hook];
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zhomeblock';
        $helper->position_group_identifier = 0;

        $helper->tpl_vars = array('block_type' => array(
            $this->btproduct => $this->product_types,
            $this->bthtml => $this->trans(
                'Static HTML',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
            $this->bttabs => $this->trans(
                'Tabs Layout',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
        ));

        return $helper->generateList($blocks, $this->getHomeBlockList());
    }

    protected function getHomeBlockList()
    {
        $fields_list = array(
            'id_zhomeblock' => array(
                'title' => $this->trans(
                    'Block ID',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_homeblock',
            ),
            'title' => array(
                'title' => $this->trans(
                    'Title',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'class' => 'fixed-width-30',
                'orderby' => false,
                'search' => false,
            ),
            'block_type' => array(
                'title' => $this->trans(
                    'Content Type',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'class' => 'fixed-width-20',
                'orderby' => false,
                'search' => false,
                'type' => 'zblocktype',
            ),
            'position' => array(
                'title' => $this->trans(
                    'Position',
                    array(),
                    'Admin.Global'
                ),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'class' => 'fixed-width-md',
                'position' => true,
                'type' => 'zposition',
            ),
            'active' => array(
                'title' => $this->trans(
                    'Desktop',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
            'active_mobile' => array(
                'title' => $this->trans(
                    'Mobile',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'statusmobile',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
        );

        return $fields_list;
    }

    protected function renderHomeBlockForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezhomeblock';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getHomeBlockFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'module_dir' => $this->_path,
        );

        $form = $helper->generateForm(array($this->getHomeBlockForm()));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));

        return $form;
    }

    protected function getHomeBlockForm()
    {
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $zhomeblock = new ZHomeBlock($id_zhomeblock, (int) $this->context->language->id);
        $hook = Tools::getValue('hook');
        $default_category = isset($zhomeblock->product_options['selected_category']) ? (int) $zhomeblock->product_options['selected_category'] : 0;
        $selected_category = array((int) Tools::getValue('selected_category', $default_category));
        $root = Category::getRootCategory();

        $legent_title = $this->trans(
            'Add New Block',
            array(),
            'Modules.ZoneHomeblocks.Admin'
        );
        if ($id_zhomeblock) {
            $legent_title = $this->trans(
                'Edit Block',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            );
            $hook = $zhomeblock->hook;
        }

        $tab_list = $this->renderHomeTabList();

        $hook_options = array(
            'query' => array(),
            'id' => 'id',
            'name' => 'name',
        );
        foreach ($this->hooks as $key => $name) {
            $hook_options['query'][] = array(
                'id' => $key,
                'name' => $name
            );
        }

        $block_type_options = array(
            'query' => array(
                array('id' => $this->btproduct, 'name' => $this->trans(
                    'Product Block',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => $this->bthtml, 'name' => $this->trans(
                    'Static HTML',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => $this->bttabs, 'name' => $this->trans(
                    'Tabs Layout',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $product_filter_options = array(
            'query' => array(),
            'id' => 'id',
            'name' => 'name',
        );
        foreach ($this->product_types as $key => $name) {
            $product_filter_options['query'][] = array(
                'id' => $key,
                'name' => $name
            );
        }

        $number_column_options = array(
            'query' => array(
                array('id' => 1, 'name' => 1),
                array('id' => 2, 'name' => 2),
                array('id' => 3, 'name' => 3),
                array('id' => 4, 'name' => 4),
                array('id' => 5, 'name' => 5),
                array('id' => 6, 'name' => 6),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $sort_order_options = array(
            'query' => array(
                array('id' => 'product.position.asc', 'name' => $this->trans(
                    'Default',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.name.asc', 'name' => $this->trans(
                    'Name, A to Z',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.name.desc', 'name' => $this->trans(
                    'Name, Z to A',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.price.asc', 'name' => $this->trans(
                    'Price, low to high',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.price.desc', 'name' => $this->trans(
                    'Price, high to low',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.date_add.desc', 'name' => $this->trans(
                    'Date added, newest to oldest',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.date_add.asc', 'name' => $this->trans(
                    'Date added, oldest to newest',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zhomeblock',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Title',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable on Desktop',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable on Mobile',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'active_mobile',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_mobile_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_mobile_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Position',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'hook',
                        'options' => $hook_options,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Custom CSS Class',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'custom_class',
                        'col' => 3,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Block Type',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'block_type',
                        'id' => 'block_type_selectbox',
                        'options' => $block_type_options,
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'product_option_title',
                        'html_content' => '<h4>'.$this->trans(
                            'Product Block',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_product',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Number of Products',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'limit',
                        'form_group_class' => 'block_type_product',
                        'col' => 1,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Number of Products on Moblie',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'mobile_limit',
                        'form_group_class' => 'block_type_product',
                        'col' => 1,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'enable_slider',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'slider_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'slider_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider on Mobile',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'mobile_enable_slider',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'mobile_slider_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'mobile_slider_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Slider Autoplay',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'auto_scroll',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'scroll_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'scroll_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Products per Row',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'number_column',
                        'form_group_class' => 'block_type_product',
                        'options' => $number_column_options,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Sort Order',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'sort_order',
                        'form_group_class' => 'block_type_product',
                        'options' => $sort_order_options,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Get Products From',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'product_filter',
                        'id' => 'product_filter_selectbox',
                        'form_group_class' => 'block_type_product',
                        'options' => $product_filter_options,
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->trans(
                            'Select the Products',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'selected_products',
                        'form_group_class' => 'block_type_product filter_selected_products',
                        'ajax_path' => $this->currentIndex.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'hint' => $this->trans(
                            'Begin typing the First Letters of the Product Name, then select the Product from the Drop-down List.',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->trans(
                            'Select a Category',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'selected_category',
                        'form_group_class' => 'block_type_product filter_select_category categories_tree',
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'categoryBox',
                            'root_category' => $root->id,
                            'selected_categories' => $selected_category,
                        ),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'static_html_title',
                        'html_content' => '<h4>'.$this->trans(
                            'Static HTML',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_static_html',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Content',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'static_html',
                        'form_group_class' => 'block_type_static_html',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'tabs_layout_title',
                        'html_content' => '<h3>'.$this->trans(
                            'Tabs Layout',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ).'</h3>',
                        'form_group_class' => 'block_type_tabs',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'tab_count',
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'tabs_layout_content',
                        'html_content' => $tab_list,
                        'form_group_class' => 'block_type_tabs',
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->currentIndex.'&hook='.$hook.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->trans(
                            'Back to Block List',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'icon' => 'process-icon-back',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getHomeBlockFormValues()
    {
        $fields_value = array();

        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $zhomeblock = new ZHomeBlock($id_zhomeblock);

        $fields_value['id_zhomeblock'] = $id_zhomeblock;
        $fields_value['active'] = Tools::getValue('active', $zhomeblock->active);
        $fields_value['active_mobile'] = Tools::getValue('active_mobile', $zhomeblock->active_mobile);
        $fields_value['position'] = Tools::getValue('position', $zhomeblock->position);
        $fields_value['hook'] = Tools::getValue('hook', $zhomeblock->hook);
        $fields_value['block_type'] = Tools::getValue('block_type', $zhomeblock->block_type);
        $fields_value['custom_class'] = Tools::getValue('custom_class', $zhomeblock->custom_class);
        $fields_value['product_filter'] = Tools::getValue('product_filter', $zhomeblock->product_filter);

        $fields_value['selected_products'] = $zhomeblock->getProductsAutocompleteInfo($this->context->language->id);
        $fields_value['limit'] = Tools::getValue('limit', $zhomeblock->product_options['limit']);
        $fields_value['mobile_limit'] = Tools::getValue('mobile_limit', $zhomeblock->product_options['mobile_limit']);
        $fields_value['enable_slider'] = Tools::getValue('enable_slider', $zhomeblock->product_options['enable_slider']);
        $fields_value['mobile_enable_slider'] = Tools::getValue('mobile_enable_slider', $zhomeblock->product_options['mobile_enable_slider']);
        $fields_value['auto_scroll'] = Tools::getValue('auto_scroll', $zhomeblock->product_options['auto_scroll']);
        $fields_value['number_column'] = Tools::getValue('number_column', $zhomeblock->product_options['number_column']);
        $fields_value['sort_order'] = Tools::getValue('sort_order', $zhomeblock->product_options['sort_order']);

        $fields_value['tab_count'] = 1;

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_title = isset($zhomeblock->title[$lang['id_lang']]) ? $zhomeblock->title[$lang['id_lang']] : '';
            $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int) $lang['id_lang'], $default_title);
            $default_static_html = isset($zhomeblock->static_html[$lang['id_lang']]) ? $zhomeblock->static_html[$lang['id_lang']] : '';
            $fields_value['static_html'][$lang['id_lang']] = Tools::getValue('static_html_'.(int) $lang['id_lang'], $default_static_html);
        }

        return $fields_value;
    }

    protected function renderHomeTabList()
    {
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $zhomeblock = new ZHomeBlock($id_zhomeblock);

        if ($zhomeblock->block_type != $this->bttabs) {
            return $this->trans(
                'You have to SAVE this block before adding a tab panel.',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            );
        }

        $tabs = ZHomeTab::getList($id_zhomeblock, (int) $this->context->language->id);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzhometab&id_zhomeblock='.$id_zhomeblock.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($tabs);
        $helper->identifier = 'id_zhometab';
        $helper->table = 'zhometab';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->ajax = true;
        $helper->module = $this;
        $helper->title = $this->trans(
            'Tab List',
            array(),
            'Modules.ZoneHomeblocks.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex.'&id_zhomeblock='.$id_zhomeblock;
        $helper->position_identifier = 'zhometab';
        $helper->position_group_identifier = $id_zhomeblock;

        $helper->tpl_vars = array('block_type' => array(
            $this->btproduct => $this->product_types,
            $this->bthtml => $this->trans(
                'Static HTML Block',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            ),
        ));

        return $helper->generateList($tabs, $this->getHomeTabList());
    }

    protected function getHomeTabList()
    {
        $fields_list = array(
            'id_zhometab' => array(
                'title' => $this->trans(
                    'Tab ID',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_hometab',
            ),
            'title' => array(
                'title' => $this->trans(
                    'Title',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'orderby' => false,
                'search' => false,
            ),
            'block_type' => array(
                'title' => $this->trans(
                    'Content Type',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zblocktype',
            ),
            'position' => array(
                'title' => $this->trans(
                    'Position',
                    array(),
                    'Admin.Global'
                ),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'class' => 'fixed-width-md',
                'position' => true,
                'type' => 'zposition',
            ),
            'active' => array(
                'title' => $this->trans(
                    'Desktop',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
            'active_mobile' => array(
                'title' => $this->trans(
                    'Mobile',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'statusmobile',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
        );

        return $fields_list;
    }

    protected function renderHomeTabForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezhometab';
        $helper->currentIndex = $this->currentIndex.'&updatezhomeblock&id_zhomeblock='.$id_zhomeblock;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getHomeTabFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'module_dir' => $this->_path,
        );

        $form = $helper->generateForm(array($this->getHomeTabForm()));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));
        
        return $form;
    }

    protected function getHomeTabForm()
    {
        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $zhomeblock = new ZHomeBlock($id_zhomeblock, (int) $this->context->language->id);
        $id_zhometab = (int) Tools::getValue('id_zhometab');
        $zhometab = new ZHomeTab($id_zhomeblock, $id_zhometab, (int) $this->context->language->id);
        $default_category = isset($zhometab->product_options['selected_category']) ? (int) $zhometab->product_options['selected_category'] : 0;
        $selected_category = array((int) Tools::getValue('selected_category', $default_category));
        $root = Category::getRootCategory();

        $legent_title = $zhomeblock->title.' > '.$this->trans(
            'Add New Tab',
            array(),
            'Modules.ZoneHomeblocks.Admin'
        );
        if ($id_zhometab) {
            $legent_title = $zhomeblock->title.' > '.$this->trans(
                'Edit Tab',
                array(),
                'Modules.ZoneHomeblocks.Admin'
            );
        }

        $block_type_options = array(
            'query' => array(
                array('id' => $this->btproduct, 'name' => $this->trans(
                    'Product Block',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => $this->bthtml, 'name' => $this->trans(
                    'Static HTML Block',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $product_filter_options = array(
            'query' => array(),
            'id' => 'id',
            'name' => 'name',
        );
        foreach ($this->product_types as $key => $name) {
            $product_filter_options['query'][] = array(
                'id' => $key,
                'name' => $name
            );
        }

        $number_column_options = array(
            'query' => array(
                array('id' => 1, 'name' => 1),
                array('id' => 2, 'name' => 2),
                array('id' => 3, 'name' => 3),
                array('id' => 4, 'name' => 4),
                array('id' => 5, 'name' => 5),
                array('id' => 6, 'name' => 6),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $sort_order_options = array(
            'query' => array(
                array('id' => 'product.position.asc', 'name' => $this->trans(
                    'Default',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.name.asc', 'name' => $this->trans(
                    'Name, A to Z',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.name.desc', 'name' => $this->trans(
                    'Name, Z to A',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.price.asc', 'name' => $this->trans(
                    'Price, low to high',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.price.desc', 'name' => $this->trans(
                    'Price, high to low',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.date_add.desc', 'name' => $this->trans(
                    'Date added, newest to oldest',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
                array('id' => 'product.date_add.asc', 'name' => $this->trans(
                    'Date added, oldest to newest',
                    array(),
                    'Modules.ZoneHomeblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zhometab',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Tab Title',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable on Desktop',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable on Mobile',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'active_mobile',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_mobile_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_mobile_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Block Type',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'block_type',
                        'id' => 'block_type_selectbox',
                        'options' => $block_type_options,
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'product_option_title',
                        'html_content' => '<h4>'.$this->trans(
                            'Product Block',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_product',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Number of Products',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'limit',
                        'form_group_class' => 'block_type_product',
                        'col' => 1,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Number of Products on Mobile',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'mobile_limit',
                        'form_group_class' => 'block_type_product',
                        'col' => 1,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'enable_slider',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'slider_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'slider_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider on Mobile',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'mobile_enable_slider',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'mobile_slider_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'mobile_slider_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Slider Autoplay',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'auto_scroll',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'scroll_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'scroll_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Products per Row',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'number_column',
                        'form_group_class' => 'block_type_product',
                        'options' => $number_column_options,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Sort Order',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'sort_order',
                        'form_group_class' => 'block_type_product',
                        'options' => $sort_order_options,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Get Products From',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'product_filter',
                        'id' => 'product_filter_selectbox',
                        'form_group_class' => 'block_type_product',
                        'options' => $product_filter_options,
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->trans(
                            'Select the Products',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'selected_products',
                        'form_group_class' => 'block_type_product filter_selected_products',
                        'ajax_path' => $this->currentIndex.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'hint' => $this->trans(
                            'Begin typing the First Letters of the Product Name, then select the Product from the Drop-down List.',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->trans(
                            'Select a Category',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'selected_category',
                        'form_group_class' => 'block_type_product filter_select_category categories_tree',
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'categoryBox',
                            'root_category' => $root->id,
                            'selected_categories' => $selected_category,
                        ),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'static_html_title',
                        'html_content' => '<h4>'.$this->trans(
                            'Static HTML',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_static_html',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Content',
                            array(),
                            'Modules.ZoneHomeblocks.Admin'
                        ),
                        'name' => 'static_html',
                        'form_group_class' => 'block_type_static_html',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->currentIndex.'&updatezhomeblock&id_zhomeblock='.$id_zhomeblock.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->trans(
                            'Cancel',
                            array(),
                            'Admin.Actions'
                        ),
                        'icon' => 'process-icon-cancel',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getHomeTabFormValues()
    {
        $fields_value = array();

        $id_zhomeblock = (int) Tools::getValue('id_zhomeblock');
        $id_zhometab = (int) Tools::getValue('id_zhometab');
        $zhometab = new ZHomeTab($id_zhomeblock, $id_zhometab);

        $fields_value['id_zhometab'] = $id_zhometab;
        $fields_value['active'] = Tools::getValue('active', $zhometab->active);
        $fields_value['active_mobile'] = Tools::getValue('active_mobile', $zhometab->active_mobile);
        $fields_value['position'] = Tools::getValue('position', $zhometab->position);
        $fields_value['block_type'] = Tools::getValue('block_type', $zhometab->block_type);
        $fields_value['product_filter'] = Tools::getValue('product_filter', $zhometab->product_filter);

        $fields_value['selected_products'] = $zhometab->getProductsAutocompleteInfo($this->context->language->id);
        $fields_value['limit'] = Tools::getValue('limit', $zhometab->product_options['limit']);
        $fields_value['mobile_limit'] = Tools::getValue('mobile_limit', $zhometab->product_options['mobile_limit']);
        $fields_value['enable_slider'] = Tools::getValue('enable_slider', $zhometab->product_options['enable_slider']);
        $fields_value['mobile_enable_slider'] = Tools::getValue('mobile_enable_slider', $zhometab->product_options['mobile_enable_slider']);
        $fields_value['auto_scroll'] = Tools::getValue('auto_scroll', $zhometab->product_options['auto_scroll']);
        $fields_value['number_column'] = Tools::getValue('number_column', $zhometab->product_options['number_column']);
        $fields_value['sort_order'] = Tools::getValue('sort_order', $zhometab->product_options['sort_order']);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_title = isset($zhometab->title[$lang['id_lang']]) ? $zhometab->title[$lang['id_lang']] : '';
            $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int) $lang['id_lang'], $default_title);
            $default_static_html = isset($zhometab->static_html[$lang['id_lang']]) ? $zhometab->static_html[$lang['id_lang']] : '';
            $fields_value['static_html'][$lang['id_lang']] = Tools::getValue('static_html_'.(int) $lang['id_lang'], $default_static_html);
        }

        return $fields_value;
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

    public function hookUpdateOrderStatus($params)
    {
        $this->_clearCache('*');
    }

    protected function getProducts($product_filter, $product_options)
    {
        $nProducts = $product_options['limit'];
        if ($this->is_mobile) {
            $nProducts = $product_options['mobile_limit'];
        }
        if ($nProducts < 0) {
            $nProducts = 10;
        }

        $searchContext = new ProductSearchContext($this->context);
        $query = new ProductSearchQuery();
        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;
        $query->setSortOrder(SortOrder::newFromString($product_options['sort_order']));
        $searchProvider = false;
        $products = false;
        $present_products = array();

        if ($product_filter == $this->ptfeatures) {
            if ($product_options['sort_order'] == 'product.position.asc') {
                $query->setSortOrder(SortOrder::random());
            }
            $category = new Category((int) $this->context->shop->getCategory());
            if (Validate::isLoadedObject($category)) {
                $searchProvider = new CategoryProductSearchProvider(
                    $this->context->getTranslator(),
                    $category
                );
            }

        } elseif ($product_filter == $this->ptnew) {
            $query->setQueryType('new-products');
            if ($product_options['sort_order'] == 'product.position.asc') {
                $query->setSortOrder(new SortOrder('product', 'date_add', 'desc'));
            }
            $searchProvider = new NewProductsProductSearchProvider(
                $this->context->getTranslator()
            );

        } elseif ($product_filter == $this->ptspecial) {
            $query->setQueryType('prices-drop');
            if ($product_options['sort_order'] == 'product.position.asc') {
                $query->setSortOrder(new SortOrder('product', 'name', 'asc'));
            }
            $searchProvider = new PricesDropProductSearchProvider(
                $this->context->getTranslator()
            );

        } elseif ($product_filter == $this->ptseller) {
            $query->setQueryType('best-sales');
            if ($product_options['sort_order'] == 'product.position.asc') {
                $query->setSortOrder(new SortOrder('product', 'sale_nbr', 'desc'));
            }
            $searchProvider = new BestSalesProductSearchProvider(
                $this->context->getTranslator()
            );

        } elseif ($product_filter == $this->ptselected && isset($product_options['selected_products'])) {
            $products = ZHomeBlock::getProductsByArrayId($product_options['selected_products'], $this->context->language->id, $nProducts);

        } elseif ($product_filter == $this->ptcategory && isset($product_options['selected_category'])) {
            if ($product_options['selected_category'] == Configuration::get('PS_HOME_CATEGORY')) {
                $products = Product::getProducts(
                    (int) $this->context->language->id,
                    0,
                    $nProducts,
                    'date_add',
                    'desc',
                    false,
                    true
                );
            } else {
                $category = new Category((int) $product_options['selected_category']);
                if (Validate::isLoadedObject($category)) {
                    $searchProvider = new CategoryProductSearchProvider(
                        $this->context->getTranslator(),
                        $category
                    );
                }
            }

        }

        if ($searchProvider) {
            $result = $searchProvider->runQuery(
                $searchContext,
                $query
            );
            $products = $result->getProducts();
        }

        if ($products) {
            $assembler = new ProductAssembler($this->context);
            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductListingPresenter(
                new ImageRetriever($this->context->link),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            foreach ($products as $rawProduct) {
                $present_products[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        return $present_products;
    }

    protected function getShowMoreLink($product_filter, $product_options)
    {
        $show_more_link = false;

        if ($product_filter == $this->ptcategory) {
            $category = new Category((int) $product_options['selected_category'], (int) $this->context->language->id);
            if (Validate::isLoadedObject($category)) {
                $show_more_link = $this->context->link->getCategoryLink($category);
            }
        }
        if ($product_filter == $this->ptfeatures) {
            $id_category = (int) $this->context->shop->getCategory();
            $show_more_link = $this->context->link->getCategoryLink($id_category);
        }
        if ($product_filter == $this->ptnew) {
            $show_more_link = $this->context->link->getPageLink('new-products');
        }
        if ($product_filter == $this->ptspecial) {
            $show_more_link = $this->context->link->getPageLink('prices-drop');
        }
        if ($product_filter == $this->ptseller) {
            $show_more_link = $this->context->link->getPageLink('best-sales');
        }

        return $show_more_link;
    }

    protected function getSliderOptions($product_options)
    {
        $number_column = (int) $product_options['number_column'];

        return array(
            'slidesToShow' => $number_column,
            'slidesToShow_1220' => min(4, $number_column),
            'slidesToShow_992' => min(3, $number_column-1),
            'slidesToShow_768' => min(2, $number_column-1),
            'speed' => 1200,
            'autoplay' => ($product_options['auto_scroll'] && !$this->is_mobile ? true : false),
            'dots' => ($this->is_mobile ? true : false),
            'arrows' => ($this->is_mobile ? false : true),
            'draggable' => ($this->is_mobile ? true : false),
            'rtl' => ($this->context->language->is_rtl ? true : false),
        );
    }

    protected function preProcess($hook)
    {
        $id_lang = (int) $this->context->language->id;
        $objectPresenter = new ObjectPresenter();
        $home_blocks = array();
        $zhomeblocks = ZHomeBlock::getList($id_lang, $hook, !$this->is_mobile, $this->is_mobile);

        if (!empty($zhomeblocks)) {
            foreach ($zhomeblocks as $zhomeblock) {
                $temp_block = array(
                    'id' => $zhomeblock['id_zhomeblock'],
                    'title' => $zhomeblock['title'],
                    'block_type' => $zhomeblock['block_type'],
                    'custom_class' => $zhomeblock['custom_class'],
                );

                if ($zhomeblock['block_type'] == $this->btproduct) {
                    $zhomeblock['product_options'] = ZHomeBlock::initProductOptions($zhomeblock['product_options']);

                    $temp_block['products'] = $this->getProducts($zhomeblock['product_filter'], $zhomeblock['product_options']);
                    $temp_block['show_more_link'] = $this->getShowMoreLink($zhomeblock['product_filter'], $zhomeblock['product_options']);

                    $temp_block['enable_slider'] = ($this->is_mobile ? $zhomeblock['product_options']['mobile_enable_slider'] : $zhomeblock['product_options']['enable_slider']);
                    $temp_block['number_column'] = (int) $zhomeblock['product_options']['number_column'];
                    if ($temp_block['enable_slider']) {
                        $temp_block['slider_options'] = $this->getSliderOptions($zhomeblock['product_options']);
                    }

                } elseif ($zhomeblock['block_type'] == $this->bthtml) {
                    $temp_block['static_html'] = $zhomeblock['static_html'];

                } elseif ($zhomeblock['block_type'] == $this->bttabs) {
                    $home_tabs = array();
                    $tabs = ZHomeTab::getList($zhomeblock['id_zhomeblock'], $id_lang, !$this->is_mobile, $this->is_mobile);

                    foreach ($tabs as $tab) {
                        $temp_tab = array(
                            'id' => 'Tab'.$tab['id_zhometab'],
                            'title' => $tab['title'],
                            'block_type' => $tab['block_type'],
                        );

                        if ($tab['block_type'] == $this->btproduct) {
                            $tab['product_options'] = ZHomeTab::initProductOptions($tab['product_options']);

                            $temp_tab['products'] = $this->getProducts($tab['product_filter'], $tab['product_options']);
                            $temp_tab['show_more_link'] = $this->getShowMoreLink($tab['product_filter'], $tab['product_options']);
                            
                            $temp_tab['enable_slider'] = ($this->is_mobile ? $tab['product_options']['mobile_enable_slider'] : $tab['product_options']['enable_slider']);
                            $temp_tab['number_column'] = (int) $tab['product_options']['number_column'];
                            if ($temp_tab['enable_slider']) {
                                $temp_tab['slider_options'] = $this->getSliderOptions($tab['product_options']);
                            }

                        } elseif ($tab['block_type'] == $this->bthtml) {
                            $temp_tab['static_html'] = $tab['static_html'];
                        }

                        $home_tabs[] = $temp_tab;
                    }

                    $temp_block['home_tabs'] = $home_tabs;
                }

                $home_blocks[] = $temp_block;
            }
        }

        $this->smarty->assign(array(
            'homeBlocks' => $home_blocks,
            'blocktype_product' => $this->btproduct,
            'blocktype_html' => $this->bthtml,
            'blocktype_tabs' => $this->bttabs,
            'mobile_device' => $this->is_mobile,
        ));
    }

    public function hookDisplayHome()
    {
        $templateFile = 'module:zonehomeblocks/views/templates/hook/zonehomeblocks.tpl';
        $cacheId = $this->name.'|home_middle|'.'device'.(int) $this->is_mobile;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess('home_middle');
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplaytopColumn()
    {
        $templateFile = 'module:zonehomeblocks/views/templates/hook/zonehomeblocks_top.tpl';
        $cacheId = $this->name.'|home_top|'.'device'.(int) $this->is_mobile;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess('home_top');
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayBottomColumn()
    {
        $templateFile = 'module:zonehomeblocks/views/templates/hook/zonehomeblocks_bottom.tpl';
        $cacheId = $this->name.'|home_bottom|'.'device'.(int) $this->is_mobile;
        
        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess('home_bottom');
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }
}
