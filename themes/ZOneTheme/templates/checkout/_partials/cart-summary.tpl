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

<section id="js-checkout-summary" class="js-cart" data-refresh-url="{url entity='cart' params=['ajax' => true, 'action' => 'refresh']}">
  <div class="cart-item">
    {block name='hook_checkout_summary_top'}
      {hook h='displayCheckoutSummaryTop'}
    {/block}

    {block name='cart_summary_products'}
      <div class="cart-summary-products">
        <div class="cart-summary-line">
          <label>{$cart.summary_string}</label>
          <span class="value">
            <a href="#" data-toggle="collapse" data-target="#cart-summary-product-list" class="show-details collapsed" aria-expanded="false">
              {l s='show details' d='Shop.Theme.Actions'}<i class="material-icons">expand_more</i>
            </a>
          </span>
        </div>

        {block name='cart_summary_product_list'}
          <div class="collapse" id="cart-summary-product-list">
            <ul class="media-list">
              {foreach from=$cart.products item=product}
                <li class="media">{include file='checkout/_partials/cart-summary-product-line.tpl' product=$product}</li>
              {/foreach}
            </ul>
          </div>
        {/block}
      </div>
    {/block}
  </div>

  {block name='cart_summary_voucher'}
    {include file='checkout/_partials/cart-voucher.tpl'}
  {/block}

  {block name='cart_summary_subtotals'}
    {include file='checkout/_partials/cart-summary-subtotals.tpl' cart=$cart}
  {/block}

  {block name='cart_summary_totals'}
    {include file='checkout/_partials/cart-summary-totals.tpl' cart=$cart}
  {/block}
</section>
