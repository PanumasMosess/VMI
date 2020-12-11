<?
require_once("application.php");

/**********************************************************************************/
/*get stats ***********************************************************************/
///////////////////////////////////
//stored procedure
//get count waiting approve
$objQuery_stats = sqlsrv_query($db_con, " EXEC sp_rpd_stats 'null' ");
$objResult_stats = sqlsrv_fetch_array($objQuery_stats, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_stats['CounterToday'] == NULL){ $strToday = '0'; } else { $strToday = $objResult_stats['CounterToday']; }
if($objResult_stats['CounterYesterday'] == NULL){ $strYesterday = '0'; } else { $strYesterday = $objResult_stats['CounterYesterday']; }
if($objResult_stats['CounttMonth'] == NULL){ $strThisMonth = '0'; } else { $strThisMonth = $objResult_stats['CounttMonth']; }
if($objResult_stats['CountlMonth'] == NULL){ $strLastMonth = '0'; } else { $strLastMonth = $objResult_stats['CountlMonth']; }
if($objResult_stats['CounttYear'] == NULL){ $strThisYear = '0'; } else { $strThisYear = $objResult_stats['CounttYear']; }
if($objResult_stats['CountlYear'] == NULL){ $strLastYear = '0'; } else { $strLastYear = $objResult_stats['CountlYear']; }
if($objResult_stats['Count2Year'] == NULL){ $str2LastYear = '0'; } else { $str2LastYear = $objResult_stats['Count2Year']; }
?>
<li class="header"><img src="<?=$CFG->imagedir;?>/138351.png" style="vertical-align: top;" height="16px" border="0"/> <span style="vertical-align: bottom;">Visitors Counter</span>
	<table class="table table-bordered" width="183" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:11px;">
		<thead>
		 <tr>
			<th colspan="2" style="background: linear-gradient(to top, #FFA500, #FFFFFF); border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #000;">Statistics</th>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td width="103" style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;Today</td>
			<td width="80" style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strToday,0);?></td>
		  </tr>
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;Yesterday</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strYesterday,0);?></td>
		  </tr>
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;This Month</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strThisMonth,0);?></td>
		  </tr>
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;Last Month</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strLastMonth,0);?></td>
		  </tr>
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;This Year</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strThisYear,0);?></td>
		  </tr>
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;Last Year</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($strLastYear,0);?></td>
		  </tr>
		  <!--
		  <tr>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: left; color: #DDD;">&nbsp;Two years ago</td>
			<td style="border-right:solid 1px #FFA500; border-top:solid 1px #FFA500; border-bottom:solid 1px #FFA500; border-left:solid 1px #FFA500; text-align: center; color: #DDD;"><?=number_format($str2LastYear,0);?></td>
		  </tr>
		  -->
		</tbody>
	</table>
</li>