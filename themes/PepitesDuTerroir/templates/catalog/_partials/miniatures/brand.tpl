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
{block name='brand_miniature_item'}
  <div class="brand col-12 col-lg-6 col-xl-4">
    <div class="brand-container box-bg">
      <div class="brand-img">
        <a href="{$brand.url}"><img class="img-thumbnail" src="{$brand.image|replace:'small_default':'manufacturer_default'}" alt="{$brand.name}"></a>
      </div>
      <h5 class="brand-name"><a href="{$brand.url}" class="li-a">{$brand.name}</a></h5>
      <div class="brand-infos">
        <div class="brand-desc">{$brand.text nofilter}</div>
        <div class="brand-products">
          <span class="nb-products"><i>{$brand.nb_products}</i></span>
          <a class="url-view" href="{$brand.url}">{l s='View products' d='Shop.Theme.Actions'} <i class="material-icons">trending_flat</i></a>
        </div>
      </div>
    </div>
  </div>
{/block}
