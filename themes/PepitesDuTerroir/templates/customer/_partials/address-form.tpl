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
{block name="address_form"}
  <div class="js-address-form">
    {include file='_partials/form-errors.tpl' errors=$errors['']}

    {block name="address_form_url"}
    <form
      method="POST"
      action="{url entity='address' params=['id_address' => $id_address]}"
      data-id-address="{$id_address}"
      data-refresh-url="{url entity='address' params=['ajax' => 1, 'action' => 'addressForm']}"
    >
    {/block}
    
      {block name="address_form_fields"}
        <section class="form-fields">
          {block name='form_fields'}
            {foreach from=$formFields item="field"}
              {block name='form_field'}
                {form_field field=$field}
              {/block}
            {/foreach}
          {/block}
        </section>
      {/block}

      {block name="address_form_footer"}
        <div class="form-footer row align-items-end">
          <input type="hidden" name="submitAddress" value="1">
          <div class="col-lg-3"></div>
          <div class="col-lg-6 text-center">
            {block name='form_buttons'}
              <button class="btn btn-primary form-control-submit" type="submit">{l s='Save' d='Shop.Theme.Actions'}</button>
            {/block}
          </div>
          <div class="col-12 text-right">
            {block name='form_extra_buttons'}{/block}
          </div>
        </div>
      {/block}
      
    </form>
  </div>
{/block}
