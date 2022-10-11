<?php
$java = "C:\Progra~1\Java\jdk-18.0.2\bin\java.exe";
$javac = "C:\Progra~1\Java\jdk-18.0.2\bin\javac.exe";
$test = "henry";
$java_json = "D:/test_php\json-20220320.jar";
echo shell_exec(" $javac   -classpath .;$java_json called_java.java  &&  $java -classpath .;$java_json XYZ $test");



?>