{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<section class="featured-products">
  <div class="block clearfix">

    <div class="title-block d-flex flex-wrap">
      <span>{l s='Our Products' d='Modules.Featuredproducts.Shop'}</span>
      <span class="view-all-link">
        <a href="{$allProductsLink}">{l s='All products' d='Modules.Featuredproducts.Shop'} <i class="material-icons">&#xE8E4;</i></a>
      </span>
    </div>
    
    <div class="product-list">
      <div class="product-list-wrapper clearfix grid columns-5 columns-slick js-featuredproducts-slider">
        {foreach from=$products item="product"}
          {include file='catalog/_partials/miniatures/product-simple.tpl' product=$product}
        {/foreach}
      </div>
    </div>

  </div>
</section>