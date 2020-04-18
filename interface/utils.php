<?php

function check_variable($variable, $variable_name)
{
    $variable_err = "";

    $input_variable = trim($variable);
    if(empty($input_variable)){
        $variable_err = "Per favore inserisci '" . $variable_name . "'";
    } else{
        $variable = $input_variable;
    }

    return array($variable, $variable_err);

}


?>