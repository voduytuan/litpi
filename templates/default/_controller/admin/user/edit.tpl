<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menuuser}</a> <span class="divider">/</span></li>
	<li class="active">{$lang.controller.head_edit}</li>
</ul>

<div class="page-header" rel="menu_user_list"><h1>{$lang.controller.head_edit}</h1></div>

<form action="" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="{$smarty.session.userEditToken}" />

	{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}

	{if $myUser->avatar != ''}
	<div class="control-group">
		<label class="control-label">Avatar</label>
		<div class="controls">
			<a href="{$conf.rooturl}{$setting.avatar.imageDirectory}{$myUser->avatar}" target="_blank"><img src="{$conf.rooturl}{$setting.avatar.imageDirectory}{$myUser->thumbImage()}" width="100" border="0" /></a><input type="checkbox" name="fdeleteimage" value="1" />Delete<br />
		</div>
	</div>
	{/if}

	{if $me->id != $myUser->id}
	<div class="control-group">
		<label class="control-label" for="fgroupid">Group</label>
		<div class="controls">
			<select id="fgroupid" name="fgroupid">
			<option value="">- - - -</option>
			{foreach item=groupname key=key from=$userGroups}
					<option value="{$key}" {if $formData.fgroupid == $key}selected="selected"{/if}>{$groupname}</option>
			{/foreach}
			</select>
		</div>
	</div>
	{/if}
		
		
	<div class="control-group">
		<label class="control-label" for="femail">Email <span class="star_require">*</span></label>
		<div class="controls">
			<input type="text" name="femail" id="femail" disabled="disabled" value="{$formData.femail|@htmlspecialchars}" class="">
		</div>
	</div>
		
				
				
	<div class="control-group">
		<label class="control-label" for="ffullname">Fullname <span class="star_require">*</span></label>
		<div class="controls">
			<input type="text" name="ffullname" id="ffullname" value="{$formData.ffullname|@htmlspecialchars}" class="">
		</div>
	</div>
			
	<div class="form-actions">
		<input type="submit" name="fsubmit" value="{$lang.default.formUpdateSubmit}" class="btn btn-large btn-primary" />
		<a href="javascript:delm('{$conf.rooturl_admin}user/resetpass/id/{$myUser->id}')" class="btn btn-info pull-right">Reset Password</a>
		<span class="help-inline"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>
	</div>
           
		
</form>

