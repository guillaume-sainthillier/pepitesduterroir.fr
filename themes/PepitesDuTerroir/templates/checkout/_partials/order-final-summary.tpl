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
<section id="order-summary-content" class="page-order-confirmation box-bg mb-5">
  <p class="h5 text-center p-2 summary-message alert-info">{l s='Please check your order before payment' d='Shop.Theme.Checkout'}</p>

  <div class="order-summary-block order-summary-address mb-4">
    <h5 class="step-title">
      {l s='Addresses' d='Shop.Theme.Checkout'}
      <span class="step-edit step-to-addresses js-edit-addresses"><i class="material-icons edit">mode_edit</i> {l s='Edit' d='Shop.Theme.Actions'}</span>
    </h5>

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="light-box-bg mb-2">
          <p class="h5">{l s='Your Delivery Address' d='Shop.Theme.Checkout'}</p>
          {$customer.addresses[$cart.id_address_delivery]['formatted'] nofilter}
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="light-box-bg mb-2">
          <p class="h5">{l s='Your Invoice Address' d='Shop.Theme.Checkout'}</p>
          {$customer.addresses[$cart.id_address_invoice]['formatted'] nofilter}
        </div>
      </div>
    </div>
  </div>
  
  <div class="order-summary-block order-summary-shipping mb-4">
    <h5 class="step-title">
      {l s='Shipping Method' d='Shop.Theme.Checkout'}
      <span class="step-edit step-to-delivery js-edit-delivery"><i class="material-icons edit">mode_edit</i> {l s='Edit' d='Shop.Theme.Actions'}</span>
    </h5>
    <div class="summary-selected-carrier light-box-bg grid-small-padding py-0">
      <div class="delivery-option row flex-nowrap">
        <div class="carrier-logo">
          {if $selected_delivery_option.logo}
            <img src="{$selected_delivery_option.logo}" alt="{$selected_delivery_option.name}">
          {else}
            &nbsp;
          {/if}
        </div>
        <div class="d-flex flex-wrap justify-content-between w-100">
          <span class="carrier-name">{$selected_delivery_option.name}</span>
          <span class="carrier-delay">{$selected_delivery_option.delay}</span>
          <span class="carrier-price">{$selected_delivery_option.price}</span>
        </div>
      </div>
    </div>
    <div class="mb-2"></div>
  </div>

  <div class="order-summary-block order-summary-items">
    {block name='order_confirmation_table'}
      {include file='checkout/_partials/order-final-summary-table.tpl'
        products=$cart.products
        products_count=$cart.products_count
        subtotals=$cart.subtotals
        totals=$cart.totals
        labels=$cart.labels
        add_product_link=true
      }
    {/block}
  </div>
</section>
