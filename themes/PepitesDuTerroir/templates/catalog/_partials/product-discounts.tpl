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
<section class="product-discounts">
    {if $product.quantity_discounts}
        <div class="product-discounts-wrapper md-bottom">
            {block name='product_discount_table'}
                {$cartonDiscount = null}
                {foreach from=$product.quantity_discounts item='quantity_discount' name='quantity_discounts'}
                    {if $quantity_discount.quantity == 6}
                        {$cartonDiscount = $quantity_discount.save}
                    {/if}
                {/foreach}
                {if null !== $cartonDiscount}
                    <p class="product-discounts-title font-weight-bold text-danger">
                        Pour chaque carton de 6 bouteilles acheté, vous bénéficiez d'une bouteille offerte (Soit {$cartonDiscount} d'économie). Profitez-en !
                    </p>
                {else}
                    <label class="product-discounts-title">Remises sur les quantités</label>
                    <table class="table table-bordered table-product-discounts">
                        <thead class="thead-default">
                        <tr>
                            <th>{l s='Quantity' d='Shop.Theme.Catalog'}</th>
                            <th>{$configuration.quantity_discount.label}</th>
                            <th>{l s='You Save' d='Shop.Theme.Catalog'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$product.quantity_discounts item='quantity_discount' name='quantity_discounts'}
                            <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
                                <td>{$quantity_discount.quantity}</td>
                                <td>{$quantity_discount.discount}</td>
                                <td>{l s='Up to %discount%' d='Shop.Theme.Catalog' sprintf=['%discount%' => $quantity_discount.save]}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {/if}
            {/block}
        </div>
    {/if}
</section>


