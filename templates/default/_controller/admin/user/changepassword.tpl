<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menuuser}</a> <span class="divider">/</span></li>
	<li class="active">{$lang.controller.head_changepassword}</li>
</ul>

<div class="page-header" rel="menu_user_list"><h1>{$lang.controller.head_changepassword}</h1></div>

<form action="" method="post" name="myform" class="form-horizontal">

	{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
		
	<div class="control-group">
		<label class="control-label" for="foldpass">{$lang.controller.oldpass} <span class="star_require">*</span></label>
		<div class="controls">
			<input type="password" name="foldpass" id="foldpass">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="fnewpass1">{$lang.controller.newpass1} <span class="star_require">*</span></label>
		<div class="controls">
			<input type="password" name="fnewpass1" id="fnewpass1">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="fnewpass2">{$lang.controller.newpass2} <span class="star_require">*</span></label>
		<div class="controls">
			<input type="password" name="fnewpass2" id="fnewpass2">
		</div>
	</div>
		
			
	<div class="form-actions">
		<input type="submit" name="fsubmit" value="{$lang.default.formUpdateSubmit}" class="btn btn-large btn-primary" />
		<span class="help-inline"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>
	</div>
           
		
</form>


	
	
	
	