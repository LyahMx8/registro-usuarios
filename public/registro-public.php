<?php
/**
 * Funciones del plugin en la parte pública
 *
 * @package		registro-usuarios
 * @subpackage	registro-usuarios/public
 * @author		YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

class RG_Public{

	/**
	 * Registro es el identificador general del plugin
	 */
	protected $registro;

	/**
	 * Inicializar clase y brindarle propiedades
	 *
	 * @since 	0.1.0
	 * @param 	string 	$registro.
	 */
	public function __construct($registro) {
		$this->registro = $registro;
	}


	/**
	 * Registro de los estilos para el lado público
	 *
	 * @since   0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * la instancia de esta clase inicia con el método run()
		 * El Loader puede crear una relacion entre los hooks definidos y las funciones definidas en esta clase
		 */
		wp_enqueue_style($this->registro, plugin_dir_path(dirname( __FILE__ )).'assets/css/public.css', array(), 'all');
	}

	/**
	 * Registro de scripts para el lado público
	 *
	 * @since   0.1.0
	 */
	public function enqueue_scripts() {
		GLOBAL $wpd_settings;
		$options = $wpd_settings['wpc-general-options'];
		wp_enqueue_script('jquery');
		wp_enqueue_script($this->editor, plugin_dir_url(__FILE__) . 'assets/js/scripts.js', array('jquery'), false);
	}

	/**
	* Registro de shortcodes para el lado público
	*
	* @since 0.1.0
	*/
	public function register_shortcodes() {
		add_shortcode('open-modal', array($this, 'button_action'));
	}

	/**
	* Método que ejecuta el formulario de registro
	*
	* @since 0.1.0
	*/
	public function register_form(){
	?>
		<section id="rg_regis_form">
			<form action="">
				
			</form>
		</section>
	<?php
	}

}
?>

<script>
	
	// FB.api(
	// 	'/me',
	// 	'GET',
	// 	{"fields":"email,address,first_name,last_name"},
	// 	function(response) {
	// 			// Insert your code here
	// 	}
	// );
</script>