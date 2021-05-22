<?php

if(isset($get["err"]) && !empty($get["err"])): 

    foreach ($ErrList["form"] as $key => $value):
        if($key == $get["err"]):
            $Alert->setConfig($value[1], $value[0]);
        endif;
    endforeach;

elseif (isset($get["exit"]) && !empty($get["exit"])):


    foreach ($ErrList["session"] as $key => $value):

        if($key == $get["exit"]):
            $Alert->setConfig($value[1], $value[0]);
        endif;  

    endforeach;

else:

    $Alert->setStatus(false);

endif;

echo ($Alert->displayPrint());

?>