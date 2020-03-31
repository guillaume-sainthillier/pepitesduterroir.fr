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
<div class="cart-item cart-summary-totals">
  {block name='cart_summary_total'}
    {if !$configuration.display_prices_tax_incl && $configuration.taxes_enabled}
      <div class="cart-summary-line">
        <label>{$cart.totals.total.label}&nbsp;{$cart.labels.tax_short}</label>
        <span class="value price">{$cart.totals.total.value}</span>
      </div>
      <div class="cart-summary-line cart-total">
        <label>{$cart.totals.total_including_tax.label}</label>
        <span class="value price price-total">{$cart.totals.total_including_tax.value}</span>
      </div>
    {else}
      <div class="cart-summary-line cart-total">
        <span class="label">{$cart.totals.total.label}&nbsp;{if $configuration.taxes_enabled}{$cart.labels.tax_short}{/if}</span>
        <span class="value price price-total">{$cart.totals.total.value}</span>
      </div>
    {/if}
  {/block}
</div>