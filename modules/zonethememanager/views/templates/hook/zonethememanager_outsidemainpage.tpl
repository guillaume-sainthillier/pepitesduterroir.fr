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

{if $cookieMessage}
  <div id="cookieMessage" class="cookie-message js-cookieMessage">
    <div class="cookie-message-wrapper">
      <div class="cookie-message-content">
        {$cookieMessage nofilter}
      </div>
      <a class="cookie-close-button btn js-cookieCloseButton">{l s='Accept' d='Shop.Zonetheme'}</a>
    </div>
  </div>
{/if}

{if $enableScrollTop}
  <div id="scrollTopButton" data-scroll-to-top>
    <a class="scroll-button" href="#scroll-to-top" title="{l s='Back to Top' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top"><i class="fa fa-angle-double-up"></i></a>
  </div>
{/if}
