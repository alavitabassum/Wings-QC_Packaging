<?php

if(isset($_POST['submit'])){

    require_once 'db.php';
    $created = add_new_inventory( $_POST );
    if( $created ){
        header('location: new_inventory.php?success=Inventory created');
    }else{
       header('location: new_inventory.php?error=Error occured while creating inventory');
    }

}else{
    header('location: new_inventory.php');

}
