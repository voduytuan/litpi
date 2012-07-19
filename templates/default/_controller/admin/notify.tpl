{if count($notifySuccess) > 0}
<div class="alert alert-success">
	<a class="close" data-dismiss="alert" href="#">&times;</a>
	<h4 class="alert-heading">Success!</h4>
	{if $notifySuccess|@is_array}
		{foreach item=notifySuccessItem from=$notifySuccess name="notifysuccess"}
			<p><i class="icon-chevron-right"></i> {$notifySuccessItem}</p>
		{/foreach}
	{else}
		<p><i class="icon-chevron-right"></i> {$notifySuccess}</p>
	{/if}
</div>
{/if}

{if count($notifyError) > 0}
<div class="alert alert-error">
	<a class="close" data-dismiss="alert" href="#">&times;</a>
	<h4 class="alert-heading">Error!</h4>
	{if $notifyError|@is_array}
		{foreach item=notifyErrorItem from=$notifyError name="notifyerror"}
			<p><i class="icon-chevron-right"></i> {$notifyErrorItem}</p>
		{/foreach}
	{else}
		<p><i class="icon-chevron-right"></i> {$notifyError}</p>
	{/if}
</div>
{/if}

{if count($notifyWarning) > 0}
<div class="alert alert-block">
	<a class="close" data-dismiss="alert" href="#">&times;</a>
	<h4 class="alert-heading">Warning!</h4>
	{if $notifyWarning|@is_array}
		{foreach item=notifyWarningItem from=$notifyWarning name="notifywarning"}
			<p><i class="icon-chevron-right"></i> {$notifyWarningItem}</p>
		{/foreach}
	{else}
		<p><i class="icon-chevron-right"></i> {$notifyWarning}</p>
	{/if}
</div>
{/if}

{if count($notifyInformation) > 0}
<div class="alert alert-info">
	<a class="close" data-dismiss="alert" href="#">&times;</a>
	<h4 class="alert-heading">Information!</h4>
	{if $notifyInformation|@is_array}
		{foreach item=notifyInformationItem from=$notifyInformation name="notifyinformation"}
			<p><i class="icon-chevron-right"></i> {$notifyInformationItem}</p>
		{/foreach}
	{else}
		<p><i class="icon-chevron-right"></i> {$notifyInformation}</p>
	{/if}
</div>
{/if}