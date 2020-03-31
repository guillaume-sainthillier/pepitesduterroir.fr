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

class ZOneThemeManagerCategoryTreeModuleFrontController extends ModuleFrontController
{
    public function displayAjax()
    {
    	$category_tree = $this->minify_html($this->module->displayCategoryTree());

        die($category_tree);
    }

    public function minify_html($input)
    {
        if (trim($input) === "") {
            return $input;
        }

        $input = preg_replace_callback(
            '#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s',
            function($matches) {
                return '<'.$matches[1].preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]).$matches[3].'>';
            },
            str_replace("\r", "", $input)
        );

        $searchdata = array(
            '#<(img|input)(>| .*?>)#s',
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s',
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s',
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s',
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s',
            '#<(img|input)(>| .*?>)<\/\1>#s',
            '#(&nbsp;)&nbsp;(?![<\s])#',
            '#(?<=\>)(&nbsp;)(?=\<)#',
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
        );
        $replacedata = array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
        );

        return preg_replace($searchdata, $replacedata, $input);
    }
}
