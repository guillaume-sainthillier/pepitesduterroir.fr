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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{function name="categories" nodes=[] depth=0}
  {strip}
    {if $nodes|count}
      <ul>
        {foreach from=$nodes item=node}
          <li data-depth="{$depth}">
              <a href="{$node.link}" title="{$node.name}" data-category-id="{$node.id}"><span>{$node.name}</span></a>
              {if $node.children}
                <div class="collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar{$node.id}">
                  <i class="material-icons add"></i>
                  <i class="material-icons remove"></i>
                </div>
                <div class="category-sub-menu collapse" id="exCollapsingNavbar{$node.id}">
                  {categories nodes=$node.children depth=$depth+1}
                </div>
              {/if}
          </li>
        {/foreach}
      </ul>
    {/if}
  {/strip}
{/function}

<div class="left-categories column-block md-bottom">
  <h4 class="column-title">
    {$categories.name}
  </h4>
  <div class="category-tree js-category-tree">
    {categories nodes=$categories.children}
  </div>
</div>
