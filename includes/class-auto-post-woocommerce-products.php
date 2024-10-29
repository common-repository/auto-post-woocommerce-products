<?php
/**
 * Description: The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Saturday, Jun-15-2019 at 09:57:49
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.0.0
 */

/**
 * Plugin class.
 *
 * @since  1.0.0
 *
 * @return mixed
 */
class Auto_Post_Woocommerce_Products {
	/**
	 * The loader that is responsible for maintaining and registering all
	 * hooks that power the plugin.
	 *
	 * @access   protected
	 * @var Auto_Post_Woocommerce_Products_Loader $loader Maintains and registers all hooks for the plugin.
	 * @since    1.0.0
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var string $apwcp The string used to uniquely identify this plugin.
	 * @since    1.0.0
	 */
	protected $apwcp;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var string $version The current version of the plugin.
	 * @since    1.0.0
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'APWP_VERSION' ) ) {
			$this->version = APWP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->auto_post_woocommerce_products = 'auto_post_woocommerce_products';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 * Include the following files that make up the plugin:
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access private
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-auto-post-woocommerce-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-auto-post-woocommerce-products-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-auto-post-woocommerce-products-admin.php';
		$this->loader = new Auto_Post_Woocommerce_Products_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access private
	 * @since  1.0.0
	 */
	private function set_locale() {
		$plugin_i18n = new Auto_Post_Woocommerce_Products_I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Auto_Post_Woocommerce_Products_Admin( $this->get_auto_post_woocommerce_products(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Run the loader.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 *
	 * @return string The name of the plugin.
	 */
	public function get_auto_post_woocommerce_products() {
		return $this->auto_post_woocommerce_products;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 *
	 * @return Plugin_Name_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
