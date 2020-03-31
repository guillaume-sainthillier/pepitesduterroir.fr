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

<li class="cart-product-line">
  <a class="product-image" href="{$product.url}">
    {if $product.cover}
      <img src="{$product.cover.small.url}" alt="{$product.name}" class="img-fluid">
    {else}
      <img src="{$urls.no_picture_image.small.url}" alt="{$product.name}" class="img-fluid">
    {/if}
  </a>
  <div class="product-infos">
    <a class="product-name" href="{$product.url}">{$product.name}</a>
    <div class="product-attributes">{', '|implode:$product.attributes}</div>
    <div class="product-price-quantity">
      <div class="product-quantity-touchspin">
        {if isset($product.is_gift) && $product.is_gift}
          {$product.quantity}
        {else}
          <input
            class="js-cart-line-product-quantity"
            data-down-url="{$product.down_quantity_url}"
            data-up-url="{$product.up_quantity_url}"
            data-update-url="{$product.update_quantity_url}"
            data-product-id="{$product.id_product}"
            type="number"
            value="{$product.quantity}"
            name="product-sidebar-quantity-spin"
            min="{$product.minimal_quantity}"
          />
        {/if}
      </div>
      <div class="product-cart-price">
        {if $product.has_discount}<span class="regular-price">{$product.regular_price}</span>{/if}
        <span class="product-price">{$product.price}</span>
        <span class="x-character">x</span>
        <span class="product-qty">{$product.quantity}</span>
      </div>
    </div>
  </div>
  <a
    class                       = "remove-from-cart"
    rel                         = "nofollow"
    href                        = "{$product.remove_from_cart_url}"
    data-link-action            = "delete-from-cart"
    data-id-product             = "{$product.id_product}"
    data-id-product-attribute   = "{$product.id_product_attribute}"
    data-id-customization       = "{$product.id_customization}"
    title                       = "{l s='Delete' d='Shop.Theme.Actions'}"
  >
    {if !isset($product.is_gift) || !$product.is_gift}
      <i class="fa fa-trash-o" aria-hidden="true"></i>
    {/if}
  </a>
  {if $product.customizations|count}{/if}
</li>