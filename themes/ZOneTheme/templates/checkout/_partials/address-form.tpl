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
{extends file='customer/_partials/address-form.tpl'}

{block name='form_field'}
  {if $field.name eq "alias"}
    {* we don't ask for alias here *}
  {else}
    {$smarty.block.parent}
  {/if}
{/block}

{block name="address_form_url"}
  <form
    method="POST"
    action="{url entity='order' params=['id_address' => $id_address]}"
    data-id-address="{$id_address}"
    data-refresh-url="{url entity='order' params=['ajax' => 1, 'action' => 'addressForm']}"
  >
{/block}

{block name='form_fields' append}
  <input type="hidden" name="saveAddress" value="{$type}">
  {if $type === "delivery"}
    <div class="form-group row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6 text-center">
        <label class="custom-checkbox">
          <span class="check-wrap">
            <input name="use_same_address" id="use_same_address" type="checkbox" value="1" {if $use_same_address}checked{/if}>
            <span class="check-shape"><i class="material-icons check-icon">check</i></span>
          </span>
          <span>{l s='Use this address for invoice too' d='Shop.Theme.Checkout'}</span>
        </label>
      </div>
    </div>
  {/if}
{/block}

{if !$form_has_continue_button || $customer.addresses|count > 0}
  {block name='form_extra_buttons'}
    <a class="js-cancel-address cancel-address text-danger text-monospace" href="{url entity='order' params=['cancelAddress' => {$type}]}"><i class="material-icons check-icon">cancel</i>{l s='Cancel' d='Shop.Theme.Actions'}</a>
  {/block}
{/if}
{if $form_has_continue_button}
  {block name='form_buttons'}
    <button type="submit" class="continue btn btn-primary" name="confirm-addresses" value="1">
      {l s='Continue' d='Shop.Theme.Actions'}
    </button>
  {/block}
{/if}


