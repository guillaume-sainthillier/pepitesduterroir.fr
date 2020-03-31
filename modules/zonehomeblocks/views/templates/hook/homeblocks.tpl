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
{foreach from=$homeBlocks item=block name=homeBlocks}
  <div class="block block_id_{$block.id} clearfix {$block.custom_class}">
    {if $block.block_type == $blocktype_product}    
      <div class="title-block d-flex flex-wrap">
        <span>{$block.title}</span>
        {if $block.show_more_link}
          <span class="view-all-link">
            <a href="{$block.show_more_link}">{l s='Show More' d='Shop.Zonetheme'} &nbsp;<i class="material-icons trending_flat"></i></a>
          </span>
        {/if}
      </div>
      
      {include file="module:zonehomeblocks/views/templates/hook/block-product.tpl" ablock=$block}

    {elseif $block.block_type == $blocktype_html}
      {include file="module:zonehomeblocks/views/templates/hook/block-html.tpl" ablock=$block}

    {elseif $block.block_type == $blocktype_tabs}
      {if $mobile_device}
        {include file="module:zonehomeblocks/views/templates/hook/hometabs-mobile.tpl" homeTabs=$block.home_tabs}
      {else}
        {include file="module:zonehomeblocks/views/templates/hook/hometabs.tpl" homeTabs=$block.home_tabs}
      {/if}
    {/if}
  </div>
{/foreach}