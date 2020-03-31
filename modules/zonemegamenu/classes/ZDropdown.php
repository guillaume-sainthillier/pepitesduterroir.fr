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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class ZDropdown extends ObjectModel
{
    public $id;
    public $id_zdropdown;
    public $id_zmenu;
    public $active = 1;
    public $position;
    public $column = 1;
    public $custom_class;
    public $content_type; // none, category, product, html, manufacturer

    public $categories = 'a:0:{}';
    public $products = 'a:0:{}';
    public $manufacturers = 'a:0:{}';
    public $static_content;

    public $category_options;
    public $manufacturer_options;

    public static $definition = array(
        'table' => 'zdropdown',
        'primary' => 'id_zdropdown',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_zmenu' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'column' => array('type' => self::TYPE_INT),
            'custom_class' => array('type' => self::TYPE_STRING, 'size' => 254),
            'content_type' => array('type' => self::TYPE_STRING, 'size' => 50),
            'categories' => array('type' => self::TYPE_STRING),
            'products' => array('type' => self::TYPE_STRING),
            'manufacturers' => array('type' => self::TYPE_STRING),
            'static_content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isAnything'),
            'category_options' => array('type' => self::TYPE_STRING),
            'manufacturer_options' => array('type' => self::TYPE_STRING),
        ),
    );

    public function __construct($id_zmenu, $id_zdropdown = null, $id_lang = null)
    {
        parent::__construct($id_zdropdown, $id_lang);

        if ($id_zmenu) {
            $this->id_zmenu = $id_zmenu;
        }

        $this->categories = Tools::unSerialize($this->categories);
        $this->products = Tools::unSerialize($this->products);
        $this->manufacturers = Tools::unSerialize($this->manufacturers);

        $this->category_options = $this->initCategoryOptions($this->category_options);
        $this->manufacturer_options = $this->initManufacturerOptions($this->manufacturer_options);

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
        }
    }

    public static function initCategoryOptions($str)
    {
        $options = Tools::unSerialize($str);
        if (!$options) {
            $options = array();
        }
        $default_options = array(
            'image' => 0,
            'icon' => 0,
            'subcategories' => 1,
        );

        return array_merge($default_options, $options);
    }

    public static function initManufacturerOptions($str)
    {
        $options = Tools::unSerialize($str);
        if (!$options) {
            $options = array();
        }
        $default_options = array(
            'layout' => 'logo',
        );

        return array_merge($default_options, $options);
    }

    public function save($null_values = false, $autodate = true)
    {
        $this->categories = serialize($this->categories);
        $this->products = serialize($this->products);
        $this->manufacturers = serialize($this->manufacturers);
        
        $this->category_options = serialize($this->category_options);
        $this->manufacturer_options = serialize($this->manufacturer_options);

        return (int) $this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    public static function getList($id_zmenu, $id_lang = null, $active = true)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : $id_lang;

        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zdropdown` d
            LEFT JOIN `'._DB_PREFIX_.'zdropdown_lang` dl ON d.`id_zdropdown` = dl.`id_zdropdown`
            WHERE d.`id_zmenu` = '.(int) $id_zmenu.'
            AND `id_lang` = '.(int) $id_lang.'
            '.($active ? 'AND `active` = 1' : '').'
            GROUP BY d.`id_zdropdown`
            ORDER BY d.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public function getMaxPosition()
    {
        $query = 'SELECT MAX(d.`position`)
            FROM `'._DB_PREFIX_.'zdropdown` d
            WHERE d.`id_zmenu` = '.(int) $this->id_zmenu;

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zdropdown, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zdropdown`
			SET `position` = '.(int) $position.'
			WHERE `id_zdropdown` = '.(int) $id_zdropdown;

        Db::getInstance()->execute($query);
    }

    public function getProductsInfoBack($id_lang = null)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : $id_lang;
        $products = array();

        if (!empty($this->products)) {
            $implode_product_id = implode(array_map('intval', $this->products), ',');
            $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                'SELECT p.`id_product`, p.`reference`, pl.name
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int) $id_lang.')
                '.Shop::addSqlRestrictionOnLang('pl').'
                WHERE p.`id_product` IN ('.pSQL($implode_product_id).')
                ORDER BY FIELD(p.`id_product`, '.pSQL($implode_product_id).')'
            );

            foreach ($rows as $row) {
                $products[$row['id_product']] = trim($row['name']).(!empty($row['reference']) ? ' (ref: '.$row['reference'].')' : '');
            }
        }

        return $products;
    }

    public static function getListFront($id_zmenu)
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $result = self::getList($id_zmenu, $id_lang, true);

        $zdropdowns = false;

        if ($result) {
            $zdropdowns = array();

            foreach ($result as $dropdown) {
                $dropdown['selfclass'] = '';
                if ($dropdown['content_type'] == 'category') {
                    $category_ids = Tools::unSerialize($dropdown['categories']);
                    $category_options = self::initCategoryOptions($dropdown['category_options']);
                    $dropdown['categories'] = self::getCategoriesByArrayId($category_ids, $category_options);
                    if ($category_options['image'] && !$category_options['subcategories']) {
                        $dropdown['selfclass'] = 'yesimage-nosub';
                    }
                } elseif ($dropdown['content_type'] == 'product') {
                    $array_ids = Tools::unSerialize($dropdown['products']);
                    $dropdown['products'] = false;
                    $products = self::getProductsByArrayId($array_ids, $id_lang);

                    if ($products) {
                        $present_products = array();
                        $assembler = new ProductAssembler($context);

                        $presenterFactory = new ProductPresenterFactory($context);
                        $presentationSettings = $presenterFactory->getPresentationSettings();
                        $presenter = new ProductListingPresenter(
                            new ImageRetriever($context->link),
                            $context->link,
                            new PriceFormatter(),
                            new ProductColorsRetriever(),
                            $context->getTranslator()
                        );

                        foreach ($products as $rawProduct) {
                            $present_products[] = $presenter->present(
                                $presentationSettings,
                                $assembler->assembleProduct($rawProduct),
                                $context->language
                            );
                        }

                        $dropdown['products'] = $present_products;
                    }
                } elseif ($dropdown['content_type'] == 'manufacturer') {
                    $array_ids = Tools::unSerialize($dropdown['manufacturers']);
                    $manufacturer_options = self::initManufacturerOptions($dropdown['manufacturer_options']);
                    $dropdown['manufacturers'] = self::getManufacturersByArrayId($array_ids, $manufacturer_options);
                }

                $zdropdowns[] = $dropdown;
            }
        }

        return $zdropdowns;
    }

    public static function getCategoriesByArrayId($array_category_id = null, $options = null)
    {
        if (empty($array_category_id) && !is_array($array_category_id)) {
            return false;
        }

        $context = Context::getContext();
        $id_lang = $context->language->id;
        $retriever = new ImageRetriever($context->link);
        $objectPresenter = new ObjectPresenter();
        $home_cat = array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'));
        $result = array();

        foreach ($array_category_id as $id_category) {
            $object_category = new Category($id_category, $id_lang);

            if (Validate::isLoadedObject($object_category)) {
                $category = $objectPresenter->present($object_category);
                
                $category['url'] = $context->link->getCategoryLink(
                    $category['id'],
                    $category['link_rewrite']
                );

                $category['image'] = false;
                if ($options['image']) {
                    $category['image'] = $retriever->getImage($object_category, $object_category->id_image);
                }

                $category['menu_thumb'] = false;
                if ($options['icon']) {
                    $thumb = false;
                    for ($i = 0; $i < 3; ++$i) {
                        if (file_exists(_PS_CAT_IMG_DIR_ . (int) $category['id'] . '-' . $i . '_thumb.jpg')) {
                            $thumb = $i;
                            break;
                        }
                    }
                    if ($thumb !== false) {
                        list($tmpWidth, $tmpHeight, $type) = getimagesize(_PS_CAT_IMG_DIR_ . (int) $category['id'] . '-' . $thumb . '_thumb.jpg');
                        $category['menu_thumb'] = array(
                            'url' => $context->link->getCatImageLink($category['link_rewrite'], (int) $category['id'], $thumb.'_thumb'),
                            'width' => $tmpWidth,
                            'height' => $tmpHeight,
                        );
                    }
                }

                $category['subcategories'] = false;
                if ($options['subcategories']) {
                    $subCategories = $object_category->getSubCategories($id_lang);
                    if ($subCategories) {
                        foreach ($subCategories as &$sub) {
                            $sub['url'] = $context->link->getCategoryLink(
                                $sub['id_category'],
                                $sub['link_rewrite']
                            );

                            $sub['menu_thumb'] = false;
                            if ($options['icon'] && in_array($category['id'], $home_cat)) {
                                $thumb = false;
                                for ($i = 0; $i < 3; ++$i) {
                                    if (file_exists(_PS_CAT_IMG_DIR_ . (int) $sub['id_category'] . '-' . $i . '_thumb.jpg')) {
                                        $thumb = $i;
                                        break;
                                    }
                                }
                                if ($thumb !== false) {
                                    list($tmpWidth, $tmpHeight, $type) = getimagesize(_PS_CAT_IMG_DIR_ . (int) $sub['id_category'] . '-' . $thumb . '_thumb.jpg');
                                    $sub['menu_thumb'] = array(
                                        'url' => $context->link->getCatImageLink($sub['link_rewrite'], (int) $sub['id_category'], $thumb.'_thumb'),
                                        'width' => $tmpWidth,
                                        'height' => $tmpHeight,
                                    );
                                }
                            }
                        }
                    }
                    $category['subcategories'] = $subCategories;
                }

                if (in_array($category['id'], $home_cat)) {
                    $category['name'] = false;
                }

                $result[] = $category;
            }
        }

        return $result;
    }

    public static function getProductsByArrayId($array_product_id = null, $id_lang = null)
    {
        if (empty($array_product_id)) {
            return false;
        }
        $context = Context::getContext();
        if (!$id_lang) {
            $id_lang = $context->language->id;
        }
        $implode_product_id = implode(array_map('intval', $array_product_id), ',');

        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
            pl.`description`, pl.`description_short`, pl.`link_rewrite`,
            pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
            image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
            DATEDIFF(
                product_shop.`date_add`,
                DATE_SUB(
                    "' . date('Y-m-d') . ' 00:00:00",
                    INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                )
            ) > 0 AS new'
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl'));
        $sql->leftJoin('image', 'i', 'i.`id_product` = p.`id_product`');
        $sql->join(Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1'));
        $sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
        $sql->leftJoin('category_lang', 'cl', 'product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('cl'));

        $sql->where('p.`id_product` IN ('.pSQL($implode_product_id).')');
        $sql->where('product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog")');

        $sql->orderBy('FIELD(p.`id_product`, '.pSQL($implode_product_id).')');
        $sql->groupBy('product_shop.id_product');

        if (Combination::isFeatureActive()) {
            $sql->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute');
            $sql->leftJoin('product_attribute_shop', 'product_attribute_shop', 'p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id);
        }

        $sql->join(Product::sqlStock('p', 0));

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        return Product::getProductsProperties((int) $id_lang, $result);
    }

    public static function getManufacturersByArrayId($array_manufacturer_id = null, $options = null)
    {
        if (empty($array_manufacturer_id)) {
            return false;
        }

        $context = Context::getContext();
        $id_lang = $context->language->id;

        $query = 'SELECT m.*, ml.`description`, ml.`short_description`
            FROM `'._DB_PREFIX_.'manufacturer` m
            '.Shop::addSqlAssociation('manufacturer', 'm').'
            LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int) $id_lang.')
            WHERE m.`id_manufacturer` IN ('.implode(array_map('intval', $array_manufacturer_id), ',').')
            AND m.`active` = 1
            GROUP BY m.`id_manufacturer`
            ORDER BY m.`name`';

        $manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$manufacturers) {
            return false;
        }

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

        foreach ($manufacturers as &$item) {
            $item['url'] = $context->link->getManufacturerLink($item['id_manufacturer']);
            if ($options['layout'] == 'logo' || $options['layout'] == 'logo_name') {
                $item['image'] = $context->link->getManufacturerImageLink($item['id_manufacturer'], 'manufacturer_default');
                $item['image_width'] = $image_size['width'];
                $item['image_height'] = $image_size['height'];
                $item['title'] = $item['name'];
            } else {
                $item['image'] = false;
            }
            if ($options['layout'] == 'logo') {
                $item['name'] = false;
            }
        }

        return $manufacturers;
    }
}
