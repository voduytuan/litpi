<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menulanguage}</a> <span class="divider">/</span></li>
	<li class="active">{$lang.controller.head_edit}</li>
</ul>

<div class="page-header" rel="menu_language"><h1>{$lang.controller.head_edit} <code>{$langFolder}{$folder}/{if $subfolder != ''}{$subfolder}/{/if}{$file}</code></h1></div>



<form class="form-horizontal" name="manage" action="" method="post">
	
	{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
	
	
	{foreach item=node from=$fileData}
		<div class="control-group">
			<label class="control-label" title="{$node.name}"><code>{$node.name}</code></label>
			<div class="controls">
				<textarea class="input-xxlarge" rows="1" {if $warning|@count > 0}disabled="disabled"{/if} name="fname[{$node.name}]">{$node.values}</textarea>
				{if $node.descr != ''}
					<span class="help-block">{$lang.controller.formDescriptionLabel}:{$node.descr}</span>
				{/if}
			</div>
		</div>
	{/foreach}

	<div class="control-group">
		<label class="control-label">&nbsp;</label>
		<div class="controls">
			<label class="checkbox"><input type="checkbox" name="fsortbyalphabet" value="1"/> <em>{$lang.controller.formReorderAlphabetLabel}</em></label>
		</div>
	</div>
	
	<div class="form-actions">
		<input type="submit" name="fsubmit" {if $warning|@count > 0}disabled="disabled"{/if} value="{$lang.default.formUpdateSubmit}" class="btn btn-primary btn-large">
	</div>
</form>

