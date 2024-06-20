<?php 
/**
 * @author Bernardo Fuentes
 * @since 18/06/2024
 */

require_once(__DIR_MODULES__."itivos_branches/classes/itivos_branches.php");

class ItivosBranches extends modules
{
	public $html = "";
    public function __construct()
    {
        $this->name ='itivos_branches';
        $this->displayName = "Sucursales";
        $this->description = $this->l('Agrega sucursales a los clientes');
        $this->category  ='front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Bernardo Fuentes';
        $this->versions_compliancy = array('min'=>'1.0.0', 'max'=> __SYSTEM_VERSION__);
        $this->confirmUninstall = $this->l('Are you sure about removing these details?');
        $this->template_dir = __DIR_MODULES__."itivos_branches/views/back/";
        $this->template_dir_front = __DIR_MODULES__."itivos_branches/views/front/";
        parent::__construct();

        $this->key_module = "a690b178b4d7ddc86565daada9766edc";
        $this->crontLink = __URI__.__ADMIN__."/module/".$this->name."/crontab?key=".$this->key_module."";
    }
    public function install()
    {
    	 if(!$this->registerHook("displayHead") ||
            !$this->registerHook("displayBottom") ||
            !$this->registerHook("displayCustomerTabMenu") ||
            !$this->registerHook("displayCustomerTabContent") ||
            !$this->installDb()
            ){
            return false;
        }
        return true;
    }
    public function uninstall($drop = false)
    {
    	$return = true;
    	$return &= connect::execute("DELETE FROM ".__DB_PREFIX__. "configuration WHERE module = '".$this->name."'");
        if ($drop == true) {
            /*
            $return &= connect::execute("DROP TABLE IF EXISTS ".__DB_PREFIX__. $this->name."_branches");
            */
        }
    	return $return;
    }
    public function installDb()
    {
        $return = true;
        $return &= connect::execute('
            CREATE TABLE IF NOT EXISTS `'.__DB_PREFIX__.$this->name.'_branches` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `id_customer` varchar(12) NOT NULL,
              `code` varchar(150) NULL,
              `name` varchar(450) NOT NULL,
              `status_db` set("enabled", "deleted") DEFAULT "enabled",
              PRIMARY KEY (id)
            ) ENGINE ='.__MYSQL_ENGINE__.' DEFAULT CHARSET=utf8 ;'
        );
        if (!$return) {
            return true;
        }
        return $return;
    }
    public function hookDisplayCustomerTabMenu($params = null)
    {
        if ($params != null) {
            $tab = null;
            if (isIsset('tab')) {
                $tab = getValue('tab');
            }
            $this->view->assign(
                array(
                    "data_customer" => $params,
                    "tab" => $tab,
                )
            );
            if (isset($params['id'])) {
                if ($params['id_group'] == 4) {
                    $this->view->display($this->template_dir."hookDisplayCustomerTabMenu.tpl");
                }
            }
        }
    }
    public function hookDisplayCustomerTabContent($params = null)
    {
        $tab = getValue("tab");
        $order_by = "id";
        $sort = "desc";
        $page = 1;
        $show_per_page = 10;
        $search = null;
        $page = 1;
        if (isIsset('order_by')) {
            $order_by = getValue('order_by');
        }
        if (isIsset('sort')) {
            $sort = getValue('sort');
        }
        if (isIsset('page')) {
            $page = getValue('page');
            if (empty($page)) {
                $page = 1;
            }
        }
        if (isIsset('show_per_page')) {
            $show_per_page = getValue('show_per_page');
        }
        if (isIsset('search')) {
            $search = getValue('search');
        }
        $list = Branches::getlist(getValue('id_customer'), $page, $order_by, $sort, $show_per_page, $search);
        if (!empty($list)) {
            $orders = $list['data'];
            unset($list['data']);
        }
        $pagination = $list;
        $page = 1;
        if (isIsset('page')) {
            $page = getValue('page'); 
        }
        $default_currency = Currencies::getIsoDefaultCurrency();
        if (isIsset('show_per_page')) {
            $uri_show =  __URI__.__ADMIN__."/module/itivos_branches/branches/add?id_customer=".getValue('id_customer')."&page=".$page."&show_per_page=".getValue('show_per_page')."&id=";
        }else {
            $uri_show =  __URI__.__ADMIN__."/module/itivos_branches/branches/add?id_customer=".getValue('id_customer')."&page=".$page."&id=";
        }
        $uri_add = __URI__.__ADMIN__."/module/itivos_branches/branches/add?id_customer=".getValue('id_customer');
        if (isIsset('page')) {
            $page = getValue('page');
            $uri_add .= "&page=".$page."";
        }
        $table = array(
                'table' => array(
                    'legend' => array(
                        'title' => "Sucursales",
                        'icon' => 'article',
                    ),
                    'hidenSearchBar' => true,
                    'buttons_header' => array(
                        array(
                            "key_row" => "id",
                            "class" => "",
                            "label" => "Agregar",
                            "icon" => "add",
                            "uri" => $uri_add,
                        ),
                    ),
                    'titles' => array(
                        array(
                            "label" => "id",
                            "key" => "id",
                            "class" => "table-left",
                        ),
                        array(
                            "label" => "Código",
                            "key" => "code",
                            "class" => "table-center",
                        ),
                        array(
                            "label" => "Nombre",
                            "key" => "name",
                            "class" => "table-center",
                        ),
                    ),
                    'buttons_row' => array(
                        array(
                            "key_row" => "id",
                            "class" => "button button-secondary loading_full_screen_enable",
                            "icon" => "edit",
                            "label" => "",
                            "uri" => $uri_show,
                        ),
                        array(
                            "key_row" => "id",
                            "class" => "button button-danger confirm_link",
                            "label" => "",
                            "attr" => array(
                                "message_es" => "¿Reaelmente desea eliminar este registro?",
                                "message_en" => "¿Do you really want to delete this customer?",
                            ),
                            "icon" => "delete_outline",
                            "uri" => __URI__.__ADMIN__."/module/itivos_branches/branches/delete?ud=",
                        ),
                    ),
                    'data' => $orders,
                    'pagination' => $pagination,
                    'search' => getValue('search'),
                ),
            );
        $this->view->assign(
            array(
                "table" => $table['table'],
                "template_dir" => $this->template_dir,
                "module_name_" => $this->name,
                "tab" => getValue('tab'),
                "hide_customer_footer" => true,
                "show_per_page_list" => array(
                    "10"=>"10",
                    "25"=>"25",
                    "100"=>"100",
                    "500"=>"500",
                ),
                "show_per_page" => $show_per_page,
            )
        );
        $this->view->display($this->template_dir."hookDisplayCustomerTabContent.tpl");  
    }
}