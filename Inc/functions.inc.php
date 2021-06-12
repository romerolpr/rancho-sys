<?php

/* Functions */

function clearString($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

function resizeString($desc, $qnt_1, $r = 10){

    if (mb_strlen($desc, "UTF-8") > $qnt_1):
        $desc = mb_substr($desc, 0, $qnt_1 - ($r / 5));
        $final = strrpos($desc, " ");
        $desc = substr($desc, 0, $final);
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

function encodeRegexPg($p_g){
    return strtolower(preg_replace(array("/(º|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($p_g)))));
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