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

include_once dirname(__FILE__).'/classes/ZMenu.php';
include_once dirname(__FILE__).'/classes/ZDropdown.php';

class ZOneMegaMenu extends Module
{
    public $bg_img_folder = 'views/img/bg_images/';
    public $title_img_folder = 'views/img/title_images/';
    protected $html = '';
    protected $currentIndex;
    protected $is_mobile;

    public function __construct()
    {
        $this->name = 'zonemegamenu';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Mega Menu',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        $this->description = $this->trans(
            'Mega Menu in the top of website.',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );

        $this->is_mobile = ($this->context->isMobile() && !$this->context->isTablet());

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
            && $this->registerHook('actionObjectManufacturerAddAfter')
            && $this->registerHook('actionObjectManufacturerUpdateAfter')
            && $this->registerHook('actionObjectManufacturerDeleteAfter')
            && $this->registerHook('displayNavFullWidth')
            && $this->registerHook('displayMobileMenu')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zmenu`,
            `'._DB_PREFIX_.'zmenu_lang`,
            `'._DB_PREFIX_.'zdropdown`,
            `'._DB_PREFIX_.'zdropdown_lang`';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $this->_clearCache('*');

        return parent::uninstall();
    }

    protected function about()
    {
        $this->smarty->assign(array(
            'doc_url' => $this->_path.'documentation.pdf',
        ));

        return $this->display(__FILE__, 'views/templates/admin/about.tpl');
    }

    public function backOfficeHeader()
    {
        $this->context->controller->addJqueryPlugin('tablednd');
        $this->context->controller->addJS($this->_path.'views/js/position.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        $about = $this->about();

        if (Tools::isSubmit('savezmenu')) {
            if ($this->processSaveMenu()) {
                return $this->html.$this->renderMenuList().$about;
            } else {
                return $this->html.$this->renderMenuForm();
            }
        } elseif (Tools::isSubmit('addzmenu') || Tools::isSubmit('updatezmenu')) {
            return $this->renderMenuForm();
        } elseif (Tools::isSubmit('deleteTitleImage')) {
            $id_zmenu = (int) Tools::getValue('id_zmenu');
            $zmenu = new ZMenu($id_zmenu);
            if ($zmenu->title_image) {
                $image_path = $this->local_path.$this->title_img_folder.$zmenu->title_image;

                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $zmenu->title_image = null;
                $zmenu->update(false);
                $this->_clearCache('*');
            }

            Tools::redirectAdmin($this->currentIndex.'&id_zmenu='.$id_zmenu.'&updatezmenu&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
        } elseif (Tools::isSubmit('deleteBackgroundImage')) {
            $id_zmenu = (int) Tools::getValue('id_zmenu');
            $zmenu = new ZMenu($id_zmenu);
            if ($zmenu->drop_bgimage) {
                $image_path = $this->local_path.$this->bg_img_folder.$zmenu->drop_bgimage;

                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $zmenu->drop_bgimage = null;
                $zmenu->update(false);
                $this->_clearCache('*');
            }

            Tools::redirectAdmin($this->currentIndex.'&id_zmenu='.$id_zmenu.'&updatezmenu&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
        } elseif (Tools::isSubmit('deletezmenu')) {
            $zmenu = new ZMenu((int) Tools::getValue('id_zmenu'));
            $zmenu->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszmenu')) {
            $this->ajaxStatusMenu();
        } elseif (Tools::getValue('updatePositions') == 'zmenu') {
            $this->ajaxPositionsMenu();
        } elseif (Tools::isSubmit('savezdropdown')) {
            if ($this->processSaveDropdown()) {
                Tools::redirectAdmin($this->currentIndex.'&updatezmenu&id_zmenu='.(int) Tools::getValue('id_zmenu').'&token='.Tools::getAdminTokenLite('AdminModules'));
            } else {
                return $this->html.$this->renderDropdownForm();
            }
        } elseif (Tools::isSubmit('addzdropdown') || Tools::isSubmit('updatezdropdown')) {
            return $this->renderDropdownForm();
        } elseif (Tools::isSubmit('deletezdropdown')) {
            $id_zmenu = (int) Tools::getValue('id_zmenu');
            $id_zdropdown = (int) Tools::getValue('id_zdropdown');
            $zdropdown = new ZDropdown($id_zmenu, $id_zdropdown);
            $zdropdown->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&updatezmenu&id_zmenu='.$id_zmenu.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszdropdown')) {
            $this->ajaxStatusDropdown();
        } elseif (Tools::getValue('updatePositions') == 'zdropdown') {
            $this->ajaxPositionsDropdown();
        } elseif (Tools::isSubmit('listzdropdown')) {
            return $this->renderDropdownList();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } else {
            return $this->renderMenuList().$about;
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

    protected function ajaxStatusMenu()
    {
        $id_zmenu = (int)Tools::getValue('id_zmenu');
        if (!$id_zmenu) {
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
            $zmenu = new ZMenu($id_zmenu);
            $zmenu->active = !(int)$zmenu->active;
            if ($zmenu->save()) {
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

    protected function ajaxPositionsMenu()
    {
        $positions = Tools::getValue('zmenu');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZMenu::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function ajaxStatusDropdown()
    {
        $id_zmenu = (int)Tools::getValue('id_zmenu');
        $id_zdropdown = (int)Tools::getValue('id_zdropdown');
        if (!$id_zdropdown) {
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
            $zdropdown = new ZDropdown($id_zmenu, $id_zdropdown);
            $zdropdown->active = !(int)$zdropdown->active;
            if ($zdropdown->save()) {
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

    protected function ajaxPositionsDropdown()
    {
        $positions = Tools::getValue('zdropdown');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZDropdown::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function processSaveMenu()
    {
        $zmenu = new ZMenu();
        $id_zmenu = (int) Tools::getValue('id_zmenu');
        if ($id_zmenu) {
            $zmenu = new ZMenu($id_zmenu);
        }

        $zmenu->position = (int) Tools::getValue('position');
        $zmenu->active = (int) Tools::getValue('active');
        $zmenu->link_newtab = (int) Tools::getValue('link_newtab');
        $zmenu->label_color = Tools::getValue('label_color');
        $zmenu->drop_column = Tools::getValue('drop_column');
        $zmenu->custom_class = Tools::getValue('custom_class');
        $zmenu->drop_bgcolor = Tools::getValue('drop_bgcolor');
        $zmenu->bgimage_position = Tools::getValue('bgimage_position');
        $zmenu->position_x = Tools::getValue('position_x');
        $zmenu->position_y = Tools::getValue('position_y');

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $name = array();
        $link = array();
        $label = array();
        foreach ($languages as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            if (!$name[$lang['id_lang']]) {
                $name[$lang['id_lang']] = Tools::getValue('name_'.$id_lang_default);
            }
            $link[$lang['id_lang']] = Tools::getValue('link_'.$lang['id_lang']);
            if (!$link[$lang['id_lang']]) {
                $link[$lang['id_lang']] = Tools::getValue('link_'.$id_lang_default);
            }
            $label[$lang['id_lang']] = Tools::getValue('label_'.$lang['id_lang']);
            if (!$label[$lang['id_lang']]) {
                $label[$lang['id_lang']] = Tools::getValue('label_'.$id_lang_default);
            }
        }
        $zmenu->name = $name;
        $zmenu->link = $link;
        $zmenu->label = $label;

        $result = $zmenu->validateFields(false) && $zmenu->validateFieldsLang(false);
        if ($result) {
            $zmenu->save();

            $this->saveMenuImages($zmenu);

            if ($id_zmenu) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Menu has been updated.',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Menu has been created successfully.',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Menu.',
                array(),
                'Modules.ZoneMegamenu.Admin'
            ));
        }

        return $result;
    }

    private function saveMenuImages($zmenu)
    {
        if ($zmenu->id) {
            if (isset($_FILES['title_image']) && !empty($_FILES['title_image']['tmp_name'])) {
                if ($error = ImageManager::validateUpload($_FILES['title_image'], Tools::getMaxUploadSize())) {
                    $this->html .= $this->displayError($error);
                } else {
                    $file_name = $zmenu->id.'.'.pathinfo($_FILES['title_image']['name'], PATHINFO_EXTENSION);
                    if (move_uploaded_file($_FILES['title_image']['tmp_name'], $this->local_path.$this->title_img_folder.$file_name)) {
                        $zmenu->title_image = $file_name;
                    } else {
                        $this->html .= $this->displayError($this->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'
                        ));
                    }
                }
            }

            if (isset($_FILES['drop_bgimage']) && !empty($_FILES['drop_bgimage']['tmp_name'])) {
                if ($error = ImageManager::validateUpload($_FILES['drop_bgimage'], Tools::getMaxUploadSize())) {
                    $this->html .= $this->displayError($error);
                } else {
                    $file_name = $zmenu->id.'.'.pathinfo($_FILES['drop_bgimage']['name'], PATHINFO_EXTENSION);
                    if (move_uploaded_file($_FILES['drop_bgimage']['tmp_name'], $this->local_path.$this->bg_img_folder.$file_name)) {
                        $zmenu->drop_bgimage = $file_name;
                    } else {
                        $this->html .= $this->displayError($this->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'
                        ));
                    }
                }
            }

            $zmenu->update(false);
        }
    }

    protected function processSaveDropdown()
    {
        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $id_zdropdown = (int) Tools::getValue('id_zdropdown');
        $zdropdown = new ZDropdown($id_zmenu);
        if ($id_zdropdown) {
            $zdropdown = new ZDropdown($id_zmenu, $id_zdropdown);
        }

        $zdropdown->active = (int) Tools::getValue('active');
        $zdropdown->position = (int) Tools::getValue('position');
        $zdropdown->column = Tools::getValue('column');
        $zdropdown->custom_class = Tools::getValue('custom_class');
        $zdropdown->content_type = Tools::getValue('content_type');
        $zdropdown->categories = Tools::getValue('categories', array());
        $zdropdown->products = Tools::getValue('products', array());
        $zdropdown->manufacturers = Tools::getValue('manufacturers', array());

        $category_options = array();
        $category_options['image'] = (int) Tools::getValue('category_image');
        $category_options['icon'] = (int) Tools::getValue('category_icon');
        $category_options['subcategories'] = (int) Tools::getValue('category_subcategories');
        $zdropdown->category_options = $category_options;

        $manufacturer_options = array();
        $manufacturer_options['layout'] = Tools::getValue('manufacturer_layout');
        $zdropdown->manufacturer_options = $manufacturer_options;

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $static_content = array();
        foreach ($languages as $lang) {
            $static_content[$lang['id_lang']] = Tools::getValue('static_content_'.$lang['id_lang']);
            if (!$static_content[$lang['id_lang']]) {
                $static_content[$lang['id_lang']] = Tools::getValue('static_content_'.$id_lang_default);
            }
        }
        $zdropdown->static_content = $static_content;

        $result = $zdropdown->validateFields(false);
        if ($result) {
            $zdropdown->save();

            if ($id_zdropdown) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Dropdown Content has been updated.',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Dropdown Content has been created successfully.',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Dropdown Content.',
                array(),
                'Modules.ZoneMegamenu.Admin'
            ));
        }

        return $result;
    }

    protected function renderMenuList()
    {
        $zmenus = ZMenu::getList((int) $this->context->language->id);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzmenu&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($zmenus);
        $helper->identifier = 'id_zmenu';
        $helper->table = 'zmenu';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->trans(
            'Mega Menu',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zmenu';
        $helper->position_group_identifier = 0;

        return $helper->generateList($zmenus, $this->getMenuList());
    }

    protected function getMenuList()
    {
        $fields_list = array(
            'id_zmenu' => array(
                'title' => $this->trans(
                    'Menu ID',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_menu',
            ),
            'name' => array(
                'title' => $this->trans(
                    'Name',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zmenu',
            ),
            'drop_column' => array(
                'title' => $this->trans(
                    'Dropdown Columns',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zdropdowncolumn',
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
                    'Displayed',
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
        );

        return $fields_list;
    }

    protected function renderMenuForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezmenu';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getMenuFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $this->smarty->assign(array(
            'menuForm' => $helper->generateForm(array($this->getMenuForm())),
            'dropdownList' => $this->renderDropdownList(),
        ));

        return $this->display(__FILE__, 'views/templates/admin/menu-form.tpl');
    }

    protected function getMenuForm()
    {
        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $zmenu = new ZMenu($id_zmenu, (int) $this->context->language->id);

        $legent_title = $this->trans(
            'Add New Menu',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        if ($id_zmenu) {
            $legent_title = $zmenu->name;
        }

        $list_columns = array();
        $list_columns[0]['id'] = 'drop_0_column';
        $list_columns[0]['value'] = 0;
        $list_columns[0]['label'] = $this->trans(
            'No Dropdown',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        for ($i = 1; $i < 6; ++$i) {
            $list_columns[$i]['id'] = 'drop_'.$i.'_column';
            $list_columns[$i]['value'] = $i;
            $list_columns[$i]['label'] = $i.($i == 1 ? $this->trans(
                'col',
                array(),
                'Modules.ZoneMegamenu.Admin'
            ) : $this->l('cols'));
        }

        $list_positions = array(
            'query' => array(
                array('id' => 'left top', 'name' => $this->trans(
                    'left top',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'left center', 'name' => $this->trans(
                    'left center',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'left bottom', 'name' => $this->trans(
                    'left bottom',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'right top', 'name' => $this->trans(
                    'right top',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'right center', 'name' => $this->trans(
                    'right center',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'right bottom', 'name' => $this->trans(
                    'right bottom',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'center top', 'name' => $this->trans(
                    'center top',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'center center', 'name' => $this->trans(
                    'center center',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                array('id' => 'center bottom', 'name' => $this->trans(
                    'center bottom',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $drop_bgimage_url = false;
        $drop_bgimage_size = false;
        if ($id_zmenu) {
            if ($zmenu->drop_bgimage) {
                $drop_bgimage_url = $this->_path.$this->bg_img_folder.$zmenu->drop_bgimage;
                $drop_bgimage_size = filesize($this->local_path.$this->bg_img_folder.$zmenu->drop_bgimage) / 1000;
            }
        }
        $title_image_url = false;
        $title_image_size = false;
        if ($id_zmenu) {
            if ($zmenu->title_image) {
                $title_image_url = $this->_path.$this->title_img_folder.$zmenu->title_image;
                $title_image_size = filesize($this->local_path.$this->title_img_folder.$zmenu->title_image) / 1000;
            }
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zmenu',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Title',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'name',
                        'lang' => true,
                        'required' => true,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->trans(
                            'Title Image',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'title_image',
                        'hint' => $this->trans(
                            'Upload a image for title menu',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'display_image' => true,
                        'image' => $title_image_url ? '<img src="'.$title_image_url.'" alt="" class="img-thumbnail" style="max-height:50px;" />' : false,
                        'size' => $title_image_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteTitleImage&id_zmenu='.$id_zmenu,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'URL',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'link',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Open URL in new tab',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'link_newtab',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'link_newtab_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'link_newtab_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Displayed',
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
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Label',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'label',
                        'lang' => true,
                        'hint' => $this->trans(
                            'Label for this menu. E.g. SALE, NEW, HOT,...',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->trans(
                            'Label Background Color',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'label_color',
                        'lang' => true,
                        'hint' => $this->trans(
                            'Background color of Label. Default is #e95144. Text color is white.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Dropdown Menu Columns',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'drop_column',
                        'values' => $list_columns,
                        'hint' => $this->trans(
                            'The number of columns of dropdown menu',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Custom CSS Class',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'custom_class',
                        'hint' => $this->trans(
                            'Using it for special stylesheet.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'col' => 5,
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->trans(
                            'Dropdown Background Color',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'drop_bgcolor',
                        'hint' => $this->trans(
                            'The background color for dropdown menu',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->trans(
                            'Dropdown Background Image',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'drop_bgimage',
                        'hint' => $this->trans(
                            'Upload a background image for dropdown menu',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'display_image' => true,
                        'image' => $drop_bgimage_url ? '<img src="'.$drop_bgimage_url.'" alt="" class="img-thumbnail" style="max-height:120px;" />' : false,
                        'size' => $drop_bgimage_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteBackgroundImage&id_zmenu='.$id_zmenu,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Background Image Position',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'bgimage_position',
                        'options' => $list_positions,
                        'hint' => $this->trans(
                            'The starting position of a background image',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Position X',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'position_x',
                        'hint' => $this->trans(
                            'The horizontal position. Negative values are allowed.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'suffix' => 'px',
                        'col' => 5,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Position Y',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'position_y',
                        'hint' => $this->trans(
                            'The vertical position. Negative values are allowed.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'suffix' => 'px',
                        'col' => 5,
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
                        'href' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->trans(
                            'Back',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'icon' => 'process-icon-back',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getMenuFieldsValues()
    {
        $fields_value = array();

        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $zmenu = new ZMenu($id_zmenu);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_name = isset($zmenu->name[$lang['id_lang']]) ? $zmenu->name[$lang['id_lang']] : '';
            $fields_value['name'][$lang['id_lang']] = Tools::getValue('name_'.(int) $lang['id_lang'], $default_name);
            $default_link = isset($zmenu->link[$lang['id_lang']]) ? $zmenu->link[$lang['id_lang']] : '';
            $fields_value['link'][$lang['id_lang']] = Tools::getValue('link_'.(int) $lang['id_lang'], $default_link);
            $default_label = isset($zmenu->label[$lang['id_lang']]) ? $zmenu->label[$lang['id_lang']] : '';
            $fields_value['label'][$lang['id_lang']] = Tools::getValue('label_'.(int) $lang['id_lang'], $default_label);
        }

        $fields_value['id_zmenu'] = $id_zmenu;
        $fields_value['active'] = Tools::getValue('active', $zmenu->active);
        $fields_value['position'] = Tools::getValue('position', $zmenu->position);
        $fields_value['title_image'] = Tools::getValue('title_image', $zmenu->title_image);
        $fields_value['link_newtab'] = Tools::getValue('link_newtab', $zmenu->link_newtab);
        $fields_value['label_color'] = Tools::getValue('label_color', $zmenu->label_color);
        $fields_value['drop_column'] = Tools::getValue('drop_column', $zmenu->drop_column);
        $fields_value['custom_class'] = Tools::getValue('custom_class', $zmenu->custom_class);
        $fields_value['drop_bgcolor'] = Tools::getValue('drop_bgcolor', $zmenu->drop_bgcolor);
        $fields_value['drop_bgimage'] = Tools::getValue('drop_bgimage', $zmenu->drop_bgimage);
        $fields_value['bgimage_position'] = Tools::getValue('bgimage_position', $zmenu->bgimage_position);
        $fields_value['position_x'] = Tools::getValue('position_x', $zmenu->position_x);
        $fields_value['position_y'] = Tools::getValue('position_y', $zmenu->position_y);

        return $fields_value;
    }

    protected function renderDropdownList()
    {
        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $zmenu = new ZMenu($id_zmenu);
        if ((int) $zmenu->drop_column < 1) {
            $msg_type = 'enable_column';
            if (!$id_zmenu) {
                $msg_type = 'save_menu';
            }

            $this->smarty->assign(array(
                'msg_type' => $msg_type,
            ));

            return $this->display(__FILE__, 'views/templates/admin/dropdown-list-message.tpl');
        }

        $zdropdowns = ZDropdown::getList($id_zmenu, (int) $this->context->language->id, false);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzdropdown&id_zmenu='.$id_zmenu.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($zdropdowns);
        $helper->identifier = 'id_zdropdown';
        $helper->table = 'zdropdown';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->trans(
            'Dropdown Contents',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex.'&id_zmenu='.$id_zmenu;
        $helper->position_identifier = 'zdropdown';
        $helper->position_group_identifier = $id_zmenu;

        return $helper->generateList($zdropdowns, $this->getDropdownList());
    }

    protected function getDropdownList()
    {
        $fields_list = array(
            'id_zdropdown' => array(
                'title' => $this->trans(
                    'ID',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_dropdown',
            ),
            'content_type' => array(
                'title' => $this->trans(
                    'Content Type',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zdropdowntype',
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
                //'class' => 'fixed-width-md',
                'position' => true,
                'type' => 'zposition',
            ),
            'active' => array(
                'title' => $this->trans(
                    'Displayed',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'status',
                'type' => 'bool',
                //'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
        );

        return $fields_list;
    }

    protected function renderDropdownForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $id_zmenu = (int) Tools::getValue('id_zmenu');

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezdropdown';
        $helper->currentIndex = $this->currentIndex.'&id_zmenu='.$id_zmenu;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getDropdownFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $form = $helper->generateForm(array($this->getDropdownForm()));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));
        
        return $form;
    }

    protected function getDropdownForm()
    {
        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $zmenu = new ZMenu($id_zmenu, (int) $this->context->language->id);
        $id_zdropdown = (int) Tools::getValue('id_zdropdown');
        $zdropdown = new ZDropdown($id_zmenu, $id_zdropdown, (int) $this->context->language->id);
        $root = Category::getRootCategory();

        $legent_title = $zmenu->name.' > '.$this->trans(
            'Add New Dropdown Content',
            array(),
            'Modules.ZoneMegamenu.Admin'
        );
        if ($id_zdropdown) {
            $legent_title = $zmenu->name.' > '.$this->trans(
                'Edit Dropdown Content',
                array(),
                'Modules.ZoneMegamenu.Admin'
            );
        }

        $list_columns = array();
        for ($i = 1; $i <= $zmenu->drop_column; ++$i) {
            $list_columns[$i]['id'] = 'content_'.$i.'_column';
            $list_columns[$i]['value'] = $i;
            $list_columns[$i]['label'] = $i.'-'.$this->trans(
                'column',
                array(),
                'Modules.ZoneMegamenu.Admin'
            );
        }

        $content_type_options = array(
            'query' => array(
                0 => array('id' => 'none', 'name' => ''),
                1 => array('id' => 'category', 'name' => $this->trans(
                    'Category',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                2 => array('id' => 'product', 'name' => $this->trans(
                    'Product',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                3 => array('id' => 'html', 'name' => $this->trans(
                    'Custom HTML',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
                4 => array('id' => 'manufacturer', 'name' => $this->trans(
                    'Manufacturer',
                    array(),
                    'Modules.ZoneMegamenu.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $manufacturers = Manufacturer::getManufacturers();
        $list_manufacturer = array();
        if ($manufacturers) {
            foreach ($manufacturers as $manufacturer) {
                $list_manufacturer[$manufacturer['id_manufacturer']] = $manufacturer['name'];
            }
        }

        $list_manufacturer_layout = array(
            array('id' => 'logo', 'value' => 'logo', 'label' => "Logo"),
            array('id' => 'name', 'value' => 'name', 'label' => "Name"),
            array('id' => 'logo_name', 'value' => 'logo_name', 'label' => "Logo & Name"),
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
                        'name' => 'id_zdropdown',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Displayed',
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
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Content Columns',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'column',
                        'values' => $list_columns,
                        'hint' => $this->trans(
                            'The number of columns of dropdown content. Maximum value is "Dropdown Menu Columns"',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Custom CSS Class',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'custom_class',
                        'hint' => $this->trans(
                            'Using it for special stylesheet.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'desc' => 'E.g. category-horizontally, small-category-title',
                        'col' => 3,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Content Type',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'content_type',
                        'id' => 'content_type_selectbox',
                        'options' => $content_type_options,
                        'hint' => $this->trans(
                            'Dropdown Content Type.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->trans(
                            'Chooes the Categories',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'categories',
                        'hint' => $this->trans(
                            'You can choose multiple categories',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'categoryBox',
                            'root_category' => $root->id,
                            'use_checkbox' => true,
                            'selected_categories' => $zdropdown->categories,
                        ),
                        'form_group_class' => 'content_type_category categories_tree',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Category Image',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'category_image',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'category_image_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'category_image_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                        'form_group_class' => 'content_type_category',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Category Icon',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'category_icon',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'category_icon_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'category_icon_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                        'form_group_class' => 'content_type_category',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Sub-categories',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'category_subcategories',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'category_subcategories_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'category_subcategories_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                        'form_group_class' => 'content_type_category',
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->trans(
                            'Select the Products',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'products',
                        'ajax_path' => $this->currentIndex.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'hint' => $this->trans(
                            'Begin typing the First Letters of the Product Name, then select the Product from the Drop-down List.',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'form_group_class' => 'content_type_product',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Custom HTML Content',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'static_content',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                        'form_group_class' => 'content_type_html',
                    ),
                    array(
                        'type' => 'manufacturer',
                        'label' => $this->trans(
                            'Select the Manufacturers',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'manufacturers',
                        'list_manufacturer' => $list_manufacturer,
                        'form_group_class' => 'content_type_manufacturer',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Layout',
                            array(),
                            'Modules.ZoneMegamenu.Admin'
                        ),
                        'name' => 'manufacturer_layout',
                        'values' => $list_manufacturer_layout,
                        'form_group_class' => 'content_type_manufacturer',
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
                        'href' => $this->currentIndex.'&updatezmenu&id_zmenu='.$id_zmenu.'&token='.Tools::getAdminTokenLite('AdminModules'),
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

    protected function getDropdownFieldsValues()
    {
        $fields_value = array();

        $id_zmenu = (int) Tools::getValue('id_zmenu');
        $id_zdropdown = (int) Tools::getValue('id_zdropdown');
        $zdropdown = new ZDropdown($id_zmenu, $id_zdropdown);
        $category_options = $zdropdown->category_options;
        $manufacturer_options = $zdropdown->manufacturer_options;

        $fields_value['id_zdropdown'] = $id_zdropdown;
        $fields_value['active'] = Tools::getValue('active', $zdropdown->active);
        $fields_value['position'] = Tools::getValue('position', $zdropdown->position);
        $fields_value['column'] = Tools::getValue('column', $zdropdown->column);
        $fields_value['custom_class'] = Tools::getValue('custom_class', $zdropdown->custom_class);
        $fields_value['content_type'] = Tools::getValue('content_type', $zdropdown->content_type);
        $fields_value['products'] = $zdropdown->getProductsInfoBack($this->context->language->id);
        $fields_value['manufacturers'] = $zdropdown->manufacturers;

        $fields_value['category_image'] = Tools::getValue('category_image', $category_options['image']);
        $fields_value['category_icon'] = Tools::getValue('category_icon', $category_options['icon']);
        $fields_value['category_subcategories'] = Tools::getValue('category_subcategories', $category_options['subcategories']);

        $fields_value['manufacturer_layout'] = Tools::getValue('manufacturer_layout', $manufacturer_options['layout']);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_static_content = isset($zdropdown->static_content[$lang['id_lang']]) ? $zdropdown->static_content[$lang['id_lang']] : '';
            $fields_value['static_content'][$lang['id_lang']] = Tools::getValue('static_content_'.(int) $lang['id_lang'], $default_static_content);
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

    public function hookActionObjectManufacturerAddAfter()
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectManufacturerUpdateAfter()
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectManufacturerDeleteAfter()
    {
        $this->_clearCache('*');
    }

    private function preProcess()
    {
        $title_image_url = $this->getPathUri().$this->title_img_folder;
        $title_image_url = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($title_image_url).$title_image_url;
        $title_image_local = $this->local_path.$this->title_img_folder;
        $zmenus = ZMenu::getListFront($title_image_url, $title_image_local);

        $this->smarty->assign(array(
            'zmenus' => $zmenus,
            'is_rtl' => $this->context->language->is_rtl,
            'ajaxDrodownContentController' => $this->context->link->getModuleLink($this->name, 'menuDropdownContent', array(), true),
        ));
    }

    public function hookDisplayTop()
    {
        if ($this->is_mobile) {
            return;
        }

        $templateFile = 'module:zonemegamenu/views/templates/hook/zonemegamenu.tpl';
        $cacheId = $this->name.'|desktop';

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess();
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayNavFullWidth()
    {
        return $this->hookDisplayTop();
    }

    public function hookDisplayMobileMenu()
    {
        if (!$this->is_mobile) {
            return;
        }
        
        $templateFile = 'module:zonemegamenu/views/templates/hook/zonemegamenu_mobile.tpl';
        $cacheId = $this->name.'|mobile';

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess();
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function getDropdownContent()
    {
        $rtl = $this->context->language->is_rtl;
        $results = array();
        $bg_image_url = $this->getPathUri().$this->bg_img_folder;
        $bg_image_url = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($bg_image_url).$bg_image_url;
        $default_dropdown_bgcolor = '#ffffff';
        $templateFile = 'module:zonemegamenu/views/templates/hook/dropdown-content.tpl';
        $zmenus = ZMenu::getListLight();

        foreach ($zmenus as $menu) {
            $cacheId = $this->name.'|dropdowncontent|'.$menu['id_zmenu'];
            if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
                $menu_dropdowns = false;
                if ($menu['drop_column']) {
                    $menu_dropdowns = ZDropdown::getListFront($menu['id_zmenu']);
                }

                $menu_bgimage = '';
                $menu_bgcolor = '';
                if ($menu['drop_bgimage']) {
                    $menu_bgimage .= 'background-image:url('.$bg_image_url.$menu['drop_bgimage'].');';
                    if ($menu['bgimage_position']) {
                        if ($rtl) {
                            $menu['bgimage_position'] = str_replace(array('left', 'right'), array('right', 'left'), $menu['bgimage_position']);
                        }
                        $menu_bgimage .= 'background-position:'.$menu['bgimage_position'].';';
                        $pos = explode(' ', $menu['bgimage_position']);
                        if ($pos[0] != 'center') {
                            $menu_bgimage .= $pos[0].':'.$menu['position_x'].'px;';
                        }
                        if ($pos[1] != 'center') {
                            $menu_bgimage .= $pos[1].':'.$menu['position_y'].'px;';
                        }
                    }
                }
                if ($menu['drop_bgcolor'] && $menu['drop_bgcolor'] != $default_dropdown_bgcolor) {
                    $menu_bgcolor .= 'background-color:'.$menu['drop_bgcolor'].';';
                }

                $this->smarty->assign(array(
                    'dropdowns' => $menu_dropdowns,
                    'bgimage' => $menu_bgimage,
                    'bgcolor' => $menu_bgcolor,
                ));
            }
            
            $results[$menu['id_zmenu']] = $this->fetch($templateFile, $this->getCacheId($cacheId));
        }

        return $results;
    }
}
