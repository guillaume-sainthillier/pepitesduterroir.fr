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

use PrestaShop\PrestaShop\Core\Product\ProductExtraContent;

include_once dirname(__FILE__).'/classes/ZProductExtraField.php';

class ZOneProductAdditional extends Module
{
    public $html = '';
    protected $image_folder = 'views/img/';

    public function __construct()
    {
        $this->name = 'zoneproductadditional';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Product Extra Fields',
            array(),
            'Modules.ZoneProductadditional.Admin'
        );
        $this->description = $this->trans(
            'Product Extra Fields on Product Page',
            array(),
            'Modules.ZoneProductadditional.Admin'
        );

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
            && $this->registerHook('displayProductExtraContent')
            && $this->registerHook('displayProduct3rdColumn')
            && $this->registerHook('displayAfterProductThumbs')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zproduct_extra_field`,
            `'._DB_PREFIX_.'zproduct_extra_field_lang`';

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

    protected function mainModulePage()
    {
        return $this->renderList().$this->about();
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        if (Tools::isSubmit('savezproduct_extra_field')) {
            if ($this->processSaveExtraField()) {
                return $this->html.$this->mainModulePage();
            } else {
                return $this->html.$this->renderForm();
            }
        } elseif (Tools::isSubmit('addzproduct_extra_field') || Tools::isSubmit('updatezproduct_extra_field')) {
            return $this->renderForm();
        } elseif (Tools::isSubmit('deletezproduct_extra_field')) {
            $product_extrafield = new ZProductExtraField((int) Tools::getValue('id_zproduct_extra_field'));
            $product_extrafield->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszproduct_extra_field')) {
            $this->ajaxStatus();
        } elseif (Tools::getValue('updatePositions') == 'zproduct_extra_field') {
            $this->ajaxPositions();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } elseif (Tools::isSubmit('deleteTitleImage')) {
            $id_zproduct_extra_field = (int) Tools::getValue('id_zproduct_extra_field');
            $product_extrafield = new ZProductExtraField($id_zproduct_extra_field);
            if ($product_extrafield->title_image) {
                $image_path = $this->local_path.$this->image_folder.$product_extrafield->title_image;

                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $product_extrafield->title_image = null;
                $product_extrafield->update(false);
                $this->_clearCache('*');
            }

            Tools::redirectAdmin($this->currentIndex.'&id_zproduct_extra_field='.$id_zproduct_extra_field.'&updatezproduct_extra_field&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
        } else {
            return $this->mainModulePage();
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

    protected function ajaxStatus()
    {
        $id_zproduct_extra_field = (int)Tools::getValue('id_zproduct_extra_field');
        if (!$id_zproduct_extra_field) {
            die(Tools::jsonEncode(array('success' => false, 'error' => true, 'text' => $this->trans(
                'Failed to update the status',
                array(),
                'Admin.Notifications.Error'
            ))));
        } else {
            $product_extrafield = new ZProductExtraField($id_zproduct_extra_field);
            $product_extrafield->active = !(int)$product_extrafield->active;
            if ($product_extrafield->save()) {
                $this->_clearCache('*');
                die(Tools::jsonEncode(array('success' => true, 'text' => $this->trans(
                    'The status has been updated successfully',
                    array(),
                    'Admin.Notifications.Success'
                ))));
            } else {
                die(Tools::jsonEncode(array('success' => false, 'error' => true, 'text' => $this->trans(
                    'Failed to update the status',
                    array(),
                    'Admin.Notifications.Error'
                ))));
            }
        }
    }

    protected function ajaxPositions()
    {
        $positions = Tools::getValue('zproduct_extra_field');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZProductExtraField::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function processSaveExtraField()
    {
        $product_extrafield = new ZProductExtraField();
        $id_zproduct_extra_field = (int) Tools::getValue('id_zproduct_extra_field');
        if ($id_zproduct_extra_field) {
            $product_extrafield = new ZProductExtraField($id_zproduct_extra_field);
        }

        $product_extrafield->position = Tools::getValue('position');
        $product_extrafield->active = (int) Tools::getValue('active');
        $product_extrafield->hook = Tools::getValue('hook');
        $product_extrafield->popup = (int) Tools::getValue('popup');
        $product_extrafield->popup_width = Tools::getValue('popup_width');
        $product_extrafield->scope = Tools::getValue('scope');
        $product_extrafield->custom_class = Tools::getValue('custom_class');
        $product_extrafield->categories = implode(',', Tools::getValue('categories', array()));
        $product_extrafield->products = implode(',', Tools::getValue('products', array()));
        $product_extrafield->manufacturers = implode(',', Tools::getValue('manufacturers', array()));

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $content = array();
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
            if (!$content[$lang['id_lang']]) {
                $content[$lang['id_lang']] = Tools::getValue('content_'.$id_lang_default);
            }
        }

        $product_extrafield->title = $title;
        $product_extrafield->content = $content;

        if (isset($_FILES['title_image']) && isset($_FILES['title_image']['tmp_name']) && !empty($_FILES['title_image']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['title_image'], Tools::convertBytes(ini_get('upload_max_filesize')))) {
                $this->html .= $this->displayError($error);
            } else {
                if (move_uploaded_file($_FILES['title_image']['tmp_name'], $this->local_path.$this->image_folder.$_FILES['title_image']['name'])) {
                    $product_extrafield->title_image = $_FILES['title_image']['name'];
                } else {
                    $this->html .= $this->displayError($this->trans(
                        'File upload error.',
                        array(),
                        'Modules.ZoneProductadditional.Admin'
                    ));
                }
            }
        }

        $result = $product_extrafield->validateFields(false) && $product_extrafield->validateFieldsLang(false);

        if ($result) {
            $product_extrafield->save();
            $this->_clearCache('*');

            if ($id_zproduct_extra_field) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Field has been updated.',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Field has been created successfully.',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ));
            }
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save.',
                array(),
                'Modules.ZoneProductadditional.Admin'
            ));
        }

        return $result;
    }

    public function renderList()
    {
        $fields_list = array(
            'id_zproduct_extra_field' => array(
                'title' => $this->trans(
                    'Field ID',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_zproduct_extra_field',
            ),
            'title' => array(
                'title' => $this->trans(
                    'Title',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ),
                'orderby' => false,
                'search' => false,
            ),
            'hook' => array(
                'title' => $this->trans(
                    'Hook',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ),
                'orderby' => false,
                'search' => false,
            ),
            'scope' => array(
                'title' => $this->trans(
                    'Displayed in',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                ),
                'orderby' => false,
                'search' => false,
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

        $extra_fields = ZProductExtraField::getList((int) $this->context->language->id);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzproduct_extra_field&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($extra_fields);
        $helper->identifier = 'id_zproduct_extra_field';
        $helper->table = 'zproduct_extra_field';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->title = $this->trans(
            'Product Extra Fields',
            array(),
            'Modules.ZoneProductadditional.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zproduct_extra_field';
        $helper->position_group_identifier = 0;

        return $helper->generateList($extra_fields, $fields_list);
    }

    protected function renderForm()
    {
        $id_zproduct_extra_field = (int) Tools::getValue('id_zproduct_extra_field');
        $product_extrafield = new ZProductExtraField($id_zproduct_extra_field);
        $root = Category::getRootCategory();
        $selected_categories = ZProductExtraField::getCategoriesByIdField($id_zproduct_extra_field);

        $field_category_tree = array(
            'type' => 'categories',
            'label' => $this->trans(
                'Select the Categories',
                array(),
                'Modules.ZoneProductadditional.Admin'
            ),
            'name' => 'categories',
            'desc' => $this->trans(
                'Mark the boxes of categories to which this field applies.',
                array(),
                'Modules.ZoneProductadditional.Admin'
            ),
            'tree' => array(
                'use_search' => false,
                'id' => 'categoryBox',
                'root_category' => $root->id,
                'use_checkbox' => true,
                'selected_categories' => $selected_categories,
            ),
            'col' => 7,
        );

        $manufacturers = Manufacturer::getManufacturers();
        $list_manufacturer = array();
        if ($manufacturers) {
            foreach ($manufacturers as $manufacturer) {
                $list_manufacturer[$manufacturer['id_manufacturer']] = $manufacturer['name'];
            }
        }

        $legent_title = $this->trans(
            'Add New Field',
            array(),
            'Modules.ZoneProductadditional.Admin'
        );
        if ($id_zproduct_extra_field) {
            $legent_title = $this->trans(
                'Edit Field',
                array(),
                'Modules.ZoneProductadditional.Admin'
            );
        }

        $image_url = false;
        $image_size = false;
        if ($id_zproduct_extra_field) {
            if ($product_extrafield->title_image) {
                $image_url = $this->_path.$this->image_folder.$product_extrafield->title_image;
                $image_size = filesize($this->local_path.$this->image_folder.$product_extrafield->title_image) / 1000;
            }
        }

        $hook_options = array(
            'query' => array(
                array('id' => 'ProductAdditionalInfo', 'name' => 'ProductAdditionalInfo'),
                array('id' => 'ProductExtraContent', 'name' => 'ProductExtraContent'),
                array('id' => 'AfterProductThumbs', 'name' => 'AfterProductThumbs'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $scope_options = array(
            'query' => array(
                array('id' => 'All Products', 'name' => $this->trans(
                    'All Products',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                )),
                array('id' => 'Specific Categories', 'name' => $this->trans(
                    'Specific Categories',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                )),
                array('id' => 'Specific Manufacturers', 'name' => $this->trans(
                    'Specific Manufacturers',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                )),
                array('id' => 'Specific Products', 'name' => $this->trans(
                    'Specific Products',
                    array(),
                    'Modules.ZoneProductadditional.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zproduct_extra_field',
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
                        'type' => 'text',
                        'label' => $this->trans(
                            'Title',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->trans(
                            'Title Image',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'title_image',
                        'display_image' => true,
                        'image' => $image_url ? '<img src="'.$image_url.'" alt="" class="img-thumbnail" style="max-width:100px;" />' : false,
                        'size' => $image_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteTitleImage&id_zproduct_extra_field='.$id_zproduct_extra_field,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Position',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'hook',
                        'options' => $hook_options,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Display content in Popup',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'popup',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'popup_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'popup_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Popup Width',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'popup_width',
                        'suffix' => 'px',
                        'col' => 1,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Content',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'content',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Custom CSS Class',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'custom_class',
                        'hint' => $this->trans(
                            'Using it for special stylesheet.',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'col' => 3,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans(
                            'Displayed in',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'scope',
                        'id' => 'scope_selectbox',
                        'options' => $scope_options,
                    ),
                    $field_category_tree,
                    array(
                        'type' => 'manufacturer',
                        'label' => $this->trans(
                            'Select the Manufacturers',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'manufacturers',
                        'list_manufacturer' => $list_manufacturer,
                        'id' => 'manufacturerBox',
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->trans(
                            'Select the Products',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'name' => 'products',
                        'id' => 'productBox',
                        'ajax_path' => $this->currentIndex.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'desc' => $this->trans(
                            'Begin typing the First Letters of the Product Name, then select the Product from the Drop-down List.',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'col' => 7,
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
                            'Back to list',
                            array(),
                            'Modules.ZoneProductadditional.Admin'
                        ),
                        'icon' => 'process-icon-back',
                    ),
                ),
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezproduct_extra_field';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getFormFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $form = $helper->generateForm(array($fields_form));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));
        
        return $form;
    }

    protected function getFormFieldsValues()
    {
        $fields_value = array();

        $id_zproduct_extra_field = (int) Tools::getValue('id_zproduct_extra_field');
        $product_extrafield = new ZProductExtraField($id_zproduct_extra_field);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields_value['title'][$lang['id_lang']] = Tools::getValue(
                'title_'.(int) $lang['id_lang'],
                $product_extrafield->title[$lang['id_lang']]
            );
            $fields_value['content'][$lang['id_lang']] = Tools::getValue(
                'content_'.(int) $lang['id_lang'],
                $product_extrafield->content[$lang['id_lang']]
            );
        }

        $fields_value['id_zproduct_extra_field'] = $id_zproduct_extra_field;
        $fields_value['active'] = Tools::getValue('active', $product_extrafield->active);
        $fields_value['hook'] = Tools::getValue('hook', $product_extrafield->hook);
        $fields_value['popup'] = Tools::getValue('popup', $product_extrafield->popup);
        $fields_value['popup_width'] = Tools::getValue('popup_width', $product_extrafield->popup_width);
        $fields_value['title_image'] = Tools::getValue('title_image', $product_extrafield->title_image);
        $fields_value['scope'] = Tools::getValue('scope', $product_extrafield->scope);
        $fields_value['position'] = Tools::getValue('position', $product_extrafield->position);
        $fields_value['custom_class'] = Tools::getValue('custom_class', $product_extrafield->custom_class);
        $fields_value['products'] = ZProductExtraField::getProductsByIdField($id_zproduct_extra_field);
        $fields_value['manufacturers'] = ZProductExtraField::getManufacturersByIdField($id_zproduct_extra_field);

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

    public function hookDisplayProductExtraContent($params)
    {
        $id_product = (int) Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $extra_fields = ZProductExtraField::getFieldsByProductId($id_product, 'ProductExtraContent');

        $result = array();
        if ($extra_fields) {
            foreach ($extra_fields as $row) {
                $extra_content = new ProductExtraContent();
                $extra_content->setTitle($row['title']);
                $extra_content->setContent($row['content']);

                $result[] = $extra_content;
            }
        }

        return $result;
    }

    protected function preProcessProductAdditional($id_product, $hook)
    {
        $extra_fields = ZProductExtraField::getFieldsByProductId($id_product, $hook);

        $image_path = $this->_path.$this->image_folder;
        $image_path = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($image_path).$image_path;

        $this->smarty->assign(array(
            'extraFields' => $extra_fields,
            'image_path' => $image_path,
            'hookName' => $hook,
        ));
    }

    public function hookDisplayProduct3rdColumn()
    {
        $id_product = (int) Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $templateFile = 'module:zoneproductadditional/views/templates/hook/zone_product_additional.tpl';
        $cacheId = $this->name.'|additional|'.$id_product;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcessProductAdditional($id_product, 'ProductAdditionalInfo');
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayAfterProductThumbs()
    {
        $id_product = (int) Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $templateFile = 'module:zoneproductadditional/views/templates/hook/zone_product_additional.tpl';
        $cacheId = $this->name.'|leftcolumn|'.$id_product;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcessProductAdditional($id_product, 'AfterProductThumbs');
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }
}
