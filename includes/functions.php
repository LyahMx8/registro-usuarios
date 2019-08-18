<?php
/**
 * Registro de acciones y funciones del plugin
 *
 * @package    registro-usuarios
 * @subpackage registro-usuarios/includes
 * @author     YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

?>

<?php
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
			rg_ip_usr varchar(20) NOT NULL,
			rg_tipdoc_usr varchar(10) NOT NULL,
			rg_doc_usr varchar(10) NOT NULL,
			rg_nmbr_usr varchar(30) NOT NULL,
			rg_aplld_usr varchar(30) NOT NULL,
			rg_cel_usr varchar(10) NOT NULL,
			rg_mail_usr varchar(60) NOT NULL,
			rg_fto_usr varchar(255) NOT NULL,
			rg_feed_usr varchar(3) NOT NULL,
			rg_actv_usr boolean NOT NULL,
			rg_fch DATETIME NOT NULL,
			PRIMARY KEY (rg_id_usr)
		) $charset_collate;";		

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );				
	}

	public function functionScripts(){ ?>
		<script>
			/**
			* Constante de conección con Facebook
			* Las constantes deben permanecer privadas
			* Uso de llaves de app Api Proveedores
			*/
			window.fbAsyncInit = function() {
					FB.init({
						appId	: '303269393938393',
						cookie	: false,
						xfbml	: true,
						version	: 'v3.3'
					});
					FB.AppEvents.logPageView();
				};
				(function(d, s, id){
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) {return;}
					js = d.createElement(s); js.id = id;
					js.src = "https://connect.facebook.net/es_ES/sdk.js";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
		</script>

		<script>
			/**
			* Funcion que manipula las cookies de la página
			*
			* @param nombre de cookie que se desea definir o consultar
			*/
			function getCook(cookiename) {
				var cookiestring=RegExp(""+cookiename+"[^;]+").exec(document.cookie);
				return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./,"") : "");
			}
		</script>
	<?php }
	
}