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


function insert_data($table,$field,$data,$mysqli)
    {
        $field_values= implode(',',$field);
        $data_values=implode("','",array_map(array($mysqli, 'real_escape_string'), $data));
        $sql= "INSERT INTO $table (".$field_values.") VALUES ('".$data_values."');";
        error_log($sql);

        return $mysqli->query($sql);
    }

function update_data($table,$field,$data,$id,$mysqli)
    {
        error_log("update_data");
        $set_values = "";
        error_log($set_values);
        foreach (array_combine($field, $data) as $key => $value){
            $set_values = $set_values." ".$key."='".$mysqli->real_escape_string($value)."',";
            error_log($set_values);
        }

        $sql= "UPDATE $table SET".rtrim($set_values, ",")." WHERE id=".$id.";";
        error_log($sql);

        return $mysqli->query($sql);
    }
?>