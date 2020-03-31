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

<div class="contact-rich column-block lg-bottom">
  <h4 class="column-title">{l s='Store information' d='Modules.Contactinfo.Shop'}</h4>

  <div class="info-line">
    <div class="icon"><i class="material-icons">&#xE55F;</i></div>
    <div class="data data-address">{$contact_infos.address.formatted nofilter}</div>
  </div>
  {if $contact_infos.phone}
    <hr/>
    <div class="info-line">
      <div class="icon"><i class="material-icons">&#xE0CD;</i></div>
      <div class="data data-phone">
        {l s='Call us: [1]%phone%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%phone%' => $contact_infos.phone
          ]
          d='Modules.Contactinfo.Shop'
        }
      </div>
    </div>
  {/if}
  {if $contact_infos.fax}
    <hr/>
    <div class="info-line">
      <div class="icon"><i class="material-icons">&#xE0DF;</i></div>
      <div class="data data-fax">
        {l
          s='Fax: [1]%fax%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%fax%' => $contact_infos.fax
          ]
          d='Modules.Contactinfo.Shop'
        }
      </div>
    </div>
  {/if}
  {if $contact_infos.email}
    <hr/>
    <div class="info-line">
      <div class="icon"><i class="material-icons">&#xE158;</i></div>
      <div class="data data-email">
        {l
          s='Email: [1]%email%[/1]'
          sprintf=[
            '%email%' => $contact_infos.email,
            '[1]' => '<a href="mailto:'|cat:$contact_infos.email|cat:'">',
            '[/1]' => '</a>'
          ]
          d='Modules.Contactinfo.Shop'
        }
      </div>
    </div>
  {/if}
</div>
