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
<div class="d-flex flex-wrap">
  <label class="form-control-label d-none d-lg-block sort-label">{l s='Sort by:' d='Shop.Theme.Global'}</label>
  <div class="sort-select dropdown js-dropdown">
    <button
      class="custom-select select-title"
      data-toggle="dropdown"
      data-offset="0,1px"
      aria-haspopup="true"
      aria-expanded="false"
    >
      {if isset($listing.sort_selected)}{$listing.sort_selected}{else}{l s='Select' d='Shop.Theme.Actions'}{/if}
    </button>
    <div class="dropdown-menu">
      {foreach from=$listing.sort_orders item=sort_order}
        <a
          rel="nofollow"
          href="{$sort_order.url}"
          class="dropdown-item {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
        >
          {$sort_order.label}
        </a>
      {/foreach}
    </div>
  </div>

  {if !isset($cat_productView)}
    {assign var='cat_productView' value='grid'}
  {/if}

  {if isset($zoneIsMobile) && $zoneIsMobile}
    <!-- Remove product display on mobile -->
  {else}
    <div class="product-display d-none d-md-block">
      <div class="d-flex">
        <label class="form-control-label display-label d-none d-lg-block">{l s='View' d='Shop.Zonetheme'}</label>
        <ul class="display-select" id="product_display_control">
          <li class="d-flex">
            <a data-view="grid" {if $cat_productView == 'grid'}class="selected"{/if} rel="nofollow" href="#grid" title="{l s='Grid' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
              <i class="material-icons">view_comfy</i>
            </a>
            <a data-view="list" {if $cat_productView == 'list'}class="selected"{/if} rel="nofollow" href="#list" title="{l s='List' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
              <i class="material-icons">view_list</i>
            </a>
            <a data-view="table-view" {if $cat_productView == 'table'}class="selected"{/if} rel="nofollow" href="#table" title="{l s='Table' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
              <i class="material-icons">view_headline</i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  {/if}
</div>
