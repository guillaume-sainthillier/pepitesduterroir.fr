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

<div class="product-information light-box-bg sm-bottom">
  {if $product.is_customizable && count($product.customizations.fields)}
    {block name='product_customization'}
      {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
    {/block}
  {/if}

  <div class="product-actions">
    {block name='product_buy'}
      <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
        <input type="hidden" name="token" value="{$static_token}">
        <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
        <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

        {block name='product_variants'}
          {include file='catalog/_partials/product-variants.tpl'}
        {/block}

        {block name='product_pack'}
          {if $packItems}
            <section class="product-pack md-bottom">
              <label>{l s='This pack contains' d='Shop.Theme.Catalog'}</label>
              <div class="pack-product-items">
                {foreach from=$packItems item="product_pack"}
                  {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                {/foreach}
              </div>
            </section>
          {/if}
        {/block}

        {block name='product_discounts'}
          {include file='catalog/_partials/product-discounts.tpl'}
        {/block}

        {block name='product_prices'}
          {include file='catalog/_partials/product-prices.tpl'}
        {/block}

        {if isset($product_enableCountdown) && $product_enableCountdown}
          <div class="js-product-countdown" data-specific-prices-to="{if isset($product.specific_prices) && isset($product.specific_prices.to) && $product.specific_prices.to != '0000-00-00 00:00:00'}{$product.specific_prices.to}{/if}"></div>
        {/if}

        {block name='product_add_to_cart'}
          {include file='catalog/_partials/product-add-to-cart.tpl'}
        {/block}

        {block name='product_refresh'}{/block}

      </form>
    {/block}
  </div>
</div><!-- /product-information -->
