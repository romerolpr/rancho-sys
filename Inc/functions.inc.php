<?php

/* Functions */

function clearString($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

function resizeString($desc, $qnt_1, $r = 10, $hash = false){

    if (mb_strlen($desc, "UTF-8") > $qnt_1):
        if ($hash !== false):
            $desc = mb_substr($desc, 0, $qnt_1 - ($r / 5));
        else:
            $desc = mb_substr($desc, 0, $qnt_1 - ($r / 5));
            $final = strrpos($desc, " ");
            $desc = substr($desc, 0, $final);
        endif;
        // $desc .= ".";
    elseif (mb_strlen($desc, "UTF-8") < $qnt_1 - $r && mb_strlen($desc, "UTF-8") > $r - 10 ):
        $desc .= "...";
    endif;

    return $desc;

}

function replaceUrl($urlString){
    $current = str_replace($urlString, "", (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
    return str_replace($urlString, "", $current) . $urlString;
    
} 

function aasort (&$array, $order, $key) {

    $sorter = array();
    $ret = array();
    reset($array);

    foreach ($array as $ii => $va):
        $sorter[$ii] = $va[$key];
    endforeach;

    if ($order == "A-Z"):
        asort($sorter);
    else:
        arsort($sorter);
    endif;

    foreach ($sorter as $ii => $va):
        $ret[$ii] = $array[$ii];
    endforeach;

    $array = $ret;
}

function encodeRegexPg($p_g, $switch = false){
    return ( !$switch ? strtolower(preg_replace(array("/(º|ª|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($p_g))))) : strtolower(preg_replace(array("/(º|ª|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_encode(trim($p_g))))));
}


function getRefc($posto_graduacao, $refc, $returnCount = true, $table = TB_RESP) {

    $count = 0;
    $militar = array();

    $Db = new ObjectDB();
    $Db->setter(HOST, USER, PASS, DBNAME);

    $datasheet = (isset($_SESSION["objfile"]) ? $_SESSION["objfile"]["name"] : null);
    $formDb = $Db->return_query($Db->connect_db(), $table, null, false, null);

    foreach ($formDb as $key => $value):

        if ($value["datasheet"] == $datasheet):

            foreach ($posto_graduacao as $keypg => $p_g):
                
                if ($p_g == encodeRegexPg($value["posto_graduacao"])):

                    // var_dump($p_g);

                    $foreachPg = array(
                        "segunda_feira" => explode("&&", utf8_decode($value["segunda_feira"])),
                        "terca_feira" => explode("&&", utf8_decode($value["terca_feira"])),
                        "quarta_feira" => explode("&&", utf8_decode($value["quarta_feira"])),
                        "quinta_feira" => explode("&&", utf8_decode($value["quinta_feira"])),
                        "sexta_feira" => explode("&&", utf8_decode($value["sexta_feira"])),
                        "sabado" => explode("&&", utf8_decode($value["sabado"])),
                        "domingo" => explode("&&", utf8_decode($value["domingo"])),
                    );

                    // $count += calcValuesByPg($foreachPg, $refc);

                    foreach ($foreachPg as $key => $valDia):

                        $foreachValue = explode(",", $valDia[0]);

                        foreach ($foreachValue as $keyforeach => $foreach):

                            if (preg_match("/$refc/", trim($foreach))):

                                $count += 1;
                                if (!in_array($value, $militar))
                                    array_push($militar, $value);

                            endif;

                        endforeach;

                        

                    endforeach;

                endif;

             endforeach;

        endif;

    endforeach;

    return array(intval($count), $militar);

}

function countPreTotal($array, $arg = null){
    $t = 0;
    if (is_null($arg)):
        foreach ($array as $key => $value)
            $t += 1;
    else:
        foreach ($array[$arg] as $key => $value)
            $t += $value[0];
    endif;
    return $t;
}

function testListValues($lista1, $lista2){  
    $clearArray = array(
        0 => array(),
        1 => array()
    );
    foreach ($lista1 as $key => $value) 
        array_push($clearArray[0], trim($value));
    foreach ($lista2 as $key => $value) 
        array_push($clearArray[1], trim($value));
    return array_diff($clearArray[0], $clearArray[1]);
}

function clearArrayValues($list){
    $n = array();
    foreach ($list as $key => $item)
        array_push($n, trim($item));
    return $n;
}

function encryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}

function sortByColumns(&$array) {
    if (isset($_GET["sort"])):
        $getSort = explode(":", $_GET["sort"]);
        $sorted = aasort($array, $getSort[0], $getSort[1]);
        if (is_array($sorted))
            $array = $sorted;
    endif;
}

function convertDayName($diasemana, $x = false){
    $diasemana = ($x !== false) ? date('w', strtotime(str_replace("/", "-", $diasemana))) : $diasemana;

    $diasemana_numero = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
    return $diasemana_numero[$diasemana];
}

function tryArrayPg($pg, &$array){
    // echo(utf8_decode($pg));
    foreach ($array as $key => $value) {
        if (!in_array(utf8_decode($pg), $array))
            $array[encodeRegexPg($pg)] = array(0, 0);
    }
}

function array_combine_($keys, $values)
{
    $result = array();
    foreach ($keys as $i => $k) {
        $result[$k][] = $values[$i];
    }
    
    # create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;')

    array_walk($result, function(&$v) {$v = (count($v) == 1)? array_pop($v): $v;});
    return $result;
}

function encode_to_url($string) 
{
    return preg_replace(array("/\//"),explode(" ","-"), trim($string));
}
function decode_to_url($string) 
{
    return preg_replace(array("/-/"),explode(" ","/"), trim($string));
}