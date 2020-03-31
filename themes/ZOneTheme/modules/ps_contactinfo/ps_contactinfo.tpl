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

<div class="block-contact col-12 col-md-6 col-lg-3 links wrapper sm-bottom">
  <h4 class="text-uppercase block-contact-title">{l s='Store information' d='Shop.Theme.Global'}</h4>

  <p>{$contact_infos.address.formatted nofilter}</p>

  {if $contact_infos.phone}
    <p>
      {l
        s='Tel: %phone%'
        sprintf=[
          '%phone%' => $contact_infos.phone
        ]
        d='Modules.Contactinfo.Shop'
      }
    </p>
  {/if}
  {if $contact_infos.fax}
    <p>
      {l
        s='Fax: %fax%'
        sprintf=[
          '%fax%' => $contact_infos.fax
        ]
        d='Modules.Contactinfo.Shop'
      }
    </p>
  {/if}
  {if $contact_infos.email && $display_email}
    <p>
      {l
        s='Email: [1]%email%[/1]'
        sprintf=[
          '%email%' => $contact_infos.email,
          '[1]' => '<a href="mailto:'|cat:$contact_infos.email|cat:'">',
          '[/1]' => '</a>'
        ]
        d='Modules.Contactinfo.Shop'
      }
    </p>
  {/if}
</div>