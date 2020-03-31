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

<div id="aone-popup-newsletter-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-hidepopup-time="{$cookie_time|intval}">
  <div class="modal-dialog" role="document" style="max-width: {$width + 16}px;">
    <div class="modal-content">
      <div class="modal-body">
        <div class="aone-popupnewsletter" style="min-height:{$height|intval}px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="material-icons">close</i>
          </button>

          <div class="popup-background" style="background-color:{$bg_color}; background-image:url('{$bg_image}');"></div>
          <div class="popup-content">
            <div class="clearfix newsletter-content">
              {$content nofilter}
            </div>
            <div class="ps-email-subscription-module js-popupemailsubscription">
              {$subscribe_form nofilter}
            </div>
          </div>
          <div class="noshow">
            <a href="#no-thanks" class="js-newsletter-nothanks" rel="nofollow"><i class="fa fa-minus-circle"></i>{l s='Do not show this popup again.' d='Shop.Zonetheme'}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
