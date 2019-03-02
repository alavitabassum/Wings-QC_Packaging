
        <div style="clear: both; margin-left: 1%">
        <p id="ordercount" style="color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp;Order Count : <span style="font: 13px 'paperfly roman'"><u><?php echo $total_rows;?></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span>
        <?php if ($unassaignedOrder > 0  and $user_type !="Merchant"){?>
        <span id="ordercount" style="color: red; font: 13px 'paperfly roman'">&nbsp;&nbsp;New orders for delivery : <span style="color: red; font: 13px 'paperfly roman'"><u><?php echo $unassaignedOrder;?></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span></span>
        <?php }?>
            </p>
            <input id="usercode" type="hidden" value="<?php echo $user_code ;?>">
            <form action="" method="post">
            <table border="0" style="margin-left: 1px">
                <tr>
                    <td style="padding-left: 1px; width: 190px">
                        <input  style="height: 25px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="searchText" placeholder="Search Order ID">
                    </td>
                    <td>
                        <button style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="submit" class="btn btn-default" name="searchOrder">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </td>
                    <?php if ($user_type != 'Merchant'){?>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label></td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Pick-up Merchant&nbsp;&nbsp;<img id="img6" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 6)"/></label></td>
                    <?php }?>
                </tr>
            </table>
            </form>
        </div>        