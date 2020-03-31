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

{block name='product_miniature_item'}
<article class="product-miniature product-style js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" data-product-id-category="{$product.id_category_default}" data-product-id-manufacturer="{$product.id_manufacturer}">
  <div class="product-container">
    <div class="first-block">
      {include file='catalog/_partials/miniatures/_product_thumbnail.tpl'}

      {include file='catalog/_partials/miniatures/_product_countdown.tpl'}
    </div>

    <div class="second-block">
      {block name='product_name'}
        <h5 class="product-name"><a href="{$product.url}" title="{$product.name}">{$product.name}</a></h5>
      {/block}

      <div class="second-block-wrapper">
        <div class="informations-section">
          {block name='product_price_and_shipping'}
            {if $product.show_price}
              <div class="product-price-and-shipping d-flex flex-wrap align-items-center">
                <div class="first-prices d-flex flex-wrap align-items-center">
                  {hook h='displayProductPriceBlock' product=$product type="before_price"}

                  <span class="price product-price">{$product.price}</span>
                </div>

                {if $product.has_discount}
                  <div class="second-prices d-flex flex-wrap align-items-center {if isset($ps_legalcompliance_spl) && $ps_legalcompliance_spl}has-aeuc{/if}">
                    {hook h='displayProductPriceBlock' product=$product type="old_price"}

                    <span class="regular-price">{$product.regular_price}</span>

                    {if $product.discount_type === 'percentage'}
                      <span class="discount-product discount-percentage">{$product.discount_percentage}</span>
                    {elseif $product.discount_type === 'amount'}
                      <span class="discount-product discount-amount">{$product.discount_amount_to_display}</span>
                    {/if}
                  </div>
                {/if}

                <div class="third-prices d-flex flex-wrap align-items-center">
                  {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                  {hook h='displayProductPriceBlock' product=$product type="weight"}
                </div>
              </div>
            {/if}
          {/block}

          {block name='product_reviews'}
            {hook h='displayProductListReviews' product=$product}
          {/block}
        </div>
        <div class="buttons-sections">
          {block name='product_grid_buy'}
            <div class="grid-buy-button">
              {if isset($zoneNotCatalogMode) && $zoneNotCatalogMode && $product.add_to_cart_url && $product.customizable == 0 && $product.minimal_quantity == 1}
                <a class="btn add-to-cart js-ajax-add-to-cart" href="{$product.url}" data-id-product="{$product.id_product}"><span>{l s='Buy' d='Shop.Zonetheme'}</span></a>
              {else}
                <a class="btn add-to-cart details-link" href="{$product.url}"><span>{l s='Details' d='Shop.Zonetheme'}</span></a>
              {/if}
            </div>
          {/block}
        </div>
      </div>
    </div>
  </div><!-- /product-container -->
</article>
{/block}
