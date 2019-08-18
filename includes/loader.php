<?php
/**
 * Mantiene y registra los hooks del plugin
 *
 * @package    registro-usuarios
 * @subpackage registro-usuarios/includes
 * @author     YIMMY MOTTA <yimsanabria@gmail.com>
 *
*/

class RG_Loader {

	/**
	 * Array de acciones registradas con Wordpress
	 *
	 * @access   protected
	 * @var      array    $actions   Acciones registradas con Wordpress para correr cuando el plugin arranca.
	 */
	protected $actions;

	/**
	 * Array de filtros registrados con Wordpress
	 *
	 * @access   protected
	 * @var      array    $filters    Filtros registrados con Wordpress para correr cuando el plugin arranca.
	 */
	protected $filters;
	protected $apply;

	/**
	 * Inicializar colecciones para mantener las acciones y filtros.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		$this->apply = array();

	}

	/**
	 * Agrega una nueva accion a la coleccion para ser registrado con Wordpress
	 *
	 * @param      string               $hook             The name of the WordPress action that is being registered.
	 * @param      object               $component        A reference to the instance of the object on which the action is defined.
	 * @param      string               $callback         The name of the function definition on the $component.
	 * @param      int      Optional    $priority         The priority at which the function should be fired.
	 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	public function apply_filters( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->apply = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}



	/**
	 * Agrega un nuevo filtro a la coleccion para ser registrado con Wordpress
	 *
	 * @since    0.1.0
	 * @param      string               $hook             The name of the WordPress filter that is being registered.
	 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param      string               $callback         The name of the function definition on the $component.
	 * @param      int      Optional    $priority         The priority at which the function should be fired.
	 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * UnaUna función que se usa para registrar acciones y hookd dentro de una sola
	 * colección.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @param      array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param      string               $hook             The name of the WordPress filter that is being registered.
	 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param      string               $callback         The name of the function definition on the $component.
	 * @param      int      Optional    $priority         The priority at which the function should be fired.
	 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   type                                   The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;
	}

	/**
	 * Registro de filtros y acciones con WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
		foreach ( $this->apply as $hook ) {
			apply_filters( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}

}
