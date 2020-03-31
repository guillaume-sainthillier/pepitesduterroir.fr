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

use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

include_once dirname(__FILE__).'/classes/ZSlideshow.php';

class ZOneSlideshow extends Module
{
    protected $slide_img_folder = 'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'slideImages'.DIRECTORY_SEPARATOR;
    protected $slide_thumb_folder = 'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'slideImages'.DIRECTORY_SEPARATOR.'thumbs'.DIRECTORY_SEPARATOR;
    protected $slide_mobile_folder = 'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'slideImages'.DIRECTORY_SEPARATOR.'mobiles'.DIRECTORY_SEPARATOR;
    protected $html = '';
    protected $currentIndex;
    protected $nivo_effects = array(
        array('id' => 'random', 'name' => 'random', 'val' => 1),
        array('id' => 'sliceDown', 'name' => 'sliceDown', 'val' => 1),
        array('id' => 'sliceDownLeft', 'name' => 'sliceDownLeft', 'val' => 1),
        array('id' => 'sliceUp', 'name' => 'sliceUp', 'val' => 1),
        array('id' => 'sliceUpLeft', 'name' => 'sliceUpLeft', 'val' => 1),
        array('id' => 'sliceUpDown', 'name' => 'sliceUpDown', 'val' => 1),
        array('id' => 'sliceUpDownLeft', 'name' => 'sliceUpDownLeft', 'val' => 1),
        array('id' => 'fold', 'name' => 'fold', 'val' => 1),
        array('id' => 'fade', 'name' => 'fade', 'val' => 1),
        array('id' => 'slideInRight', 'name' => 'slideInRight', 'val' => 1),
        array('id' => 'slideInLeft', 'name' => 'slideInLeft', 'val' => 1),
        array('id' => 'boxRandom', 'name' => 'boxRandom', 'val' => 1),
        array('id' => 'boxRain', 'name' => 'boxRain', 'val' => 1),
        array('id' => 'boxRainReverse', 'name' => 'boxRainReverse', 'val' => 1),
        array('id' => 'boxRainGrow', 'name' => 'boxRainGrow', 'val' => 1),
        array('id' => 'boxRainGrowReverse', 'name' => 'boxRainGrowReverse', 'val' => 1),
    );
    protected $nivoSettings = array(
        'layout' => 'wide',
        'caption_effect' => 'opacity',
        'disable_slider' => false,
        'mobile_disable_slider' => false,
        'slices' => 15,
        'boxCols' => 8,
        'boxRows' => 4,
        'animSpeed' => 500,
        'pauseTime' => 5000,
        'startSlide' => 0,
        'directionNav' => true,
        'controlNav' => true,
        'controlNavThumbs' => false,
        'pauseOnHover' => true,
        'manualAdvance' => false,
        'randomStart' => false,
        'effect' => array('random'),
    );
    protected $is_mobile;

    public function __construct()
    {
        $this->name = 'zoneslideshow';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans(
            'Z.One - Nivo Slideshow',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        $this->description = $this->trans(
            'Add a jQuery Nivo slideshow on the homepage.',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );

        $this->is_mobile = ($this->context->isMobile()  && !$this->context->isTablet());

        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
    }

    public function install()
    {
        Configuration::updateGlobalValue('ZONESLIDESHOW_SETTINGS', serialize($this->nivoSettings));

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
            && $this->registerHook('displayTopColumn')
        ;
    }

    public function uninstall()
    {
        Configuration::deleteByName('ZONESLIDESHOW_SETTINGS');

        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zslideshow`,
            `'._DB_PREFIX_.'zslideshow_lang`';

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

        if (Tools::isSubmit('submitSettings')) {
            $this->processSaveSettings();

            return $this->html.$this->renderSlideshowList().$this->renderSettingsForm().$about;
        } elseif (Tools::isSubmit('savezslideshow')) {
            if ($this->processSaveSlideshow()) {
                return $this->html.$this->renderSlideshowList().$this->renderSettingsForm().$about;
            } else {
                return $this->html.$this->renderSlideshowForm();
            }
        } elseif (Tools::isSubmit('addzslideshow') || Tools::isSubmit('updatezslideshow')) {
            return $this->renderSlideshowForm();
        } elseif (Tools::isSubmit('deletezslideshow')) {
            $zslideshow = new ZSlideshow((int) Tools::getValue('id_zslideshow'));
            $zslideshow->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszslideshow')) {
            $this->ajaxStatus('active');
        } elseif (Tools::isSubmit('statusmobilezslideshow')) {
            $this->ajaxStatus('active_mobile');
        } elseif (Tools::getValue('updatePositions') == 'zslideshow') {
            $this->ajaxPositions();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } else {
            return $this->renderSlideshowList().$this->renderSettingsForm().$about;
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

    protected function ajaxPositions()
    {
        $positions = Tools::getValue('zslideshow');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZSlideshow::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function ajaxStatus($field = 'active')
    {
        $id_zslideshow = (int)Tools::getValue('id_zslideshow');
        if (!$id_zslideshow) {
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
            $zslideshow = new ZSlideshow($id_zslideshow);
            $zslideshow->$field = !(int)$zslideshow->$field;
            if ($zslideshow->save()) {
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

    protected function processSaveSettings()
    {
        $settings = array();
        $settings['layout'] = Tools::getValue('layout');
        $settings['disable_slider'] = Tools::getValue('disable_slider');
        $settings['mobile_disable_slider'] = Tools::getValue('mobile_disable_slider');
        $settings['animSpeed'] = Tools::getValue('animSpeed');
        $settings['pauseTime'] = Tools::getValue('pauseTime');
        $settings['directionNav'] = Tools::getValue('directionNav');
        $settings['controlNav'] = Tools::getValue('controlNav');
        $settings['pauseOnHover'] = Tools::getValue('pauseOnHover');
        $settings['manualAdvance'] = Tools::getValue('manualAdvance');
        $settings['randomStart'] = Tools::getValue('randomStart');

        $effects = array();
        foreach ($this->nivo_effects as $effect) {
            if (Tools::getValue('effect_'.$effect['id'], false)) {
                $effects[] = $effect['id'];
            }
        }
        if (empty($effects)) {
            $effects[] = 'random';
        }
        $settings['effect'] = $effects;

        Configuration::updateGlobalValue('ZONESLIDESHOW_SETTINGS', serialize($settings));

        $this->_clearCache('*');

        $this->html .= $this->displayConfirmation($this->trans(
            'Settings updated',
            array(),
            'Modules.ZoneSlideshow.Admin'
        ));
    }

    protected function processSaveSlideshow()
    {
        $zslideshow = new ZSlideshow();
        $id_zslideshow = (int) Tools::getValue('id_zslideshow');
        if ($id_zslideshow) {
            $zslideshow = new ZSlideshow($id_zslideshow);
        }

        $zslideshow->position = (int) Tools::getValue('position');
        $zslideshow->active = (int) Tools::getValue('active');
        $zslideshow->active_mobile = (int) Tools::getValue('active_mobile');

        $zslideshow->related_products = null;
        if (Tools::getValue('related_products')) {
            $zslideshow->related_products = Tools::getValue('related_products');
        }

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $slide_link = array();
        $caption = array();
        $image_name = $zslideshow->image_name;
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            if (!$title[$lang['id_lang']]) {
                $title[$lang['id_lang']] = Tools::getValue('title_'.$id_lang_default);
            }
            $slide_link[$lang['id_lang']] = Tools::getValue('slide_link_'.$lang['id_lang']);
            if (!$slide_link[$lang['id_lang']]) {
                $slide_link[$lang['id_lang']] = Tools::getValue('slide_link_'.$id_lang_default);
            }
            $caption[$lang['id_lang']] = Tools::getValue('caption_'.$lang['id_lang']);
            if (!$caption[$lang['id_lang']]) {
                $caption[$lang['id_lang']] = Tools::getValue('caption_'.$id_lang_default);
            }

            $file = false;
            if (isset($_FILES['image_name_'.$lang['id_lang']]) && !empty($_FILES['image_name_'.$lang['id_lang']]['tmp_name'])) {
                $file = $_FILES['image_name_'.$lang['id_lang']];
            }
            if (!$file && !isset($image_name[$lang['id_lang']]) && isset($image_name[$id_lang_default])) {
                $image_name[$lang['id_lang']] = $image_name[$id_lang_default];
            }

            if ($file) {
                if ($error = ImageManager::validateUpload($file, Tools::getMaxUploadSize())) {
                    $this->html .= $this->displayError($error);
                } else {
                    $file_name = strtotime('now').'.'.pathinfo($file['name'], PATHINFO_EXTENSION);
                    if (move_uploaded_file($file['tmp_name'], $this->local_path.$this->slide_img_folder.$file_name)) {
                        $image_name[$lang['id_lang']] = $file_name;
                        $this->generateThumbs($file_name);
                    } else {
                        $this->html .= $this->displayError($this->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                    }
                }
            }
        }
        $zslideshow->title = $title;
        $zslideshow->slide_link = $slide_link;
        $zslideshow->caption = $caption;
        $zslideshow->image_name = $image_name;
        if (isset($image_name[$id_lang_default])) {
            $zslideshow->image = $image_name[$id_lang_default];
        }
        
        $result = $zslideshow->validateFields(false) && $zslideshow->validateFieldsLang(false);

        if ($result) {
            $zslideshow->save();

            if ($id_zslideshow) {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Slide has been updated.',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->trans(
                    'Slide has been created successfully.',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->trans(
                'An error occurred while attempting to save Slide.',
                array(),
                'Modules.ZoneSlideshow.Admin'
            ));
        }

        return $result;
    }

    private function generateThumbs($image)
    {
        list($tmpWidth, $tmpHeight, $type) = getimagesize($this->local_path.$this->slide_img_folder.$image);

        $thumb_h = 120 * $tmpHeight / $tmpWidth;
        ImageManager::resize(
            $this->local_path.$this->slide_img_folder.$image,
            $this->local_path.$this->slide_thumb_folder.$image,
            120,
            $thumb_h
        );
        $mobile_h = 767 * $tmpHeight / $tmpWidth;
        ImageManager::resize(
            $this->local_path.$this->slide_img_folder.$image,
            $this->local_path.$this->slide_mobile_folder.$image,
            767,
            $mobile_h
        );
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
        $helper->currentIndex = $this->currentIndex;
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
        $layout_values = array(
            array(
                'id' => 'layout_wide',
                'value' => 'wide',
                'label' => $this->trans(
                    'Wide',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                )
            ),
            array(
                'id' => 'layout_boxed',
                'value' => 'boxed',
                'label' => $this->trans(
                    'Boxed',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                )
            ),
        );

        $effect_options = array(
            'query' => $this->nivo_effects,
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans(
                        'Nivo Slider Options',
                        array(),
                        'Modules.ZoneSlideshow.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->trans(
                            'Slideshow Layout',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'layout',
                        'values' => $layout_values,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Disable Slider Effect on Desktop',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'disable_slider',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'disable_slider_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'disable_slider_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Disable the Nivo Slider effect and show all images on Desktop',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Disable Slider Effect on Mobile',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'mobile_disable_slider',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'mobile_disable_slider_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'mobile_disable_slider_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Disable the Nivo Slider effect and show all images on Mobile device',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->trans(
                            'Slide Effect',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'effect',
                        'values' => $effect_options,
                        'col' => 7,
                    ),  
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Animation Speed',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'animSpeed',
                        'col' => 1,
                        'hint' => $this->trans(
                            'Slide transition speed',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Pause Time',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'pauseTime',
                        'col' => 1,
                        'hint' => $this->trans(
                            'How long each slide will show',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Direction Navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'directionNav',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'directionNav_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'directionNav_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Next & Prev navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Control Navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'controlNav',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'controlNav_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'controlNav_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            '1,2,3... navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Pause on Hover',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'pauseOnHover',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'pauseOnHover_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'pauseOnHover_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Stop animation while hovering',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Manual Advance',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'manualAdvance',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'manualAdvance_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'manualAdvance_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Force manual transitions',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans(
                            'Random Start',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'randomStart',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'value' => true,
                                'id' => 'randomStart_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'randomStart_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
                            ),
                        ),
                        'hint' => $this->trans(
                            'Start on a random slide',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
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
            $this->nivoSettings,
            Tools::unSerialize(Configuration::getGlobalValue('ZONESLIDESHOW_SETTINGS'))
        );

        $fields_value = array(
            'layout' => Tools::getValue('layout', $settings['layout']),
            'disable_slider' => Tools::getValue('disable_slider', $settings['disable_slider']),
            'mobile_disable_slider' => Tools::getValue('mobile_disable_slider', $settings['mobile_disable_slider']),
            'animSpeed' => Tools::getValue('animSpeed', $settings['animSpeed']),
            'pauseTime' => Tools::getValue('pauseTime', $settings['pauseTime']),
            'directionNav' => Tools::getValue('directionNav', $settings['directionNav']),
            'controlNav' => Tools::getValue('controlNav', $settings['controlNav']),
            'pauseOnHover' => Tools::getValue('pauseOnHover', $settings['pauseOnHover']),
            'manualAdvance' => Tools::getValue('manualAdvance', $settings['manualAdvance']),
            'randomStart' => Tools::getValue('randomStart', $settings['randomStart']),
        );

        foreach ($this->nivo_effects as $effect) {
            $effect_id = 'effect_'.$effect['id'];
            $current_value = in_array($effect['id'], $settings['effect']);
            $fields_value[$effect_id] = Tools::getValue($effect_id, $current_value);
        }

        return $fields_value;
    }

    protected function renderSlideshowList()
    {
        $slides = ZSlideshow::getList((int) $this->context->language->id);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzslideshow&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($slides);
        $helper->identifier = 'id_zslideshow';
        $helper->table = 'zslideshow';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->trans(
            'Slides List',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zslideshow';
        $helper->position_group_identifier = 0;

        $helper->tpl_vars = array(
            'image_baseurl' => $this->_path.$this->slide_thumb_folder,
        );

        return $helper->generateList($slides, $this->getSlideshowList());
    }

    protected function getSlideshowList()
    {
        $fields_list = array(
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
            'image' => array(
                'title' => $this->trans(
                    'Image',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'type' => 'zimage',
            ),
            'details' => array(
                'title' => $this->trans(
                    'Details',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zdetails',
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

    protected function renderSlideshowForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezslideshow';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getSlideshowFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getSlideshowForm()));
    }

    protected function getSlideshowForm()
    {
        $id_zslideshow = (int) Tools::getValue('id_zslideshow');

        $legent_title = $this->trans(
            'Add New Slide',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        if ($id_zslideshow) {
            $legent_title = $this->trans(
                'Edit Slide',
                array(),
                'Modules.ZoneSlideshow.Admin'
            );
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
                        'name' => 'id_zslideshow',
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
                                'value' => true,
                                'id' => 'active_on',
                                'label' => $this->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'value' => false,
                                'id' => 'active_off',
                                'label' => $this->trans('Disabled', array(), 'Admin.Global')
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
                        'type' => 'text',
                        'label' => $this->trans(
                            'Title',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans(
                            'Link',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'slide_link',
                        'lang' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'file_lang',
                        'label' => $this->trans(
                            'Image',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'image_name',
                        'desc' => $this->trans(
                            'Upload a image from your computer.',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'required' => true,
                        'image_folder' => $this->_path.$this->slide_img_folder,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans(
                            'Caption',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'caption',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->trans(
                            'Related Products',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'related_products',
                        'ajax_path' => AdminController::$currentIndex.'&configure='.$this->name.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'desc' => $this->trans(
                            'You can add up to 2 related products for each slide.',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
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
                            'Back to Slides List',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'icon' => 'process-icon-back',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getSlideshowFormValues()
    {
        $fields_value = array();

        $id_zslideshow = (int) Tools::getValue('id_zslideshow');
        $zslideshow = new ZSlideshow($id_zslideshow);

        $fields_value['id_zslideshow'] = $id_zslideshow;
        $fields_value['active'] = Tools::getValue('active', $zslideshow->active);
        $fields_value['active_mobile'] = Tools::getValue('active_mobile', $zslideshow->active_mobile);
        $fields_value['position'] = Tools::getValue('position', $zslideshow->position);
        $fields_value['image'] = Tools::getValue('image', $zslideshow->image);
        $fields_value['related_products'] = $zslideshow->getProductsAutocompleteInfo($this->context->language->id);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_title = isset($zslideshow->title[$lang['id_lang']]) ? $zslideshow->title[$lang['id_lang']] : '';
            $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int) $lang['id_lang'], $default_title);
            $default_image_name = isset($zslideshow->image_name[$lang['id_lang']]) ? $zslideshow->image_name[$lang['id_lang']] : '';
            $fields_value['image_name'][$lang['id_lang']] = Tools::getValue('image_name_'.(int) $lang['id_lang'], $default_image_name);
            $default_link = isset($zslideshow->slide_link[$lang['id_lang']]) ? $zslideshow->slide_link[$lang['id_lang']] : '';
            $fields_value['slide_link'][$lang['id_lang']] = Tools::getValue('slide_link_'.(int) $lang['id_lang'], $default_link);
            $default_caption = isset($zslideshow->caption[$lang['id_lang']]) ? $zslideshow->caption[$lang['id_lang']] : '';
            $fields_value['caption'][$lang['id_lang']] = Tools::getValue('caption_'.(int) $lang['id_lang'], $default_caption);
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

    protected function getRelatedProducts($array_product_id, $id_lang)
    {
        $products = ZSlideshow::getProductsByArrayId($array_product_id, $id_lang);
        $present_products = array();

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

    protected function preProcess()
    {
        $id_lang = (int) $this->context->language->id;
        $settings = array_merge(
            $this->nivoSettings,
            Tools::unSerialize(Configuration::getGlobalValue('ZONESLIDESHOW_SETTINGS'))
        );
        $slides = ZSlideshow::getList($id_lang, !$this->is_mobile, $this->is_mobile);
        $slideshow = array();
        $img_folder = $this->slide_img_folder;
        if ($this->is_mobile) {
            $img_folder = $this->slide_mobile_folder;
        }

        if ($slides) {
            foreach ($slides as $slide) {
                if (isset($slide['image_name'])) {
                    $slide['image'] = $slide['image_name'];
                }
                list($tmpWidth, $tmpHeight, $type) = getimagesize($this->local_path.$img_folder.$slide['image']);
                $slide['image_width'] = $tmpWidth;
                $slide['image_height'] = $tmpHeight;

                if ($this->is_mobile) {
                    $slide['related_products'] = false;
                } else {
                    $slide['related_products'] = $this->getRelatedProducts($slide['related_products'], $id_lang);
                }

                $slideshow[] = $slide;
            }
        }

        if ($this->is_mobile) {
            $settings['effect'] = 'fade';
        } else {
            $settings['effect'] = implode(',', $settings['effect']);
        }

        $show_all_images = false;
        if (!$this->is_mobile && isset($settings['disable_slider']) && $settings['disable_slider']) {
            $show_all_images = true;
        }
        if ($this->is_mobile && isset($settings['mobile_disable_slider']) && $settings['mobile_disable_slider']) {
            $show_all_images = true;
        }
        if (count($slideshow) < 2) {
            $show_all_images = true;
        }
        
        $this->smarty->assign(array(
            'aslides' => $slideshow,
            'showAllImages' => $show_all_images,
            'settings' => $settings,
            'image_baseurl' => Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($this->_path.$img_folder).$this->_path.$img_folder,
            'thumb_baseurl' => Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($this->_path.$this->slide_thumb_folder).$this->_path.$this->slide_thumb_folder,
        ));
    }

    public function hookDisplayHome()
    {
        $templateFile = 'module:zoneslideshow/views/templates/hook/zoneslideshow.tpl';
        $cacheId = $this->name.'|'.'device'.(int) $this->is_mobile;

        if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
            $this->preProcess();
        }

        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function hookDisplayTopColumn()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }

        return $this->hookDisplayHome();
    }
}
