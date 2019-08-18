<?php
/**
 * Funciones del plugin en la parte administrativa
 *
 * @package		registro-usuarios
 * @subpackage	registro-usuarios/admin
 * @author		YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

class RG_Admin{

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
	 * Registro de los estilos para el lado administrativo
	 *
	 * @since   0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * la instancia de esta clase inicia con el método run()
		 * El Loader puede crear una relacion entre los hooks definidos y las funciones definidas en esta clase
		 */
		wp_enqueue_style($this->registro, RG_EDIT_URL_PB.'/assets/css/admin.css', array(), 'all');
		wp_enqueue_style($this->registro, '/wp-admin/load-styles.php', array(), 'all');
	}

	/**
	 * Crear item en el menu
	 */
	public function api_plugin_menu(){
		add_menu_page(
			'Usuarios Registrados', //Titulo de la pagina
			'Usuarios Registrados', //Titulo en el menu
			'edit_posts', //Rol mínimo de usuario que puede ingresar
			'registro', //Sku en el menu
			array($this, 'usuarios_registrados'), //Funcion que llama
			'dashicons-clipboard'); //Icono
		add_submenu_page(
			'registro',
			'Configurar',
			'Configuraciones',
			'edit_posts', //Rol de usuario
			'configurar-registros',
			array($this, 'configurar_registros')
		);
	}
	
	/**
	 * Administrador que consulta las ediciones de producto realizadas
	 */
	function usuarios_registrados(){
		
		$items_per_page = 10;
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		$offset = ( $page * $items_per_page ) - $items_per_page;

		global $wpdb;
	?>
		<h1>Lista de usuarios registrados</h1>
		<!-- <button type="button" class="bestBtn">Ver usuarios activos</button> -->
		
		<section class="UsersList">
	<?php

		$total_pages_sql = $wpdb->get_var("SELECT COUNT(*) FROM registro_usuarios WHERE rg_actv_usr = 1");

		$resultados= $wpdb->get_results( "SELECT * FROM registro_usuarios WHERE rg_actv_usr = 1 ORDER BY rg_nmbr_usr DESC LIMIT ". $offset.", ". $items_per_page, OBJECT, ARRAY_A );

			$i = 0;
			//$array = json_decode($resultados, true);
		foreach ( $resultados as $rows => $ch ) {
			if($i>1){$i=0;}
	?>
			<div class="usrCard" onclick="openAll('usrAll<?= $ch->rg_id_usr; ?>')">
				<img src="<?= RG_EDIT_URL_PB.'/photos/'.$ch->rg_fto_usr; ?>" alt="<?= $ch->rg_nmbr_usr.' '.$ch->rg_aplld_usr; ?>">
				<p><?= $ch->rg_nmbr_usr.' '.$ch->rg_aplld_usr; ?></p>
			</div>

			<section class="popContainer" id="usrAll<?= $ch->rg_id_usr; ?>" style="display:none;">
				<div class="popLayer" onclick="openAll('usrAll<?= $ch->rg_id_usr; ?>')"></div>
				<div class="usrAll">
					<img src="<?= RG_EDIT_URL_PB.'/photos/'.$ch->rg_fto_usr; ?>" alt="<?= $ch->rg_nmbr_usr.' '.$ch->rg_aplld_usr; ?>">
					<div class="info">
						<h2><?= $ch->rg_nmbr_usr.' '.$ch->rg_aplld_usr; ?></h2>
						<p><strong><?= $ch->rg_tipdoc_usr; ?>:</strong> <?= $ch->rg_doc_usr; ?></p>
						<a target="_blank" rel="noreferrer noopener" href="https://www.ip-tracker.org/locator/ip-lookup.php?ip=<?= $ch->rg_ip_usr; ?>"><?= $ch->rg_ip_usr; ?></a>
						<hr>
						<a target="_blank" rel="noreferrer noopener" href="tel:<?= $ch->rg_cel_usr; ?>"><?= $ch->rg_cel_usr; ?></a><br>
						<a target="_blank" rel="noreferrer noopener" href="mailto:<?= $ch->rg_mail_usr; ?>"><?= $ch->rg_mail_usr; ?></a>
						<hr>
						<p><strong>¿Acepta recibir ofertas y promociones?</strong> <?php if($ch->rg_feed_usr == 'on') echo "Si"; else echo "No"; ?></p>
						<p>Fecha de registro: <?= $ch->rg_fch; ?></p>
					</div>
				</div>
			</section>
	<?php
			$i++;
		}
		echo paginate_links( array(
			'base' => add_query_arg( 'cpage', '%#%' ),
			'format' => '',
			'prev_text' => __('<span class="dashicons dashicons-arrow-left"></span>'),
			'next_text' => __('<span class="dashicons dashicons-arrow-right"></span>'),
			'total' => ceil($total_pages_sql / $items_per_page),
			'current' => $page
		));
	?>
		</section>

		<script>
			/**
			* Abrir o cerrar el modal
			* @param modal, string: id del modal que se usará 
			*/
			function openAll(modal){
				var modalBox = document.getElementById(modal); 
				if(modalBox.style.display == "none"){
					modalBox.style.display = "block";
				}else{
					modalBox.style.display = "none";
				}
			}
		</script>
	<?php
	}

}