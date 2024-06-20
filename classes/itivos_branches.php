<?php
/**
 * @author Bernardo Fuentes
 * @since 18/06/2024
 */

class itivos_branches_branches extends Model
{
	public $id;
	public $id_customer;
	public $code;
	public $name;

	public static function getByCode($code, $id_customer)
	{
		$query = "SELECT * FROM 
					".__DB_PREFIX__."itivos_branches 
				  WHERE code = '".$code."' AND 
				  	    id_customer = '".$id_customer."
				 ";
		return connect::execute($query, "select", true);
	}
	public static function getIDByCode($code, $id_customer)
	{
		$query = "SELECT b.id FROM 
					".__DB_PREFIX__."itivos_branches b
				  WHERE b.code = '".$code."' AND 
				  	    b.id_customer = '".$id_customer."'
				 ";
		$data = connect::execute($query, "select", true);
		if (!empty($data)) {
			return $data['id'];
		}else{
			return false;
		}
	}
	public static function getBranchName($id)
	{
		$query = "SELECT b.name FROM 
					".__DB_PREFIX__."itivos_branches_branches b
				  WHERE b.id = ".$id."";
		$data = connect::execute($query, "select", true);
		if (empty($data)) {
			return array();
		}else {
			return $data['name'];
		}
	}
	public static function getlist($id_customer, $page = 1, $order_by = "id", $sort = "ASC", $show_per_page = 25, $search = null)
    {
        $result = self::paginateTable(
            'itivos_branches_branches b ',
            'b.id,
             b.code,
             upper(b.name) as name',
            $order_by,
            $sort,
            $page,
            $show_per_page,
            $search,
            ['name'],
            '',
            'b.id_customer = "'.$id_customer.'" AND 
             b.status_db = "enabled" 
            '
        );
        return  $result;
    }
}
class_alias("itivos_branches_branches", "branches");