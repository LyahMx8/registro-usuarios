<?php
/**
*	@wordpress-plugin
*	Plugin Name: 	Registro-Usuarios
*	Plugin URI: 	https://
*	description: 	Plugin que recolecta información de los usuarios para posteriores usos
*	Version: 		0.0.1
*	Author: 		Yimmy Motta (Mx8live)
*	Author URI: 	https://www.zalemto.com
*	License: 		GPL2
*	License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
*/

if (!defined('WPINC')) die;
defined( 'ABSPATH' ) || exit;


require plugin_dir_path( __FILE__ ) . 'includes/settings.php';


/**
 * Ejecución del plugin.
 *
 * Mantener todos los recursos del plugin via hooks
 *
 * @since    0.0.1
 */
function run_registro() {

	$plugin = new Settings();
	$plugin->run();

}

run_registro();
