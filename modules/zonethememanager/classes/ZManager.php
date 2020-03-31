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

class ZManager extends ObjectModel
{
    public $id;
    public $id_zthememanager;
    public $id_shop;
    public $general_settings;
    public $category_settings;
    public $product_settings;
    public $svg_logo;
    public $header_top;
    public $header_top_bg_color = '#f9f2e8';
    public $header_phone;
    public $header_save_date;
    public $footer_cms_links;
    public $footer_about_us;
    public $footer_static_links;
    public $footer_bottom;
    public $cookie_message;

    public static $definition = array(
        'table' => 'zthememanager',
        'primary' => 'id_zthememanager',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'general_settings' => array('type' => self::TYPE_STRING, 'validate' => 'isAnything'),
            'category_settings' => array('type' => self::TYPE_STRING, 'validate' => 'isAnything'),
            'product_settings' => array('type' => self::TYPE_STRING, 'validate' => 'isAnything'),
            'svg_logo' => array('type' => self::TYPE_HTML, 'validate' => 'isAnything'),
            'header_top' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'header_top_bg_color' => array('type' => self::TYPE_STRING, 'size' => 50, 'validate' => 'isColor'),
            'header_phone' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'header_save_date' => array('type' => self::TYPE_INT, 'validate' => 'isAnything'),
            'footer_cms_links' => array('type' => self::TYPE_STRING, 'size' => 254, 'validate' => 'isAnything'),
            'footer_about_us' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'footer_static_links' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'footer_bottom' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'cookie_message' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
        ),
    );

    public function __construct($id_zthememanager = null, $id_lang = null)
    {
        parent::__construct($id_zthememanager, $id_lang);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        $gst = Tools::unSerialize($this->general_settings);
        if (!$gst) {
            $gst = array();
        }
        $default_gst = array(
            'layout' => 'wide',
            'boxed_bg_color' => '#bdbdbd',
            'boxed_bg_img' => false,
            'boxed_bg_img_style' => 'stretch',
            'svg_logo' => false,
            'svg_width' => '150',
            'lazy_loading' => 1,
            'sticky_menu' => 1,
            'sticky_mobile' => 1,
            'quickview' => 1,
            'scroll_top' => 1,
            'progress_bar' => 1,
            'sidebar_cart' => 1,
            'sidebar_navigation' => 1,
            'sidebar_categories' => 'ALL',
            'mobile_menu' => 'categorytree',
        );
        $this->general_settings = array_merge($default_gst, $gst);

        $cst = Tools::unSerialize($this->category_settings);
        if (!$cst) {
            $cst = array();
        }
        $default_cst = array(
            'show_image' => 1,
            'show_description' => 1,
            'expand_description' => 1,
            'show_subcategories' => 1,
            'product_grid_columns' => 3,
            'buy_in_new_line' => 0,
            'default_product_view' => 'grid',
        );
        $this->category_settings = array_merge($default_cst, $cst);

        $pst = Tools::unSerialize($this->product_settings);
        if (!$pst) {
            $pst = array();
        }
        $default_pst = array(
            'product_info_layout' => 'normal',
            'product_add_to_cart_layout' => 'normal',
            'product_actions_position' => 'left',
            'product_image_zoom' => 1,
            'product_countdown' => 1,
            'product_attributes_layout' => 'default',
            'combination_price' => true,
            'combination_quantity' => false,
            'combination_separator' => ' + ',
        );
        $this->product_settings = array_merge($default_pst, $pst);

        if ($this->footer_cms_links) {
            $this->footer_cms_links = explode(',', $this->footer_cms_links);
        } else {
            $this->footer_cms_links = array();
        }
    }

    public function save($null_values = false, $autodate = true)
    {
        $this->footer_cms_links = implode(',', $this->footer_cms_links);
        $this->general_settings = serialize($this->general_settings);
        $this->category_settings = serialize($this->category_settings);
        $this->product_settings = serialize($this->product_settings);

        return (int) $this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    public static function getSettingsByShop($id_lang = null)
    {
        $id_shop = Context::getContext()->shop->id;

        $query = 'SELECT id_zthememanager
            FROM `'._DB_PREFIX_.'zthememanager` m
            WHERE m.`id_shop` = '.(int) $id_shop;

        $id_zthememanager = (int) Db::getInstance()->getValue($query);

        if ($id_zthememanager) {
            return new self($id_zthememanager, $id_lang);
        } else {
            return new self();
        }
    }

    public static function getStyleClassesByShop()
    {
        $id_shop = Context::getContext()->shop->id;
        $classes = '';

        $zcolorsfonts_content = Db::getInstance()->getValue(
            'SELECT `content`
            FROM `'._DB_PREFIX_.'zcolorsfonts` m
            WHERE m.`id_shop` = '.(int) $id_shop
        );

        if ($zcolorsfonts_content) {
            $sc = Tools::unSerialize($zcolorsfonts_content);
            if (isset($sc['disable_border_radius']) && (int) $sc['disable_border_radius']) {
                $classes .= ' remove-border-radius';
            }
            if (isset($sc['disable_box_shadow']) && (int) $sc['disable_box_shadow']) {
                $classes .= ' remove-box-shadow';
            }
            if (isset($sc['background_block_title']) && (int) $sc['background_block_title']) {
                $classes .= ' background-for-title background-for-tab-title background-for-column-title';
            }
        }

        return $classes;
    }
}
