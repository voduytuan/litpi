<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menulog}</a> <span class="divider">/</span></li>
	<li class="active">Detail</li>
</ul>

<div class="page-header" rel="menu_log"><h1>{$lang.controller.head_detail} : #{$log->id}</h1></div>

<div class="navgoback">
<a href="{$redirectUrl}">{$lang.default.formBackLabel}</a>
</div>

<h3>{$lang.controller.title_detail}</h3>


<table class="table table-striped">
	<tr>
		<td class="td_right"><strong>{$lang.controller.formTypeLabel} :</strong></td>
		<td>{$log->type}</td>
	</tr>
    
	<tr>
		<td width="150" class="td_right"><strong>Entry ID :</strong></td>
		<td>{$log->id}</td>
	</tr>
	<tr>
		<td class="td_right"><strong>{$lang.controller.formEmailLabel} :</strong></td>
		<td>{$log->email} (UID: {$log->uid})</td>
	</tr>
	
	
	<tr>
		<td class="td_right"><strong>{$lang.controller.formIpLabel} :</strong></td>
		<td>{$log->ipaddress}</td>
	</tr>
	
	<tr>
		<td class="td_right"><strong>{$lang.controller.formDatecreatedLabel} :</strong></td>
		<td>{$log->datecreated|date_format:$lang.default.dateFormatTimeSmarty}</td>
	</tr>
	
	<tr>
		<td class="td_right"><strong>{$lang.controller.formMoreDataLabel} :</strong></td>
		<td>
			<ul>
				<li><em>{$lang.controller.formMainIdLabel}</em>: {$log->mainid}</li>
			{foreach from=$log->moredata key=k item=v}
				<li><em>{$k}</em>: {$v}</li>
			{/foreach}
			</ul>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td><a class="btn btn-danger" title="{$lang.default.formActionDeleteTooltip}" href="javascript:delm('{$conf.rooturl_admin}log/delete/id/{$log->id}/redirect/{$encodedRedirectUrl}?token={$smarty.session.securityToken}');">{$lang.default.formDeleteLabel}</a></td>
	</tr>
	
</table>

