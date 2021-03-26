<?
require_once("../../application.php");
/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$date_start = isset($_POST['startDate_']) ? $_POST['startDate_'] : '';
$date_end = isset($_POST['endDate_']) ? $_POST['endDate_'] : '';



//set project is setup terminal
$str_terminal = array('TSESA','TSPT','TSRA');

?>
<script>
    var donutData = [];
    var data_bar = [];
</script>

<?

/*query get project name **********************************************************/
function _get_all_project_name_by_user($db_con)
{

    require_once("../../get_authorized.php");
	if(($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "IT") 
    || ($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "GDJ") 
    || ($objResult_authorized['user_section'] == "IT") 
    || ($objResult_authorized['user_section'] == NULL)
    ){
        $strSQL = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name order by bom_pj_name asc ";
    }
    else 
    {     
        $section = $objResult_authorized['user_section']; 
        $strSQL = " SELECT bom_pj_name FROM tbl_bom_mst where bom_cus_code = '$section' group by bom_pj_name";
    }
    

	$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
	//clear
	$str_all_pj = '';
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$str_all_pj.="".ltrim(rtrim($objResult["bom_pj_name"]))."".",";
	}
	
	//substr last digit
	$str_all_pj = substr($str_all_pj, 0, -1);
	
	return $str_all_pj;
	sqlsrv_close($db_con);
}

//<!--for loop get all project-->
$str_implode_all_PJ = _get_all_project_name_by_user($db_con);
//explode
$separated_all_PJ = explode(",", $str_implode_all_PJ);
$num_all_PJ_separated = count($separated_all_PJ);

foreach ($separated_all_PJ as $value_all_PJ) 
{
//check word
$str_chk_pj = $value_all_PJ;

//project list allow
$array  = $str_terminal;
$str_chk = strpos_var($str_chk_pj, $array); // will return true

if($str_chk == false)
{
$str_word_pre_fix = "Project";
}
else
{
$str_word_pre_fix = "Terminal";
}
   $price = get_vmi_stock_price($db_con, $value_all_PJ, $date_start, $date_end);
   $pricephp =  number_format($price,2);
?>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-blue">
        <div class="inner">
            <font style="font-size:10px; color: #FFF;">Total Price</font>
            <h4 style="color: #FFF;"><?= $pricephp . "฿";  ?></h4>
            <p style="color: #FFF;"><?= $str_word_pre_fix ?> <b><?= $value_all_PJ; ?></b></p>
        </div>
        <div class="icon">
            <i class="fa fa-bar-chart"></i>
        </div>
        <!-- <a href="<?= $CFG->wwwroot; ?>/xxxxxx" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
    </div>
</div>
<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    var num_ = 0;
    num_ = parseFloat(<?= $price ?>);
    var object = {
        label: '<?= $value_all_PJ; ?>',
        data: num_,
        color: getRandomColor()
    }
    donutData.push(object);

    data_bar.push([
        '<?= $value_all_PJ; ?>', num_
    ]);
</script>
<?		                            	
}
?>
<script>
    /*
     * DONUT CHART
     * -----------
     */
    $.plot('#donut-chart', donutData, {
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0.5,
                label: {
                    show: true,
                    radius: 2 / 3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: true,
            labelFormatter: function(label, series) {
                var number = series.data[0][1]; //kinda weird, but this is what it takes
                var bath = number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                return ('&nbsp;<b>' + label + '</b>:&nbsp;<b>' + bath + ' ฿</b>');
            }
        }
    })
    /*
     * END DONUT CHART
     */



    /*
     * BAR CHART
     * ---------
     */
    var bar_data = {
        data: data_bar,
        color: getRandomColor()
    }
    $.plot('#bar-chart', [bar_data], {
        grid: {
            hoverable: true,
            borderWidth: 0.2,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            axisLabel: "Project",
            mode: 'categories',
            tickLength: 0
        },
        tooltip: {
            show: true,
            cssClass: "flotTip",
            content: function(label, xval, yval, flotItem) {
                var bath = yval.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                return ('&nbsp;<b>' + bath + '&nbsp;฿</b>');
            },
        }
    })
    /* END BAR CHART */

    $("#spn_time").html("<?=date("Y-m-d H:i:s")?>");
</script>