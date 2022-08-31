<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$iden_hdn_ps_h_picking_code = isset($_POST['iden_hdn_ps_h_picking_code']) ? $_POST['iden_hdn_ps_h_picking_code'] : '';
$iden_txt_curr_dtn_no = isset($_POST['iden_txt_curr_dtn_no']) ? $_POST['iden_txt_curr_dtn_no'] : '';
$iden_txt_dtn_delivery_date = isset($_POST['iden_txt_dtn_delivery_date']) ? $_POST['iden_txt_dtn_delivery_date'] : '';
$iden_txt_dtn_delivery_time = isset($_POST['iden_txt_dtn_delivery_time']) ? $_POST['iden_txt_dtn_delivery_time'] : '';
$iden_txt_dtn_curr_scan_driver_iden_card = isset($_POST['iden_txt_dtn_curr_scan_driver_iden_card']) ? $_POST['iden_txt_dtn_curr_scan_driver_iden_card'] : '';
$tmp_sel = isset($_POST['tmp_sel']) ? $_POST['tmp_sel'] : '';

//get details for tbl_picking_head
$strSql_data_picking_h = " 
	SELECT 
		[ps_h_picking_code]
		,[ps_h_cus_code]
		,[ps_h_cus_name]
	FROM 
	tbl_picking_head 
	where ps_h_picking_code = '$iden_hdn_ps_h_picking_code' 
";
$objQuery_data_picking_h = sqlsrv_query($db_con, $strSql_data_picking_h, $params, $options);
$num_row_data_picking_h = sqlsrv_num_rows($objQuery_data_picking_h);

while($objResult_data_picking_h = sqlsrv_fetch_array($objQuery_data_picking_h, SQLSRV_FETCH_ASSOC))
{
	$ps_h_cus_code = $objResult_data_picking_h['ps_h_cus_code'];
	$ps_h_cus_name = $objResult_data_picking_h['ps_h_cus_name'];
}

//check first row
if($tmp_sel == 1)
{
	///////////////////insert tbl_dn_head///////////////////
	$strSql_insert_dtn_header = " 
		INSERT INTO [dbo].[tbl_dn_head]
			   (
				[dn_h_dtn_code]
				,[dn_h_cus_code]
				,[dn_h_cus_name]
				,[dn_h_cus_address]
				,[dn_h_driver_code]
				,[dn_h_delivery_date]
				,[dn_h_delivery_time]
				,[dn_h_status]
				,[dn_h_issue_by]
				,[dn_h_issue_date]
				,[dn_h_issue_time]
				,[dn_h_issue_datetime]
			   )
		 VALUES
			   (
				'$iden_txt_curr_dtn_no'
				,'$ps_h_cus_code'
				,'$ps_h_cus_name'
				,'336/11 Moo7 Bowin, Sriracha, Chonburi 20230'
				,'$iden_txt_dtn_curr_scan_driver_iden_card'
				,'$iden_txt_dtn_delivery_date'
				,'$iden_txt_dtn_delivery_time'
				,'Delivery Transfer Note'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
	";
	$objQuery_insert_dtn_header = sqlsrv_query($db_con, $strSql_insert_dtn_header);
}
	
///////////////////insert tbl_dn_tail///////////////////
$strSql_insert_dtn_tail = " INSERT INTO tbl_dn_tail
	   (
		[dn_t_dtn_code]
		,[dn_t_picking_code]
		,[dn_t_status]
		,[dn_t_issue_by]
		,[dn_t_issue_date]
		,[dn_t_issue_time]
		,[dn_t_issue_datetime]
	   )
 VALUES
	   (
		'$iden_txt_curr_dtn_no'
		,'$iden_hdn_ps_h_picking_code'
		,'Delivery Transfer Note'
		,'$t_cur_user_code_VMI_GDJ'
		,'$buffer_date'
		,'$buffer_time'
		,'$buffer_datetime'
	   )	
";

$objQuery_insert_dtn_tail = sqlsrv_query($db_con, $strSql_insert_dtn_tail);


if($objQuery_insert_dtn_tail)
{
	//read tbl_picking_tail for update tags ID to Delivery Transfer Note
	$strSql_update_tags_to_dtn = " 
		SELECT 
			[ps_t_picking_code]
			,[ps_t_tags_code]
		FROM [tbl_picking_tail]
		where
		[ps_t_picking_code] = '$iden_hdn_ps_h_picking_code' 
	";
	$objQuery_update_tags_to_dtn = sqlsrv_query($db_con, $strSql_update_tags_to_dtn, $params, $options);
	$num_row_update_tags_to_dtn = sqlsrv_num_rows($objQuery_update_tags_to_dtn);

	while($objResult_update_tags_to_dtn = sqlsrv_fetch_array($objQuery_update_tags_to_dtn, SQLSRV_FETCH_ASSOC))
	{
		$ps_t_picking_code = $objResult_update_tags_to_dtn['ps_t_picking_code'];
		$ps_t_tags_code = $objResult_update_tags_to_dtn['ps_t_tags_code'];
		
		//update tbl_receive - receive_status = Delivery Transfer Note
		$sqlUpdateDTN = " UPDATE tbl_receive SET receive_status = 'Delivery Transfer Note' WHERE receive_tags_code = '$ps_t_tags_code' ";
		$result_sqlUpdateDTN = sqlsrv_query($db_con, $sqlUpdateDTN);
	}

	// ///call scg express for B2C
	if(($ps_h_cus_code == 'B2C') && ($tmp_sel == 1))
	{
	
		//get data
		$strSql_d = " 
		SELECT 
		 dn_h_dtn_code
		,dn_h_delivery_date
		,[b2c_customer_code]
		,[b2c_customer_name]
		,[b2c_delivery_address]
		,[b2c_zipcode]
		,b2c_cus_zipcode
		,[b2c_contact_name]
		,[b2c_tel]
		,[b2c_note]
		,[b2c_order_date]
		,[b2c_repn_order_ref]
		,[b2c_sender]
	   ,[ps_t_cus_name]
	   ,[ps_t_pj_name]
	   ,[ps_t_replenish_unit_type]
		FROM tbl_dn_head
		left join tbl_dn_tail 
		on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
		left join tbl_picking_head
		on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
		left join tbl_picking_tail
		on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
		left join tbl_b2c_detail
		on tbl_picking_tail.ps_t_ref_replenish_code = tbl_b2c_detail.[b2c_repn_order_ref]
		left join tbl_bom_mst 
		on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
		and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
		and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
		and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
		and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
		and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
		where
		[dn_t_dtn_code] = '$iden_txt_curr_dtn_no'
		and bom_status = 'Active'
		group by 		
		 dn_h_dtn_code
		,dn_h_delivery_date
		,[dn_h_status]
		,[b2c_customer_code]
		,[b2c_customer_name]
		,[b2c_delivery_address]
		,b2c_cus_zipcode
		,[b2c_zipcode]
		,[b2c_contact_name]
		,[b2c_tel]
		,[b2c_note]
		,[b2c_order_date]
		,[b2c_repn_order_ref]	
	    ,[ps_t_cus_name]
	    ,[ps_t_pj_name]
	    ,[ps_t_replenish_unit_type]
		,[b2c_sender]
	";
	
	
	
			$objQuery_d = sqlsrv_query($db_con, $strSql_d);
			while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
			{

				$dn_h_dtn_code = $objResult_d['dn_h_dtn_code'];
				$dn_h_delivery_date = $objResult_d['dn_h_delivery_date'];
				$b2c_repn_order_ref = $objResult_d['b2c_repn_order_ref'];
				$b2c_customer_code = $objResult_d['b2c_customer_code'];
				$b2c_customer_name = $objResult_d['b2c_customer_name'];
				$b2c_delivery_address = $objResult_d['b2c_delivery_address'];
				$b2c_zipcode = $objResult_d['b2c_zipcode'];
				$b2c_contact_name = $objResult_d['b2c_contact_name'];
				$b2c_tel = $objResult_d['b2c_tel'];
				$b2c_note = $objResult_d['b2c_note'];
				$b2c_order_date = $objResult_d['b2c_order_date'];
				$b2c_order_date = $objResult_d['b2c_order_date'];
				$ps_t_location = $objResult_d['ps_t_location'];
				$qty = $objResult_d['qty'];
				$ps_t_cus_name = $objResult_d['ps_t_cus_name'];
				$ps_t_pj_name = $objResult_d['ps_t_pj_name'];
				$ps_t_replenish_unit_type = $objResult_d['ps_t_replenish_unit_type'];
				$ps_t_replenish_qty_to_pack = $objResult_d['ps_t_replenish_qty_to_pack'];
				$b2c_cus_zipcode = $objResult_d['b2c_cus_zipcode'];
				$b2c_sender = $objResult_d['b2c_sender'];
	
			$token_ = '';
	
			//login for send API 
			$url_login = $CFG->api_aut_link;
	
			$curl_login = curl_init($url_login);
			curl_setopt($curl_login, CURLOPT_URL, $url_login);
			curl_setopt($curl_login, CURLOPT_POST, true);
			curl_setopt($curl_login, CURLOPT_RETURNTRANSFER, true);
	
			$headers = array(
			   "Content-Type: application/x-www-form-urlencoded",
			);
			curl_setopt($curl_login, CURLOPT_HTTPHEADER, $headers);
	
			$data_login = "username=info%40GLONGDUANGJAI.com&password=Initial%401234";
	
			curl_setopt($curl_login, CURLOPT_POSTFIELDS, $data_login);
	
			//for debug only!
			curl_setopt($curl_login, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl_login, CURLOPT_SSL_VERIFYPEER, false);
	
			$resp_login = curl_exec($curl_login);
			$data_arr_login = json_decode($resp_login, true);
			curl_close($curl_login);
	
			$token_  = $data_arr_login['token'];
	
			//check Login 
			if($data_arr_login['status'] == true && $b2c_sender == null){
				
					//Order for send API 
					$url = $CFG->api_call;
	
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
					$headers = array(
					   "Content-Type: application/x-www-form-urlencoded",
					);
					curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				
					$data = "token=$token_&ShipperCode=101092&ShipperName=GLONG DUANG JAI&ShipperTel=0989878678&ShipperAddress=GLONG DUANG JAI CO.,LTD. Head Office 336/11 Moo 7 Bowin - Sriracha ชลบุรี&ShipperZipcode=20110&DeliveryAddress=$b2c_delivery_address&Zipcode=$b2c_cus_zipcode&ContactName=$b2c_customer_name&Tel=$b2c_tel&OrderCode=$iden_txt_curr_dtn_no&OrderDate=$b2c_order_date&TotalBoxs=1";
				
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				
					//for debug only!
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				
					$resp = curl_exec($curl);
					$data_arr_create = json_decode($resp, true);

					$trackingNumber = $data_arr_create['trackingNumber'];
					$trackingNumber = substr($trackingNumber,1,12);

					//update track and tracknumber
					$sqlUpdateb2c_table = " UPDATE tbl_b2c_detail SET b2c_dtn = '$dn_h_dtn_code', b2c_track_num = '$trackingNumber' WHERE b2c_repn_order_ref = '$b2c_repn_order_ref'";
					$sqlUpdateb2c_table = sqlsrv_query($db_con, $sqlUpdateb2c_table);
					
					curl_close($curl);
					echo $resp. '\n';
					
			}else if($data_arr_login['status'] == true){
				//update track and tracknumber
				$sqlUpdateb2c_table = " UPDATE tbl_b2c_detail SET b2c_dtn = '$dn_h_dtn_code' WHERE b2c_repn_order_ref = '$b2c_repn_order_ref'";
				$sqlUpdateb2c_table = sqlsrv_query($db_con, $sqlUpdateb2c_table);
						
			}
			else
			{
				echo 'Login False';
				
			}
	
		 }
	
	}

	$index_check++;
 
}
//update tbl_picking_head - ps_h_status = Delivery Transfer Note
$sqlUpdatePicking_head = " UPDATE tbl_picking_head SET ps_h_status = 'Delivery Transfer Note' WHERE ps_h_picking_code = '$iden_hdn_ps_h_picking_code' and ps_h_status = 'Picking' ";
$result_sqlUpdatePicking_head = sqlsrv_query($db_con, $sqlUpdatePicking_head);

//update tbl_picking_tail - ps_t_status = Delivery Transfer Note
$sqlUpdatePicking_tail = " UPDATE tbl_picking_tail SET ps_t_status = 'Delivery Transfer Note' WHERE ps_t_picking_code = '$iden_hdn_ps_h_picking_code' and ps_t_status = 'Picking' ";
$result_sqlUpdatePicking_tail = sqlsrv_query($db_con, $sqlUpdatePicking_tail);
	
//update tbl_dn_running - dn_status = Matched
$sqlUpdateDTNRunning = " UPDATE tbl_dn_running SET dn_status = 'Matched' WHERE dn_dtn_code = '$iden_txt_curr_dtn_no' ";
$result_sqlUpdateDTNRunning = sqlsrv_query($db_con, $sqlUpdateDTNRunning);



sqlsrv_close($db_con);

?>