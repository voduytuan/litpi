<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menusessionmanager}</a> <span class="divider">/</span></li>
	<li class="active">Listing</li>
</ul>

<div class="page-header" rel="menu_sessionmanager"><h1>{$lang.default.menusessionmanager}</h1></div>


<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Listing</a></li>
		<li><a href="#tab2" data-toggle="tab">{$lang.default.filterLabel}</a></li>
		{if $formData.search != ''}
			<li><a href="{$conf.rooturl_admin}sessionmanager/{$action}">{$lang.default.formViewAll}</a></li>
		{/if}
		{if $action == 'index'}
			<li><a href="{$conf.rooturl_admin}sessionmanager/indexfull">Full Session</a></li>
		{else}
			<li><a href="{$conf.rooturl_admin}sessionmanager/index">Distinct IP Session (Exclude Same IP)</a></li>
		{/if}
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<h3>{if $action == 'index'}Distinct{/if} Sessions {if $formData.search != ''}| Search result {/if}({$total})</h3>
			
			{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
			<form action="" method="post" name="manage" onsubmit="return confirm('Are You Sure ?');">
				<table class="table table-striped">
					
				{if $entries|@count > 0}
					<thead>
						<tr>
							<th align="center" width="10"><input class="check-all" type="checkbox" /></th>
							<th align="left">{$lang.controller.formSessionIdLabel}</th>
							<th width="160" align="center"><a href="{$filterUrl}sortby/ip/sorttype/{if $formData.sortby eq 'ip'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">{$lang.controller.formIpAddressLabel}</a></th>
							<th width="10" align="center"><a href="{$filterUrl}sortby/agent/sorttype/{if $formData.sortby eq 'agent'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">{$lang.controller.formBrowserLabel}</a></th>
							<th width="10" align="center">{$lang.controller.formOsLabel}</th>
							<th width="110" align="center"><a href="{$filterUrl}sortby/controller/sorttype/{if $formData.sortby eq 'controller'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">{$lang.controller.formPageLabel}</a></th>
							<th align="center" width="100"><a href="{$filterUrl}sortby/userid/sorttype/{if $formData.sortby eq 'userid'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">{$lang.controller.formLoggedInLabel}</a></th>
							<th align="center" width="120"><a href="{$filterUrl}sortby/dateexpired/sorttype/{if $formData.sortby eq 'dateexpired'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">{$lang.controller.formDateExpiredLabel}</a></th>
							
							<th width="70"></th>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<td colspan="9">
								<div class="pagination">
								   {assign var="pageurl" value="page/::PAGE::"}
									{paginate count=$totalPage curr=$curPage lang=$paginateLang max=10 url="`$paginateurl``$pageurl`"}
								</div> <!-- End .pagination -->
								
								<div class="bulk-actions align-left">
									<input type="submit" name="btnDel" class="btn btn-danger" value="{$lang.controller.deleteSelected}" />
								</div>
								
								<div class="clear"></div>
							</td>
						</tr>
					</tfoot>
					<tbody>
				{foreach from=$entries item=entry}
					
						<tr>
							<td align="center"><input type="checkbox" name="delid[]" value="{$entry->id}"/></td>
							<td align="left"><a href="javascript:void(0)" onclick="openEditWindow('{$conf.rooturl_admin}sessionmanager/detail/sid/{$entry->id}/{if $formData.fip neq ''}fip/{$formData.fip}/{/if}{if $formData.fsession neq ''}fsession/{$formData.fsession}{/if}');" title="{$entry->id}">{$entry->id}</a></td>
							<td align="center" style="font-weight:bold;font-family:'Courier New', Courier, monospace;font-size:14px;">
								{$entry->ipaddress}
							</td>
							<td align="center"><img alt="{$entry->browser->getBrowser()|lower}" title="{$entry->browser->getBrowser()} {$entry->browser->getVersion()}" src="{$imageDir}browsers/{$entry->browser->getBrowser()|lower}.png" /></td>
							<td align="center"><img alt="{$entry->browser->getPlatform()|lower}" title="{$entry->browser->getPlatform()}" src="{$imageDir}browsers/{$entry->browser->getPlatform()|lower}.png" width="16" /></td>
							<td align="center">[<b>{$entry->controller}&nbsp;</b>/{$entry->action}]</td>
							<td align="center">{if $entry->actor->id > 0}<a href="{$conf.rooturl_admin}user/edit/id/{$entry->actor->id}" title="{$entry->actor->fullname}">{$entry->actor->fullname} (#{$entry->actor->id})</a>{else}--{/if}</td>
							<td align="center"><span title="{$entry->dateexpired|date_format:$lang.default.dateFormatTimeSmarty}">{if $entry->isExpired()}<span class="label label-important">{$lang.controller.formExpiredLabel}</span>{else}{$entry->dateexpired|date_format:$lang.default.dateFormatTimeSmarty}{/if}</span></td>
							
							<td width="140">
								<a title="{$lang.default.formActionDetailTooltip}" onclick="openEditWindow('{$conf.rooturl_admin}sessionmanager/detail/sid/{$entry->id}/{if $formData.fip neq ''}fip/{$formData.fip}/{/if}{if $formData.fsession neq ''}fsession/{$formData.fsession}{/if}');" class="btn btn-mini"><i class="icon-info-sign"></i> {$lang.default.formDetailLabel}</a> &nbsp;
								<a title="{$lang.default.formActionDeleteTooltip}" href="javascript:delm('{$conf.rooturl_admin}sessionmanager/kill/sid/{$entry->id}/{if $formData.fip neq ''}fip/{$formData.fip}/{/if}{if $formData.fsession neq ''}fsession/{$formData.fsession}{/if}');" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i> {$lang.default.formDeleteLabel}</a>
						</tr>
						
					
				{/foreach}
				</tbody>
					
				  
				{else}
					<tr>
						<td colspan="9"> {$lang.default.notfound}</td>
					</tr>
				{/if}
				
				</table>
			</form>
			
			
			
		</div><!-- end #tab 1 -->
		<div class="tab-pane" id="tab2">
			<form action="{$conf.rooturl_admin}sessionmanager/{$action}" method="post" class="form-inline">
					{$lang.controller.formSessionIdLabel}:
					<input type="text" name="fsession"  id="fsession" value="{$formData.fsession}"/>
					{$lang.controller.formIpAddressLabel}:
					<input type="text" name="fip"  id="fip" value="{$formData.fip}" />
					
					<input type="submit" name="fsubmit" value="{$lang.default.filterSubmit}" class="btn btn-primary" />
					<input type="button" name="fclear" onclick="$('#fsession').val('').focus();$('#fip').val('');" class="btn" value="Clear" /></td>       
					
				</tr>
			</table>
			</form>
			
			
			
		</div><!-- end #tab2 -->
	</div>
</div>



<script type="text/javascript">
{literal}
function openEditWindow(url)
{
	var width = 900;
	var height = 500;
	
	if(screen.width != null && screen.height != null)
	{
		width = screen.width - 10;
		height = screen.height - 20;
	}
	else
	{
		width = $(window).width();
		height = $(window).height();
	}
	
	window.open(url, '', 'toolbar=0,scrollbars=yes,resizable=yes,width='+width+',height='+height+',top=0,left=0');
}


{/literal}
</script>





