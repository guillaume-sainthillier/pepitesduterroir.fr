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
<div class="product-add-to-cart {if !$product.add_to_cart_url}add-to-cart-disabled{/if}">
  {if !$configuration.is_catalog}
  
    {if isset($product_AddToCartLayout) && $product_AddToCartLayout == 'normal'}

      {block name='product_quantity'}
        <div class="product-quantity-touchspin row sm-bottom">
          <label class="form-control-label col-3">{l s='Quantity' d='Shop.Theme.Catalog'}</label>
          <div class="col-9">
            <div class="qty">
              <input
                type="number"
                name="qty"
                id="quantity_wanted"
                value="{$product.quantity_wanted}"
                class="form-control"
                min="{$product.minimal_quantity}"
                aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
              />
            </div>
          </div>
        </div>
      {/block}

      {block name='product_minimal_quantity'}
        <div class="product-minimal-quantity">
          {if $product.minimal_quantity > 1}
            <p class="product-minimal-quantity-text">
              <i>{l
                s='The minimum purchase order quantity for the product is %quantity%.'
                d='Shop.Theme.Checkout'
                sprintf=['%quantity%' => $product.minimal_quantity]
              }</i>
            </p>
          {/if}
        </div>
      {/block}

      {block name='product_add_to_cart_button'}
        <div class="product-add-to-cart-button mb-2 row">
          <div class="add col-12 col-md-9 col-xl-10">
            <button
              class="btn add-to-cart"
              data-button-action="add-to-cart"
              type="submit"
              {if !$product.add_to_cart_url}disabled{/if}
            >
              <i class="material-icons shopping-cart">shopping_cart</i><span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
              <span class="js-waitting-add-to-cart page-loading-overlay add-to-cart-loading">
                <span class="page-loading-backdrop d-flex align-items-center justify-content-center">
                  <span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span>
                </span>
              </span>
            </button>
          </div>
        </div>
      {/block}

      {hook h='displayProductActions' product=$product}

    {else}

      <div class="inline-style d-flex align-items-center">
        {block name='product_quantity'}
          <div class="product-quantity-touchspin">
            <div class="qty">
              <input
                type="number"
                name="qty"
                id="quantity_wanted"
                value="{$product.quantity_wanted}"
                class="form-control"
                min="{$product.minimal_quantity}"
                aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
              />
            </div>
          </div>
        {/block}

        {block name='product_add_to_cart_button'}
          <div class="add">
            <button
              class="btn add-to-cart"
              data-button-action="add-to-cart"
              type="submit"
              {if !$product.add_to_cart_url}disabled{/if}
            >
              <i class="material-icons shopping-cart">shopping_cart</i><span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
              <span class="js-waitting-add-to-cart page-loading-overlay add-to-cart-loading">
                <span class="page-loading-backdrop d-flex align-items-center justify-content-center">
                  <span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span>
                </span>
              </span>
            </button>
          </div>
        {/block}

        {hook h='displayProductActions' product=$product}
      </div>

      {block name='product_minimal_quantity'}
        <div class="product-minimal-quantity">
          {if $product.minimal_quantity > 1}
            <p class="product-minimal-quantity-text">
              <i>{l
                s='The minimum purchase order quantity for the product is %quantity%.'
                d='Shop.Theme.Checkout'
                sprintf=['%quantity%' => $product.minimal_quantity]
              }</i>
            </p>
          {/if}
        </div>
      {/block}

    {/if}

    {block name='product_availability'}
      {if $product.show_availability && $product.availability_message}
        <div class="js-product-availability-source d-none">
          <span id="product-availability">
            {if $product.availability == 'available' && $product.quantity > 0}
              <span class="product-availability product-available alert alert-success">
                <i class="material-icons">check</i>&nbsp;{$product.availability_message}
              </span>
            {elseif $product.availability == 'last_remaining_items'}
              <span class="product-availability product-last-items alert alert-info">
                <i class="material-icons">warning</i>&nbsp;{$product.availability_message}
              </span>
            {else}
              <span class="product-availability product-unavailable alert alert-warning">
                <i class="material-icons">block</i>&nbsp;{$product.availability_message}
              </span>
            {/if}
          </span>
        </div>
      {/if}
    {/block}
  {/if}
</div>
