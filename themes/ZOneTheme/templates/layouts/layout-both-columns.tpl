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
<!doctype html>
<html lang="{$language.iso_code}">
  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames} {if isset($zoneBodyClasses)}{$zoneBodyClasses}{/if} st-wrapper">

    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    {include file="_partials/st-menu-left.tpl"}

    <main id="page" class="st-pusher">

      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}

      <header id="header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>

      <section id="wrapper">

        {include file='_partials/breadcrumb.tpl'}

        {block name='notifications'}
          {include file='_partials/notifications.tpl'}
        {/block}

        {hook h="displayWrapperTop"}

        {block name='top_content'}{/block}

        {block name='main_content'}
          <div class="main-content">
            <div class="container">
              <div class="row {if isset($zoneIsMobile) && $zoneIsMobile}mobile-main-content{/if}">

                {block name='left_column'}
                  <div id="left-column" class="sidebar-column col-12 col-md-4 col-lg-3">
                    <div class="column-wrapper">
                      {hook h="displayLeftColumn"}
                    </div>
                  </div>
                {/block}

                {block name='content_wrapper'}
                  <div id="center-column" class="center-column col-12 col-md-8 col-lg-9">
                    <div class="center-wrapper">
                      {hook h="displayContentWrapperTop"}
                      
                      {block name='content'}
                        <p>Hello! This is ZONE theme layout.</p>
                      {/block}

                      {hook h="displayContentWrapperBottom"}
                    </div>
                  </div>
                {/block}

                {block name='right_column'}
                  <div id="right-column" class="sidebar-column col-12 col-md-4 col-lg-3">
                    <div class="column-wrapper">
                      {hook h="displayRightColumn"}
                    </div>
                  </div>
                {/block}
                  
              </div><!-- /row -->
            </div><!-- /container -->
          </div><!-- /main-content -->
        {/block}

        {block name='bottom-content'}{/block}

        {hook h="displayWrapperBottom"}

      </section>

      <footer id="footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>

    {block name='hook_outside_main_page'}
      {hook h='displayOutsideMainPage'}
    {/block}

    {include file="_partials/st-menu-right.tpl"}

    {include file="_partials/modal-message.tpl"}

    <div class="st-overlay" data-close-st-menu></div>

    {block name='external_html'}{/block}

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}

  </body>
</html>
