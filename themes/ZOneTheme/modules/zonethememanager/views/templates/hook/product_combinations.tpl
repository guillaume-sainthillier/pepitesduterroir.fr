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

{if $combinations}
<div class="product-combinations js-product-combinations">
  <div class="combinations-wrapper">
    {foreach from=$combinations key=id_combination item=combination}
      <div class="combination-item js-switch-cbnt {if $id_combination == $id_product_attribute}active{/if} {if $combination.disable}disabled{/if}" data-groups="{$combination.groups|json_encode}">
        <div class="switch-cbnt">
          {if $combination.price}<span class="cbnt-price product-price">{$combination.price}</span>{/if}
          <span class="cbnt-name">{$combination.title nofilter}</span>
          {if $combination.quantity}<span class="cbnt-qty">({$combination.quantity} {$combination.quantity_label})</span>{/if}
        </div>
      </div>
    {/foreach}
  </div>
</div>
{/if}
