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
<div class="images-container">
  <div class="images-container-wrapper {if isset($product_imageZoom) && $product_imageZoom}js-enable-zoom-image{else}js-cover-image{/if}">
    {if $product.cover}
      <meta itemprop="image" content="{$product.cover.bySize.medium_default.url}" />

      {if isset($zoneIsMobile) && $zoneIsMobile}
        {block name='product_images_mobile'}
          <div class="product-cover sm-bottom">
            <div class="flex-scrollbox-wrapper js-mobile-images-scrollbox">
              <ul class="product-mobile-images">
                {foreach from=$product.images item=image}
                  <li>
                    {if isset($zoneLazyLoading) && $zoneLazyLoading && $image@first}
                      <img
                        src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$image.bySize.medium_default.width}%20{$image.bySize.medium_default.height}%22%3E%3C/svg%3E"
                        data-original = "{$image.bySize.medium_default.url}"
                        class = "img-fluid js-lazy"
                        alt = "{$product.cover.legend|default: $product.name}"
                        width = "{$image.bySize.medium_default.width}"
                        height = "{$image.bySize.medium_default.height}"
                      >
                    {else}
                      <img
                        src = "{$image.bySize.medium_default.url}"
                        class = "img-fluid"
                        alt = "{$product.cover.legend|default: $product.name}"
                        width = "{$image.bySize.medium_default.width}"
                        height = "{$image.bySize.medium_default.height}"
                      >
                    {/if}
                  </li>
                {/foreach}
              </ul>
            </div>
            <div class="scroll-box-arrows">
              <i class="material-icons left">chevron_left</i>
              <i class="material-icons right">chevron_right</i>
            </div>
          </div>
        {/block}
      {else}
        {block name='product_cover'}
          <div class="product-cover sm-bottom">
            {if isset($zoneLazyLoading) && $zoneLazyLoading}
              <img
                src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$product.cover.bySize.medium_default.width}%20{$product.cover.bySize.medium_default.height}%22%3E%3C/svg%3E"
                data-original = "{$product.cover.bySize.medium_default.url}"
                class = "img-fluid js-qv-product-cover js-main-zoom js-lazy"
                alt = "{$product.cover.legend|default: $product.name}"
                data-zoom-image = "{$product.cover.bySize.large_default.url}"
                data-id-image = "{$product.cover.id_image}"
                width = "{$product.cover.bySize.medium_default.width}"
                height = "{$product.cover.bySize.medium_default.height}"
              >
            {else}
              <img
                src = "{$product.cover.bySize.medium_default.url}"
                class = "img-fluid js-qv-product-cover js-main-zoom"
                alt = "{$product.cover.legend|default: $product.name}"
                data-zoom-image = "{$product.cover.bySize.large_default.url}"
                data-id-image = "{$product.cover.id_image}"
                width = "{$product.cover.bySize.medium_default.width}"
                height = "{$product.cover.bySize.medium_default.height}"
              >
            {/if}
            <div class="layer d-flex align-items-center justify-content-center">
              <span class="zoom-in js-mfp-button"><i class="material-icons">zoom_out_map</i></span>
            </div>
          </div>
        {/block}

        {block name='product_images'}
          <div class="thumbs-list">
            <div class="flex-scrollbox-wrapper js-product-thumbs-scrollbox">
              <ul class="product-images" id="js-zoom-gallery">
                {foreach from=$product.images item=image}
                  <li class="thumb-container">
                    <a
                      class="thumb js-thumb {if $image.id_image == $product.cover.id_image}selected{/if}"
                      data-image="{$image.bySize.medium_default.url}"
                      data-zoom-image="{$image.bySize.large_default.url}"
                      data-id-image="{$image.id_image}"
                    >
                      {if isset($zoneLazyLoading) && $zoneLazyLoading}
                        <img
                          src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$image.bySize.small_default.width}%20{$image.bySize.small_default.height}%22%3E%3C/svg%3E"
                          data-original = "{$image.bySize.small_default.url}"
                          alt = "{$image.legend|default: $product.name}"
                          class = "img-fluid js-lazy"
                          width = "{$image.bySize.small_default.width}"
                          height = "{$image.bySize.small_default.height}"
                        >
                      {else}
                        <img
                          src = "{$image.bySize.small_default.url}"
                          alt = "{$image.legend|default: $product.name}"
                          class = "img-fluid"
                          width = "{$image.bySize.small_default.width}"
                          height = "{$image.bySize.small_default.height}"
                        >
                      {/if}
                    </a>
                  </li>
                {/foreach}
              </ul>
            </div>

            <div class="scroll-box-arrows">
              <i class="material-icons left">chevron_left</i>
              <i class="material-icons right">chevron_right</i>
            </div>
          </div>
        {/block}
      {/if}
    {else}
      {block name='product_cover'}
        <div class="product-cover sm-bottom">
          <img src="{$urls.no_picture_image.bySize.medium_default.url}" class="img-fluid" alt="{$product.name}">
        </div>
      {/block}
    {/if}
  </div>

  {hook h='displayAfterProductThumbs'}
</div>
