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

<div id="js-colorsLivePreview">
  <div class="live-preview-title">Preview Colors</div>

  <div class="live-preview-boxed-wide js-boxedWide">
    <a href="#" class="js-previewBoxed">Boxed</a>
    <a href="#" class="js-previewWide active">Wide</a>
    <span class="style">
      <span class="boxed_bg_css js-boxedBackgroundCSS">{$boxed_bg_css}</span>
      <span class="preview"></span>
    </span>
  </div>

  <div class="live-preview-special-style js-specialStyle">
    <label class="custom-checkbox">
      <span>Disable Border Radius</span>
      <span class="check-wrap">
        <input type="checkbox" name="disable_border_radius">
        <span class="check-shape"><i class="material-icons check-icon">check</i></span>
      </span>
    </label>
    <label class="custom-checkbox">
      <span for="disable_box_shadow">Disable Box Shadow</span>
      <span class="check-wrap">
        <input type="checkbox" name="disable_box_shadow">
        <span class="check-shape"><i class="material-icons check-icon">check</i></span>
      </span>
    </label>
    <label class="custom-checkbox">
      <span for="background_block_title">Background for Block Title</span>
      <span class="check-wrap">
        <input type="checkbox" name="background_block_title">
        <span class="check-shape"><i class="material-icons check-icon">check</i></span>
      </span>
    </label>
  </div>

  {if $variables}
  	<div class="live-preview-wrapper">
      <p class="hint">Pick a color, then click OK</p>

      {foreach from=$variables key=k item=v}
        <div class="acolor js-color {$k}">
          <label title="{$v.desc}" data-toggle="tooltip" data-placement="top">{$v.label}</label>
          <div class="color-pick js-colorPicker {$k}" style="background-color: {$v.default};" data-color="{$v.default}"></div>
          {foreach from=$v.styles item=style}
            <span class="style">
              <span class="selector">{$style.selector}</span>
              <span class="property">{$style.property}: {if isset($style.boxShadow)}{$style.boxShadow} {/if}</span>
              <span class="preview"></span>
            </span>
          {/foreach}
        </div>
      {/foreach}
    </div>
  {/if}

  <div class="live-preview-reset"><a href="#" class="js-previewReset">RESET</a></div>
</div>
