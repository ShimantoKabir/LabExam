<?php


namespace App\Helpers;


class StringManager
{

    public static function cleanString($string)
    {

        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);
        return strtolower(trim($string, '-'));

    }

    public static function getMaxOidSql($tableName, $hostelId)
    {

        return "SELECT
                  IF(MAX(oId) IS NULL, 101, MAX(oId) + 1) AS oId
                FROM
                  ".$tableName."
                WHERE hostelId = " . $hostelId;

    }

    public static function getMemberSelectSql()
    {

        return 'id,IF(memberName IS NULL,SUBSTRING_INDEX(memberEmail,"@",1),memberName) AS memberName';

    }

}
