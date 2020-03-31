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

{if isset($zoneIsMobile) && $zoneIsMobile}
  <!-- mobile header -->
  <div class="mobile-header-version">
    {block name='header_banner'}
      <div class="header-banner clearfix">
        {hook h='displayBanner'}
      </div>
    {/block}

    {block name='header_nav'}
      <div class="header-nav clearfix">
        <div class="container">
          <div class="header-nav-wrapper d-inline-flex align-items-center">
            <div class="left-nav d-flex">
              {hook h='displayNav1'}
            </div>
            <div class="right-nav d-flex">
              {hook h='displayNav2'}
            </div>
          </div>
        </div>
      </div>
    {/block}

    {block name='main_header'}
      <div class="main-header clearfix">
        <div class="container">
          <div class="header-wrapper">

            {block name='header_logo'}
              <div class="header-logo">
                <a href="{$urls.base_url}" title="{$shop.name}">
                  {if isset($svgLogo) && $svgLogo}
                    <img class="svg-logo" src="{$svgLogo}" style="width: {$svgWidth}" alt="{$shop.name}">
                  {elseif isset($zoneLogo) && $zoneLogo}
                    <img class="logo" src="{$zoneLogo.url}" alt="{$shop.name}" width="{$zoneLogo.width}" height="{$zoneLogo.height}">
                  {else}
                    <img class="logo" src="{$shop.logo}" alt="{$shop.name}">
                  {/if}
                </a>
              </div>
            {/block}

            {block name='header_right'}
              <div class="header-right" data-mobile-sticky>
                <div class="mobile-display-left d-flex">
                  <div class="mobile-menu-icon d-flex align-items-center justify-content-center" data-left-nav-trigger>
                    <i class="material-icons">view_headline</i>
                  </div>
                </div>
                <div class="display-top align-items-center d-flex justify-content-end">
                  {hook h='displayTop'}
                </div>
                <div class="sticky-background"></div>
              </div>
            {/block}

          </div>
        </div>
      </div>
    {/block}

    {block name='header-bottom'}
      <div class="header-bottom clearfix">
        <div class="header-main-menu">
          <div class="container">
            {hook h='displayNavFullWidth'}
          </div>
        </div>
      </div>
    {/block}
  </div>

{else}
  <!-- desktop header -->
  <div class="desktop-header-version">
    {block name='header_banner'}
      <div class="header-banner clearfix">
        {hook h='displayBanner'}
      </div>
    {/block}

    {block name='header_nav'}
      <div class="header-nav clearfix">
        <div class="container">
          <div class="header-nav-wrapper d-flex align-items-center justify-content-between">
            <div class="left-nav d-flex">
              {hook h='displayNav1'}
            </div>
            <div class="right-nav d-flex">
              {hook h='displayNav2'}
            </div>
          </div>
        </div>
      </div>
    {/block}

    {block name='main_header'}
      <div class="main-header clearfix">
        <div class="container">
          <div class="header-wrapper d-flex align-items-center">

            {block name='header_logo'}
              <div class="header-logo">
                <a href="{$urls.base_url}" title="{$shop.name}">
                  {if isset($svgLogo) && $svgLogo}
                    <img class="svg-logo" src="{$svgLogo}" style="width: {$svgWidth}" alt="{$shop.name}">
                  {elseif isset($zoneLogo) && $zoneLogo}
                    <img class="logo" src="{$zoneLogo.url}" alt="{$shop.name}" width="{$zoneLogo.width}" height="{$zoneLogo.height}">
                  {else}
                    <img class="logo" src="{$shop.logo}" alt="{$shop.name}">
                  {/if}
                </a>
              </div>
            {/block}

            {block name='header_right'}
              <div class="header-right">
                <div class="display-top align-items-center d-flex flex-md-wrap flex-lg-nowrap justify-content-end">
                  {hook h='displayTop'}
                </div>
              </div>
            {/block}

          </div>
        </div>
      </div>
    {/block}

    {block name='header-bottom'}
      <div class="header-bottom clearfix">
        <div class="header-main-menu" id="header-main-menu" data-sticky-menu>
          <div class="container">
            <div class="header-main-menu-wrapper">
              {if isset($zoneSidebarNavigation) && $zoneSidebarNavigation}
                <div class="left-nav-trigger" data-left-nav-trigger>
                  <div class="left-nav-icon d-flex align-items-center justify-content-center">
                    <i class="material-icons">view_headline</i>
                  </div>
                </div>
              {/if}
              {hook h='displayNavFullWidth'}
              <div class="sticky-icon-cart" data-sticky-cart data-sidebar-cart-trigger></div>
            </div>
          </div>
        </div>
      </div>
    {/block}
  </div>

{/if}


