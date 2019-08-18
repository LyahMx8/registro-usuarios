<?php
/**
 * Registro de configuraciones del plugin
 *
 * @package    registro-usuarios
 * @subpackage registro-usuarios/includes
 * @author     YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

/**
* Configurar el entorno
*/
define('RG_APP_ENV', 'development');

if(RG_APP_ENV == 'development'){
	//Mostrar errores de php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

ini_set('max_execution_time', 300);

define('RG_EDIT_SERV', $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/edicion-de-productos");
define('RG_EDIT_URL_PS', $_SERVER['HTTP_HOST']."/wp-content/plugins/edicion-de-productos");
define('RG_EDIT_URL_PB', "/wp-content/plugins/edicion-de-productos");

define('RG_W_URL', plugins_url('/registro-usuarios/') );
define('RG_UPLOAD_DIR', RG_EDIT_SERV.'/usuarios/');

$ip = file_get_contents('https://api.ipify.org');
define('RG_USER_IP', $ip);

date_default_timezone_set('America/Bogota');
$fecha = date("Y"). date("m"). date("d"). date("H"). date("i"). date("s");
$dia = date("Y").'-'.date("m").'-'.date("d");

define('RG_FECHA', $fecha);
define('RG_DIA', $dia);


class RG_Settings{
	//header('Content-type: application/json; charset=utf-8');

	/**
	 * El loader mantiene y registra los hooks del plugin
	 */
	protected $loader;

	/**
	 * registro es el identificador general del plugin
	 */
	protected $registro;


	/**
	 * Constructor del núcleo del plugin
	 */
	public function __construct() {
		$this->registro = 'registro';

		$this->load_dependencies();
		$this->define_function_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Carga las dependencias requeridas para el plugin
	 *
	 * Incluye los siguientes archivos:
	 *
	 * - loader. Define los hooks del plugin.
	 * - Functions. Funciones que usa el plugin.
	 *
	 * Crear e instanciar el loader habilitará los hooks de wordpress para su uso
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {
		//Importar clase que carga todos los hooks del plugin
		require_once  plugin_dir_path(dirname( __FILE__ )).'includes/loader.php';

		//Importar clase que trae funcionalidades adicionales usadas en el plugin
		require_once plugin_dir_path(dirname( __FILE__ )).'includes/functions.php';

		//Importar clase con las funciones publicas
		require_once plugin_dir_path(dirname( __FILE__ )).'public/registro-public.php';

		//Importar clase con las funciones administrativas
		require_once plugin_dir_path(dirname( __FILE__ )).'admin/registro-admin.php';
		
		$this->loader = new RG_Loader();
	}

	/**
	 * Registro de hooks relacionados con las funciones del plugin
	 * 
	 * @since 0.1.0
	 * @access   private
	 */
	private function define_function_hooks(){

		$plugin_functions = new RG_Functions( $this->registro() );

		// Agregar accion para crear tabla de usuarios
		$this->loader->add_action('init', $plugin_functions, 'create_user_table',99);
	}

	/**
	 * Registro de hooks relacionados con la parte administrativa del plugin
	 * 
	 * @since 0.1.0
	 * @access   private
	 */
	private function define_admin_hooks(){

		$plugin_admin = new RG_Admin( $this->registro() );
		$this->loader->add_action('admin_menu', $plugin_admin, 'api_plugin_menu');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
	}

	/**
	 * Registro de hooks relacionados con la parte pública del plugin
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new RG_Public( $this->registro() );

		$this->loader->add_action('wp_footer', $plugin_public, 'enqueue_styles');
		// $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		// $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
		// $this->loader->add_action('wp_ajax_handle_picture_upload', $plugin_public, 'handle_picture_upload');
		// $this->loader->add_action('wp_ajax_nopriv_handle_picture_upload', $plugin_public, 'handle_picture_upload');
		// $this->loader->add_action('woocommerce_after_add_to_cart_button', $plugin_public, 'button_action', 10, 0);


	}

	/**
	 * Iniicar loader para arrencar todos los hooks de wordpress
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * El nombre del plugin para darle un identidad y brindarle una funcionalidad global
	 *
	 * @since     0.1.0
	 * @return    string    El nombre del plugin
	 */
	public function registro() {
		return $this->registro;
	}

	/**
	 * Referencia a la clase que habilita los hooks del plugin
	 *
	 * @since     0.1.0
	 * @return    Loader    Habilita los hooks del plugin
	 */
	public function get_loader() {
		return $this->loader;
	}
}