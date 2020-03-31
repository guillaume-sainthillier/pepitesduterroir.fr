{*
* 2007-2019 PrestaShop and Contributors
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA and Contributors
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $extraFields}
<div class="zone-product-extra-fields hook-{$hookName} mb-3" data-key-zone-product-extra-fields>
  {foreach from=$extraFields item=field name=extraFields}
    <div class="product-right-extra-field">
      {if $field.popup}
        <div class="extra-field-type-modal {if $field.custom_class}{$field.custom_class}{/if}">
          <button type="button" class="btn btn-link extra-title" data-extrafield="popup" data-width="{$field.popup_width}" data-content="{$field.content}">
            {if $field.title_image}<img src="{$image_path}{$field.title_image}" class="extra-title-image" alt="" />{/if}<span>{$field.title}</span>
          </button>
        </div>
      {else}
        <div class="{if $field.custom_class}{$field.custom_class}{/if}">
          <div class="extra-content typo">
            {$field.content nofilter}
          </div>
        </div>
      {/if}
    </div>
  {/foreach}
</div>
{/if}