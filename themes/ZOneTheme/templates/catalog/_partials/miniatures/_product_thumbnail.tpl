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

{block name='product_thumbnail'}
  <div class="product-thumbnail">
    <a href="{$product.url}" class="product-cover-link">
      {if $product.cover}
        {if !isset($image_type) || !array_key_exists($image_type, $product.cover.bySize)}
          {assign var='image_type' value='home_default'}
        {/if}
        {assign var=thumbnail value=$product.cover.bySize.$image_type}

        {if isset($zoneLazyLoading) && $zoneLazyLoading}
          <img
            src       = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$thumbnail.width}%20{$thumbnail.height}%22%3E%3C/svg%3E"
            data-original = "{$thumbnail.url}"
            alt       = "{$product.cover.legend|default: $product.name}"
            title     = "{$product.name}"
            class     = "img-fluid js-lazy"
            width     = "{$thumbnail.width}"
            height    = "{$thumbnail.height}"
          >
        {else}
          <img
            src       = "{$thumbnail.url}"
            alt       = "{$product.cover.legend|default: $product.name}"
            class     = "img-fluid"
            title     = "{$product.name}"
            width     = "{$thumbnail.width}"
            height    = "{$thumbnail.height}"
          >
        {/if}
      {else}
        <img
          src       = "{$urls.no_picture_image.medium.url}"
          class     = "img-fluid"
          alt       = "{$product.name}"
          title     = "{$product.name}"
          width     = "{$urls.no_picture_image.medium.width}"
          height    = "{$urls.no_picture_image.medium.height}"
        >
      {/if}
    </a>
  </div>
{/block}
