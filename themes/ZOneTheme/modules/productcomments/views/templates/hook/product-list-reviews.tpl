{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<div class="js-product-comment" data-id-product="{$product.id_product}" data-comment-grade-url="{$product_comment_grade_url nofilter}">
  <div class="product-list-reviews"> 
    <div class="grade-stars small-stars"></div>
    <div class="comments-nb"></div>
  </div>
</div>

{if $nb_comments != 0 && isset($page) && isset($page.page_name) && $page.page_name == 'category'}
  <div itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
    <meta itemprop="reviewCount" content="{$nb_comments}" />
    <meta itemprop="ratingValue" content="{$average_grade}" />
  </div>
{/if}
