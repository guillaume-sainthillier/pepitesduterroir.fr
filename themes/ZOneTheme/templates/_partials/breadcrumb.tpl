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

<nav class="breadcrumb-wrapper {if isset($zoneIsMobile) && $zoneIsMobile}mobile-breadcrumb-wrapper{/if}">
  <div class="container">
    <ol class="breadcrumb" data-depth="{$breadcrumb.count}" itemscope itemtype="http://schema.org/BreadcrumbList">
      {block name='breadcrumb'}
        {foreach from=$breadcrumb.links item=path name=breadcrumb}
          {block name='breadcrumb_item'}
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
              {if !$smarty.foreach.breadcrumb.first}
                <span class="separator material-icons">chevron_right</span>
              {/if}
              <a itemprop="item" href="{$path.url}">
                {if $smarty.foreach.breadcrumb.first}
                  <i class="fa fa-home home" aria-hidden="true"></i>
                {/if}
                  <span itemprop="name">{$path.title}</span>
              </a>
              <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
            </li>
          {/block}
        {/foreach}
      {/block}
    </ol>
  </div>
</nav>
