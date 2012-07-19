<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menuuser}</a> <span class="divider">/</span></li>
	<li class="active">{$lang.controller.head_add}</li>
</ul>

<div class="page-header" rel="menu_user_list"><h1>{$lang.controller.head_add}</h1></div>

<form action="" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="{$smarty.session.userAddToken}" />


	{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
	
	<div class="control-group">
		<label class="control-label" for="fgroupid">Group <span class="star_require">*</span></label>
		<div class="controls">
			<select id="fgroupid" name="fgroupid">
				<option value="">- - - -</option>
				{foreach item=groupname key=key from=$userGroups}
						<option value="{$key}" {if $formData.fgroupid == $key}selected="selected"{/if}>{$groupname}</option>
				{/foreach}
			</select>
		</div>
	</div>	
	
	<div class="control-group">
		<label class="control-label" for="femail">Email <span class="star_require">*</span></label>
		<div class="controls">
			<input type="text" name="femail" id="femail" value="{$formData.femail|@htmlspecialchars}" class="">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="fpassword">Password <span class="star_require">*</span></label>
		<div class="controls">
			<input type="password" name="fpassword" id="fpassword" />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="fpassword2">Retype Password <span class="star_require">*</span></label>
		<div class="controls">
			<input type="password" name="fpassword2" id="fpassword2" />
		</div>
	</div>
			
	<div class="control-group">
		<label class="control-label" for="ffullname">Fullname <span class="star_require">*</span></label>
		<div class="controls">
			<input type="text" name="ffullname" id="ffullname" value="{$formData.ffullname|@htmlspecialchars}" class="">
		</div>
	</div>
	
	<div class="form-actions">
		<input type="submit" name="fsubmit" value="{$lang.default.formAddSubmit}" class="btn btn-large btn-primary" />
		<span class="help-inline"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>
	</div>
			
	
</form>

