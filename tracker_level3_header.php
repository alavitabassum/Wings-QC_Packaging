
        <div style="clear: both">
        <p id="ordercount" style="color: #16469E; font: 13px 'paperfly roman'"><span style="font: 13px 'paperfly roman'"><u></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span>
        <?php if ($unassaignedOrder > 0  and $user_type !="Merchant"){?>
        <span id="ordercount" style="color: red; font: 13px 'paperfly roman'"><span style="color: red; font: 13px 'paperfly roman'"><u></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span></span>
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
                        </tr>
                    </table>
                    </form>
        </div>        