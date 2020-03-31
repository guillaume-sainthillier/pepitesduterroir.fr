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

{if $aslides}
<div class="aone-slideshow theme-default {if isset($settings.layout) && $settings.layout == 'boxed'}container{/if}">
  <div class="nivo-slider-overlay" id="js-nivoSliderOverlay">
    {assign var=firstSlide value=$aslides|@reset}
    <img class="img-fluid" src="data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$firstSlide.image_width}%20{$firstSlide.image_height}%22%3E%3C/svg%3E" width="{$firstSlide.image_width}" height="{$firstSlide.image_height}" alt="slider overlay">
  </div>

  <div id="aoneSlider" class="nivoSlider caption-effect-fade" data-settings={$settings|json_encode}>
    {foreach from=$aslides item=aslide name=aslides}
      {if $aslide.slide_link}<a href="{$aslide.slide_link}" title="">{/if}
      
      <img
        src = "data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20{$aslide.image_width}%20{$aslide.image_height}%22%3E%3C/svg%3E"
        data-original = "{$image_baseurl}{$aslide.image}"
        data-src = "{$image_baseurl}{$aslide.image}"
        data-thumb = "{$thumb_baseurl}{$aslide.image}"
        class = "js-lazy"
        alt = "{$aslide.title}"
        title = "{$aslide.title}"
        width = "{$aslide.image_width}"
        height = "{$aslide.image_height}"
        {if $aslide.caption || $aslide.related_products}data-title="#aCaption_{$smarty.foreach.aslides.iteration|intval}"{/if}
      />
      
      {if $aslide.slide_link}</a>{/if}
    {/foreach}
  </div>
  
  {foreach from=$aslides item=aslide name=aslides}
    {if $aslide.caption || $aslide.related_products}
      <div id="aCaption_{$smarty.foreach.aslides.iteration|intval}" class="nivo-html-caption">
        {if $aslide.slide_link}<a class="slide-link" href="{$aslide.slide_link}" title=""></a>{/if}

        {if $aslide.caption}
          <div class="caption-wrapper">
            <div class="caption-content">
              {$aslide.caption nofilter} 
            </div>
          </div>
        {/if}

        {if $aslide.related_products}
          <div class="js-slide-products-related slide-products-related d-none d-xl-block">
            {include file="module:zoneslideshow/views/templates/hook/product-list.tpl" aproducts=$aslide.related_products}
          </div>
        {/if}
      </div>
    {/if}
  {/foreach}
</div>
{/if}
