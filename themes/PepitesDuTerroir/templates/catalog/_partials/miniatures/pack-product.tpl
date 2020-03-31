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

{block name='pack_miniature_item'}
  <article class="pack-product-item">
    <div class="pack-product-container">
      <div class="pack-product-left">
        {if $product.cover}
          <a class="pack-product-img" href="{$product.url}" title="{$product.name}">
            <img
              src = "{$product.cover.bySize.small_default.url}"
              alt = "{$product.cover.legend|default: $product.name}"
              data-full-size-image-url = "{$product.cover.large.url}"
              class = "img-fluid"
            >
          </a>
        {/if}
        <div class="pack-product-name product-name">
          <a href="{$product.url}" title="{$product.name}">{$product.name}</a>
        </div>
      </div>
      <div class="pack-product-right">
        <div class="pack-product-price">{$product.price}</div>
        <div class="pack-product-quantity">
          <span>x</span><span>{$product.pack_quantity}</span>
        </div>
      </div>
    </div>
  </article>
{/block}
