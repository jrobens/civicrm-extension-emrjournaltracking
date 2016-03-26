<h3>EMR Journal Export</h3>

<p>Run an EMR journal export. As part of the export, activities are generated for all
    records found. A journal export can only be run once per code.</p>
<p>A catchup batch can be run anytime after the original export to create an export
    of people who have not had label data exported for them for this batch.
    The catchup batch also attaches activities to each contact record found. These
    could be manually deleted. Catchup batches are to be considered 'once-only'.
</p>
<p>The alternative is to use <a href="/civicrm/contact/search/advanced?reset=1">custom advanced search</a> and export the data:</p>


{if $rows}
    <div id="dcode">
        {strip}
            {* handle enable/disable actions*}
            {include file="CJT/common/enableDisable.tpl"}    
            {include file="CJT/common/jsortable.tpl"}
            <table id="options" class="display">
                <thead>
                    <tr>
                        <th id="sortable">{ts}Name / Description{/ts}</th>
                        <th>{ts}Date{/ts}</th>
                        <th></th>
                    </tr>
                </thead>
                {foreach from=$rows item=row}
                    <tr id="row_{$row.id}" class="{if NOT $row.is_active} disabled{/if}{cycle values="odd-row,even-row"} {$row.class}">
                        <td class="crm-journaltracking">{$row.code}</td>	
                        <td>{if $row.batch_date neq '0000-00-00 00:00:00'}{$row.batch_date|truncate:10:''|crmDate}{/if}</td>	
                        <td>{$row.action|replace:'xx':$row.id}</td>
                    </tr>
                {/foreach}
            </table>
        {/strip}


    </div>
{else}
    <div class="messages status">
        <img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}"/>
    {capture assign=crmURL}{crmURL p='civicrm/cividiscount/discount/add' q="reset=1"}{/capture}
    {ts 1=$crmURL}There are no tracked journals.{/ts}
</div>    
{/if}
<div class="action-link">
    <a href="{crmURL p='civicrm/emrjournaltracking/export q="reset=1"}" id="newExport" class="button"><span>&raquo; {ts}New Journal Export{/ts}</span></a>
</div>