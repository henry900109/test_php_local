
<?php 
$python ="D:\program\python.exe";
$pyscript = 'D:\test_php\call_python.py';
$data = exec("$python $pyscript ");
//echo $data;
echo json_decode(json_encode($data))
?>


