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

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class ZOneBrandLogo extends Module implements WidgetInterface
{
    protected $is_mobile;
    protected $settings = array(
        'enableSlider' => true,
        'enableSliderMobile' => false,
        'autoScroll' => true,
        'showName' => false,
        'showBrandsWithLogos' => false,
        'showBrandsWithProducts' => false,
    );

    public function __construct()
    {
        $this->name = 'zonebrandlogo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Brand Logo',
            array(),
            'Modules.ZoneBrandlogo.Admin'
        );
        $this->description = $this->trans(
            'Displays Manufacturers block with Brand Logo.',
            array(),
            'Modules.ZoneBrandlogo.Admin'
        );

        $this->is_mobile = ($this->context->isMobile()  && !$this->context->isTablet());
    }

    public function install()
    {
        Configuration::updateValue('ZONEBRANDS_SETTINGS', serialize($this->settings));

        return parent::install()
            && $this->registerHook('actionObjectManufacturerAddAfter')
            && $this->registerHook('actionObjectManufacturerUpdateAfter')
            && $this->registerHook('actionObjectManufacturerDeleteAfter')
            && $this->registerHook('displayBottomColumn')
        ;
    }

    public function uninstall()
    {
        Configuration::deleteByName('ZONEBRANDS_SETTINGS');

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
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        $about = $this->about();

        if (Tools::isSubmit('submitSettings')) {
            $this->processSaveSettings();

            return $this->displayConfirmation($this->trans(
                'Settings updated',
                array(),
                'Modules.ZoneBrandlogo.Admin'
            )).$this->renderSettingsForm().$about;
        } else {
            return $this->renderSettingsForm().$about;
        }
    }

    protected function processSaveSettings()
    {
        $settings = array(
            'enableSlider' => (int) Tools::getValue('enableSlider'),
            'enableSliderMobile' => Tools::getValue('enableSliderMobile'),
            'autoScroll' => (int) Tools::getValue('autoScroll'),
            'showName' => (int) Tools::getValue('showName'),
            'showBrandsWithLogos' => (int) Tools::getValue('showBrandsWithLogos'),
            'showBrandsWithProducts' => (int) Tools::getValue('showBrandsWithProducts'),
        );

        Configuration::updateValue('ZONEBRANDS_SETTINGS', serialize($settings));

        $this->_clearCache('*');
    }

    protected function renderSettingsForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettings';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getSettingsFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getSettingsForm()));
    }

    protected function getSettingsForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Block Settings',
                        array(),
                        'Modules.ZoneBrandlogo.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'enableSlider',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enableSlider_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'enableSlider_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Enable Slider on Mobile',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'enableSliderMobile',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enableSliderMobile_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'enableSliderMobile_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Slider Autoplay',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'autoScroll',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'autoScroll_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'autoScroll_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Show Manufacturer Name',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'showName',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'showName_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'showName_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Only Show Manufacturers with Logos',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'showBrandsWithLogos',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'showBrandsWithLogos_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'showBrandsWithLogos_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Only Show Manufacturers with Products',
                            array(),
                            'Modules.ZoneBrandlogo.Admin'
                        ),
                        'name' => 'showBrandsWithProducts',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'showBrandsWithProducts_on',
                                'value' => true,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'showBrandsWithProducts_off',
                                'value' => false,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getSettingsFieldsValues()
    {
        $settings = array_merge(
            $this->settings,
            Tools::unSerialize(Configuration::get('ZONEBRANDS_SETTINGS', null, null, null, 'a:0:{}'))
        );

        $fields_value = array(
            'enableSlider' => Tools::getValue('enableSlider', $settings['enableSlider']),
            'enableSliderMobile' => Tools::getValue('enableSliderMobile', $settings['enableSliderMobile']),
            'autoScroll' => Tools::getValue('autoScroll', $settings['autoScroll']),
            'showName' => Tools::getValue('showName', $settings['showName']),
            'showBrandsWithLogos' => Tools::getValue('showBrandsWithLogos', $settings['showBrandsWithLogos']),
            'showBrandsWithProducts' => Tools::getValue('showBrandsWithProducts', $settings['showBrandsWithProducts']),
        );

        return $fields_value;
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

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        $id_lang = (int) $this->context->language->id;
        $settings = array_merge(
            $this->settings,
            Tools::unSerialize(Configuration::get('ZONEBRANDS_SETTINGS', null, null, null, 'a:0:{}'))
        );
        $brands = Manufacturer::getManufacturers(false, $id_lang, true, false, false, false, false, $settings['showBrandsWithProducts']);
        $new_brands = array();

        if (!ImageType::typeAlreadyExists('manufacturer_default')) {
            $image_type = new ImageType();
            $image_type->name = 'manufacturer_default';
            $image_type->width = 210;
            $image_type->height = 80;
            $image_type->products = false;
            $image_type->categories = false;
            $image_type->suppliers = false;
            $image_type->stores = false;
            $image_type->manufacturers = true;
            $image_type->save();
        }
        $image_size = ImageType::getByNameNType('manufacturer_default', 'manufacturers');

        if ($brands) {
            $filter_brands = array();
            if ($settings['showBrandsWithLogos']) {
                foreach ($brands as $b) {
                    if (file_exists(_PS_MANU_IMG_DIR_.$b['id_manufacturer'].'.jpg')) {
                        $filter_brands[] = $b;
                    }
                }
            } else {
                $filter_brands = $brands;
            }

            $random20brands = array();
            if (count($filter_brands) == 1) {
                $random20brands = array(0);
            }
            if (count($filter_brands) > 1) {
                $random20brands = array_rand($filter_brands, min(18, count($filter_brands)));
            }
            
            foreach ($random20brands as $i) {
                $item = $filter_brands[$i];
                $item['image'] = $this->context->link->getManufacturerImageLink($item['id_manufacturer'], 'manufacturer_default');
                $item['url'] = $this->context->link->getManufacturerLink($item['id_manufacturer'], $item['link_rewrite']);

                if (!$settings['showBrandsWithLogos'] || file_exists(_PS_MANU_IMG_DIR_.$item['id_manufacturer'].'.jpg')) {
                    $new_brands[] = $item;
                }
            }
        }

        $enable_slider = $settings['enableSlider'];
        if ($this->is_mobile && isset($settings['enableSliderMobile'])) {
            $enable_slider = $settings['enableSliderMobile'];
        }

        $this->smarty->assign(array(
            'enableSlider' => $enable_slider,
            'autoplay' => $settings['autoScroll'] ? 'true' : 'false',
            'showName' => $settings['showName'],
            'brands' => $new_brands,
            'hookName' => $hookName,
            'imageSize' => $image_size,
            'mobile_device' => $this->is_mobile,
        ));
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        $templateFile = 'module:zonebrandlogo/views/templates/hook/zonebrandlogo.tpl';
        $cacheId = $this->name.'|'.$hookName.'|'.'device'.(int) $this->is_mobile;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->getWidgetVariables($hookName, $configuration);
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }
}
