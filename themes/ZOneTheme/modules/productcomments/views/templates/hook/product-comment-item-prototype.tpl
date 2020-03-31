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

<div class="product-comment-list-item" data-product-comment-id="@COMMENT_ID@" data-product-id="@PRODUCT_ID@">
  <div class="comment-infos">
    <div class="grade-stars small-stars"></div>
    <div class="comment-date small">
      @COMMENT_DATE@
    </div>
    <div class="comment-author font-weight-bold">
      {l s='By %1$s' sprintf=['@CUSTOMER_NAME@'] d='Modules.Productcomments.Shop'}
    </div>
  </div>

  <div class="comment-content">
    <h5>@COMMENT_TITLE@</h5>
    <p>@COMMENT_COMMENT@</p>
    <ul class="comment-buttons">
      {if $usefulness_enabled}
        <li><a class="useful-review" href="#like">
          <i class="material-icons thumb_up">thumb_up</i> <span class="useful-review-value">@COMMENT_USEFUL_ADVICES@</span>
        </a></li>
        <li><a class="not-useful-review" href="#dislike">
          <i class="material-icons thumb_down">thumb_down</i> <span class="not-useful-review-value">@COMMENT_NOT_USEFUL_ADVICES@</span>
        </a></li>
      {/if}
      <li><a class="report-abuse" title="{l s='Report abuse' d='Modules.Productcomments.Shop'}" href="#report">
        <i class="material-icons outlined_flag">flag</i>
      </a></li>
    </ul>
  </div>
</div>
