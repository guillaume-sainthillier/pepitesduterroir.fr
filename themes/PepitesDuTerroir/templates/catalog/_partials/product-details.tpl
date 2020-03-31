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
{block name='product_details'}
  <div class="product-details" id="product-details" data-product="{$product.embedded_attributes|json_encode}">
    
    <div class="js-product-attributes-source d-none">
      {block name='product_manufacturer'}
        {if isset($product_manufacturer->id)}
          <div class="attribute-item product-manufacturer" itemprop="brand" itemtype="http://schema.org/Brand" itemscope>
            <label>{l s='Brand' d='Shop.Theme.Catalog'}</label>
            <a href="{$product_brand_url}" class="li-a" itemprop="url"><span itemprop="name">{$product_manufacturer->name}</span></a>

            {if isset($manufacturer_image_url)}
              <div class="brand-logo">
                <a href="{$product_brand_url}">
                  <img src="{$manufacturer_image_url}" class="img-fluid" alt="{$product_manufacturer->name}" itemprop="logo" />
                </a>
              </div>
            {/if}
          </div>
        {/if}
      {/block}

      {block name='product_reference'}
        {if isset($product.reference_to_display) && $product.reference_to_display neq ''}
          <div class="attribute-item product-reference">
            <label>{l s='Reference' d='Shop.Theme.Catalog'}</label>
            <span itemprop="sku">{$product.reference_to_display}</span>
          </div>
        {/if}
      {/block}

      {block name='product_condition'}
        {if $product.condition}
          <div class="attribute-item product-condition">
            <label>{l s='Condition' d='Shop.Theme.Catalog'}</label>
            <link itemprop="itemCondition" href="{$product.condition.schema_url}"/>
            <span>{$product.condition.label}</span>
          </div>
        {/if}
      {/block}

      {block name='product_width'}
        {if isset($product.width) && ($product.width != 0)}
          <div class="attribute-item product-width d-none">
            <label>{l s='Width' d='Shop.Theme.Catalog'}</label>
            <span>{$product.width|string_format:"%.2f"|replace:'.00':''} {if isset($psDimensionUnit)}{$psDimensionUnit}{/if}</span>
          </div>
        {/if}
      {/block}
      {block name='product_height'}
        {if isset($product.height) && ($product.height != 0)}
          <div class="attribute-item product-height d-none">
            <label>{l s='Height' d='Shop.Theme.Catalog'}</label>
            <span>{$product.height|string_format:"%.2f"|replace:'.00':''} {if isset($psDimensionUnit)}{$psDimensionUnit}{/if}</span>
          </div>
        {/if}
      {/block}
      {block name='product_depth'}
        {if isset($product.depth) && ($product.depth != 0)}
          <div class="attribute-item product-depth d-none">
            <label>{l s='Depth' d='Shop.Theme.Catalog'}</label>
            <span>{$product.depth|string_format:"%.2f"|replace:'.00':''} {if isset($psDimensionUnit)}{$psDimensionUnit}{/if}</span>
          </div>
        {/if}
      {/block}
      {block name='product_weight'}
        {if isset($product.weight) && ($product.weight != 0)}
          <div class="attribute-item product-weight d-none">
            <label>{l s='Weight' d='Shop.Theme.Catalog'}</label>
            <span>{$product.weight|string_format:"%.2f"|replace:'.00':''} {$product.weight_unit}</span>
          </div>
        {/if}
      {/block}

      {block name='product_quantities'}
        {if $product.show_quantities}
          <div class="attribute-item product-quantities">
            <label>{l s='In stock' d='Shop.Theme.Catalog'}</label>
            <span data-stock="{$product.quantity}" data-allow-oosp="{$product.allow_oosp}">{$product.quantity} {$product.quantity_label}</span>
          </div>
        {/if}
      {/block}

      {block name='product_availability_date'}
        {if $product.availability_date}
          <div class="attribute-item product-availability-date">
            <label>{l s='Availability date:' d='Shop.Theme.Catalog'}</label>
            <span>{$product.availability_date}</span>
          </div>
        {/if}
      {/block}

      {block name='product_specific_references'}
        {if !empty($product.specific_references)}
          {foreach from=$product.specific_references item=reference key=key}
            <div class="attribute-item product-specific-references {$key}">
              <label>{$key}</label>
              <span>{$reference}</span>
            </div>
          {/foreach}
        {else}
          {if $product.ean13}
            <div class="attribute-item product-specific-references ean13">
              <label>{l s='ean13' d='Shop.Theme.Catalog'}</label>
              <span>{$product.ean13}</span>
            </div>
          {/if}
          {if $product.isbn}
            <div class="attribute-item product-specific-references isbn">
              <label>{l s='isbn' d='Shop.Theme.Catalog'}</label>
              <span>{$product.isbn}</span>
            </div>
          {/if}
          {if $product.upc}
            <div class="attribute-item product-specific-references ean13">
              <label>{l s='upc' d='Shop.Theme.Catalog'}</label>
              <span>{$product.upc}</span>
            </div>
          {/if}
        {/if}
      {/block}
    </div>

    {block name='product_features'}
      {if $product.grouped_features}
        <section class="product-features">
          <h5>{l s='Data sheet' d='Shop.Theme.Catalog'}</h5>
          <dl class="data-sheet">
            {foreach from=$product.grouped_features item=feature}
              <dt class="name">{$feature.name}</dt>
              <dd class="value">{$feature.value|escape:'htmlall'|nl2br nofilter}</dd>
            {/foreach}
          </dl>
        </section>
      {/if}
    {/block}
  </div>
{/block}
