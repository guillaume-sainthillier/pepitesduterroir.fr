<?php
/*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
    exit;

class PepitesDuTerroir extends Module
{
    public function __construct()
    {
        $this->name = 'pepitesduterroir';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Guillaume Sainthillier';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('Les pépites du terroir', array(), 'Modules.PepitesDuTerroir.Admin');
        $this->description = $this->trans('Configure les fonctionnalités sur mesures des pépites du terroir.', array(), 'Modules.PepitesDuTerroir.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        parent::install();
        $this->registerHook('filterProductSearch');
        $this->registerHook('displayProductPriceBlock');
    }

    public function uninstall()
    {
        parent::uninstall();
        $this->unregisterHook('filterProductSearch');
        $this->unregisterHook('displayProductPriceBlock');
    }

    public function hookFilterProductSearch(array $params)
    {
        foreach ($params['searchVariables']['sort_orders'] as $key => $sort) {
            if ($sort['field'] === 'name') {
                unset($params['searchVariables']['sort_orders'][$key]);
            }
        }
    }

    public function hookDisplayProductPriceBlock(array $params)
    {
        if (!in_array($params['type'], ['unit_price', 'after_price'], true)) {
            return;
        }

        $product = $params['product'];
        if (false === stripos($product['name'], 'carton ')) {
            return;
        }

        $price = $product['price_amount'];
        $regularPrice = $product['regular_price_amount'];
        $unitPrice = Context::getContext()->currentLocale->formatPrice($price / 6, 'EUR');
        if ($product instanceof \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingLazyArray) {
            //List product
            return '<small class="w-100 mt-1 font-italic text-muted">Soit <span class="price product-price" >' . $unitPrice . '</span> par unité</small>';
        }

        $regularUnitPrice = Context::getContext()->currentLocale->formatPrice($regularPrice / 6, 'EUR');
        $regularPriceDescription = '';
        if($regularPrice !== $price) {
            $regularPriceDescription = ' (au lieu de <span class="price product-price" >' . $regularUnitPrice . '</span>)';
        }

        return '
            <div class="w-100 mt-2 product-prices-wrapper">
            <small><small>Soit <span class="price product-price" >' . $unitPrice . '</span> par unité'.$regularPriceDescription.'</small></small>
            </div>
        ';
    }
}