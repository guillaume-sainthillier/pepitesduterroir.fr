{**
 * 2007-2019 PrestaShop and Contributors.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div id="js-product-list-header">
  {if $listing.pagination.items_shown_from == 1}

    {if isset($cat_showImage) && $cat_showImage && $category.image}
      <div class="category-cover mb-4">
        <img class="img-fluid" src="{$category.image.bySize.category_default.url}" alt="{$category.image.legend|default: $category.name}">
      </div>
    {/if}

    <h1 class="page-heading js-category-page" data-current-category-id="{$category.id}">{$category.name}</h1>
    
    {if isset($cat_showDescription) && $cat_showDescription && $category.description}
      <div class="category-description mb-4 {if isset($cat_expandDescription) && $cat_expandDescription}js-expand-description{/if}">
        <div class="descSmall">
          <div class="typo descFull">
            {$category.description nofilter}
          </div>
        </div>
        <div class="descToggle expand">
          <a href="#expand">&nbsp;{l s='Show More' d='Shop.Zonetheme'}<i class="material-icons">expand_more</i></a>
        </div>
        <div class="descToggle collapse">
          <a href="#collapse">&nbsp;{l s='Show Less' d='Shop.Zonetheme'}<i class="material-icons">expand_less</i></a>
        </div>
      </div>
    {/if}

    {block name='category_subcategories'}
      {if isset($cat_showSubcategories) && $cat_showSubcategories}
        {if $subcategories|count}
          <aside class="subcategories mb-2">
            <h3 class="page-subheading">{l s='Subcategories' d='Shop.Zonetheme'}</h3>
            <div class="subcategories-wrapper row">
              {foreach from=$subcategories item="subcategory"}
                {block name='category_miniature'}
                  {include file='catalog/_partials/miniatures/subcategory.tpl' subcategory=$subcategory}
                {/block}
              {/foreach}
            </div>
          </aside>
        {/if}
      {/if}

      {if $subcategories|count}
        {hook h='displaySubCategoriesBlock' subcategories=$subcategories id_category=$category.id}
      {/if}
    {/block}

  {/if}
</div>
