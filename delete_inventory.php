<?php

if(isset($_POST['submit'])){

    require_once 'db.php';

    $deleted = delete_inventory( $_POST );
    if( $deleted ){
        header('location: new_inventory.php?success=Inventory deleted successfully!');
    }else{
       header('location: new_inventory.php?error=Error occured while deleting inventory');
    }

}else{
    
   header('location: new_inventory.php');

}

