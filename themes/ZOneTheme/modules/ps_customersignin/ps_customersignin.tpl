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
<div class="customer-signin-module">
  <div class="user-info">
    {if $logged}
      <div class="customer-logged">
        <div class="js-account-source">
          <ul>
            <li>
              <div class="account-link account">
                <a
                  href="{$my_account_url}"
                  title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
                  rel="nofollow"
                >
                  <i class="material-icons">person</i><span>{$customerName}</span>
                </a>
              </div>
              <a href="{$urls.actions.logout}" class="logout-link">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
              </a>
            </li>
          </ul>
        </div>
        {if isset($zoneIsMobile) && $zoneIsMobile}
          <!-- Remove product display on mobile -->
        {else}
          <div class="dropdown-customer-account-links">
            {include file="module:ps_customersignin/customer-dropdown-menu.tpl"}
          </div>
        {/if}
      </div>
    {else}
      <div class="js-account-source">
        <ul>
          <li>
            <div class="account-link">
              <a
                href="{$my_account_url}"
                title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
                rel="nofollow"
              >
                <i class="material-icons">person</i><span>{l s='Sign in' d='Shop.Theme.Actions'}</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    {/if}
  </div>
</div>