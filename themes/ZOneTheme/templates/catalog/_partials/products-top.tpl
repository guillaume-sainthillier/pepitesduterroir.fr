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
<div id="js-product-list-top" class="products-selection sm-bottom clearfix">
  <div class="row align-items-center">
    <div class="{if empty($listing.rendered_facets)}col-12{else}col-8 col-sm-9{/if} col-md-12 col-lg-8 products-sort-order order-2">
      {block name='sort_by'}
        {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
      {/block}
    </div>

    {if !empty($listing.rendered_facets)}
      <div class="col-4 col-sm-3 d-block d-md-none filter-button order-3">
        <button id="search_filter_toggler" class="btn btn-primary">
          <i class="fa fa-filter" aria-hidden="true"></i> {l s='Filter' d='Shop.Theme.Actions'}
        </button>
      </div>
    {/if}

    <div class="col-12 col-lg-4 total-products order-4 order-lg-1 d-none d-lg-block">
      <p>
      {if $listing.pagination.total_items > 1}
        {l s='There are %product_count% products.' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]}
      {else}
        {l s='There is 1 product.' d='Shop.Theme.Catalog'}
      {/if}
      </p>
    </div>
  </div>  
</div>
