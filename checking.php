<?php

define ('DB_SERVER','ajc5531.encs.concordia.ca');
define ('DB_USERNAME','ajc55311');
define ('DB_PASSWORD','5531ajkr');
define ('DB_NAME','ajc55311');

$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

if($conn == false)
{
    dir("Error : cannot connect");
}   
?>