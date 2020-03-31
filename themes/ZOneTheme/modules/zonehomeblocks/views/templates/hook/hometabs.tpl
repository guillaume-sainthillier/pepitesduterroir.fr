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
{if $homeTabs}
<div class="aone-tabs">
  <ul class="nav nav-tabs">
    {foreach from=$homeTabs item=block name=homeTabs}
      <li class="nav-item">
        <a 
          class="nav-link {if $smarty.foreach.homeTabs.first}active{/if}"
          data-toggle="tab"
          href="#aHomeTab{$block.id}"
          data-slickID="aSlick{$block.id}"
        >
          <span>{$block.title}</span>
        </a>
      </li>
    {/foreach}
  </ul>

  <div class="tab-content">
    {foreach from=$homeTabs item=block name=homeTabs}
      <div id="aHomeTab{$block.id}" class="tab-pane fade {if $smarty.foreach.homeTabs.first}show active{/if}">
        {if $block.block_type == $blocktype_product}

          {include file="module:zonehomeblocks/views/templates/hook/block-product.tpl" ablock=$block}

          {if $block.show_more_link}
            <div class="view-all-link at-bottom">
              <a class="btn btn-secondary" href="{$block.show_more_link}">{l s='Show More' d='Shop.Zonetheme'} &nbsp;<i class="material-icons trending_flat"></i></a>
            </div>
          {/if}
        {elseif $block.block_type == $blocktype_html}
          {include file="module:zonehomeblocks/views/templates/hook/block-html.tpl" ablock=$block}
        {/if}
      </div>
    {/foreach}
  </div>
</div>
{/if}
