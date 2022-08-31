<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$buffer_date_30 = date('Y-m-d', strtotime($buffer_date . ' -30 day'));

?>
<div class="box-body table-responsive padding">
  <table id="tbl_print_tags" class="table table-bordered table-hover table-striped nowrap">
    <thead>
      <tr style="font-size: 13px;">
        <th style="width: 30px;">No.</th>
        <th style="text-align: center;">Actions/Details</th>
        <th>Tag Lot</th>
        <th>FG Code GDJ</th>
        <th>Description</th>
        <th>Lot Status</th>
        <th>Issue Datetime</th>
      </tr>
    </thead>
    <tbody>
      <?
      $strSql = "select tags_token, tags_fg_code_gdj, tags_fg_code_gdj_desc, tags_issue_date, receive_status from tbl_tags_running 
left join tbl_receive
on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
where 
tags_issue_date  between  '$buffer_date_30' and '$buffer_date'
group by tags_token, tags_fg_code_gdj, tags_issue_date , tags_fg_code_gdj_desc, receive_status ";

      $objQuery = sqlsrv_query($db_con, $strSql);

      $row_id = 0;
      $str_icon_rec = "";
      $str_status_rec = "";

      while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
        $row_id++;

        $tags_token = $objResult['tags_token'];
        $tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
        $tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
        $tags_issue_date = $objResult['tags_issue_date'];
        $receive_status = $objResult['receive_status'];

      ?>
        <tr style="font-size: 13px;">
          <td><?= $row_id; ?></td>
          <td align="center">
            <button type="button" class="btn btn-success btn-sm custom_tooltip" id="<?= var_encode($tags_token); ?>" onclick="openRePrintLot(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Tag Lot</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?= var_encode($tags_token); ?>" onclick="openRePrintSet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Lot Token</span></button>
          </td>
          <td><?= $tags_token; ?></td>
          <td><?= $tags_fg_code_gdj; ?></td>
          <td><?= $tags_fg_code_gdj_desc; ?></td>
          <?
          if ($receive_status == 'Received') {
          ?>
            <td style="color: green;"><?= $receive_status; ?></td>
          <?
          } else {
          ?>
            <td><?= $receive_status; ?></td>
          <?
          }
          ?>
          <td><?= substr($tags_issue_date, 0, 19); ?></td>
        </tr>
      <?
      }
      ?>
    </tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row" id="hdn_row" value="<?= $row_id; ?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
  $(document).ready(function() {
    //search
    /*$('#tbl_print_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/

    // <!--datatable search paging-->
    $('#tbl_print_tags_lot').DataTable({
      rowReorder: true,
			columnDefs: [{
					orderable: true,
					className: 'reorder',
					targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
				},
				{
					orderable: false,
					targets: '_all'
				}
			],
			pagingType: "full_numbers"
    });

  });
</script>