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

{if $zmenus}
  <div id="amegamenu" class="d-none d-md-block {if $is_rtl}amegamenu_rtl{/if}">
    <ul class="anav-top js-ajax-mega-menu" data-ajax-dropdown-controller="{$ajaxDrodownContentController}">
      {foreach from=$zmenus item=menu}
        <li class="amenu-item mm{$menu.id_zmenu} {if $menu.dropdowns && $menu.drop_column}plex{/if} {$menu.custom_class}">
          {if $menu.link}<a href="{$menu.link}" class="amenu-link" {if $menu.link_newtab}target="_blank"{/if}>{else}<span class="amenu-link">{/if}
            {if isset($menu.title_image) && $menu.title_image}<img src="{$menu.title_image.url}" alt="{$menu.name}" width="{$menu.title_image.width}" height="{$menu.title_image.height}" />{/if}
            <span>{$menu.name nofilter}</span>
            {if $menu.label}<sup {if $menu.label_color}style="background-color: {$menu.label_color};"{/if}>{$menu.label}</sup>{/if}
          {if $menu.link}</a>{else}</span>{/if}

          {if $menu.dropdowns && $menu.drop_column}
            <div class="adropdown adrd{$menu.drop_column}">
              <div class="js-dropdown-content" data-menu-id="{$menu.id_zmenu}"></div>
            </div>
          {/if}
        </li>
      {/foreach}
    </ul>
  </div>
{/if}
