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
    <table id="tbl_detail_Customer" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 18px;">
                <th colspan="13" class="bg-light-blue"><b>
                        <font style="color: #FFF;"><i class="fa fa-user fa-lg"></i>&nbsp;Customer Master Data</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px; text-align: center;">No.</th>
                <th style="text-align: center;">Actions.</th>
                <th style="text-align: center;">Customer Code</th>
                <th style="text-align: center;">Customer Name Th</th>
                <th style="text-align: center;">Customer Name EN</th>
                <th style="text-align: center;">Email Customer</th>
                <th style="text-align: center;">Bom Customer Code</th>
                <th style="text-align: center;">Bom Project Name</th>
                <th style="text-align: center;">Customer Terminal Type</th>
                <th style="text-align: center;">Customer Type</th>
                <th style="text-align: center;">Customer Status</th>             
                <th style="text-align: center;">Customer issue by</th>
                <th style="text-align: center;">Customer issue Datetime</th>
            </tr>
        </thead>
        <tbody>
            <?
$strSql = " 
SELECT [cus_id]
      ,[cus_pass_md5]
      ,[cus_code]
      ,[cus_name_th]
      ,[cus_name_en]
      ,[cus_with_bom_cus_code]
      ,[cus_with_bom_pj_name]
      ,[cus_terminal_type]
      ,[cus_email]
      ,[cus_type]
      ,[cus_status]
      ,[cus_issue_by]
      ,[cus_issue_datetime]
  FROM [tbl_customer_mst]
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    
    $cus_id = $objResult['cus_id'];
    $cus_pass_md5 = $objResult['cus_pass_md5'];
    $cus_code = $objResult['cus_code'];
    $cus_name_th = $objResult['cus_name_th'];
    $cus_name_en = $objResult['cus_name_en'];
    $cus_with_bom_cus_code = $objResult['cus_with_bom_cus_code'];
    $cus_with_bom_pj_name = $objResult['cus_with_bom_pj_name'];
    $cus_terminal_type = $objResult['cus_terminal_type'];
    $cus_email = $objResult['cus_email'];
    $cus_type = $objResult['cus_type'];
    $cus_status = $objResult['cus_status'];
    $cus_issue_by = $objResult['cus_issue_by'];
    $cus_issue_datetime = $objResult['cus_issue_datetime'];
   
?>
            <tr style="font-size: 13px;">
                <td align="center"><?=$row_id; ?></td>
                <td>
                <?
                    if($cus_status == "Active"){ ?>  
                        <button type="button" class="btn btn-primary btn-sm" id="" onclick="openUpdateCustomer('<?=$cus_id;?>','<?=$cus_code;?>','<?=$cus_name_th;?>','<?=$cus_name_en;?>', '<?=$cus_with_bom_cus_code;?>', '<?=$cus_with_bom_pj_name;?>', '<?=$cus_terminal_type; ?>', '<?=$cus_type; ?>', '<?=$cus_email; ?>');" data-placement="top" data-toggle="tooltip" data-original-title="Update Customer"><i class="fa fa-check-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="" onclick="inActive('<?=$cus_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Delete Customer"><i class="fa fa-times fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm" id="" onclick="openChangePass('<?=$cus_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Change Password"><i class="fa fa-key fa-lg"></i></button>
                  <?  }else{ ?>

                  <?  } ?>        
                </td>
                <td align="center"><?=$cus_code; ?></td>
                <td align="center"><?=$cus_name_th; ?></td>
                <td align="center"><?=$cus_name_en; ?></td>
                <td align="center"><?=$cus_email; ?></td>
                <td align="center"><?=$cus_with_bom_cus_code; ?></td>
                <td align="center" style="text-align: center;"><?=$cus_with_bom_pj_name; ?></td>
                <td align="center"><?=$cus_terminal_type; ?></td>
                <td align="center"><?=$cus_type; ?></td>
                <td align="center"><?=$cus_status; ?></td>
                <td align="center"><?=$cus_issue_by; ?></td>
                <td align="center"><?=$cus_issue_datetime; ?></td>
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
        var check_data = jQuery('#tbl_detail_Customer').DataTable({
            rowReorder: true,
            // "aLengthMenu": [
            //     [25, 50, 75, 100, -1],
            //     [25, 50, 75, 100, "All"]
            // ],
            // "iDisplayLength": -1,
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
                    targets: 10,
                    render: function(data, type, row) {
                        var color = 'black';
                        if (data == "Active") {
                            color = 'green';
                        }
                        else {
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