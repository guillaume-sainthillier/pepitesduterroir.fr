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
<article class="product-miniature product-simple product-style" data-id-product="{$product.id_product}" data-product-id-category="{$product.id_category_default}" data-product-id-manufacturer="{$product.id_manufacturer}">
  <div class="product-container">
    <div class="first-block">
      {include file='catalog/_partials/miniatures/_product_thumbnail.tpl'}
    </div>

    <div class="second-block">
      {block name='product_name'}
        <p class="product-name" title="{$product.name}"><a href="{$product.url}">{$product.name}</a></p>
      {/block}

      {block name='product_price_and_shipping'}
        {if $product.show_price}
          <div class="product-price-and-shipping d-flex flex-wrap align-items-center">
            <span class="price product-price">{$product.price}</span>

            {if $product.has_discount}
              <span class="regular-price">{$product.regular_price}</span>
              {if $product.discount_type === 'percentage'}
                <span class="discount-percentage">{$product.discount_percentage}</span>
              {/if}
            {/if}
          </div>
        {/if}
      {/block}
    </div>
  </div>
</article>
{/block}