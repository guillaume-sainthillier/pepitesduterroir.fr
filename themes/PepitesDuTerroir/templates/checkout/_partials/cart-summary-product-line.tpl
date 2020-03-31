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
{block name='cart_summary_product_line'}
  <div class="media-left">
    {if $product.cover}
      <img class="media-object" src="{$product.cover.small.url}" alt="{$product.name}">
    {else}
      <img class="media-object" src="{$urls.no_picture_image.small.url}" alt="{$product.name}">
    {/if}
  </div>
  <div class="media-body d-flex align-items-center">
    <a class="product-name" href="{$product.url}" title="{$product.name}">
      {$product.name}
      <span class="product-attribute">{foreach from=$product.attributes item=value name=attributes}{if $smarty.foreach.attributes.first}<br>{else}, {/if}{$value}{/foreach}</span>
    </a>
    <span class="product-quantity">x{$product.quantity}</span>
    <span class="product-price">{$product.price}</span>
    {hook h='displayProductPriceBlock' product=$product type="unit_price"}
  </div>
{/block}
