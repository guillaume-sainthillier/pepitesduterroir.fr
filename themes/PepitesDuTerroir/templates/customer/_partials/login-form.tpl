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
{block name='login_form'}

  {block name='login_form_errors'}
    {include file='_partials/form-errors.tpl' errors=$errors['']}
  {/block}

  <form id="login-form" action="{block name='login_form_actionurl'}{$action}{/block}" method="post">

    {block name='login_form_fields'}
      {foreach from=$formFields item="field"}
        {block name='form_field'}
          {form_field field=$field}
        {/block}
      {/foreach}
    {/block}

    {block name='login_form_footer'}
      <div class="form-group form-footer row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6 text-center">
          <input type="hidden" name="submitLogin" value="1">
          {block name='form_buttons'}
            <button id="submit-login" class="btn btn-primary form-control-submit" data-link-action="sign-in" type="submit">
              {l s='Sign in' d='Shop.Theme.Actions'} <i class="fa fa-sign-in" aria-hidden="true"></i>
            </button>
          {/block}
        </div>
      </div>
    {/block}

    <div class="forgot-password row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6 text-center">
        <a href="{$urls.pages.password}" rel="nofollow" class="text-danger text-monospace">
          <i class="material-icons">lock_open</i> {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
        </a>
      </div>
    </div>

  </form>
{/block}