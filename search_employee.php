<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>
        <div style="background-color: #dad8d8">
            <p style="font-weight: bold; color: #808080">Database Management=>Employee=>Edit Existing Employee</p>
        </div>
        <div style="width: 85%">
            <form id="newEmp" name="newEmp" action="editEmployee" method="post"  style="width: 100%; border-style: solid; border-color: #dad8d8; border-radius: 5px">
                <p style="background-color: #000; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">Edit Existing Employee</p>
                <table>
                    <tr>
                        <td>
                            <label>Employee Code&nbsp;</label>
                        </td>
                        <td>
                            <input type="text" name="empCode" style="height: 25px" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
