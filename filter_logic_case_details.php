<?php
function ifCase($column_variable)
{
// Generating the MySQL CASE statement
    $mysql_case_statement = convertToMySQLCase($column_variable) . ' END';

    // Remove the $ sign from the generated MySQL CASE statement
    $mysql_case_statement = str_replace('$', '', $mysql_case_statement);

   return $mysql_case_statement;
}
function sumCase($column_variable)
{
    // Remove all dollar signs and split the assignment statement
    $parts = preg_split('/\s*=\s*/', $column_variable);

    // Extract variables and computing expression
    $as_variable = trim($parts[0], '$');
    $computing_expression = trim($parts[1]);
    $computing_expression = trim($computing_expression,'$');
    $computing_expression=preg_replace('/[^+\-\/\*A-Za-z,_\-0-9]/', '',$computing_expression);
    $computing_expression = "CONCAT($computing_expression)";

    return $computing_expression.' as '.$as_variable;
}
function getDType($variable)
{

}
function sumCaseVal($column_variable)
{
    // Remove all dollar signs and split the assignment statement
    $parts = preg_split('/\s*=\s*/', $column_variable);

    // Extract variables and computing expression
    $as_variable = trim($parts[0], '$');

    return $as_variable;
}
// Function to check if all variables in the array are string variables
function is_string_variables($vars)
{
    // You can customize this function based on your criteria for identifying string variables
    return all($vars, function ($var) {
        return is_string_variable($var);
    });
}

// Function to check if a variable is a string variable
function is_string_variable($var)
{
    // You can customize this function based on your criteria for identifying string variables
    return preg_match('/^(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\.\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$/', $var);
}


// Function to check if all elements of an array satisfy a condition
function all($array, $condition)
{
    foreach ($array as $element) {
        if (!$condition($element)) {
            return false;
        }
    }
    return true;
}
?>