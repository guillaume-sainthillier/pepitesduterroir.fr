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

<div class="product-variants">
{if $groups}
  {block name='product_combinations'}
    {if isset($product_attributesLayout) && $product_attributesLayout == 'combinations'}
      {hook h='displayProductCombinationsBlock' combinations=$combinations groups=$groups id_product=$product.id id_product_attribute=$product.id_product_attribute minimal_quantity=$product.minimal_quantity}
    {/if}
  {/block}
  
  {block name='product_swatches'}
    {if isset($product_attributesLayout) && $product_attributesLayout == 'swatches'}
      {include file='catalog/_partials/product-swatches.tpl'}
    {/if}
  {/block}

  <div class="product-variants-wrapper sm-bottom {if isset($product_attributesLayout) && $product_attributesLayout != 'default'}d-none{/if}">
    {foreach from=$groups key=id_attribute_group item=group}
      {if !empty($group.attributes)}
        <div class="product-variants-item row">
          <label class="form-control-label col-3">{$group.name}</label>

          <div class="attribute-list col-9">
            {if $group.group_type == 'select'}
              <select
                id="group_{$id_attribute_group}"
                data-product-attribute="{$id_attribute_group}"
                name="group[{$id_attribute_group}]"
                class="form-control form-control-select select-group"
              >
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <option value="{$id_attribute}" title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if}>{$group_attribute.name}</option>
                {/foreach}
              </select>
            {elseif $group.group_type == 'color'}
              <ul id="group_{$id_attribute_group}" class="color-group d-flex flex-wrap align-items-center">
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <li>
                    <label class="custom-radio custom-color" title="{$group_attribute.name}">
                      <span class="check-wrap">
                        <input class="input-color" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                        {if $group_attribute.texture}
                          <span class="check-shape color texture" style="background-image: url({$group_attribute.texture})"><span class="check-circle"></span></span>
                        {elseif $group_attribute.html_color_code}
                          <span class="check-shape color" style="background-color: {$group_attribute.html_color_code}"><span class="check-circle"></span></span>
                        {/if}
                      </span>
                      <span class="color-name">{$group_attribute.name}</span>
                    </label>
                  </li>
                {/foreach}
              </ul>
            {elseif $group.group_type == 'radio'}
              <ul id="group_{$id_attribute_group}" class="radio-group">
                {foreach from=$group.attributes key=id_attribute item=group_attribute}
                  <li>
                    <label class="custom-radio">
                      <span class="check-wrap">
                        <input class="input-radio" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                        <span class="check-shape"><i class="material-icons check-icon">check</i></span>
                      </span>
                      <span>{$group_attribute.name}</span>
                    </label>
                  </li>
                {/foreach}
              </ul>
            {/if}
          </div>
        </div>
      {/if}
    {/foreach}
  </div>
{/if}
</div>
