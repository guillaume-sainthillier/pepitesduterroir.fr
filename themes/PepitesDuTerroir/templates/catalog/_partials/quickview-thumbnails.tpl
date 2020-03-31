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

<div class="quickview-images-container">
  {if $product.cover}
    <div class="product-cover sm-bottom">
      <img
        src = "{$product.cover.bySize.medium_default.url}"
        class = "img-fluid js-qv-product-cover"
        alt = "{$product.cover.legend|default: $product.name}"
        width = "{$product.cover.bySize.medium_default.width}"
        height = "{$product.cover.bySize.medium_default.height}"
      >
    </div>
    <div class="thumbs-list">
      <div class="flex-scrollbox-wrapper js-product-thumbs-scrollbox">
        <ul class="product-images">
          {foreach from=$product.images item=image}
            <li class="thumb-container">
              <a
                class="thumb js-thumb {if $image.id_image == $product.cover.id_image}selected{/if}"
                data-image="{$image.bySize.medium_default.url}"
              >
                <img
                  src = "{$image.bySize.small_default.url}"
                  alt = "{$image.legend|default: $product.name}"
                  class = "img-fluid"
                  width = "{$image.bySize.small_default.width}"
                  height = "{$image.bySize.small_default.height}"
                >
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
  {else}
    <div class="product-cover">
      <img src="{$urls.no_picture_image.bySize.medium_default.url}" class="img-fluid" alt="{$product.name}">
    </div>
  {/if}
</div>
