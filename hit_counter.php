<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $hitSQL = "Select * from tbl_hit_counter";
    $hitResult = mysqli_query($conn, $hitSQL);
    $hitRow = mysqli_fetch_array($hitResult);
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="left: 20px; color: #16469E; font: 20px 'paperfly roman'">Home Page &nbsp;-><span style="color: #16469E;  font: 20px 'paperfly bold'">&nbsp;&nbsp;<?php echo $hitRow['home_page']?></span></span>
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #16469E; font: 20px 'paperfly roman'">About Us&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -><span style="color: #16469E; font: 20px 'paperfly bold'">&nbsp;&nbsp;<?php echo $hitRow['about_us']?></span></span>            
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #16469E; font: 20px 'paperfly roman'">Contact Us&nbsp;&nbsp; -><span style="color: #16469E; font: 20px 'paperfly bold'">&nbsp;&nbsp;<?php echo $hitRow['contact_us']?></span></span>
        </div>
    </body>
</html>
