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
    <table id="tbl_detail_bom_wrong" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 18px;">
                <th colspan="10" class="bg-light-blue"><b>
                        <font style="color: #FFF;"><i class="fa fa-bar-chart fa-lg"></i> Bom Wrong List</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 18px;">
                <th colspan="2" class="bg-yellow" style="text-align: center;">
                    <b>
                        <font style="color: #FFF;"></font>
                    </b>
                </th>
                <th colspan="4" class="bg-yellow" style="text-align: center;">
                    <b>
                        <font style="color: #000;">WMS</font>
                    </b>
                </th>
                <th colspan="4" class="bg-yellow" style="text-align: center;">
                    <b>
                        <font style="color: #000;">Bom MST</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="text-align: center;">No.</th>
                <th style="text-align: center;">Action.</th>
                <th style="text-align: center;">FG Code DGJ</th>
                <th style="text-align: center;">FG Code DGJ Description</th>
                <th style="text-align: center;">PartCustomer</th>
                <th style="text-align: center;">Packing (Pcs.)</th>
                <th style="text-align: center;">FG Code GDJ</th>
                <th style="text-align: center;">FG Code DGJ Description</th>
                <th style="text-align: center;">PartCustomer</th>
                <th style="text-align: center;">Packing (Pcs.)</th>
            </tr>
        </thead>
        <tbody>
            <?
$strSql = " 
SELECT 
	 tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_packing_std
	,bom_fg_code_gdj
    ,bom_fg_desc
    ,bom_part_customer
    ,bom_packing

FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
left join tbl_bom_mst
on tbl_bom_mst.bom_fg_code_gdj = tbl_tags_running.tags_fg_code_gdj
where
(receive_status = 'Received') and (tags_packing_std != bom_packing) 
group by
	 tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_packing_std
	,bom_fg_code_gdj
    ,bom_fg_desc
    ,bom_part_customer
	,bom_packing

order by 
tags_fg_code_gdj desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id_pallet = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	 
        $row_id_pallet++;

        $wms_fg_code_gdj = $objResult['tags_fg_code_gdj'];
        $tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
        $wms_qty_code = $objResult['tags_packing_std'];
        $bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];
        $bom_fg_desc = $objResult['bom_fg_desc'];
        $bom_qty_code = $objResult['bom_packing'];
        $bom_part_customer = $objResult['bom_part_customer'];  
    
    
?>
            <tr style="font-size: 13px;">
                <td align="center"><?= $row_id_pallet; ?></td>
                <td align="center">
                    <button type="button" class="btn btn-warning btn-sm" id="" onclick="updatePacking('<?=$wms_fg_code_gdj?>','<?=$wms_qty_code?>','<?=$bom_qty_code?>' );" data-placement="top" data-toggle="tooltip" data-original-title="Update Packing Size"><i class="fa fa-pencil-square-o fa-lg"></i></button></td>
                <td align="center"><?= $wms_fg_code_gdj; ?></td>
                <td align="center"><?= $tags_fg_code_gdj_desc; ?></td>
                <td align="center"><?= $bom_part_customer; ?></td>
                <td align="center"><?= $wms_qty_code; ?></td>
                <td align="center"><?= $bom_fg_code_gdj; ?></td>
                <td align="center"><?= $bom_fg_desc; ?></td>
                <td align="center"><?= $bom_part_customer; ?></td>
                <td align="center"><?= $bom_qty_code; ?></td>
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
        var check_data = $('#tbl_detail_bom_wrong').DataTable({
            rowReorder: true,
            // aLengthMenu: [
            //     [25, 50, 75, 100, -1],
            //     [25, 50, 75, 100, "All"]
            // ],           
            columnDefs: [{
                targets: 5,
                render: function(data, type, row) {
                    color = 'red';
                    return '<span style="color:' + color + '">' + data + '</span>';
                }
            }, {
                targets: 9,
                render: function(data, type, row) {
                    color = 'red';
                    return '<span style="color:' + color + '">' + data + '</span>';
                }
            }],
            pagingType: "full_numbers",
        });
        if (check_data.rows().count() == 0) {

            $('#remove_temp').prop('disabled', true);
        } else {

            $('#remove_temp').prop('disabled', false);
        }
    });
</script>