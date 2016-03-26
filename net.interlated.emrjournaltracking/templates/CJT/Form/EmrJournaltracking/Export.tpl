{*

Export and create activities for journal valid contacts.

gregm@interlated.com.au 201206
*}
<h3>Export EMR Journals</h3>

<div class="form-item">
    <fieldset><legend>
            {ts}New Tracked Journal Export{/ts}
        </legend>
        <div class="crm-block crm-form-block crm-journaltag-batch-form-block">
            <div class="crm-submit-buttons">{include file="CJT/common/formButtons.tpl" location="top"}</div>

            <table class="form-layout-compressed">
                <tr class="crm-journaltag-batch-form-block-code">
                    <td class="label">{$form.code.label}<br/>Letters and numbers are OK. No spaces or other characters. EMR will be added as a prefix.</td>
                    <td>{$form.code.html}&nbsp;<span class="field-suffix"><a href="# " id="generate-code" onclick="return false;">Random</a></span><br />
                    </td>
                </tr>

                <tr class="crm-journaltag-batch-form-block-description">
                    <td class="label">{$form.description.label}</td>
                    <td>{$form.description.html}
                    </td>
                </tr>

                <!--
                <tr class="crm-journaltag-batch-form-block-batch_date">
                    <td class="label">Journal export will be run for: <br/>{$form.batch_date.label}</td>
                </tr>
                -->
            </table>

            <p>
            <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
            </p>
        </div>
    </fieldset>
</div>
{literal}
    <script type="text/javascript">

                        cj(document).ready(function() {
                            // maybe export csv on new screen
                            // Doesn't work.
                            cj('.csv').click(function() {
                                var batchDate = cj('#active_on_display').val();
                                var batchCode = cj('#code').val();

                                var target = this.href + '?batch_date_display=' + batchDate + '&code=' + batchCode;
                                window.location.href = target;
                                cj('.form-layout-compressed').html('bye');
                                return false;
                            });

                            cj("#generate-code").click(function() {
                                var chars = "abcdefghjklmnpqrstwxyz23456789";
                                var len = 8;

                                code = randomString(chars, len);
                                cj("#code").val(code);

                                return false;
                            });

                            // Yanked from http://stackoverflow.com/questions/2477862/jquery-password-generator
                            function randomString(chars, len) {
                                var i = 0;
                                var str = "";
                                while (i <= len) {
                                    $max = chars.length - 1;
                                    $num = Math.floor(Math.random() * $max);
                                    $temp = chars.substr($num, 1);
                                    str += $temp;
                                    i++;
                                }

                                return str;
                            }
                        });
    </script>
{/literal}
