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

{extends file="helpers/form/form.tpl"}

{block name="input"}
	{if $input.type == 'product_autocomplete'}	
	<div>
		<input type="text" id="products_autocomplete" autocomplete="off" size="42" />
		<div id="products_autocompleteDiv" style="font-size: 1.1em; margin-top: 10px; margin-left: 10px;">
		{if isset($fields_value.related_products)}
			{foreach $fields_value.related_products as $id => $name}
			<p id="product-{$id|escape:'htmlall':'UTF-8'}">#{$id|escape:'htmlall':'UTF-8'} - {$name|escape:'htmlall':'UTF-8'} <span style="cursor: pointer;" onclick="$(this).parent().remove();"><img src="../img/admin/delete.gif" /></span><input type="hidden" name="{$input.name|escape:'htmlall':'UTF-8'}[]" value="{$id|escape:'htmlall':'UTF-8'}" /></p>
			{/foreach}
		{/if}
		</div>
		<script type="text/javascript">
			function autocompleteProduct() {
				$("#products_autocomplete").autocomplete("{$input.ajax_path}", {
					minChars: 1,
					autoFill: false,
					max:20,
					matchContains: true,
					mustMatch:true,
					scroll:false,
					cacheLength:0,
					formatItem: function(item) {
						return "#"+item[1]+" - "+item[0];
					}
				}).result(function(event, data, formatted){
					if (data == null)
						return false;
					var productId = data[1];
					var productName = data[0];
					
					$("#product-" + productId).remove();
					html = html_aclist.replace(/xproductIdy/g,productId).replace(/xproductNamey/g,productName);
					$("#products_autocompleteDiv").append(html);

					$(this).val('');
				});
			}
			var html_aclist = '<p id="product-xproductIdy">#xproductIdy - xproductNamey <span style="cursor: pointer;" onclick="$(this).parent().remove();"><img src="../img/admin/delete.gif" /></span><input type="hidden" name="{$input.name|escape:'htmlall':'UTF-8'}[]" value="xproductIdy" /></p>';
			
			$(document).ready(function(){
				autocompleteProduct();
			});
		</script>
	</div>

	{elseif $input.type == 'file_lang'}
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
			{/if}
			<div class="form-group">
				{if isset($fields_value[$input.name][$language.id_lang]) && $fields_value[$input.name][$language.id_lang] != ''}
				<div id="{$input.name}-{$language.id_lang}-images-thumbnails" class="col-lg-12">
					<img src="{$input.image_folder}{$fields_value[$input.name][$language.id_lang]}" class="img-thumbnail"/>
				</div>
				{/if}
			</div>
			<div class="form-group">
				<div class="col-lg-6">
					<input id="{$input.name}_{$language.id_lang}" type="file" name="{$input.name}_{$language.id_lang}" class="hide" />
					<div class="dummyfile input-group">
						<span class="input-group-addon"><i class="icon-file"></i></span>
						<input id="{$input.name}_{$language.id_lang}-name" type="text" class="disabled" name="filename" readonly />
						<span class="input-group-btn">
							<button id="{$input.name}_{$language.id_lang}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
								<i class="icon-folder-open"></i> {l s='Upload' mod='zoneslideshow'}
							</button>
						</span>
					</div>
				</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
			</div>
			{if $languages|count > 1}
				</div>
			{/if}
			<script>
			$(document).ready(function(){
				$('#{$input.name}_{$language.id_lang}-selectbutton').click(function(e){
					$('#{$input.name}_{$language.id_lang}').trigger('click');
				});
				$('#{$input.name}_{$language.id_lang}').change(function(e){
					var val = $(this).val();
					var file = val.split(/[\\/]/);
					$('#{$input.name}_{$language.id_lang}-name').val(file[file.length-1]);
				});
			});
		</script>
		{/foreach}
	
	{else}
		{$smarty.block.parent}
    {/if}

{/block}
