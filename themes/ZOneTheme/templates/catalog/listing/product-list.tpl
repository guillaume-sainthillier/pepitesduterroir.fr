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

    {block name='product_list_header'}
      <h1 class="page-heading" id="js-product-list-header">{$listing.label}</h1>
    {/block}

    <section id="products">
      {if $listing.products|count}

        {block name='product_list_top'}
          {include file='catalog/_partials/products-top.tpl' listing=$listing}
        {/block}

        {block name='mobile_search_filter'}
          <div id="_mobile_search_filters" class="mobile-search-fillter light-box-bg d-md-none md-bottom"></div>
        {/block}
        
        {block name='product_list_active_filters'}
          {$listing.rendered_active_filters nofilter}
        {/block}

        <div id="js-filter-scroll-here"></div>

        {block name='product_list'}
	        {include file='catalog/_partials/products.tpl' listing=$listing}
	      {/block}

        {block name='product_list_bottom'}
          {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
        {/block}

      {else}
        <div id="js-product-list-top"></div>

        <div id="js-product-list">
          {include file='errors/not-found.tpl'}
        </div>

        <div id="js-product-list-bottom"></div>

      {/if}
    </section>

    {block name='product_list_footer'}{/block}

  </section>
{/block}

{block name='external_html'}
  {if (isset($enabled_pm_advancedsearch4) && $enabled_pm_advancedsearch4)}
    <!-- Remove product display on mobile -->
  {else}
    <div class="js-pending-query page-loading-overlay">
      <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
        <span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span>
      </div>
    </div>
  {/if}
{/block}
