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

<div class="product-tabs">
  <ul class="nav nav-tabs flex-lg-nowrap">
    {if $product.description}
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#collapseDescription">
        <span>{l s='Description' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    {/if}
    {if $product.grouped_features}
    <li class="nav-item">
      <a class="nav-link {if !$product.description}active{/if}" data-toggle="tab" href="#collapseDetails">
        <span>{l s='Data sheet' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    {/if}
    {if $product.attachments}
    <li class="nav-item">
      <a class="nav-link {if !$product.description && $product.grouped_features}active{/if}" data-toggle="tab" href="#collapseAttachments">
        <span>{l s='Attachments' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    {/if}
    {if $product.extraContent}
    {foreach from=$product.extraContent item=extra key=extraKey name=productExtraContent}
      <li class="nav-item">
        <a class="nav-link {if !$product.description && !$product.grouped_features && !$product.attachments && $smarty.foreach.productExtraContent.first}active{/if}" data-toggle="tab" href="#collapseExtra{$extraKey}">
          <span>{$extra.title}</span>
        </a>
      </li>
    {/foreach}
    {/if}
  </ul>
  <div class="tab-content light-box-bg">
    <div id="collapseDescription" class="product-description-block tab-pane fade {if $product.description}show active{/if}">
      <div class="panel-content">
        {include file='catalog/_partials/product-description.tpl'}
      </div>
    </div>
    <div id="collapseDetails" class="product-features-block tab-pane fade {if $product.grouped_features && !$product.description}show active{/if}">
      <div class="panel-content">
        {include file='catalog/_partials/product-details.tpl'}
      </div>
    </div>
    {if $product.attachments}
    <div id="collapseAttachments" class="product-attachments-block tab-pane fade {if !$product.description && !$product.grouped_features}show active{/if}">
      <div class="panel-content">
        {include file='catalog/_partials/product_attachments.tpl'}
      </div>
    </div>
    {/if}
    {if $product.extraContent}
    {foreach from=$product.extraContent item=extra key=extraKey name=productExtraContent}
      <div id="collapseExtra{$extraKey}" class="product-extra-block tab-pane fade {if !$product.description && !$product.grouped_features && !$product.attachments && $smarty.foreach.productExtraContent.first}show active{/if}">
        <div class="panel-content" {foreach $extra.attr as $key => $val}{if $val}{$key}="{$val}" {/if}{/foreach}>
          <div class="extra-content typo">
            {$extra.content nofilter}
          </div>
        </div>
      </div>
    {/foreach}
    {/if}
  </div>
</div><!-- /tabs -->
