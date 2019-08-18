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
		wp_enqueue_style($this->registro, RG_EDIT_URL_PB.'/assets/css/public.css', array(), 'all');
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
		wp_enqueue_script($this->registro, RG_EDIT_URL_PB.'/assets/js/scripts.js', array('jquery'), false);
	}

	/**
	* Registro de shortcodes para el lado público
	*
	* @since 0.1.0
	*/
	public function register_shortcodes() {
		add_shortcode('open-modal', array($this, 'register_form'));
	}

	/**
	* Método que ejecuta el formulario de registro
	*
	* @since 0.1.0
	*/
	public function register_form(){ ?>
		<div id="fb-root"></div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v4.0&appId=303269393938393&autoLogAppEvents=1"></script>
		<script async defer src="https://connect.facebook.net/es_ES/sdk.js"></script>
		<script>
			jQuery(document).ready(function(){
				// Crear una cookie si es la primera vez que el usuario ingresa a la página
				// Pruebas: definir ambos estados en 'false'
				// Producción: Cambiar estado dentro del 'else' a 'true'
				console.log(getCook('client_ip'));
				if(!getCook('client_ip')){
					<?php $cookie = true; ?>
					document.cookie = "client_ip=<?php echo RG_USER_IP; ?>";
				}else{ <?php $cookie = false; ?> }
			});
		</script>
	<?php if(is_front_page() && $cookie===false): ?>
		<section id="rg_regis_form" style="display:none;">
			<div class="popLayer" onclick="openModal('rg_regis_form')"></div>
			<form method="post" enctype="multipart/form-data" id="megaform">
				<input type="hidden" name="regisForm" value="">
				<img src="<?= RG_EDIT_URL_PB; ?>/assets/img/teamwork.jpg" alt="Trabajo en equipo">
				<h3>¡Bienvenido!</h3>
				<p style="line-height: 18px;">Por favor completa tu registro para que disfrutes de todo el material que tenemos para ti.</p>
				<div class="fb-login-button" data-width="" data-size="medium" data-use-continue-as="true" data-button-type="continue_with" data-auto-logout-link="true" data-use-continue-as="true"></div><br><br>
				<label>Tipo de documento<br>
					<select name="tipdoc" id="tipdoc">
						<option value="" disabled selected>Selecciona una opción</option>
						<option value="cc">Cédula</option>
						<option value="ti">Tarjeta de identidad</option>
						<option value="ccE">Cédula de extranjería</option>
						<option value="nit">Nit</option>
					</select>
				</label>
				<label>Número de documento<br>
					<input type="tel" pattern="[0-9]{6,10}" min="6" max="11" name="doc" id="doc" placeholder="Número de documento">
				</label><br>
				<label>Nombres<br>
					<input type="text" maxlength="30" name="nmbr" id="nmbr" placeholder="Nombres">
				</label>
				<label>Apellidos<br>
					<input type="text" maxlength="30" name="aplld" id="aplld" placeholder="Apellidos">
				</label><br>
				<label>Teléfono<br>
					<input type="tel" pattern="[0-9]{6,10}" min="6" max="11" name="cel" id="cel" placeholder="3211234567">
				</label>
				<label>Correo<br>
					<input type="email" name="mail" id="mail" placeholder="tucorreo@ejemplo.com">
				</label><br><br>
				<label for="fto"><br>
					<div id="ftoContain">
						<i class="fa fa-camera"></i>
						<img src="" id="ftoPreview" alt="Tu foto increíble">
					</div>
					Sube tu foto con tu mejor sonrisa
				</label><br style="clear:left;"><br>
				<input type="file" name="fto" id="fto" accept="image/gif, image/jpeg, image/jpg, image/png" onchange="loadFile(event)" style="display:none;">
				<label style="width:100% !important;">
					<input type="checkbox" name="feed" id="feed">
					 ¿Deseas recibir noticias y promociones? 
				</label><br style="clear:left;">
				<label style="width:100% !important;">
					<input type="checkbox" onchange="getTerms(this)" name="terms" id="terms">
					 Para continuar debes aceptar nuestros <a href="https://www.termsfeed.com/blog/wp-content/uploads/2019/04/terms-and-conditions-template.pdf" target="_blank" rel="noreferrer noopener">términos y condiciones</a> 
				</label><br style="clear:left;">
				<!-- <input type="submit" value="Regístrate" id="regisButton" disabled> -->
				<button type="submit" id="regisButton" disabled class="bestBtn"><i class="fa fa-user"></i> Regístrate</button><br><br>
			</form>
		</section>
		
		<script>
			/**
			* Cambiar imagen al subir una foto
			* @param event, object: Imagen que se sube
			*/
			var loadFile = function(event) {
				var outputbck = document.getElementById('ftoPreview');
				outputbck.src = URL.createObjectURL(event.target.files[0]);
			};

			/**
			* Abrir o cerrar el modal
			* @param modal, string: id del modal que se usará 
			*/
			function openModal(modal){
				var modalBox = document.getElementById(modal); 
				if(modalBox.style.display == "none"){
					modalBox.style.display = "block";

					// Obtener los datos que llegan de Facebook al iniciar sesión
					// No funciona en páginas sin certificado de seguridad
					FB.api(
						'/me',
						FB.getUserID(),
						{"fields":"email,address,first_name,last_name"},
						function(response) {
							console.log(response);
						}
					);
				}else{
					modalBox.style.display = "none";
				}
			}
			openModal('rg_regis_form');

			/**
			* Habilitar o desactivar el botón de términos
			* @param check, object: input checkbox
			*/
			function getTerms(check){
				if(check.checked)
					jQuery("#regisButton").prop( "disabled", false );
				else
					jQuery("#regisButton").prop( "disabled", true );
			}
		</script>

		<script>
			jQuery("#megaform").submit(function(e) {
				e.preventDefault(); 
				console.log(e);
				// var formData = {
				// 	'regisForm':'',
				// 	'tipdoc':jQuery('select[name=tipdoc]').val(),
				// 	'doc':jQuery('input[name=doc]').val(),
				// 	'nmbr':jQuery('input[name=nmbr]').val(),
				// 	'aplld':jQuery('input[name=aplld]').val(),
				// 	'cel':jQuery('input[name=cel]').val(),
				// 	'mail':jQuery('input[name=mail]').val(),
				// 	'feed':jQuery('input[name=feed]').val()
				// }
				var formData = new FormData(this);

				jQuery.ajax({
					url: "<?= RG_EDIT_URL_PB.'/public/registro-usuario.php'; ?>",
					type: 'POST',
					data: formData,
					processData: false,
contentType: false,
					beforeSend: function(data){
						console.log("Datos de envío");
						console.log(data);
						jQuery('form p').html("<div class='cm-spinner'></div>");
						jQuery('#regisButton').attr('disabled','disabled');
					},
					success: function(data, status){
						console.log(data,status);
						jQuery('form p').html("<div class='confirmation'>¡Gracias! Te has registrado satisfactoriamente</div>");
					}
				});
			});
		</script>


	<?php
		endif;
	}

}
?>
