<?php

    include('session.php');
    include('header.php');
    include('config.php');
    $sql = "SELECT * from temporary_merchant_reg where status = 0";  
    $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));

    

?>

<div class= "new_merchant_tbl">
    <h4  class="tbl_title">New Merchant</h4>
<table>
<thead>
<tr class="tbl_header" >
        <th>Merchant Name</th>
        <th>Contact Number</th>
        <th>Edit Info</th>
        <th>Delete Info</th>
</tr></thead>

  <tbody>
<?php while( $row = $result->fetch_assoc()) : ?>

    <tr>
      <td>
          <p><?php echo $row['merchantName']?></p>
      </td>
      <td>
          <p><?php echo $row['contactNumber']?></p>
      </td>
        <td><a target="_blank" href="http://paperflybd.com/api_find_tem_reg.php?id=<?php echo $row['applyid']; ?>"><button type="button"  class="btn-edit">Edit</button></a></td>
        <td><a href="delete.php?delete=<?php echo $row['applyid'];?>" onclick = "return confirm('Are you sure?');" ><button  type="button" class="btn-dlt">Delete</button></a></td></tr>

<?php endwhile ?>
  </tbody>
</table>

</div>
