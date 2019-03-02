<?php

if(isset($_POST['submit'])){

    require_once 'db.php';
    $created = assign_inventory( $_POST );
    if( $created ){
        header('location: new_inventory.php?success=successful!');
    }else{
       header('location: new_inventory.php?error=Error occured!');
    }

}else{
    header('location: new_inventory.php');

}

