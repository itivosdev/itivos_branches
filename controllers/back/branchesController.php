<?php
/**
 * @author Bernardo Fuentes
 * @since 19/06/2024 
 */

require_once(__DIR_MODULES__."itivos_branches/classes/itivos_branches.php");
class branchesController extends ModulesBackControllers
{
    function __construct()
    {
        $this->is_logged = false;
        $this->ajax_anabled = true;
        $this->type_controller = "frontend";
        parent::__construct();
        $this->view->assign('page', "Sucursales");
        if (!Modules::isInstalled('itivos_commission')) {
            //die("Modulo no instalado");
        }
    }
    public function add()
    {
        if (isset($_POST['save_branch'])) {
            self::protectProcessForm();
        }
        $back_uri = __URI__.__ADMIN__."/customers/show?id_customer=".getValue('id_customer')."&tab=branches";
        if (isIsset('page')) {
            $page = getValue('page');
            $back_uri .= "&page=".$page."";
        }
        $data = (array) New branches(getValue('id'));
        $this->form = self::protectGenerateFrom($data);
        $this->html = 
        "
        <div class='menu_app'>
            <nav>
                <ul>
                    <li>
                        <a href='".$back_uri."' class='loading_full_screen_enable'>
                           <i class='material-icons'>arrow_left</i>
                            Volver al listado
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        ";
        $this->renderForm();
    }
    public function protectGenerateFrom($data = null)
    {
        $form = array(
                'form' => array(
                    'type' => "inline",
                    'method' => "POST",
                    'legend' => array(
                        'title' => $this->l('Información básica'),
                        'icon' => 'icon-cogs',
                    ),
                    'extends' => "back",
                    'values' => array(),
                    'inputs' => array(
                        array(
                            'type' => 'text',
                            'label' => "Codigo",
                            'name' => 'code',
                            'required' => true,
                        ),
                        array(
                            'type' => 'text',
                            'label' => "Nombre",
                            'name' => 'name',
                            'required' => true,
                        ),
                    ),
                    'values' => $data,
                    'submit' => array(
                        'title' => $this->l('guardar cambios'),
                        'action' => "save_branch"
                    ),
                ),
            );
        return $form;
    }
    public function protectProcessForm()
    {
        $branch_obj = new branches(getValue('id'));
        $branch_obj->id_customer = getValue('id_customer');
        $branch_obj->loadPropertyValues($_POST);
        $branch_obj->save();
        $_SESSION['type_message'] = "success";
        $_SESSION['message'] = "Cambios guardados correctamente";
        $back_uri = __URI__.__ADMIN__."/customers/show?id_customer=".getValue('id_customer')."&tab=branches";
        if (isIsset('page')) {
            $page = getValue('page');
            $back_uri .= "&page=".$page."";
        }
        header("Location: $back_uri");
    }
}