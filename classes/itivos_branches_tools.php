<?php
/**
 * @author Bernardo Fuentes
 * @since 19/06/2024
 */

class itivos_branches_tools extends Model
{
	public static function searchBranch($id_customer, $param)
    {
        $data_return = array();
        $data_return['results'] = array();
        $data_return['pagination'] = false;
        if (empty($param)) {
            $query = 'SELECT b.id as id, 
                             CASE 
                                WHEN IFNULL(b.code, "") = "" THEN 
                                    b.name
                                ELSE
                                    concat(b.code, " | ", b.name)
                             END  as text
                        from '.__DB_PREFIX__.'itivos_branches_branches b
                             WHERE b.id_customer = "'.$id_customer.'" 
                        limit 20';
        }else {
            $query = 'SELECT b.id as id, 
                             CASE 
                                WHEN IFNULL(b.code, "") = "" THEN 
                                    b.name
                                ELSE
                                    concat(b.code, " | ", b.name)
                             END  as text
                        FROM '.__DB_PREFIX__.'itivos_branches_branches b
                      WHERE b.id_customer = "'.$id_customer.'" AND 
                            (b.name LIKE "%'.$param.'%" or b.code LIKE "%'.$param.'%") 
                        LIMIT 20';
        }
        $data = connect::execute($query, "select");
        if (!empty($data)) {
            $data_return['results'] = $data;
            if ($data >1) {
                $data_return['pagination'] = true;
            }
        }else {
            $data_return['results'] = array();
        }
        return $data_return;
    }
}
class_alias("itivos_branches_tools", "branchesTools");