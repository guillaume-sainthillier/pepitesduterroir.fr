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

<div class="brand-base">
  <div class="brand-container">
    <div class="logo">
      <a href="{$brand.url}" title="{$brand.name}">
        {if $brand.image}
          {if isset($zoneLazyLoading) && $zoneLazyLoading}
            <img
              src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$imageSize.width}%20{$imageSize.height}%22%3E%3C/svg%3E"
              data-original  = "{$brand.image}"
              alt = "{$brand.name}"
              title = "{$brand.name}"
              class = "img-fluid js-lazy"
              width = "{$imageSize.width}"
              height = "{$imageSize.height}"
            >
          {else}
            <img
              src = "{$brand.image}"
              class = "img-fluid"
              alt = "{$brand.name}"
              title = "{$brand.name}"
              width = "{$imageSize.width}"
              height = "{$imageSize.height}"
            />
          {/if}
        {else}
          {$brand.name}
        {/if}
      </a>
    </div>
    {if $showName}
      <div class="middle-side">
        <a class="product-name" href="{$brand.url}">{$brand.name}</a>
      </div>
    {/if}
  </div>
</div>
