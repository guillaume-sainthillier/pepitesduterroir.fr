<?php
/**
 * 2007-2019 PrestaShop and Contributors.
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
 */

class ZOneThemeManagerGetFinalDateModuleFrontController extends ModuleFrontController
{
    public function displayAjax()
    {
        $specific_prices_to = Tools::getValue('specific-prices-to', '0000-00-00 00:00:00');

        $templateFile = 'module:zonethememanager/views/templates/front/price_countdown.tpl';
        $tpl = $this->context->smarty->createTemplate($templateFile, $this->context->smarty);
        $tpl->assign(array(
            'specific_prices_to' => $specific_prices_to,
            'finalDate' => (strtotime($specific_prices_to) * 1000),
        ));

        die($tpl->fetch());
    }
}
