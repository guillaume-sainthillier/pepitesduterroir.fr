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
{extends file='customer/page.tpl'}

{block name='page_title'}
  {l s='Order history' d='Shop.Theme.Customeraccount'}
{/block}

{block name='page_content'}
  <h5 class="sm-bottom">{l s='Here are the orders you\'ve placed since your account was created.' d='Shop.Theme.Customeraccount'}</h5>

  {if $orders}
    <div class="d-none d-xl-block">
      <table class="table table-bordered table-labeled">
        <thead class="thead-default">
          <tr>
            <th>{l s='Order reference' d='Shop.Theme.Checkout'}</th>
            <th>{l s='Date' d='Shop.Theme.Checkout'}</th>
            <th>{l s='Total price' d='Shop.Theme.Checkout'}</th>
            <th>{l s='Payment' d='Shop.Theme.Checkout'}</th>
            <th>{l s='Status' d='Shop.Theme.Checkout'}</th>
            <th>{l s='Invoice' d='Shop.Theme.Checkout'}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$orders item=order}
            <tr>
              <th scope="row"><a href="{$order.details.details_url}" class="li-a">{$order.details.reference}</a></th>
              <td>{$order.details.order_date}</td>
              <td class="text-right"><span class="price price-total">{$order.totals.total.value}</span></td>
              <td>{$order.details.payment}</td>
              <td>
                <span
                  class="label label-pill {$order.history.current.contrast}"
                  style="background-color:{$order.history.current.color}"
                >
                  {$order.history.current.ostate_name}
                </span>
              </td>
              <td class="text-center">
                {if $order.details.invoice_url}
                  <a href="{$order.details.invoice_url}"><i class="material-icons pdf-icon">&#xE415;</i></a>
                {else}
                  -
                {/if}
              </td>
              <td class="text-center order-actions">
                <a href="{$order.details.details_url}" data-link-action="view-order-details">
                  <i class="material-icons">&#xE8B6;</i>{l s='Details' d='Shop.Theme.Customeraccount'}
                </a>
                {if $order.details.reorder_url}
                  <a href="{$order.details.reorder_url}">
                    <i class="material-icons">&#xE863;</i>{l s='Reorder' d='Shop.Theme.Actions'}
                  </a>
                {/if}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>

    <div class="orders d-block d-xl-none">
      {foreach from=$orders item=order}
        <div class="order light-box-bg">
          <div class="row">
            <div class="col-10">
              <h5><a href="{$order.details.details_url}" class="li-a">{$order.details.reference}</a></h5>
              <div class="date">{$order.details.order_date}</div>
              <div class="total"><span class="price price-total">{$order.totals.total.value}</span></div>
              <div class="status">
                <span
                  class="label label-pill {$order.history.current.contrast}"
                  style="background-color:{$order.history.current.color}"
                >
                  {$order.history.current.ostate_name}
                </span>
              </div>
            </div>
            <div class="col-2 text-right">
                <div class="action">
                  <a href="{$order.details.details_url}" data-link-action="view-order-details" title="{l s='Details' d='Shop.Theme.Customeraccount'}">
                    <i class="material-icons">&#xE8B6;</i>
                  </a>
                </div>
                {if $order.details.reorder_url}
                  <div class="action">
                    <a href="{$order.details.reorder_url}" title="{l s='Reorder' d='Shop.Theme.Actions'}">
                      <i class="material-icons">&#xE863;</i>
                    </a>
                  </div>
                {/if}
            </div>
          </div>
        </div>
      {/foreach}
    </div>
  {/if}
{/block}
