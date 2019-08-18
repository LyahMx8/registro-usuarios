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
		wp_enqueue_style($this->registro, plugin_dir_path(dirname( __FILE__ )).'assets/css/admin.css', array(), 'all');
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
		<h1>Lista de productos editados</h1>
		<table id="productos">
			<tr>
				<th>id</th>
				<th>id producto</th>
				<th>Producto Editado</th>
				<th>Fecha Subida</th>
				<th>Tipo</th>
				<th>Ip Cliente</th>
			</tr>
	<?php

		$total_pages_sql = $wpdb->get_var("SELECT COUNT(*) FROM registro_usuarios WHERE rg_actv_usr = 1");

		$resultados= $wpdb->get_results( "SELECT * FROM registro_usuarios WHERE rg_actv_usr = 1 ORDER BY cmp_nmbr_usr DESC LIMIT ". $offset.", ". $items_per_page, OBJECT, ARRAY_A );

			$i = 0;
			//$array = json_decode($resultados, true);
		foreach ( $resultados as $rows => $ch ) {
			if($i>1){$i=0;}
	?>
			<tr>
				<td><?php  echo $ch->cmpidimg; ?></td>
				<td><?php  echo $ch->cmpidprdct; ?></td>
				<td>
					<div style="float: left;">
						<img style="width:100px;height:70px;object-fit: contain;" src="<?php echo $ch->cmpurlimg; ?>">
					</div>
					<a style="margin-top:calc((70px / 2) - 10px)" href="<?php echo $ch->cmpurlimg; ?>" download class="dashicons dashicons-download"></a>
				</td>
				<td><?php echo $ch->cmpfechup; ?></td>
				<td><?php if($i==0){echo 'producto frontal'; }else{ echo 'producto trasero'; } ?></td>
				<td><?php echo $ch->cmpclntip; ?></td>
			</tr>
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
		</table>
	<?php
	}

}