{*
+--------------------------------------------------------------------+
| CiviCRM version 4.1                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2011                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*}
<div class="crm-block crm-content-block crm-discount-view-form-block">

    <h3>{ts}View EMR Journal Export{/ts}</h3>

    <div class="action-link">
        <!-- no need for edit or delete -->
        <div class="crm-submit-buttons">
            {assign var='urlParams' value="reset=1&id=$id"}
            {if call_user_func(array('CRM_Core_Permission','check'), 'administer CiviCRM')}
                <a class="button" href="{crmURL p='civicrm/emrjournaltracking/export-catchup' q=$urlParams}" accesskey="e"><span><div class="icon edit-icon"></div>{ts}Run Update Batch{/ts}</span></a>
                        {/if}
                        {include file="CRM/common/formButtons.tpl" location="top"}
        </div>
    </div>

    <table class="crm-info-panel">
        <tr>
            <td class="label">{ts}Code{/ts}</td>
            <td>{$code}</td>
        </tr>
        <tr>
            <td class="label">{ts}Description{/ts}</td>
            <td>{$description}</td>
        </tr>
        <tr>
            <td class="label">{ts}Batch Date{/ts}</td>
            <td>{if $batch_date neq '0000-00-00 00:00:00'}{$batch_date|truncate:10:""|crmDate}{else}{/if}</td>
        </tr>
        <tr>
            <td class="label">{ts}Initial Batch Count{/ts}</td>
            <td>{$count}</td>
        </tr>
        <tr>   
            <td class="label">{ts}Update batches run:{/ts}</td>
            <td>
                <table>
                    <tr><th>Date Run</th><th>Description</th><th>Exported Addresses</th></tr>
                    {foreach from=$catchup_batches item=batch}
                        <tr> 
                            <td class="catchup_date">{if $batch.catchup_date neq '0000-00-00 00:00:00'}{$batch.catchup_date|truncate:10:""|crmDate} {/if}</td>
                            <td>{if empty($batch.description)}&nbsp;{/if}
                                {$batch.description}
                            </td>
                            <td>{$batch.count}</td>
                        {/foreach}
                </table>
            </td>
        </tr>
    </table>

    <div class="crm-submit-buttons">
        {if call_user_func(array('CRM_Core_Permission','check'), 'administer CiviCRM')}
            <a class="button" href="{crmURL p='civicrm/emrjournaltracking/export-catchup' q=$urlParams}" accesskey="e"><span><div class="icon edit-icon"></div>{ts}Run Update Batch{/ts}</span></a>
                    {/if}
                    {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
</div>
