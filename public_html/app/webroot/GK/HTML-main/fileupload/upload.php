<?php
if(!empty($_POST['data'])){
    $data = $_POST['data'];
    $fname = "test.pdf"; // name the file
    $file = fopen('tmp/'.$fname, 'w'); // open the file path
    fwrite($file, $data); //save data
    fclose($file);
} else {
    echo "No Data Sent";
}
?>