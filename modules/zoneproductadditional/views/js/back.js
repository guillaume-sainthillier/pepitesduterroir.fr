/**
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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$( document ).ready(function() {
	$('#scope_selectbox').change(function() {
		if (this.value == 'Specific Categories') {
			$('#productBox').closest('div.form-group').slideUp();
			$('#manufacturerBox').closest('div.form-group').slideUp();
			$('#categoryBox').closest('div.form-group').slideDown();
		}
		else if(this.value == 'Specific Products') {
			$('#categoryBox').closest('div.form-group').slideUp();
			$('#manufacturerBox').closest('div.form-group').slideUp();
			$('#productBox').closest('div.form-group').slideDown();
		}
		else if(this.value == 'Specific Manufacturers') {
			$('#categoryBox').closest('div.form-group').slideUp();
			$('#productBox').closest('div.form-group').slideUp();
			$('#manufacturerBox').closest('div.form-group').slideDown();
		}
		else {
			$('#categoryBox').closest('div.form-group').slideUp();
			$('#productBox').closest('div.form-group').slideUp();
			$('#manufacturerBox').closest('div.form-group').slideUp();
		}
			
	});

	$('#scope_selectbox').trigger( "change" );

	aInitTableDnD('zoneproductadditional', 'zproduct_extra_field');
});