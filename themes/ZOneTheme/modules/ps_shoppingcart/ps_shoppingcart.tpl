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
<div class="shopping-cart-module">
  <div class="blockcart cart-preview" data-refresh-url="{$refresh_url}" data-sidebar-cart-trigger>
    <ul class="cart-header">
      <li data-sticky-cart-source>
        <a rel="nofollow" href="{$cart_url}" class="cart-link btn-primary">
          <span class="cart-design"><i class="fa fa-shopping-basket" aria-hidden="true"></i><span class="cart-products-count">{$cart.products_count}</span></span>
          <span class="cart-total-value">{$cart.totals.total.value}</span>
        </a>
      </li>
    </ul>

    {include 'module:ps_shoppingcart/ps_shoppingcart-dropdown.tpl'}
  </div>
</div>
