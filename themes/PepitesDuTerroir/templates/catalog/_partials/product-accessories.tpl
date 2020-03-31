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
{block name='product_accessories'}
  {if $accessories}
    <section class="product-accessories mb-2 clearfix">
      <h3 class="title-block">
        <span>{l s='You might also like' d='Shop.Theme.Catalog'}</span>
      </h3>

      <div class="product-list">
        <div class="product-list-wrapper clearfix grid {if isset($zoneIsMobile) && $zoneIsMobile}product-mobile-slider js-product-page-mobile-slider{else}columns-6{/if}">
          {foreach from=$accessories item="product_accessory"}
            {include file='catalog/_partials/miniatures/product-simple.tpl' product=$product_accessory}
          {/foreach}
        </div>
      </div>
    </section>
  {/if}
{/block}
