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
{if $product.show_price}
  <div class="product-prices sm-bottom">
    {block name='product_price'}
      <div
        itemprop="offers"
        itemscope
        itemtype="https://schema.org/Offer"
        class="product-prices-wrapper d-flex flex-wrap align-items-center"
      >
        <meta itemprop="priceValidUntil" content="{($smarty.now + (86400*15))|date_format:'%Y-%m-%d'}"/>
        <meta itemprop="availability" content="{$product.seo_availability}"/>
        <meta itemprop="priceCurrency" content="{$currency.iso_code}"/>
        <meta itemprop="price" content="{$product.price_amount}"/>
        <link itemprop="url" href="{$product.link}"/>

        <span class="price product-price"><span class="current-price">{$product.price}</span> <span class="tax-label-next-price">{l s='(tax incl.)' d='Shop.Theme.Global'}</span></span>
        {block name='product_unit_price'}
          {if $displayUnitPrice}
            <span class="product-unit-price sub">{l s='(%unit_price%)' d='Shop.Theme.Catalog' sprintf=['%unit_price%' => $product.unit_price_full]}</span>
          {/if}
        {/block}

        {if $product.has_discount}
          {hook h='displayProductPriceBlock' product=$product type="old_price"}

          <span class="regular-price">{$product.regular_price}</span>

          {if $product.discount_type === 'percentage'}
            <span class="discount-percentage">{$product.discount_percentage}</span>
          {else}
            <span class="discount-amount">- {$product.discount_to_display}</span>
          {/if}
        {/if}

        <span class="w-100 show-more-without-taxes">{Tools::displayPrice($product.price_tax_exc)} <span class="tax-label-next-price">{l s='(tax excl.)' d='Shop.Theme.Global'}</span></span>
      </div>
    {/block}

    {block name='product_without_taxes'}
      {if $priceDisplay == 2}
        <p class="product-without-taxes">{l s='%price% tax excl.' d='Shop.Theme.Catalog' sprintf=['%price%' => $product.price_tax_exc]}</p>
      {/if}
    {/block}

    {block name='product_pack_price'}
      {if $displayPackPrice}
        <p class="regular-price product-pack-price"><span>{l s='Instead of %price%' d='Shop.Theme.Catalog' sprintf=['%price%' => $noPackPrice]}</span></p>
      {/if}
    {/block}

    {block name='product_ecotax'}
      {if $product.ecotax.amount > 0}
        <p class="price-ecotax">{l s='Including %amount% for ecotax' d='Shop.Theme.Catalog' sprintf=['%amount%' => $product.ecotax.value]}
          {if $product.has_discount}
            {l s='(not impacted by the discount)' d='Shop.Theme.Catalog'}
          {/if}
        </p>
      {/if}
    {/block}

    {hook h='displayProductPriceBlock' product=$product type="weight" hook_origin='product_sheet'}

    <div class="tax-shipping-delivery-label">
      {if !$configuration.taxes_enabled}
        <span class="labels-no-tax tax-label">{l s='No tax' d='Shop.Theme.Catalog'}</span>
      {elseif $configuration.display_taxes_label}
        <span class="labels-tax-long tax-label">{$product.labels.tax_long}</span>
      {/if}
      {hook h='displayProductPriceBlock' product=$product type="price"}
      {hook h='displayProductPriceBlock' product=$product type="after_price"}
      {if $product.additional_delivery_times == 1}
        {if $product.delivery_information}
          <span class="delivery-information">{$product.delivery_information}</span>
        {/if}
      {elseif $product.additional_delivery_times == 2}
        {if $product.quantity > 0}
          <span class="delivery-information">{$product.delivery_in_stock}</span>
        {elseif $product.quantity <= 0 && $product.add_to_cart_url}
          <span class="delivery-information">{$product.delivery_out_stock}</span>
        {/if}
      {/if}
    </div>

    {if isset($product.specific_prices) && isset($product.specific_prices.to) && $product.specific_prices.to != '0000-00-00 00:00:00'}
      <div class="js-new-specific-prices-to" data-new-specific-prices-to="{$product.specific_prices.to}"></div>
    {/if}
  </div>
{/if}
