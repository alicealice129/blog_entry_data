{include file='header.tpl' page_title="Pages"}

<nav class="navbar navbar-dark bg-dark">
  <span class="navbar-brand mb-0 h1">Blogエントリー検索</span>
</nav>

{include file='search.tpl' date="{$date}" url="{$url}" username="{$username}" server_name="{$server_name}" entry_number="{$entry_number}"}

{if ($error !=='')}
<div class="alert alert-danger" role="alert">{$error}</div>
{/if}

{block name=content}

<div>
  <p>検索結果数: {$nb_element}</p>
</div>

{include file='pager.tpl' page="{$page}" nb_page="{$nb_page}" ref="top"}

<table class="table table-striped table-bordered">
  <thead>
  {foreach $headers as $header}
    <th>{$header}</th>
  {/foreach}
  </thead>
  <tbody>
  {foreach $entries as $entry}
    <tr>
    {foreach array_keys($headers) as $header}
    {if $header==="url"}
      <td><a href="{$entry[$header]}"); return false;>{$entry[$header]}</a></td>
    {else}
      <td>{$entry[$header]}</td>
    {/if}
    {/foreach} 
    </tr>
  {/foreach}
  </tbody>
</table>

<div id="pager_bottom">
{include file='pager.tpl' page="{$page}" nb_page="{$nb_page}" ref="pager_bottom"}
</div>

{/block}