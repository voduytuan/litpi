
	{include file="notify.tpl" notifyWarning=$lang.global.pagenotfound}
	</div>
    
    <div class="notfound_requesturl">{$lang.default.notfoundRequestUrl} <span>{$referer|escape}</span></div>
	
	<div style="padding:20px; line-height:2;">
	<h2 style="padding-bottom:0; margin-bottom:0;"><em>{$lang.controller.headline}</em></h2>
	<p>{$lang.controller.text1}</p>
	<p>{$lang.controller.text2}</p>
	<p>{$lang.controller.text3}</p
	<p>{$lang.controller.text4}</p>
    {if $recommendurl != ''}<p>{$lang.controller.text3} <a href="{$recommendurl}">{$recommendurl}</a></p>{/if}
	</div>
