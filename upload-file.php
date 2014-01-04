<?php
$uploaddir = './uploads/';
include('resize_crop.php');
$file = $uploaddir . basename($_FILES['uploadfile']['name']);
$file21=$uploaddir . 'cr' . basename($_FILES['uploadfile']['name']);
$file22=$uploaddir . 's' . basename($_FILES['uploadfile']['name']);
    if ( $_FILES['uploadfile']['size'] > 3200000 ) {
		//die ( "SIZE LIMIT 3,5 Mb!" );
        echo "SIZE LIMIT 3 Mb!";
	} else {
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
    //ini_set('memory_limit', '35M');
    //crop($file, $file21);
    //crop('big.jpg', 'crop.jpg');
    //resize($file, $file21, 270, 270);
    
    resize($file, $file21, 300, 0);
    resize($file, $file22, 55, 0);
    resize($file, $file, 1000, 0);
    echo "success";
} else {
    echo "error";
}
    }
?>
