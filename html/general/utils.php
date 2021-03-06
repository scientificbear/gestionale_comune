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
        $set_values = "";
        foreach (array_combine($field, $data) as $key => $value){
            $set_values = $set_values." ".$key."='".$mysqli->real_escape_string($value)."',";
        }

        $sql= "UPDATE $table SET".rtrim($set_values, ",")." WHERE id=".$id.";";
        error_log($sql);

        return $mysqli->query($sql);
    }

function protect_content($content, $this_role, $allowed_list){
        if(in_array($this_role, $allowed_list)){
            echo $content;
        }
    }

function trunc_str($x, $max_l=50){
    if (strlen($x)>$max_l){
        $x = substr($x, 0, $max_l);
        $x = $x . "...";
    }

    return $x;
}

?>