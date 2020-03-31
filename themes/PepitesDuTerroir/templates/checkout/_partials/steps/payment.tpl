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
{extends file='checkout/_partials/steps/checkout-step.tpl'}

{block name='step_content'}

  {hook h='displayPaymentTop'}

  <div style="display:none" class="js-cart-payment-step-refresh"></div>

  {if !empty($display_transaction_updated_info)}
  <p class="cart-payment-step-refreshed-info p-2 alert-success">
    {l s='Transaction amount has been correctly updated' d='Shop.Theme.Checkout'}
  </p>
  {/if}

  <div class="payment-options mb-5">
    {foreach from=$payment_options item="module_options"}
      {foreach from=$module_options item="option"}
        <div class="payment-line">
          <div id="{$option.id}-container" class="payment-option clearfix">
            <label class="custom-radio">
              <span class="check-wrap">
                <input
                  class="ps-shown-by-js {if $option.binary}binary{/if}"
                  id="{$option.id}"
                  data-module-name="{$option.module_name}"
                  name="payment-option"
                  type="radio"
                  required
                  {if $selected_payment_option == $option.id || $is_free}checked{/if}
                >
                <span class="check-shape"><i class="material-icons check-icon">check</i></span>
              </span>
              <span>
                {$option.call_to_action_text}
                {if $option.logo}<img src="{$option.logo}">{/if}
              </span>
            </label>

            <form method="GET" class="ps-hidden-by-js">
              {if $option.id === $selected_payment_option}
                {l s='Selected' d='Shop.Theme.Checkout'}
              {else}
                <button class="ps-hidden-by-js" type="submit" name="select_payment_option" value="{$option.id}">
                  {l s='Choose' d='Shop.Theme.Actions'}
                </button>
              {/if}
            </form>
          </div>

          {if $option.additionalInformation}
            <div
              id="{$option.id}-additional-information"
              class="js-additional-information definition-list additional-information{if $option.id != $selected_payment_option} ps-hidden {/if}"
            >
              {$option.additionalInformation nofilter}
            </div>
          {/if}

          <div
            id="pay-with-{$option.id}-form"
            class="js-payment-option-form {if $option.id != $selected_payment_option} ps-hidden {/if}"
          >
            {if $option.form}
              {$option.form nofilter}
            {else}
              <form id="payment-form" method="POST" action="{$option.action nofilter}">
                {foreach from=$option.inputs item=input}
                  <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                {/foreach}
                <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
              </form>
            {/if}
          </div>
        </div>
      {/foreach}
    {foreachelse}
      <p class="alert alert-danger">{l s='Unfortunately, there are no payment method available.' d='Shop.Theme.Checkout'}</p>
    {/foreach}
  </div>

  {if $show_final_summary}
    {include file='checkout/_partials/order-final-summary.tpl'}
  {/if}

  {if $conditions_to_approve|count}
    <p class="ps-hidden-by-js">
      {* At the moment, we're not showing the checkboxes when JS is disabled
         because it makes ensuring they were checked very tricky and overcomplicates
         the template. Might change later.
      *}
      {l s='By confirming the order, you certify that you have read and agree with all of the conditions below:' d='Shop.Theme.Checkout'}
    </p>

    <form id="conditions-to-approve" method="GET" class="mb-3">
      <ul>
        {foreach from=$conditions_to_approve item="condition" key="condition_name"}
          <li>
            <label class="custom-checkbox">
              <span class="check-wrap">
                <input  id    = "conditions_to_approve[{$condition_name}]"
                        name  = "conditions_to_approve[{$condition_name}]"
                        required
                        type  = "checkbox"
                        value = "1"
                        class = "ps-shown-by-js"
                >
                <span class="check-shape"><i class="material-icons check-icon">check</i></span>
              </span>
              <span class="js-terms">{$condition nofilter}</span>
            </label>
          </li>
        {/foreach}
      </ul>
    </form>
  {/if}

  <div id="payment-confirmation" class="text-center">
    <div class="ps-shown-by-js">
      <button type="submit" {if !$selected_payment_option} disabled {/if} class="btn btn-primary btn-large btn-wrap">
        {l s='Order with an obligation to pay' d='Shop.Theme.Checkout'}
      </button>
      {if $show_final_summary}
        <article class="alert alert-warning mt-2 p-1 js-alert-payment-conditions" role="alert" data-alert="danger">
          {l
            s='Please make sure you\'ve chosen a [1]payment method[/1] and accepted the [2]terms and conditions[/2].'
            sprintf=[
              '[1]' => '<a href="#checkout-payment-step">',
              '[/1]' => '</a>',
              '[2]' => '<a href="#checkout-payment-step">',
              '[/2]' => '</a>'
            ]
            d='Shop.Theme.Checkout'
          }
        </article>
      {/if}
    </div>
    <div class="ps-hidden-by-js">
      {if $selected_payment_option and $all_conditions_approved}
        <label for="pay-with-{$selected_payment_option}">{l s='Order with an obligation to pay' d='Shop.Theme.Checkout'}</label>
      {/if}
    </div>
  </div>

  {hook h='displayPaymentByBinaries'}

  <div class="modal fade" id="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body js-modal-content"></div>
      </div>
    </div>
  </div>
{/block}
