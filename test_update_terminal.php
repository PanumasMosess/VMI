<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>	
<form name="frmMain" action="test_update_terminal_exe.php" method="post" enctype="multipart/form-data" onSubmit="return ChkSubmit();">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top: 25px;">
  <tr>
    <td align="center">
<fieldset style="min-height:100px;width:55%;">
<legend><font style="font-family:'Segoe UI Light'; font-size:16px;">Test อัพโหลด Excel</font></legend>
<center><table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td align="left" width="20%" bgcolor="#CCCCCC" style="color:#000; font-family:'Segoe UI Light'; font-size: 14px;">เลือก Excel File:</td>
    <td align="left" bgcolor="#CCCCCC"><input name="my_file" id="my_file" style="color:#00F; font-family:'Segoe UI Light'; font-size: 14px;" type="file"/></td>
  </tr>
  <tr>
	<td align="left" width="20%" bgcolor="#CCCCCC">&nbsp;</td>
    <td align="left" bgcolor="#CCCCCC"><font style='font-size:11px; color: #00F;'>&nbsp;* ประเภทไฟล์ (.xls,.xlsx) เท่านั้น.</font></td>
    </tr>
  <tr>
    <td colspan="2" align="center" width="100%"><div id="divFrm" name="divFrm"><input type="submit" name="submit" value="Upload" class="button_save1 button2" />&nbsp;<input type="button" onclick="javascript:location.reload();" value="Clear" class="button_clear1 button2" /></div></td>
    </tr>
</table>
</center>
</fieldset>
</td>
  </tr>
</table>
</form>

</body>
</html>