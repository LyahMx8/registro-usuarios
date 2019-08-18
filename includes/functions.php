<?php
/**
 * Registro de acciones y funciones del plugin
 *
 * @package    registro-usuarios
 * @subpackage registro-usuarios/includes
 * @author     YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

class RG_Functions{

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
	* Crear la tabla Usuario para almacenar datos
	*
	* @since 0.1.0
	*/
	public function create_user_table(){
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS registro_usuarios (
			rg_id_usr int(10) NOT NULL AUTO_INCREMENT,
			rg_ip_usr int(10) NOT NULL,
			rg_tipdoc_usr varchar(10) NOT NULL,
			rg_doc_usr varchar(10) NOT NULL,
			rg_nmbr_usr varchar(30) NOT NULL,
			rg_aplld_usr varchar(30) NOT NULL,
			rg_cel_usr varchar(10) NOT NULL,
			rg_mail_usr varchar(60) NOT NULL,
			rg_fto_usr varchar(255) NOT NULL,
			rg_feed_usr boolean NOT NULL,
			rg_actv_usr boolean NOT NULL,
			rg_fch DATETIME NOT NULL,
			PRIMARY KEY (rg_id_usr)
		) $charset_collate;";		

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );				
	}
	
}