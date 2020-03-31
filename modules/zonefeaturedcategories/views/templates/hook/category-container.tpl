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

<div class="category-block">
  <div class="category-container">
    <div class="category-image">
      {if $category.image}
        <a href="{$category.url}">
          {if isset($zoneLazyLoading) && $zoneLazyLoading}
            <img
              src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$category.image.width}%20{$category.image.height}%22%3E%3C/svg%3E"
              data-original = "{$category.image.url}"
              alt = "{$category.meta_title|default: $category.name}"
              title = "{$category.name}"
              class = "img-fluid js-lazy"
              width = "{$category.image.width}"
              height = "{$category.image.height}"
            >
          {else}
            <img
              src = "{$category.image.url}"
              class = "img-fluid"
              alt = "{$category.meta_title|default: $category.name}"
              title = "{$category.name}"
              width = "{$category.image.width}"
              height = "{$category.image.height}"
            >
          {/if}
        </a>
      {/if}
    </div>

    <h3 class="category-name"><a href="{$category.url}" class="li-a">{$category.name}</a></h3>

    <div class="category-des">
      {$category.description|strip_tags|truncate:180}
    </div>

    {if $subCategories}
      <div class="sub-categories">
        <ul class="linklist d-flex flex-wrap">
          {foreach from=$subCategories item=subcategory name=subCategories}
            <li>
              <a class="subcategory-name" href="{$subcategory.url}">{$subcategory.name}</a>
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}
  </div>
</div>
