
    
        <?php
            $get_value1=$_POST['username'];
            $get_value2=$_POST['password'];
            //echo "帳號：".$get_value1."<br />密碼：".$get_value2;
            $json = array(
                'username' => $get_value1,
                'password' => $get_value2
            );
            echo (json_encode($json))
        ?>