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
{if $ablock.products}
  {if $ablock.enable_slider}
    <div class="product-list">
      {if $mobile_device}
        <div class="product-list-wrapper clearfix {if $ablock.number_column > 2}grid{else}list{/if} product-mobile-slider js-items-mobile-slider">
          {if $ablock.number_column > 2}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-mobile-grid.tpl' product=$product}
            {/foreach}
          {else}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-mobile-list.tpl' product=$product}
            {/foreach}
          {/if}
        </div>
      {else}
        <div
          class="product-list-wrapper clearfix {if $ablock.number_column > 2}grid{else}list{/if} columns-{$ablock.number_column|intval} columns-slick js-home-block-slider"
          id="aSlick{$ablock.id}"
          data-slickoptions="{$ablock.slider_options|json_encode}"
        >
          {if $ablock.number_column > 2}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-home-grid.tpl' product=$product}
            {/foreach}
          {else}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-home-list.tpl' product=$product}
            {/foreach}
          {/if}
        </div>
      {/if}
    </div>
  {else}
    <div class="product-list">
      <div class="product-list-wrapper clearfix {if $ablock.number_column > 2}grid{else}list{/if} columns-{$ablock.number_column|intval}">
        {if $mobile_device}
          {if $ablock.number_column > 2}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-mobile-grid.tpl' product=$product}
            {/foreach}
          {else}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-mobile-list.tpl' product=$product}
            {/foreach}
          {/if}
        {else}
          {if $ablock.number_column > 2}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-home-grid.tpl' product=$product}
            {/foreach}
          {else}
            {foreach from=$ablock.products item="product"}
              {include file='catalog/_partials/miniatures/product-home-list.tpl' product=$product}
            {/foreach}
          {/if}
        {/if}
      </div>
    </div>
  {/if}
{/if}