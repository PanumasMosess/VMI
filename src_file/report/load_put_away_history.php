<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';
$fg_code = isset($_POST['fg_code_']) ? $_POST['fg_code_'] : '';


?>
<div class="box-body table-responsive padding">
    <table id="tbl_history_paicking" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 13px;">
                <th colspan="13" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp; Put-Away(Receive) History</b>&nbsp;<b class="btn" id="excel_export"></b></th>
                <!-- &nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button>-->
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px;">No.</th>
                <th style="text-align: center;">Actions/Details</th>
                <th>Pallet ID</th>
                <th>Tag ID</th>
                <th>FG Code GDJ</th>
                <th>FG Code GDJ Desc.</th>
                <th>Location</th>
                <th style="color: indigo;">Quantity (Pcs.)</th>
                <th>Status</th>
                <th>Trading From</th>
                <th>Log Split</th>
                <th>Receive Date</th>
                <th>Receive Time</th>
            </tr>
        </thead>
        <tbody>
            <?
            if($fg_code == 'ALL' and ($date_start != '' and $date_end != '' )){

                $strSql = "select 
                receive_pallet_code
                ,receive_tags_code
                ,tags_fg_code_gdj
                ,tags_fg_code_gdj_desc
                ,receive_location
                ,receive_status
                ,receive_date
                ,receive_time
                ,[tags_trading_from]
                ,log_new_tagcode
                ,tags_packing_std
                FROM tbl_pallet_running
                left join tbl_receive
                on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
                left join tbl_tags_running
                on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
                left join tbl_tags_split_log_new
                on tbl_receive.receive_tags_code = tbl_tags_split_log_new.log_new_tagcode
                where   receive_date between '$date_start' and '$date_end'
                group by
                receive_pallet_code
                ,receive_tags_code
                ,tags_fg_code_gdj
                ,tags_fg_code_gdj_desc
                ,receive_location
                ,receive_status
                ,receive_date
                ,receive_time
                ,tags_packing_std 
                ,[tags_trading_from]
                ,log_new_tagcode
                order by 
                receive_pallet_code desc ";
            }else{
                $strSql = "select 
                receive_pallet_code
                ,receive_tags_code
                ,tags_fg_code_gdj
                ,tags_fg_code_gdj_desc
                ,receive_location
                ,receive_status
                ,receive_date
                ,receive_time
                ,[tags_trading_from]
                ,log_new_tagcode
                ,tags_packing_std
                FROM tbl_pallet_running
                left join tbl_receive
                on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
                left join tbl_tags_running
                on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
                left join tbl_tags_split_log_new
                on tbl_receive.receive_tags_code = tbl_tags_split_log_new.log_new_tagcode
                where tags_fg_code_gdj = '$fg_code' and (receive_date between '$date_start' and '$date_end')
                group by
                receive_pallet_code
                ,receive_tags_code
                ,tags_fg_code_gdj
                ,tags_fg_code_gdj_desc
                ,receive_location
                ,receive_status
                ,receive_date
                ,receive_time
                ,tags_packing_std 
                ,[tags_trading_from]
                ,log_new_tagcode
                order by 
                receive_pallet_code desc ";
            }
          

            $objQuery = sqlsrv_query($db_con, $strSql);

            $row_id = 0;

            while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
                $row_id++;

                $receive_pallet_code = $objResult['receive_pallet_code'];
                $tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
                $tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
                $receive_location = $objResult['receive_location'];
                $receive_status = $objResult['receive_status'];
                $receive_date = $objResult['receive_date'];
                $receive_time = $objResult['receive_time'];
                $tags_packing_std = $objResult['tags_packing_std'];
                $tags_trading_from = $objResult['tags_trading_from'];
                $log_new_tagcode = $objResult['log_new_tagcode'];
                $receive_tags_code = $objResult['receive_tags_code'];

            ?>
                <tr style="font-size: 13px;">
                    <td><?= $row_id; ?></td>
                    <td align="center">
                        <!--<button type="button" class="btn btn-warning btn-sm" id="<?= $receive_pallet_code; ?>" onclick="openRefill(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Refill this Pallet ID"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;--><button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($receive_pallet_code); ?>" onclick="openRePrintPalletID(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Pallet Tag</span></button>
                        <!-- &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm" id="<?= var_encode($receive_tags_code); ?>" onclick="openRePrintTagOnTag(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print  Tag On Tag"><i class="fa fa-print fa-lg"></i></button> -->
                    </td>
                    <td><?= $receive_pallet_code; ?></td>
                    <td><?= $receive_tags_code; ?></td>
                    <td><?= $tags_fg_code_gdj; ?></td>
                    <td><?= $tags_fg_code_gdj_desc; ?></td>
                    <td><?= $receive_location; ?></td>
                    <td style="color: indigo;"><?= number_format($tags_packing_std); ?></td>
                    <td style="color: green;"><?= $receive_status; ?></td>
                    <td><?= $tags_trading_from; ?></td>
                    <? if ($log_new_tagcode != null) { ?>
                        <td>Tag Split</td>
                    <? } else { ?>
                        <td></td>
                    <? } ?>
                    <td><?= $receive_date; ?></td>
                    <td><?= date('H:i:s', strtotime($receive_time)); ?></td>
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

        var pickingHis = $('#tbl_history_paicking').DataTable({
            rowReorder: true,
            "oLanguage": {
                "sSearch": "Filter Data"
            },
            pagingType: "full_numbers",
        });

        var buttons = new $.fn.dataTable.Buttons(pickingHis, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Put-Away History',
                titleAttr: 'Excel Put-Away History Report',
                title: 'Excel Put-Away History Report',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    },
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            }],
            dom: {
                button: {
                    tag: 'button',
                    className: 'btn btn-default'
                }
            },
        }).container().appendTo($('#excel_export'));
        $("#loadding").modal("hide");
    });
</script>