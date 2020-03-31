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

class ZOneThemeManagerGetFinalDateMiniatureModuleFrontController extends ModuleFrontController
{
    public function displayAjax()
    {
        $specific_prices_to = Tools::getValue('specific-prices-to', false);
        $templateFile = 'module:zonethememanager/views/templates/front/price_countdown_miniature.tpl';
        $tpl = $this->context->smarty->createTemplate($templateFile, $this->context->smarty);
        $results = array();

        if ($specific_prices_to) {
            $products_times = Tools::jsonDecode($specific_prices_to);
            foreach ($products_times as $id_product => $time) {
                $tpl->assign(array(
                    'finalDate' => (strtotime($time) * 1000),
                ));
                
                $results[$id_product] = $tpl->fetch();
            }
        }
        
        $this->ajaxDie(Tools::jsonEncode($results));
    }
}
