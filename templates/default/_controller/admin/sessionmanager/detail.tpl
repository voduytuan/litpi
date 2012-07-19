
<center>
<table width="98%" style="border:1px solid #00CCCC;font-family:Verdana, Arial, Helvetica, sans-serif;">
	<tr>
		<td height="32" bgcolor="#0099FF" align="center" style="font-weight:bold;color:#FFFFFF">
			SESSION ID #{$mySession->id}
		</td>
	</tr>
{if $error|@count == 0}

	<tr>
		<td>
			<table width="100%" class="tablegrid highlightTable" style="font-size:12px;border-collapse:collapse" cellpadding="5" border="0">
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Session ID</td>
					<td align="left">: {$mySession->id}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Session Data</td>
					<td align="left">: {$mySession->data|wordwrap:60:"\n":TRUE}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">IP Address</td>
					<td align="left">: {$mySession->ipaddress}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">HTTP_USER_AGENT</td>
					<td align="left">: {$mySession->agent}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Browser </td>
					<td align="left">: <img alt="{$mySession->browser->getBrowser()}" title="{$mySession->browser->getBrowser()} {$mySession->browser->getVersion()}" src="{$currentTemplate}/images/browsers/{$mySession->browser->getBrowser()|lower}.png" /> {$mySession->browser->getBrowser()} {$mySession->browser->getVersion()}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Platform</td>
					<td align="left">: <img alt="{$entry.browser.platform}" title="{$mySession->browser->getPlatform()}" src="{$currentTemplate}/images/browsers/{$mySession->browser->getPlatform()|lower}.png" /> {$mySession->browser->getPlatform()}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Controller/Action</td>
					<td align="left">: {$mySession->controller}/{$mySession->action}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Member</td>
					<td align="left">: {if $mySession->actor->id == 0}<span style="color:#999999">GUEST</span>{else}{$mySession->actor->fullname}{/if}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Created at</td>
					<td align="left">: {$mySession->datecreated|date_format:'%H:%M, %d/%m/%Y'}</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;" valign="top">Expired at</td>
					<td align="left">: {$mySession->dateexpired|date_format:'%H:%M, %d/%m/%Y'}</td>
				</tr>
			</table>
		</td>
	</tr>
{/if}
</table>


</center>