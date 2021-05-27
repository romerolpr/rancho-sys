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