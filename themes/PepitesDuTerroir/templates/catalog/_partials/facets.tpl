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
{if $facets|count}
  <div id="search_filters">
    {block name='facets_title'}
      <h4 class="column-title d-none d-md-block">{l s='Filter By' d='Shop.Theme.Actions'}</h4>
    {/block}

    {block name='facets_clearall_button'}
      {if $activeFilters|count}
        <div id="_desktop_search_filters_clear_all" class="d-none d-md-block clear-all-wrapper text-center">
          <button data-search-url="{$clear_all_link}" class="btn btn-info js-search-filters-clear-all">
            <i class="material-icons">&#xE14C;</i>
            {l s='Clear all' d='Shop.Theme.Actions'}
          </button>
        </div>
      {/if}
    {/block}

    {foreach from=$facets item="facet"}
      {if !$facet.displayed}
        {continue}
      {/if}
      
      <section class="facet">
        <h5 class="facet-title d-none d-md-block"><i class="fa fa-angle-double-right" aria-hidden="true"></i> {$facet.label}</h5>
        {assign var=_expand_id value=10|mt_rand:100000}
        {assign var=_collapse value=true}
        {foreach from=$facet.filters item="filter"}
          {if $filter.active}{assign var=_collapse value=false}{/if}
        {/foreach}
        <div class="title d-block d-md-none" data-target="#facet_{$_expand_id}" data-toggle="collapse"{if !$_collapse} aria-expanded="true"{/if}>
          <h5 class="facet-title">{$facet.label}</h5>
          <span class="navbar-toggler collapse-icons">
            <i class="material-icons add">&#xE313;</i>
            <i class="material-icons remove">&#xE316;</i>
          </span>
        </div>
        {if $facet.widgetType !== 'dropdown'}
          {block name='facet_item_other'}
            <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
              {foreach from=$facet.filters key=filter_key item="filter"}
                {if !$filter.displayed}
                  {continue}
                {/if}
                
                <li>
                  <div class="facet-label custom-checkbox-wrapper {if $filter.active} active {/if}">
                    {if $facet.multipleSelectionAllowed}
                      <label class="custom-checkbox">
                        <span class="check-wrap">
                          <input
                            id="facet_input_{$_expand_id}_{$filter_key}"
                            data-search-url="{$filter.nextEncodedFacetsURL}"
                            type="checkbox"
                            {if $filter.active }checked{/if}
                          >
                          {if isset($filter.properties.color)}
                            <span class="check-shape color" style="background-color:{$filter.properties.color}"><span class="check-circle"></span></span>
                          {elseif isset($filter.properties.texture)}
                            <span class="check-shape color texture" style="background-image:url({$filter.properties.texture})"><span class="check-circle"></span></span>
                          {else}
                            <span class="check-shape"><i class="material-icons check-icon">check</i></span>
                          {/if}
                        </span>
                        <a href="{$filter.nextEncodedFacetsURL}" class="_gray-darker search-link js-search-link" rel="nofollow">{$filter.label}{if $filter.magnitude and $show_quantities}<span class="magnitude">({$filter.magnitude})</span>{/if}</a>
                      </label>
                    {else}
                      <label class="custom-radio">
                        <span class="check-wrap">
                          <input
                            id="facet_input_{$_expand_id}_{$filter_key}"
                            data-search-url="{$filter.nextEncodedFacetsURL}"
                            type="radio"
                            name="filter {$facet.label}"
                            {if $filter.active }checked{/if}
                          >
                          <span class="check-shape"><i class="material-icons check-icon">check</i></span>
                        </span>
                        <a href="{$filter.nextEncodedFacetsURL}" class="_gray-darker search-link js-search-link" rel="nofollow">{$filter.label}{if $filter.magnitude and $show_quantities}<span class="magnitude">({$filter.magnitude})</span>{/if}</a>
                      </label>
                    {/if}
                  </div>
                </li>
              {/foreach}
            </ul>
          {/block}
        {else}
          {block name='facet_item_dropdown'}
            <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
              <li>
                <div class="facet-dropdown dropdown">
                  <div class="select-title expand-more" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {$active_found = false}
                    <span>
                      {foreach from=$facet.filters item="filter"}
                        {if $filter.active}
                          {$filter.label}
                          {if $filter.magnitude}
                            ({$filter.magnitude})
                          {/if}
                          {$active_found = true}
                        {/if}
                      {/foreach}
                      {if !$active_found}
                        {l s='(no filter)' d='Shop.Theme.Global'}
                      {/if}
                    </span>
                    <a data-target="#" class="dropdown-icon">
                      <span class="expand-icon"></span>
                    </a>
                  </div>
                  <div class="dropdown-menu">
                    {foreach from=$facet.filters item="filter"}
                      {if !$filter.active}
                        <a
                          rel="nofollow"
                          href="{$filter.nextEncodedFacetsURL}"
                          class="select-list dropdown-item js-search-link"
                        >
                          {$filter.label}{if $filter.magnitude}<span class="magnitude">({$filter.magnitude})</span>{/if}
                        </a>
                      {/if}
                    {/foreach}
                  </div>
                </div>
              </li>
            </ul>
          {/block}
        {/if}
      </section>
    {/foreach}
  </div>
{/if}
