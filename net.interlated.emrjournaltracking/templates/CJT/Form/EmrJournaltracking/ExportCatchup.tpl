{*

Run a catchup export batch for a specific journal export.

jrobens@interlated.com.au 201206
*}
<h3>Export EMR Journal Catchup</h3>

<div class="form-item">
    <fieldset><legend>
            {ts}New Tracked EMR Journal Catchup Export{/ts}
        </legend>
        <div class="crm-block crm-form-block crm-journaltag-batch-form-block">
            <div class="crm-submit-buttons">{include file="CJT/common/formButtons.tpl" location="top"}</div>

            <table class="form-layout-compressed">
                <tr class="crm-journaltag-batch-form-block-code">
                    <td class="label">{$form.code.label}</td>
                    <td>{$form.code.html}</td>
                </tr>

                <tr class="crm-journaltag-batch-form-block-description">
                    <td class="label">{$form.description.label}</td>
                    <td>{$form.description.html}
                    </td>
                </tr>
            </table>

            <p>
            <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
            </p>
        </div>
        {literal}
            <script type="text/javascript">
                cj(document).ready(function() {
                    cj('#_qf_ExportCatchup_cancel-bottom').click(function() {
                        window.location.href = "/civicrm/emrjournaltracking/view?id=" + {/literal}"{$form.catchup_id.value}"{literal} + "&reset=1";
                        return false;
                    });
                });
            </script>
        {/literal}
    </fieldset>
</div>