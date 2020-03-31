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

class ZOnePopupNewsletterModalNewsletterModuleFrontController extends ModuleFrontController
{
    public function displayAjax()
    {
    	require_once $this->module->getLocalPath().'classes/ZPopupNewsletter.php';

        $id_lang = (int) $this->context->language->id;
        $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop($id_lang);

        $subscribe_form = false;
        if ($zpopupnewsletter->subscribe_form) {
            $name = 'ps_emailsubscription';
            $module_instance = Module::getInstanceByName($name);
            if (Module::isInstalled($name) && Module::isEnabled($name)) {
                $subscribe_form = $module_instance->renderWidget();
                $subscribe_form = str_replace('js-emailsubscription', "", $subscribe_form);
                $subscribe_form = preg_replace('#<div class=\"custom-checkbox-wrapper\">(.*?)<\/div>#is', " ", $subscribe_form);
                $subscribe_form = preg_replace('#<div id=\"gdpr_consent\" class=\"(.*?)\">(.*?)<\/div>#is', " ", $subscribe_form);
                $subscribe_form = preg_replace('#<script (.*?)>(.*?)<\/script>#is', " ", $subscribe_form);
            }
        }
        
        $bg_image_url = $this->module->getPathUri().$this->module->bg_img_folder;
        $bg_image_url = Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer($bg_image_url).$bg_image_url;

        $templateFile = 'module:zonepopupnewsletter/views/templates/hook/modal-newsletter.tpl';
        $tpl = $this->context->smarty->createTemplate($templateFile, $this->context->smarty);
        $tpl->assign(array(
            'width' => $zpopupnewsletter->width,
            'height' => $zpopupnewsletter->height,
            'bg_color' => $zpopupnewsletter->bg_color,
            'bg_image' => $bg_image_url.$zpopupnewsletter->bg_image,
            'cookie_time' => $zpopupnewsletter->cookie_time,
            'content' => $zpopupnewsletter->content,
            'subscribe_form' => $subscribe_form,
        ));

        die($tpl->fetch());
    }
}
