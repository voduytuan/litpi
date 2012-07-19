<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menulog}</a> <span class="divider">/</span></li>
	<li class="active">Listing</li>
</ul>

<div class="page-header" rel="menu_log"><h1>{$lang.controller.head_list}</h1></div>


<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">{$lang.controller.title_list} {if $formData.search != ''}| {$lang.controller.title_listSearch} {/if}({$total})</a></li>
		<li><a href="#tab2" data-toggle="tab">{$lang.default.filterLabel}</a></li>
		{if $formData.search != ''}
			<li><a href="{$conf.rooturl_admin}log">{$lang.default.formViewAll}</a></li>
		{/if}
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
			
			<form class="form-inline" action="" method="post" onsubmit="return confirm('Are You Sure ?');">
				<table class="table table-striped" cellpadding="5" width="100%">
					{if $logs|@count > 0}
						<thead>
							<tr>
							   <th width="40"><input class="check-all" type="checkbox" /></th>
								<th width="30">ID</th>
								<th>{$lang.controller.formDatecreatedLabel}</th>
								<th>{$lang.controller.formEmailLabel}</th>
								<th>{$lang.controller.formTypeLabel}</th>
								<th>{$lang.controller.formIpLabel}</th>
								<th></th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<td colspan="8">
									<div class="pagination">
									   {assign var="pageurl" value="page/::PAGE::"}
										{paginate count=$totalPage curr=$curPage lang=$paginateLang max=10 url="`$paginateurl``$pageurl`"}
									</div> <!-- End .pagination -->
									
									
									<div class="bulk-actions align-left">
										<select name="fbulkaction">
											<option value="">{$lang.default.bulkActionSelectLabel}</option>
											<option value="delete">{$lang.default.bulkActionDeletetLabel}</option>
										</select>
										<input type="submit" name="fsubmitbulk" class="btn" value="{$lang.default.bulkActionSubmit}" />
									</div>

									
									<div class="clear"></div>
								</td>
							</tr>
						</tfoot>
						<tbody>
					{foreach item=log from=$logs}

							<tr>
								<td align="center"><input type="checkbox" name="fbulkid[]" value="{$log->id}" {if in_array($log->id, $formData.fbulkid)}checked="checked"{/if}/></td>
								<td align="center">{$log->id}</td>
								<td align="center">{$log->datecreated|date_format:$lang.default.dateFormatTimeSmarty}</td>
								<td align="left"><a href="{$conf.rooturl_admin}log/index/uid/{$log->uid}/redirect/{$redirectUrl}">{$log->email}</a></td>
								<td align="center"><a href="{$conf.rooturl_admin}log/index/type/{$log->type}">{$log->type}</a></td>
								<td align="center"><a href="{$conf.rooturl_admin}log/index/ip/{$log->ipaddress}">{$log->ipaddress}</a></td>

								<td align="center"><a title="{$lang.default.formActionDetailTooltip}" href="{$conf.rooturl_admin}log/detail/id/{$log->id}/redirect/{$redirectUrl}" class="btn btn-mini"><i class="icon-info-sign"></i> {$lang.default.formDetailLabel}</a> &nbsp;
									<a title="{$lang.default.formActionDeleteTooltip}" href="javascript:delm('{$conf.rooturl_admin}log/delete/id/{$log->id}/redirect/{$redirectUrl}?token={$smarty.session.securityToken}');" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i> {$lang.default.formDeleteLabel}</a>
								</td>
							</tr>

					{/foreach}
						</tbody>



					{else}
						<tr>
							<td colspan="10">{$lang.default.notfound}</td>
						</tr>
					{/if}
					
					
				
				</table>
			</form>
			
			
		</div><!-- end #tab 1 -->
		<div class="tab-pane" id="tab2">
			<form class="form-inline" action="" method="post" onsubmit="return false;">
	
				User ID:
				<input type="text" name="fuid" id="fuid" size="20" value="{$formData.fuid|@htmlspecialchars}" class="input-mini" /> -

				{$lang.controller.formTypeLabel}:
				<input type="text" name="ftype" id="ftype" size="20" value="{$formData.ftype|@htmlspecialchars}" class="" /> -


				{$lang.controller.formIpLabel}:
				<input type="text" name="fip" id="fip" size="20" value="{$formData.fip|@htmlspecialchars}" class="" /> -

				
				<input type="button" value="{$lang.default.filterSubmit}" class="btn btn-primary" onclick="gosearch();"  />
		
			</form>
		</div><!-- end #tab2 -->
	</div>
</div>



{literal}
<script type="text/javascript">
	function gosearch()
	{
		var path = rooturl_admin + "log/index";
		
		
		var uid = $("#fuid").val();
		if(uid.length > 0)
		{
			path += "/uid/" + uid;
		}
		
		var type = $("#ftype").val();
		if(type.length > 0)
		{
			path += "/type/" + type;
		}
		
		
		
	
		var ip = $("#fip").val();
		if(ip.length > 0)
		{
			path += "/ip/" + ip;
		}
		
		
		
		
		document.location.href= path;
	}
</script>
{/literal}










