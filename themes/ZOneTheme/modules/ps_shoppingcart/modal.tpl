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
<div id="blockcart-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="h5 modal-title text-center"><i class="material-icons">check</i>{l s='Product successfully added to your shopping cart' d='Shop.Theme.Checkout'}</h3>
      </div>
      <div class="modal-body">
        <div class="cart-modal-wrapper row align-items-md-center">
          <div class="col-12 col-md-5">
            <div class="cart-product sm-bottom">
              <div class="row px-3">
                <div class="product-image col-4">
                  {if $product.cover}<img class="img-thumbnail" src="{$product.cover.bySize.small_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}">{/if}
                </div>
                <div class="product-infos col-8">
                  <p class="product-name">{$product.name}</p>
                  <p class="price product-price xs-bottom">{$product.price}</p>
                  {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                  <div class="product-attributes">
                    {foreach from=$product.attributes item="property_value" key="property"}
                      <p>{l s='%label%:' sprintf=['%label%' => $property] d='Shop.Theme.Global'}<strong> {$property_value}</strong></p>
                    {/foreach}
                    <p><span>{l s='Quantity:' d='Shop.Theme.Checkout'}</span> <strong>{$product.cart_quantity}</strong></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="cart-content">
              <p class="cart-products-count alert-info">
                {if $cart.products_count > 1}{l s='There are %products_count% items in your cart.' sprintf=['%products_count%' => $cart.products_count] d='Shop.Theme.Checkout'}
                {else}{l s='There is %product_count% item in your cart.' sprintf=['%product_count%' =>$cart.products_count] d='Shop.Theme.Checkout'}{/if}
              </p>
              
              <div class="cart-prices px-3 py-1">
                <div class="cart-summary-subtotals">
                  {foreach from=$cart.subtotals item="subtotal"}
                    {if $subtotal.value}
                      <div class="cart-summary-line cart-subtotal-{$subtotal.type}">
                        <label>{$subtotal.label}</label>
                        {if 'discount' == $subtotal.type}
                          <span class="price discount-price">-&nbsp;{$subtotal.value}</span>
                        {else}
                          <span class="price">{$subtotal.value}</span>
                        {/if}
                      </div>
                    {/if}
                  {/foreach}
                  {if $cart.subtotals.shipping}
                    <div class="cart-summary-line shipping-hook">
                      {hook h='displayCheckoutSubtotalDetails' subtotal=$cart.subtotals.shipping}
                    </div>
                  {/if}
                </div>

                <div class="cart-total">
                  {if !$configuration.display_prices_tax_incl && $configuration.taxes_enabled}
                    <div class="cart-summary-line">
                      <label>{$cart.totals.total.label}&nbsp;{$cart.labels.tax_short}</label>
                      <span class="price">{$cart.totals.total.value}</span>
                    </div>
                    <div class="cart-summary-line product-total">
                      <label>{$cart.totals.total_including_tax.label}</label>
                      <span class="price price-total">{$cart.totals.total_including_tax.value}</span>
                    </div>
                  {else}
                    <div class="cart-summary-line product-total">
                      <label>{$cart.totals.total.label}&nbsp;{if $configuration.taxes_enabled}{$cart.labels.tax_short}{/if}</label>
                      <span class="price price-total">{$cart.totals.total.value}</span>
                    </div>
                  {/if}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="cart-buttons">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Continue shopping' d='Shop.Theme.Actions'}</button>
          <a href="{$cart_url}" class="btn btn-primary"><i class="material-icons">check</i> {l s='Proceed to checkout' d='Shop.Theme.Actions'}</a>
        </div>
      </div>
    </div>
  </div>
</div>
