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

{extends file=$layout}

{block name='content'}
  <section id="main">
    <h1 class="page-heading">{l s='Checkout' d='Shop.Theme.Actions'}</h1>

    <div class="cart-grid mb-3 row">
      <div class="cart-grid-body col-12 col-lg-8 mb-4">
        {render file='checkout/checkout-process.tpl' ui=$checkout_process}
      </div>

      <div class="cart-grid-right col-12 col-lg-4 mb-4">
        <div class="light-box-bg cart-summary">
          {include file='checkout/_partials/cart-summary.tpl' cart = $cart}

          <div class="js-cart-update-voucher page-loading-overlay cart-overview-loading">
            <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
              <span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {block name='hook_shopping_cart_footer'}{/block}
  </section>
{/block}
