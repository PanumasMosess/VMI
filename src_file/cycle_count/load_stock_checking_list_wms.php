<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

?>

<div class="box-body table-responsive padding">
    <table id="tbl_detail_checking_Stock" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 18px;">
                <th colspan="8" class="bg-yellow"><b>
                        <font style="color: #FFF;"><i class="fa fa-bar-chart fa-lg"></i> Stock Checking List</font>
                    </b>
                    <span style="float: right;"> <button type="button" class="btn btn-default btn-sm" onclick="_export_stock_check_by_tags();"><i class="fa fa-file-excel-o fa-lg"></i> Export Excel</button>
                        <button type="button" class="btn btn-default btn-sm" onclick=" _export_stock_check_by_FG_code  ();"><i class="fa fa-file-excel-o fa-lg"></i> Export Excel By FG</button>
                        <button type="button" class="btn btn-default btn-sm" id="remove_temp" onclick="_remove_stock_temp();"><i class="fa fa-trash fa-lg"></i> Clear</button>
                    </span></th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px; text-align: center;">No.</th>
                <th style="text-align: center;">Pallet ID</th>
                <th style="text-align: center;">Tag ID</th>
                <th style="text-align: center;">FG Code GDJ</th>
                <th style="text-align: center;">Location</th>
                <th style="color: indigo; text-align: center;">Quantity (Pcs.)</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Stock Checking Date</th>
            </tr>
        </thead>
        <tbody>
            <?
$strSql = " 
SELECT [stock_pallet_code]
,[stock_tags_code]
,[stock_tags_fg_code_gdj]
,[stock_location]
,[stock_tags_packing_std]
,[stock_status]
,[stock_date]
FROM [tbl_stock_checking] 
order by 
stock_tags_code desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id_pallet = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id_pallet++;

	$stock_pallet_code = $objResult['stock_pallet_code'];
	$stock_tags_code = $objResult['stock_tags_code'];
	$stock_tags_fg_code_gdj = $objResult['stock_tags_fg_code_gdj'];
	$stock_location = $objResult['stock_location'];
	$stock_qty = $objResult['stock_tags_packing_std'];
    $stock_status = $objResult['stock_status'];
    $stock_date = $objResult['stock_date'];
?>
            <tr style="font-size: 13px;">
                <td align="center"><?= $row_id_pallet; ?></td>
                <td align="center">
                    <?= $stock_pallet_code ?>
                </td>
                <td align="center"><?= $stock_tags_code; ?></td>
                <td align="center"><?= $stock_tags_fg_code_gdj; ?></td>
                <td align="center"><?= $stock_location; ?></td>
                <td align="center" style="color: indigo; text-align: center;"><?= $stock_qty; ?></td>
                <td align="center"><?= $stock_status; ?></td>
                <td align="center"><?= $stock_date; ?></td>
            </tr>
            <?
}
?>
        </tbody>
    </table>
</div>


<!-- /.box-body -->
<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
    $(document).ready(function() {
        var check_data = $('#tbl_detail_checking_Stock').DataTable({
            rowReorder: true,
            "aLengthMenu": [
                [25, 50, 75, 100, -1],
                [25, 50, 75, 100, "All"]
            ],
            "iDisplayLength": -1,
            columnDefs: [{
                    orderable: true,
                    className: 'reorder',
                    targets: [0, 2, 3, 4, 5, 6, 7]
                },
                {
                    orderable: false,
                    targets: '_all'
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        var color = 'black';
                        if (data == "Match") {
                            color = 'green';
                        }
                        if (data == "Not Match") {
                            color = 'red';
                        }
                        return '<span style="color:' + color + '">' + data + '</span>';
                    }
                }
            ],
            pagingType: "full_numbers",
        });
        if (check_data.rows().count() == 0) {

            $('#remove_temp').prop('disabled', true);
        } else {

            $('#remove_temp').prop('disabled', false);
        }
    });
</script>