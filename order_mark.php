<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and orderMark = 'Y'"));
    if ($userPrivCheckRow['orderMark'] != 'Y'){
        exit();
    }
?>

	<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
	<script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <input id="editid" type="hidden" name="desigid">
                <div>
                    <?php
                    if ((!isset($_POST['submit']))){
                    $orderMarkSQL = "select * from tbl_order_mark";
                    $orderMarkResult = mysqli_query($conn, $orderMarkSQL);
                    $orderMarkRow = mysqli_fetch_array($orderMarkResult);
                    ?>
                        <table class='table table-hover'>
                            <tr style='background-color:#dad8d8; li'>
                                <th>Express Background</th>
                                <th>Express Font</th>
                                <th>Large/Special</th>
                                <th>Price</th>
                                <th>Price Color</th>
                            </tr>
                            <tr>
                                <td><input style="background-color: <?php echo "#".$orderMarkRow['delOptionBack']; ?> ;color: <?php echo "#".$orderMarkRow['delOptionFont'] ;?>" id="expBack" name="delOptionBack" type="text" value="<?php echo $orderMarkRow['delOptionBack'];?>"></td>
                                <td><input style="background-color: <?php echo "#".$orderMarkRow['delOptionBack']; ?> ;color: <?php echo "#".$orderMarkRow['delOptionFont'] ;?>" id="expFont" name="delOptionFont" type="text" value="<?php echo $orderMarkRow['delOptionFont'];?>"></td>
                                <td><input style="color: <?php echo "#".$orderMarkRow['large'];?>" id="largeSP" name="large" type="text" value="<?php echo $orderMarkRow['large'];?>"></td>
                                <td><input id="pr" name="price" type="text" value="<?php echo $orderMarkRow['price'];?>"></td>
                                <td><input style="color: <?php echo "#".$orderMarkRow['priceColor'];?>" id="prColor" name="priceColor" type="text" value="<?php echo $orderMarkRow['priceColor'];?>"></td>
                            </tr>

                        </table>
                    <?php
                        }
                    ?>
                </div>
                <input type="submit" name="submit" value="Update" class="btn-primary"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
            </form>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <?php
                    if (isset($_POST['submit'])) {
                        $delOptionBack = trim($_POST['delOptionBack']);
                        $delOptionBack = mysqli_real_escape_string($conn, $delOptionBack);
                        $delOptionFont = trim($_POST['delOptionFont']);
                        $delOptionFont = mysqli_real_escape_string($conn, $delOptionFont);
                        $large = trim($_POST['large']);
                        $price = trim($_POST['price']);
                        $priceColor = trim($_POST['priceColor']);
                        $updateMarkSQL = "UPDATE tbl_order_mark SET delOptionBack='$delOptionBack', delOptionFont='$delOptionFont', large='$large', price='$price', priceColor='$priceColor' WHERE markid = '1'";
                        if (!mysqli_query($conn,$updateMarkSQL))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "Order Mark updated successfully";
                            echo "</div>";
                        }
                    }
                    mysqli_close($conn);
            ?>
        </div>
        <script>
        $('#expBack').ColorPicker({
	        color: '#0000ff',
	        onSubmit: function(hsb, hex, rgb, el) {
		        $(el).val(hex);
		        $(el).ColorPickerHide();
	        },	
	        onShow: function (colpkr) {
		        $(colpkr).fadeIn(500);
		        return false;
	        },
	        onHide: function (colpkr) {
		        $(colpkr).fadeOut(500);
		        return false;
	        },
	        onChange: function (hsb, hex, rgb) {
		        $('#expBack').css('backgroundColor', '#' + hex);
                $('#expFont').css('backgroundColor', '#' + hex);
		        $('#expBack').val(hex);
	        }
        });

        $('#expFont').ColorPicker({
	        color: '#00ffff',
	        onSubmit: function(hsb, hex, rgb, el) {
		        $(el).val(hex);
		        $(el).ColorPickerHide();
	        },	
	        onShow: function (colpkr) {
		        $(colpkr).fadeIn(500);
		        return false;
	        },
	        onHide: function (colpkr) {
		        $(colpkr).fadeOut(500);
		        return false;
	        },
	        onChange: function (hsb, hex, rgb) {
		        $('#expBack').css('color', '#' + hex);
                $('#expFont').css('color', '#' + hex);
		        $('#expFont').val(hex);
	        }
        });

        $('#largeSP').ColorPicker({
	        color: '#ff0000',
	        onSubmit: function(hsb, hex, rgb, el) {
		        $(el).val(hex);
		        $(el).ColorPickerHide();
	        },	
	        onShow: function (colpkr) {
		        $(colpkr).fadeIn(500);
		        return false;
	        },
	        onHide: function (colpkr) {
		        $(colpkr).fadeOut(500);
		        return false;
	        },
	        onChange: function (hsb, hex, rgb) {
		        $('#largeSP').css('color', '#' + hex);

	        }
        });

        $('#prColor').ColorPicker({
	        color: '#ff0000',
	        onSubmit: function(hsb, hex, rgb, el) {
		        $(el).val(hex);
		        $(el).ColorPickerHide();
	        },	
	        onShow: function (colpkr) {
		        $(colpkr).fadeIn(500);
		        return false;
	        },
	        onHide: function (colpkr) {
		        $(colpkr).fadeOut(500);
		        return false;
	        },
	        onChange: function (hsb, hex, rgb) {
		        $('#prColor').css('color', '#' + hex);

	        }
        });

        </script>
    </body>
</html>
