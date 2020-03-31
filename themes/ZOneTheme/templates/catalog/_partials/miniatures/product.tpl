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

{block name='product_miniature_item'}
<article class="product-miniature product-style js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" data-product-id-category="{$product.id_category_default}" data-product-id-manufacturer="{$product.id_manufacturer}" itemscope itemtype="https://schema.org/Product">
  <div class="product-container">
    <div class="first-block">
      {include file='catalog/_partials/miniatures/_product_thumbnail.tpl'}

      {block name='product_flags'}
        {if $product.flags}
          <a href="{$product.url}">{include file='catalog/_partials/product-flags.tpl'}</a>
        {/if}
      {/block}

      {block name='grid_hover'}
        <div class="grid-hover-btn">
          {include file='catalog/_partials/miniatures/_product_quickview.tpl'}
        </div>
      {/block}

      {include file='catalog/_partials/miniatures/_product_countdown.tpl'}
    </div>

    <div class="second-block">
      {block name='product_name'}
        <h5 class="product-name"><a href="{$product.url}" title="{$product.name}">{$product.name}</a></h5>
      {/block}

      <div class="second-block-wrapper">
        <div class="informations-section">
          <div class="price-and-status">
            {block name='product_price_and_shipping'}
              {if $product.show_price}
                <div class="product-price-and-shipping d-flex flex-wrap align-items-center" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                  <div class="first-prices d-flex flex-wrap align-items-center">
                    {hook h='displayProductPriceBlock' product=$product type="before_price"}

                    <span class="price product-price" itemprop="price" content="{$product.price_amount}">{$product.price}</span>
                  </div>

                  {if $product.has_discount}
                    <div class="second-prices d-flex flex-wrap align-items-center {if isset($ps_legalcompliance_spl) && $ps_legalcompliance_spl}has-aeuc{/if}">
                      {hook h='displayProductPriceBlock' product=$product type="old_price"}

                      <span class="regular-price">{$product.regular_price}</span>

                      {if $product.discount_type === 'percentage'}
                        <span class="discount-product discount-percentage">{$product.discount_percentage}</span>
                      {elseif $product.discount_type === 'amount'}
                        <span class="discount-product discount-amount">{$product.discount_amount_to_display}</span>
                      {/if}
                    </div>
                  {/if}

                  <div class="tax-shipping-delivery-label d-none">
                    {if $configuration.display_taxes_label}
                      <span class="aeuc_tax_label">{$product.labels.tax_short}</span>
                    {/if}
                    {hook h='displayProductPriceBlock' product=$product type="price"}
                  </div>

                  <div class="third-prices d-flex flex-wrap align-items-center">
                    {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                    {hook h='displayProductPriceBlock' product=$product type="weight"}
                  </div>

                  <meta itemprop="availability" content="{$product.seo_availability}"/>
                  <link itemprop="url" href="{$product.url}" />
                  {if isset($currency)}<meta itemprop="priceCurrency" content="{$currency.iso_code}">{/if}
                </div>
              {/if}
            {/block}

            {block name='product_availability'}
              {if $product.show_availability && $product.availability_message}
                <div class="product-availability">
                  <span class='{$product.availability}'>{$product.availability_message}</span>
                </div>
              {/if}
            {/block}
          </div>

          {block name='product_reviews'}
            {hook h='displayProductListReviews' product=$product}
          {/block}

          {block name='product_description_short'}
            <div class="product-description-short" itemprop="description">{$product.description_short|strip_tags:false}</div>
          {/block}

          {block name='product_variants'}
            {if $product.main_variants}
              {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
            {/if}
          {/block}
        </div>
        <div class="buttons-sections">
          {if isset($zoneNotCatalogMode) && $zoneNotCatalogMode && $product.add_to_cart_url && $product.customizable == 0 && $product.minimal_quantity > 0}
            <div class="addtocart-quantity product-quantity-touchspin d-none">
              <input
                type="number"
                name="qty"
                value="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity}{$product.product_attribute_minimal_quantity}{else}{$product.minimal_quantity}{/if}"
                class="form-control js-add-to-cart-quantity"
                min="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity}{$product.product_attribute_minimal_quantity}{else}{$product.minimal_quantity}{/if}"
                data-id-product="{$product.id_product}"
              />
            </div>
          {/if}
          
          {block name='product_grid_buy'}
            <div class="grid-buy-button">
              {if isset($zoneNotCatalogMode) && $zoneNotCatalogMode && $product.add_to_cart_url && $product.customizable == 0 && $product.minimal_quantity == 1}
                <a
                  class="btn add-to-cart js-ajax-add-to-cart" 
                  href="{$product.url}"
                  data-id-product="{$product.id_product}"
                >
                  <span>{l s='Buy' d='Shop.Zonetheme'}</span>
                </a>
              {else}
                <a class="btn add-to-cart details-link" href="{$product.url}">
                  <span>{l s='Details' d='Shop.Zonetheme'}</span>
                </a>
              {/if}
            </div>
          {/block}

          {block name='product_add_to_cart'}
            <div class="add-to-cart-button">
              {if isset($zoneNotCatalogMode) && $zoneNotCatalogMode && $product.add_to_cart_url && $product.customizable == 0 && $product.minimal_quantity == 1}
                <a
                  class="btn add-to-cart js-ajax-add-to-cart" 
                  href="{$product.url}"
                  data-id-product="{$product.id_product}"
                >
                  <i class="fa fa-plus" aria-hidden="true"></i><span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
                </a>
              {else}
                <a class="btn add-to-cart details-link" href="{$product.url}">
                  <span>{l s='View details' d='Shop.Zonetheme'}</span> &nbsp;<i class="caret-right"></i>
                </a>
              {/if}
            </div>
          {/block}

          {block name='product_actions'}
            <div class="product-actions">{hook h='displayProductListFunctionalButtons' product=$product}</div>
          {/block}
        </div>
      </div>
    </div>
  </div><!-- /product-container -->

  {if $product.cover}<link itemprop="image" href="{$product.cover.medium.url}" />{/if}
  {if $product.reference_to_display}<meta itemprop="sku" content="{$product.reference_to_display}" />{/if}
  {if $product.manufacturer_name}
    <div itemprop="brand" itemtype="http://schema.org/Brand" itemscope>
      <meta itemprop="name" content="{$product.manufacturer_name}" />
    </div>
  {/if}
  {if isset($product.ean13) && $product.ean13 != ''}
    <meta itemprop="gtin13" content="{$product.ean13}" />
  {elseif isset($product.upc) && $product.upc != ''}
    <meta itemprop="gtin13" content="{$product.upc}" />
  {elseif isset($product.isbn) && $product.isbn != ''}
    <meta itemprop="gtin13" content="{$product.isbn}" />
  {/if}
</article>
{/block}
