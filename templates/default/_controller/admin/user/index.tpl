<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menuuser}</a> <span class="divider">/</span></li>
	<li class="active">Listing</li>
</ul>

<a class="pull-right btn btn-success" href="{$conf.rooturl_admin}{$controller}/add">{$lang.controller.head_add}</a>

<div class="page-header" rel="menu_user_list"><h1>{$lang.controller.head_list}</h1></div>


<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">{$lang.controller.title_list} {if $formData.search != ''}| {$lang.controller.title_listSearch} {/if}({$total})</a></li>
		<li><a href="#tab2" data-toggle="tab">{$lang.default.filterLabel}</a></li>
		{if $formData.search != ''}
			<li><a href="{$conf.rooturl_admin}user">{$lang.default.formViewAll}</a></li>
		{/if}
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			{include file="`$smartyControllerGroupContainer`notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}
			
			<form class="form-inline" action="" method="post" onsubmit="return confirm('Are You Sure ?');">
				<table class="table table-striped" cellpadding="5" width="100%">
					
				{if $users|@count > 0}
					<thead>
						<tr>
						   <th width="40"><input class="check-all" type="checkbox" /></th>
							<th align="left" width="30"><a href="{$filterUrl}sortby/id/sorttype/{if $formData.sortby eq 'id'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">ID</a></th>	
							<th align="left"><a href="{$filterUrl}sortby/email/sorttype/{if $formData.sortby eq 'email'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">Email</a></th>		
                            <th align="center"><a href="{$filterUrl}sortby/group/sorttype/{if $formData.sortby eq 'group'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">Group</a></th>
							<th align="left"><a href="{$filterUrl}sortby/fullname/sorttype/{if $formData.sortby eq 'fullname'}{if $formData.sorttype|upper neq 'DESC'}DESC{else}ASC{/if}{/if}">Full Name</a></th>				
							<th width="200"></th>
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
				{foreach item=user from=$users}
					
						<tr>
							<td align="center"><input type="checkbox" name="fbulkid[]" value="{$user->id}" {if in_array($user->id, $formData.fbulkid)}checked="checked"{/if}/></td>
							<td style="font-weight:bold;"><a title="{$lang.default.formActionEditTooltip}" href="{$conf.rooturl_admin}user/edit/id/{$user->id}/redirect/{$redirectUrl}">{$user->id}</a></td>
							<td><a title="{$lang.default.formActionEditTooltip}" href="{$conf.rooturl_admin}user/edit/id/{$user->id}/redirect/{$redirectUrl}">{$user->email}</a></td>
                            <td align="center">{$user->groupname($user->groupid)}</td>
							<td>{$user->fullname}</td>
							<td><a title="{$lang.default.formActionEditTooltip}" href="{$conf.rooturl_admin}user/edit/id/{$user->id}/redirect/{$redirectUrl}" class="btn btn-mini"><i class="icon-pencil"></i> {$lang.default.formEditLabel}</a> &nbsp;
								<a title="{$lang.default.formActionDeleteTooltip}" href="javascript:delm('{$conf.rooturl_admin}user/delete/id/{$user->id}/redirect/{$redirectUrl}?token={$smarty.session.securityToken}');" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i> {$lang.default.formDeleteLabel}</a>
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
			<form class="form-inline" action="" method="post" style="padding:0px;margin:0px;" onsubmit="return false;">
	
				ID: 
				<input type="text" name="fid" id="fid" size="8" value="{$formData.fid|@htmlspecialchars}" class="input-mini" /> - 
				{$lang.controller.formKeywordLabel}:
				
				<input type="text" name="fkeyword" id="fkeyword" size="20" value="{$formData.fkeyword|@htmlspecialchars}" class="" />
				
				<select name="fsearchin" id="fsearchin">
					<option value="">- - - - - - - - - - - - -</option>
					<option value="email" {if $formData.fsearchin eq 'email'}selected="selected"{/if}>{$lang.controller.formKeywordInEmailLabel}</option>
					<option value="fullname" {if $formData.fsearchin eq 'fullname'}selected="selected"{/if}>{$lang.controller.formKeywordInFullnameLabel}</option>
				</select>
				
				<select name="fgroupid" id="fgroupid">
					<option value="">- - in Group - -</option>
					{html_options options=$userGroups selected=$formData.fgroupid}
				</select>
				
				
				<input type="button" value="{$lang.default.filterSubmit}" class="btn btn-primary" onclick="gosearch();"  />
		
			</form>
		</div><!-- end #tab2 -->
	</div>
</div>



{literal}
<script type="text/javascript">
	function gosearch()
	{
		var path = rooturl_admin + "user/index";
		
		var id = $("#fid").val();
		if(parseInt(id) > 0)
		{
			path += "/id/" + id;
		}
		
		var groupid = $("#fgroupid").val();
		if(parseInt(groupid) > 0)
		{
			path += "/groupid/" + groupid;
		}
		
		var keyword = $("#fkeyword").val();
		if(keyword.length > 0)
		{
			path += "/keyword/" + keyword;
		}
		
		var keywordin = $("#fsearchin").val();
		if(keywordin.length > 0)
		{
			path += "/searchin/" + keywordin;
		}
		
				
		document.location.href= path;
	}
</script>
{/literal}






