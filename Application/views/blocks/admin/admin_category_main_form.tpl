<tr>
    <td class="edittext">
        [{oxmultilang ident="GW_FRONTEND_TITLE"}]
    </td>
    <td class="edittext" colspan="2">
        <input type="text" class="editinput" size="25" maxlength="[{$edit->oxcategories__gw_frontend_title->fldmax_length}]" name="editval[oxcategories__gw_frontend_title]" value="[{$edit->oxcategories__gw_frontend_title->value}]" [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_FRONTEND_TITLE"}]
    </td>
</tr>
[{$smarty.block.parent}]
