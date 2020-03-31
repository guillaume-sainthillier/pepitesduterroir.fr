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
<div id="order-items">
  {block name='order_items_table_head'}
    <h4>{l s='Order items' d='Shop.Theme.Checkout'}</h4>
    <h4 class="d-none _desktop-title">{l s='Unit price' d='Shop.Theme.Checkout'}</h4>
    <h4 class="d-none _desktop-title">{l s='Quantity' d='Shop.Theme.Checkout'}</h4>
    <h4 class="d-none _desktop-title">{l s='Total products' d='Shop.Theme.Checkout'}</h4>
  {/block}

  <div class="order-confirmation-table grid-small-padding">
    {block name='order_confirmation_table'}
      {foreach from=$products item=product}
        <div class="order-line">
          <div class="row align-items-center">
            <div class="col-md-2 col-3 order-line-left">
              <span class="image">
                {if $product.cover}
                  <img src="{$product.cover.small.url}" alt="{$product.name}" class="img-fluid">
                {else}
                  <img src="{$urls.no_picture_image.small.url}" alt="{$product.name}" class="img-fluid">
                {/if}
              </span>
            </div>
            <div class="col-md-10 col-9 order-line-right">
              <div class="row">
                <div class="col-lg-5 col-12 details">
                  {if $add_product_link}<a href="{$product.url}" target="_blank">{/if}
                    <span class="product-name">{$product.name}</span>
                  {if $add_product_link}</a>{/if}
                  <div class="product-attributes">
                    {foreach from=$product.attributes item=value name=attributes}
                      {if !$smarty.foreach.attributes.first}, {/if}<i>{$value}</i>
                    {/foreach}
                  </div>
                  {if is_array($product.customizations) && $product.customizations|count}
                    {foreach from=$product.customizations item="customization"}
                      <div class="customizations">
                        <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                      </div>
                      <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                            </div>
                            <div class="modal-body">
                              {foreach from=$customization.fields item="field"}
                                <div class="product-customization-line row">
                                  <div class="col-sm-3 col-4 label">
                                    {$field.label}
                                  </div>
                                  <div class="col-sm-9 col-8 value">
                                    {if $field.type == 'text'}
                                      {if (int)$field.id_module}
                                        {$field.text nofilter}
                                      {else}
                                        {$field.text}
                                      {/if}
                                    {elseif $field.type == 'image'}
                                      <img src="{$field.image.small.url}">
                                    {/if}
                                  </div>
                                </div>
                              {/foreach}
                            </div>
                          </div>
                        </div>
                      </div>
                    {/foreach}
                  {/if}
                  {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                </div>
                <div class="col-lg-7 col-12 qty">
                  <div class="row">
                    <div class="col-5 text-left text-lg-center"><strong>{$product.price}</strong></div>
                    <div class="col-2 text-center p-0">{$product.quantity}</div>
                    <div class="col-5 text-right font-weight-bold"><span class="product-price">{$product.total}</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/foreach}

      <div class="order-confirmation-total">
        <div class="row">
          {foreach $subtotals as $subtotal}
            {if $subtotal.type !== 'tax' && $subtotal.label !== null && isset($subtotal.value)}
              <div class="col-8 text-right"><label>{$subtotal.label}</label></div>
              <div class="col-4 text-right">
                {if 'discount' == $subtotal.type}
                  <span class="price price-normal discount-price">-&nbsp;{$subtotal.value}</span>
                {else}
                  <span class="price price-normal">{$subtotal.value}</span>
                {/if}
              </div>
            {/if}
          {/foreach}

          {if $subtotals.tax.label !== null}
            <div class="col-8 text-right"><label>{l s='%label%:' sprintf=['%label%' => $subtotals.tax.label] d='Shop.Theme.Global'}</label></div>
            <div class="col-4 text-right"><span class="price price-normal">{$subtotals.tax.value}</span></div>
          {/if}

          {if !$configuration.display_prices_tax_incl && $configuration.taxes_enabled}
            <div class="col-8 text-right"><label>{$totals.total.label}&nbsp;{$labels.tax_short}</label></div>
            <div class="col-4 text-right"><span class="price">{$totals.total.value}</span></div>
            <div class="col-8 text-right"><label>{$totals.total_including_tax.label}</label></div>
            <div class="col-4 text-right"><span class="price price-total">{$totals.total_including_tax.value}</span></div>
          {else}
            <div class="col-8 text-right"><label>{$totals.total.label}&nbsp;{if $configuration.taxes_enabled}{$labels.tax_short}{/if}</label></div>
            <div class="col-4 text-right"><span class="price price-total">{$totals.total.value}</span></div>
          {/if}
        </div>
      </div>
    {/block}
  </div>
</div>
