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

<div class="module-product-comment-modal">
<script type="text/javascript">
  var productCommentPostErrorMessage = '{l s='Sorry, your review cannot be posted.' d='Modules.Productcomments.Shop' js=1}';
</script>

<div id="post-product-comment-modal" class="modal fade product-comment-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="mb-0">{l s='Write your review' d='Modules.Productcomments.Shop'}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="post-product-comment-form" action="{$post_comment_url nofilter}" method="POST">
          {if isset($product) && $product}
            <div class="product-preview sm-bottom">
              <div class="d-flex align-items-center justify-content-center">
                {if $product.cover}
                  <img class="product-image img-fluid" src="{$product.cover.small.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}"/>
                {/if} 
                <div class="product-name">{$product.name}</div>
              </div>
            </div>
          {/if}

          {if $criterions|@count > 0}
            <div id="criterions_list">
            {foreach from=$criterions item='criterion'}
              <div class="criterion-rating">
                <label>{$criterion.name }</label>
                <div
                  class="grade-stars"
                  data-grade="3"
                  data-input="criterion[{$criterion.id_product_comment_criterion}]">
                </div>
              </div>
            {/foreach}
            </div>
          {/if}

          {if !$logged}
            <div class="form-group row">
              <label class="col-md-3 form-control-label">{l s='Your name' d='Modules.Productcomments.Shop'}<sup class="required">*</sup></label>
              <div class="col-md-8">
                <input class="form-control" name="customer_name" type="text" value=""/>
              </div>
            </div>
          {/if}
          <div class="form-group row">
            <label class="col-md-3 form-control-label">{l s='Title' d='Modules.Productcomments.Shop'}<sup class="required">*</sup></label>
            <div class="col-md-8">
              <input class="form-control" name="comment_title" type="text" value=""/>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 form-control-label">{l s='Review' d='Modules.Productcomments.Shop'}<sup class="required">*</sup></label>
            <div class="col-md-8">
              <textarea class="form-control" name="comment_content" rows="3"></textarea>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-3"></div>
            <div class="col-md-9 post-comment-buttons">
              <button type="submit" class="btn btn-primary"><span>{l s='Send' d='Modules.Productcomments.Shop'}</span></button>
              <p class="small"><sup>*</sup> {l s='Required fields' d='Modules.Productcomments.Shop'}</p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{* Comment posted modal *}
{if $moderation_active}
  {assign var='comment_posted_message' value={l s='Your comment has been submitted and will be available once approved by a moderator.' d='Modules.Productcomments.Shop'}}
{else}
  {assign var='comment_posted_message' value={l s='Your comment has been added!' d='Modules.Productcomments.Shop'}}
{/if}
{include file='module:productcomments/views/templates/hook/alert-modal.tpl'
  modal_id='product-comment-posted-modal'
  modal_title={l s='Review sent' d='Modules.Productcomments.Shop'}
  modal_message=$comment_posted_message
}

{* Comment post error modal *}
{include file='module:productcomments/views/templates/hook/alert-modal.tpl'
  modal_id='product-comment-post-error'
  modal_title={l s='Your review cannot be sent' d='Modules.Productcomments.Shop'}
  icon='error'
}
</div>
