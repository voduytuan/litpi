<ul class="breadcrumb">
	<li><a href="{$conf.rooturl_admin}">{$lang.default.menudashboard}</a> <span class="divider">/</span></li>
	<li><a href="{$conf.rooturl_admin}{$controller}">{$lang.default.menulanguage}</a> <span class="divider">/</span></li>
	<li class="active">Listing</li>
</ul>

<div class="page-header" rel="menu_language"><h1>{$lang.default.menulanguage}</h1></div>

<h3>{$lang.controller.title_list}</h3>



<div>
{foreach item=language from=$langPacks}
	<div class="langfolder well span4">
		<div class="langfolder_langpack">
			{$language.folder}
		</div>
		<ul class="unstyled">
			{foreach item=groupfiles key=groupname from=$language.controllergroup}
				<li class="langfolder_folder">
					<i class="icon-folder-open"></i> {$groupname}
					<ul class="unstyled">
						{foreach item=langfile from=$groupfiles}
							<li class="langfolder_file">
								<a href="{$conf.rooturl_admin}language/edit/folder/{$language.folder}/subfolder/{$groupname}/file/{$langfile}"><i class="icon-file"></i> {$langfile}</a>
							</li>
						{/foreach}
					</ul>
				</li>
			{/foreach}
			
			{foreach item=langfile from=$language.files}
				<li class="langfolder_file">
					<a href="{$conf.rooturl_admin}language/edit/folder/{$language.folder}/file/{$langfile}"><i class="icon-file"></i> {$langfile}</a>
				</li>
			{/foreach}
		</ul>
		
	</div>
{/foreach}
</div>
	


