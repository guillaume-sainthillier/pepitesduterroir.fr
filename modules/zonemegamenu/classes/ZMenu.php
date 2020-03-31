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

class ZMenu extends ObjectModel
{
    public $id;
    public $id_zmenu;
    public $id_shop;
    public $name;
    public $active = 1;
    public $position;
    public $title_image;
    public $link;
    public $link_newtab = 0;
    public $label;
    public $label_color = '#e95144';
    public $drop_column = 0;
    public $custom_class;
    public $drop_bgcolor = '#ffffff';
    public $drop_bgimage;
    public $bgimage_position = 'right bottom';
    public $position_x = 0;
    public $position_y = 0;

    public static $definition = array(
        'table' => 'zmenu',
        'primary' => 'id_zmenu',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'label_color' => array('type' => self::TYPE_STRING, 'validate' => 'isColor', 'size' => 32),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'title_image' => array('type' => self::TYPE_STRING, 'size' => 128),
            'link_newtab' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'drop_column' => array('type' => self::TYPE_INT),
            'custom_class' => array('type' => self::TYPE_STRING, 'size' => 254),
            'drop_bgcolor' => array('type' => self::TYPE_STRING, 'validate' => 'isColor', 'size' => 32),
            'drop_bgimage' => array('type' => self::TYPE_STRING, 'size' => 128),
            'bgimage_position' => array('type' => self::TYPE_STRING, 'size' => 50),
            'position_x' => array('type' => self::TYPE_INT),
            'position_y' => array('type' => self::TYPE_INT),
            'name' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml','required' => true, 'size' => 254),
            'link' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrlOrEmpty', 'size' => 254),
            'label' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
        ),
    );

    public function __construct($id_zmenu = null, $id_lang = null)
    {
        parent::__construct($id_zmenu, $id_lang);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
        }

        if (!$id_zmenu && Context::getContext()->language->is_rtl) {
            $this->bgimage_position = 'left bottom';
        }
    }

    public static function getList($id_lang, $active = false)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : (int) $id_lang;
        $id_shop = Context::getContext()->shop->id;

        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zmenu` m
            LEFT JOIN `'._DB_PREFIX_.'zmenu_lang` ml ON m.`id_zmenu` = ml.`id_zmenu`
            WHERE m.`id_shop` = '.(int) $id_shop.'
            AND `id_lang` = '.(int) $id_lang.'
            '.($active ? 'AND `active` = 1' : '').'
            GROUP BY m.`id_zmenu`
            ORDER BY m.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public static function getListFront($title_image_url = '', $title_image_local = '')
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $result = self::getList($id_lang, true);
        
        $zmenus = false;

        if ($result) {
            $default_label_color = '#e95144';
            $cat_syntax = '#category=';
            $cms_syntax = '#page=';
            $page_controller = array(
                'prices-drop',
                'new-products',
                'best-sales',
                'contact',
                'sitemap',
                'stores',
                'authentication',
                'my-account',
                'manufacturer',
                'supplier',
            );

            $zmenus = array();
            $total_dropdown = self::getTotalDropdown();

            foreach ($result as $menu) {
                $menu['dropdowns'] = false;
                if (isset($total_dropdown[$menu['id_zmenu']])) {
                    $menu['dropdowns'] = $total_dropdown[$menu['id_zmenu']];
                }

                if ($menu['label_color'] == $default_label_color) {
                    $menu['label_color'] = false;
                }

                if ($menu['name'] && $menu['name'][0] === '#') {
                    if (Tools::strpos($menu['name'], $cat_syntax) === 0) {
                        $id_category = Tools::substr($menu['name'], Tools::strlen($cat_syntax));
                        $object_category = new Category((int) $id_category, $id_lang);
                        if (Validate::isLoadedObject($object_category)) {
                            $menu['name'] = $object_category->name;
                        }
                    }
                    if (Tools::strpos($menu['name'], $cms_syntax) === 0) {
                        $id_cms = Tools::substr($menu['name'], Tools::strlen($cms_syntax));
                        $object_cms = new CMS((int) $id_cms, $id_lang);
                        if (Validate::isLoadedObject($object_cms)) {
                            $menu['name'] = $object_cms->meta_title;
                        }
                    }
                }

                if ($menu['link'] && $menu['link'][0] === '#') {
                    if (Tools::strpos($menu['link'], $cat_syntax) === 0) {
                        $id_category = Tools::substr($menu['link'], Tools::strlen($cat_syntax));
                        $object_category = new Category((int) $id_category, $id_lang);
                        if (Validate::isLoadedObject($object_category)) {
                            $menu['link'] = $context->link->getCategoryLink(
                                $object_category,
                                $object_category->link_rewrite
                            );
                        }
                    }
                    if (Tools::strpos($menu['link'], $cms_syntax) === 0) {
                        $id_cms = Tools::substr($menu['link'], Tools::strlen($cms_syntax));
                        if ($id_cms == 'home') {
                            $menu['link'] = $context->shop->getBaseURL(true, true);
                        } elseif (in_array($id_cms, $page_controller)) {
                            $menu['link'] = $context->link->getPageLink($id_cms);
                        } else {
                            $object_cms = new CMS((int) $id_cms, $id_lang);
                            if (Validate::isLoadedObject($object_cms)) {
                                $menu['link'] = $context->link->getCMSLink(
                                    $object_cms,
                                    $object_cms->link_rewrite
                                );
                            }
                        }
                    }
                }

                if ($menu['title_image']) {
                    list($tmpWidth, $tmpHeight, $type) = getimagesize($title_image_local.$menu['title_image']);
                    $menu['title_image'] = array(
                        'url' => $title_image_url.$menu['title_image'],
                        'width' => $tmpWidth,
                        'height' => $tmpHeight,
                    );
                }

                $zmenus[] = $menu;
            }
        }

        return $zmenus;
    }

    public static function getListLight()
    {
        $id_shop = Context::getContext()->shop->id;

        $query = 'SELECT m.`id_zmenu`, m.`drop_column`, m.`drop_bgcolor`, m.`drop_bgimage`, m.`bgimage_position`, m.`position_x`, m.`position_y`
            FROM `'._DB_PREFIX_.'zmenu` m
            WHERE m.`id_shop` = '.(int) $id_shop.'
            AND `active` = 1';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public static function getTotalDropdown()
    {
        $query = 'SELECT d.`id_zmenu`, COUNT(d.`id_zdropdown`) as total
            FROM `'._DB_PREFIX_.'zdropdown` d
            WHERE `active` = 1
            GROUP BY d.`id_zmenu`';

        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $result = array();
        foreach ($rows as $row) {
            $result[$row['id_zmenu']] = $row['total'];
        }

        return $result;
    }

    public function getMaxPosition()
    {
        $id_shop = Context::getContext()->shop->id;
        $query = 'SELECT MAX(m.`position`)
            FROM `'._DB_PREFIX_.'zmenu` m
            WHERE m.`id_shop` = '.(int) $id_shop;

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zmenu, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zmenu`
			SET `position` = '.(int) $position.'
			WHERE `id_zmenu` = '.(int) $id_zmenu;

        Db::getInstance()->execute($query);
    }
}
