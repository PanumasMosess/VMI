<?
require_once("application.php");
$CFG->dbhost_pur = "203.154.39.115";
$CFG->dbhostPing_pur = "203.154.39.115";
$CFG->dbname_pur = "PUR";
$CFG->dbuser_pur = "sa";
$CFG->dbpass_pur = "P09iQA!WaT_?#R41!eXO";

//set var to array (var must be string type)
$connectionInfo_pur = array("Database" => "$CFG->dbname_pur", "UID" => "$CFG->dbuser_pur", "PWD" => "$CFG->dbpass_pur", "MultipleActiveResultSets" => true, 'ReturnDatesAsStrings' => true, "CharacterSet" => 'UTF-8');
$db_con_pur = sqlsrv_connect($CFG->dbhost_pur, $connectionInfo_pur);

if ($db_con_pur === false) {
    echo "Connection could not be established. <br/>";
    die(print_r(sqlsrv_errors(), true));
}
require_once("get_authorized.php");
require_once("js_css_header.php");

?>


<!DOCTYPE html>
<html>

<head>
    <style>
        .btn_submit {
            float: right;
        }

        li.active {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="hold-transition register-page">
    <header class="main-header" style="background-color: #D1D1D1;">
        <div class="navbar-header">
            <a href="#" class="navbar-brand" style="color: #3c8dbc;"><b>B2C Special Order</b></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle hidden-md hidden-lg" title="Toggle navigation" data-toggle="push-menu" role="button"></a>
            <div class="visible-lg" style="float: right; padding: 5px; color: #FFF;">
                <img src="<?= $CFG->logodir; ?>/dscm.jpg" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;" /><img src="<?= $CFG->logodir; ?>/GDJ.png" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;" />
            </div>
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?= $CFG->wwwroot; ?>/speial_b2c_form">Form<span class="sr-only">(current)</span></a></li>
                    <li><a href="<?= $CFG->wwwroot; ?>/list_confirm_b2c">List Confirm</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="register-box" style="width: 550px;">
        <div class="register-logo">
            <p>Special Order Form</p>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">ที่อยู่ในการจัดส่ง</p>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="ชื่อ-นามสกุล*" id="name_customer">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="ชื่อบริษัท (ถ้ามี)" id="name_business">
                <span class="glyphicon glyphicon-object-align-bottom form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <textarea class="form-control" rows="5" placeholder="ที่อยู่ ...*" id="address"></textarea>
                <span class="glyphicon glyphicon-home form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <select id="sel_provind" name="sel_provind" class="form-control">
                    <option selected="selected" value="">จังหวัด*</option>
                    <?
                    $strSQL_trading = "SELECT [name_th],[id],[geography_id] FROM [PUR].[dbo].[tbl_provinces_mst]";
                    $objQuery_trading = sqlsrv_query($db_con_pur, $strSQL_trading) or die("Error Query [" . $strSQL_trading . "]");
                    while ($objResult_trading = sqlsrv_fetch_array($objQuery_trading, SQLSRV_FETCH_ASSOC)) {
                    ?>
                        <option value="<?= $objResult_trading["id"] . "###" . $objResult_trading["geography_id"]; ?>"><?= $objResult_trading["name_th"]; ?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
            <div class="form-group has-feedback">
                <select class="form-control" id="sel_district">
                    <option selected="selected" value="">เขต / อำเภอ*</option>
                </select>
            </div>
            <div class="form-group has-feedback">
                <select class="form-control" id="sel_sub_district">
                    <option selected="selected" value="">ตำบล*</option>
                </select>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="postcode" placeholder="รหัสไปรษณีย์*">
                <span class="glyphicon glyphicon-home form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="โทรศัพท์*" id="tel">
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="อีเมล" id="email_customer">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <p class="login-box-msg">ใบกำกับภาษี</p>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="check_tax_full" value="false" />ต้องการใบกำกับภาษี
                </label>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="เลขประจำตัวผู้เสียภาษี หรือ เลขบัตรประชาชน" id="id_card_tax_full" disabled>
                <span class="glyphicon glyphicon-tags form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Branch id" id="branch_id_tax_full" disabled>
                <span class="glyphicon glyphicon-tags form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <textarea class="form-control" rows="5" placeholder="บันทึกเพิ่มเติม (ถ้ามี)" id="note"></textarea>
                <span class="glyphicon glyphicon-commen form-control-feedback"></span>
            </div>
            <p class="login-box-msg">รายการสินค้า</p>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="check_scg_send" value="false" />ส่งลูกค้าเอง
                </label>
            </div>
            <div class="form-group has-feedback">
                <select id="sel_product_code" name="sel_product_code" class="form-control select2">
                    <option selected="selected" value="">รหัสสินค้า*</option>
                    <?
                    $strSQL_code = "SELECT  *  FROM [tbl_bom_mst] where bom_pj_name = 'B2C' and bom_status = 'Active' order by bom_id desc";
                    $objQuery_code = sqlsrv_query($db_con, $strSQL_code) or die("Error Query [" . $strSQL_code . "]");
                    while ($objResult_code = sqlsrv_fetch_array($objQuery_code, SQLSRV_FETCH_ASSOC)) {
                    ?>
                        <option value="<?= $objResult_code["bom_fg_code_gdj"] . "###" . $objResult_code["bom_price_sale_per_pcs"] . "###" .  $objResult_code["bom_fg_code_set_abt"] . "###" . $objResult_code["bom_fg_sku_code_abt"] . "###" . $objResult_code["bom_ship_type"] . "###" . $objResult_code["bom_part_customer"] . "###" . $objResult_code["bom_fg_desc"]; ?>"><?= $objResult_code["bom_fg_desc"]; ?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
            <div class="form-group has-feedback">
                <input type="number" class="form-control" placeholder="กรุณากรอกราคาขาย / ชิ้น Selling Price*" id="number_selling">
                <span class="glyphicon glyphicon-modal-window form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="number" class="form-control" placeholder="กรุณากรอกจำนวน แล้วกด Enter*" id="number_product">
                <span class="glyphicon glyphicon-modal-window form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="ราคา" id="sale_price" disabled>
                <span class="glyphicon glyphicon-xbt form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="number" class="form-control" placeholder="น้ำหนัก (กรัม) Enter*" id="weight">
                <span class="glyphicon glyphicon-modal-window form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="ค่าขนส่ง" id="shipping_price" disabled>
                <span class="glyphicon glyphicon-xbt form-control-feedback"></span>
            </div>
            <p class="login-box-msg">การแบ่งชำระ</p>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="check_installment" value="false" />มีการแบ่งชำระ
                </label>
            </div>
            <div class="form-group has-feedback">
                <select class="form-control" id="sel_InstallMent" disabled>
                    <option selected="selected" value="" disabled>เลือกแบ่งชำระ</option>
                    <option value="2">2 งวด</option>
                    <option value="3">3 งวด</option>
                </select>
            </div>
            <div class="row">
                <div class="col-xs-4 btn_submit">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" onclick="submit_form()">Submit</button>
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->

    <?
    require_once("js_css_footer.php");
    ?>

    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        var subdistrict = $('#sel_sub_district');
        var districtObject = $('#sel_district');
        var provinObject = $('#sel_provind');

        // on change provid
        provinObject.on('change', function() {
            $('#shipping_price').val('');
            var provinId = $('#sel_provind').val();
            var str_split_provinId = provinId;
            var str_split__provinId_result = str_split_provinId.split("###");

            var provinId_id = str_split__provinId_result[0];
            var provinId_geo = str_split__provinId_result[1];


            districtObject.html('<option value="">เขต / อำเภอ*</option>');
            subdistrict.html('<option value="">ตำบล*</option>');

            $.get("<?= $CFG->src_replenishment; ?>/get_districts.php?districtsId=" + escape(provinId_id) + "", function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    districtObject.append(
                        $('<option></option>').val(item.id).html(item.name_th)
                    );
                });
            });
        });

        //on change district
        districtObject.on('change', function() {
            var districtId = $('#sel_district').val();

            subdistrict.html('<option value="">ตำบล*</option>');

            $.get("<?= $CFG->src_replenishment; ?>/get_sub_districts.php?sub_districtsId=" + escape(districtId) + "", function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    subdistrict.append(
                        $('<option></option>').val(item.zip_code).html(item.name_th)
                    );
                });
            });
        });

        var input_num_product = document.getElementById("number_product");
        input_num_product.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                var data_values = $("#sel_product_code").val();
                var str_split_result = data_values.split("###");

                // var product_num = $('#number_product').val();
                // var price_qty = str_split_result[1];

                var product_num = $('#number_product').val();
                var price_qty = $('#number_selling').val();

                var price_sum = product_num * price_qty;

                var price_sum = new Intl.NumberFormat('en-IN', {
                    maximumSignificantDigits: 3
                }).format(price_sum)

                $('#sale_price').val(price_sum);
            }
        });

        var input_weight = document.getElementById("weight");
        input_weight.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {


                var data_values = $("#sel_provind").val();
                var str_split_result = data_values.split("###");
                var provinId_id_geo = str_split_result[1];

                var product_weight = $('#weight').val();
                var product_qty = $('#number_product').val();

                if (($('#weight').val() == '') || $('#weight').val() == 0) {
                    $('#shipping_price').val('');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '<?= $CFG->src_replenishment; ?>/get_shipment_price.php',
                        data: {
                            ajax_provinId_id_geo: provinId_id_geo,
                            ajax_product_weight: product_weight,
                            ajax_product_qty: product_qty,
                        },
                        success: function(response) {

                            $('#shipping_price').val(response);
                        },
                        error: function() {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[D002]ติดต่อ Admin</span>",
                                type: "warning",
                                timer: 3000,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        }
                    });
                }


            }
        });


        $("#check_tax_full").on('change', function() {

            $('#id_card_tax_full').each(function() {
                if ($(this).attr('disabled')) {
                    $(this).removeAttr('disabled');
                } else {
                    $(this).attr({
                        'disabled': 'disabled'
                    });
                }
            });

            $('#branch_id_tax_full').each(function() {
                if ($(this).attr('disabled')) {
                    $(this).removeAttr('disabled');
                } else {
                    $(this).attr({
                        'disabled': 'disabled'
                    });
                }
            });
        });

        $("#check_scg_send").on('change', function() {

            $('#weight').each(function() {
                if ($(this).attr('disabled')) {
                    $(this).removeAttr('disabled');
                } else {
                    $(this).attr({
                        'disabled': 'disabled'
                    });
                }
            });
            $('#weight').val('');
        });

        $("#check_installment").on('change', function() {

            $('#sel_InstallMent').each(function() {
                if ($(this).attr('disabled')) {
                    $(this).removeAttr('disabled');
                } else {
                    $(this).attr({
                        'disabled': 'disabled'
                    });
                }
            });
            $('#sel_InstallMent').val('');
        });


        function submit_form() {


            if ($('#name_customer').val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก ชื่อลูกค้า</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#name_customer").focus();
                }, 3000);
            } else if ($('#address').val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก ที่อยู่</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#address").focus();
                }, 3000);
            } else if ($("#sel_provind").val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณาเลือก จังหวัด</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $('#sel_provind').select2('open');
                }, 3000);
            } else if ($("#sel_district").val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณาเลือก เขต / อำเภอ</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $('#sel_district').select2('open');
                }, 3000);
            } else if ($("#sel_sub_district").val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณาเลือก ตำบล</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $('#sel_sub_district').select2('open');
                }, 3000);
            } else if ($('#postcode').val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก รหัสไปรษณีย์</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#postcode").focus();
                }, 3000);
            } else if ($('#tel').val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก โทรศัพท์</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#tel").focus();
                }, 3000);
            } else if ($("#sel_product_code").val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณาเลือก รหัสสินค้า</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $('#sel_product_code').select2('open');
                }, 3000);
            } else if ($("#number_selling").val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรอกราคาต่อชิ้น</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $('#sel_product_code').select2('open');
                }, 3000);
            } else if ($('#sale_price').val() == '') {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก จำนวน</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#sale_price").focus();
                }, 3000);
            } else if (($('#shipping_price').val() == '') &&  ($("#check_scg_send").val() == true)) {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                    text: "<span style='font-size: 20px; color: #000;'>กรุณากรอก ค่าขนส่ง</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                setTimeout(function() {
                    $("#shipping_price").focus();
                }, 3000);
            } else {
                var data_values_bom = $("#sel_product_code").val();
                var str_split_result = data_values_bom.split("###");

                var bom_fg_code_gdj = str_split_result[0];
                var price_qty = str_split_result[1];
                var bom_fg_code_set_abt = str_split_result[2];
                var bom_fg_sku_code_abt = str_split_result[3];
                var bom_ship_type = str_split_result[4];
                var bom_part_customer = str_split_result[5];
                var bom_GDJ_description = str_split_result[6];

                $.ajax({
                    type: 'POST',
                    url: '<?= $CFG->src_replenishment; ?>/confirm_special_case.php',
                    data: {
                        ajax_province: $("#sel_provind").val(),
                        ajax_district: $("#sel_district").val(),
                        ajax_sel_sub_district: $("#sel_sub_district").val(),
                        ajax_name_customer: $("#name_customer").val(),
                        ajax_name_business: $("#name_business").val(),
                        ajax_address: $("#address").val(),
                        ajax_postcode: $("#postcode").val(),
                        ajax_tel: $("#tel").val(),
                        ajax_sel_product_code: $("#sel_product_code").val(),
                        ajax_sale_price: $("#sale_price").val(),
                        ajax_id_card_tax_full: $("#id_card_tax_full").val(),
                        ajax_branch_id_tax_full: $("#branch_id_tax_full").val(),   
                        ajax_bom_fg_code_gdj: bom_fg_code_gdj,
                        ajax_price_qty: price_qty,
                        ajax_bom_fg_code_set_abt: bom_fg_code_set_abt,
                        ajax_bom_ship_type: bom_ship_type,
                        ajax_bom_part_customer: bom_part_customer,
                        ajax_qty: $('#number_product').val(),
                        ajax_bom_fg_sku_code_abt: bom_fg_sku_code_abt,
                        ajax_selling_pcs: $("#number_selling").val(),
                        ajax_sender_: $('#check_scg_send').is(":checked"),
                        ajax_sel_InstallMent: $('#sel_InstallMent').val(),
                        ajax_desc: bom_GDJ_description
                    },
                    success: function(response) {

                        //clear
                        $("#sel_provind").val(null).trigger('change');
                        $("#sel_district").val(null).trigger('change');
                        $("#sel_sub_district").val(null).trigger('change');
                        $("#name_customer").val('');
                        $("#name_business").val('');
                        $("#address").val('');
                        $("#postcode").val('');
                        $("#tel").val('');
                        $("#sel_product_code").val(null).trigger('change');
                        $("#sale_price").val('');
                        $("#id_card_tax_full").val('');
                        $("#branch_id_tax_full").val('');
                        $('#number_product').val('');
                        $('#check_scg_send').prop('checked', false);
                        $("#number_selling").val('');
                        $("#shipping_price").val('');
                        $("#sel_InstallMent").val(null).trigger('change');
                        $("#weight").val('');
                        $("#email_customer").val('');

                        swal({
                            html: true,
                            title: "<span style='font-size: 20px; font-weight: bold;'>เพิ่มข้อมูลสำเร็จ</span>",
                            text: "<span style='font-size: 15px; color: #000;'>ข้อมูล Order ได้ถูกเพิ่มเข้าระบบ</span>",
                            type: "success",
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                    },
                    error: function() {
                        //dialog ctrl
                        swal({
                            html: true,
                            title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                            text: "<span style='font-size: 15px; color: #000;'>[D002]ติดต่อ Admin</span>",
                            type: "warning",
                            timer: 3000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>