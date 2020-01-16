<?php
/**
 *
 * @author  Oxibug
 * @version 1.0.0
 */
class CLSOXB_TGMPA_Includes {

    /**
     * An instance of the class
     *
     * @var     CLSOXB_TGMPA_Includes
     * @since   1.0.0
     *
     */
    private static $_instance = null;


    /**
     * The main directory of TGMPA files
     *
     * @var mixed
     */
    private $tgmpa_dir;

    /**
     * The main prefix used in actions to prefix each TGMPA included
     * in the active projects
     * 
     * Used For
     * 
     * - Prefix for all TGMPA's actions
     * - Prefix for GLOBALS instance
     * - Prefix for Menu
     * 
     * @var string
     */
    const   TGMPA_MAIN_PREFIX = 'tgmpaoxibug';


    /**
     * Instantiate Class
     *
     * @return  CLSOXB_TGMPA_Includes
     *
     * @since   1.0.0
     *
     */
    public static function instance( $tgmpa_dir ) {

        if( is_null( self::$_instance ) ) {

            self::$_instance = new self;

            self::$_instance->tgmpa_dir = untrailingslashit( $tgmpa_dir );

            self::$_instance->include_files();

            self::$_instance->trigger_actions();

        }

        return self::$_instance;

    }


    public function include_files() {

        /**
         * WP_List_Table isn't always available. If it isn't available,
         * we load it here.
         *
         * @since 2.2.0
         */
        if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        }


        /*
         * Oxibug Modifications
         * Implementation of function: tgmpa_load_bulk_installer
         *
         * Load Bulk Installer
         *
         * == The Old function description ==
         *
         * The WP_Upgrader file isn't always available. If it isn't available,
         * we load it here.
         *
         * We check to make sure no action or activation keys are set so that WordPress
         * does not try to re-include the class when processing upgrades or installs outside
         * of the class.
         *
         *
         * */
        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        require_once self::$_instance->tgmpa_dir . '/tgmpa-activation.php';
        require_once self::$_instance->tgmpa_dir . '/tgmpa-engine.php';

    }


    public function trigger_actions() {


        if ( did_action( 'plugins_loaded' ) ) {
            self::$_instance->load_tgm_plugin_activation();
        } else {
            add_action( 'plugins_loaded', array( $this, 'load_tgm_plugin_activation' ) );
        }


        /**
         * This file represents an example of the code that themes would use to register
         * the required plugins.
         *
         * It is expected that theme authors would copy and paste this code into their
         * functions.php file, and amend to suit.
         *
         * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
         *
         * @package    TGM-Plugin-Activation
         * @subpackage Example
         * @version    2.6.1 for parent theme Dusky for publication on ThemeForest
         * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
         * @copyright  Copyright (c) 2011, Thomas Griffin
         * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
         * @link       https://github.com/TGMPA/TGM-Plugin-Activation
         */

        /**
         * Include the CLSOXB_TGMPA_Activation class.
         *
         * Depending on your implementation, you may want to change the include call:
         *
         * Parent Theme:
         * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
         *
         * Child Theme:
         * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
         *
         * Plugin:
         * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
         */

        add_action( ( self::TGMPA_MAIN_PREFIX . '/tgmpa_register' ), array( &$this, 'register_required_plugins' ) );

    }


    /**
     * Plugin installation and activation for WordPress themes.
     *
     * Please note that this is a drop-in library for a theme or plugin.
     * The authors of this library (Thomas, Gary and Juliette) are NOT responsible
     * for the support of your plugin or theme. Please contact the plugin
     * or theme author for support.
     *
     * @package   TGM-Plugin-Activation
     * @version   2.6.1 for parent theme Dusky for publication on ThemeForest
     * @link      http://tgmpluginactivation.com/
     * @author    Thomas Griffin, Gary Jones, Juliette Reinders Folmer
     * @copyright Copyright (c) 2011, Thomas Griffin
     * @license   GPL-2.0+
     */
    public function load_tgm_plugin_activation() {

        $GLOBALS[ self::TGMPA_MAIN_PREFIX ] = CLSOXB_TGMPA_Activation::get_instance();

    }


    /**
     * Register the required plugins for this theme.
     *
     * In this example, we register two plugins - one included with the TGMPA library
     * and one from the .org repo.
     *
     * The variable passed to tgmpa_register_plugins() should be an array of plugin
     * arrays.
     *
     * This function is hooked into tgmpa_init, which is fired within the
     * CLSOXB_TGMPA_Activation class constructor.
     */

    function register_required_plugins() {
        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            array(
                'name'               => 'Kirki Toolkit', // The plugin name.
                'slug'               => 'kirki', // The plugin slug (typically the folder name).
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            ),

            //array(
            //    'name'               => 'Oxibug Core', // The plugin name.
            //    'slug'               => 'oxibug-core', // The plugin slug (typically the folder name).
            //    'source'             => 'xxxxxxxxxxxxx', // The plugin source.
            //    'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            //    'version'            => '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            //    'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            //    'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            //    'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            //    'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
            //),

            // Easy Google Fonts Plugin - from WordPress Repository
            array(
                'name' => 'Google Analytics Dashboard for WP', // The plugin name
                'slug' => 'google-analytics-dashboard-for-wp', // The plugin slug (typically the folder name)
                'required' => false, // If false, the plugin is only 'recommended' instead of required
            ),

            // Contact Form 7 Plugin - from WordPress Repository
            array(
                'name' => 'Contact Form 7', // The plugin name
                'slug' => 'contact-form-7', // The plugin slug (typically the folder name)
                'required' => false, // If false, the plugin is only 'recommended' instead of required
            ),

            // Regenerate Thumbnails Plugin
            array(
                'name' => 'Instagram Feed', // The plugin name
                'slug' => 'instagram-feed', // The plugin slug (typically the folder name)
                'required' => false, // If false, the plugin is only 'recommended' instead of required
            ),
        );

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'id'           => self::TGMPA_MAIN_PREFIX,                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => self::TGMPA_MAIN_PREFIX . '-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'activate_plugins',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.

            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.

            'strings'      => array(
                'page_title'                      => __( 'Install (Oxibug Theme) Required Plugins', CONST_OXIBUG_TEXTDOMAIN ),
                'menu_title'                      => __( 'Install Oxibug Plugins', CONST_OXIBUG_TEXTDOMAIN ),
                /* translators: %s: plugin name. */
                'installing'                      => __( 'Installing Plugin: %s', CONST_OXIBUG_TEXTDOMAIN ),
                /* translators: %s: plugin name. */
                'updating'                        => __( 'Updating Plugin: %s', CONST_OXIBUG_TEXTDOMAIN ),
                'oops'                            => __( 'Something went wrong with the plugin API.', CONST_OXIBUG_TEXTDOMAIN ),
                'notice_can_install_required'     => _n_noop(
                    /* translators: 1: plugin name(s). */
                    '(Oxibug Theme) requires the following plugin: %1$s.',
                    '(Oxibug Theme) requires the following plugins: %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'notice_can_install_recommended'  => _n_noop(
                    /* translators: 1: plugin name(s). */
                    '(Oxibug Theme) recommends the following plugin: %1$s.',
                    '(Oxibug Theme) recommends the following plugins: %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'notice_ask_to_update'            => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with (Oxibug Theme): %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with (Oxibug Theme): %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'notice_ask_to_update_maybe'      => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'notice_can_activate_required'    => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'notice_can_activate_recommended' => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'update_link' 					  => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    CONST_OXIBUG_TEXTDOMAIN
                ),
                'return'                          => __( 'Return to Required Plugins Installer', CONST_OXIBUG_TEXTDOMAIN ),
                'plugin_activated'                => __( 'Plugin activated successfully.', CONST_OXIBUG_TEXTDOMAIN ),
                'activated_successfully'          => __( 'The following plugin was activated successfully:', CONST_OXIBUG_TEXTDOMAIN ),
                /* translators: 1: plugin name. */
                'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', CONST_OXIBUG_TEXTDOMAIN ),
                /* translators: 1: plugin name. */
                'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for Envato Castle. Please update the plugin.', CONST_OXIBUG_TEXTDOMAIN ),
                /* translators: 1: dashboard link. */
                'complete'                        => __( 'All plugins installed and activated successfully. %1$s', CONST_OXIBUG_TEXTDOMAIN ),
                'dismiss'                         => __( 'Dismiss this notice', CONST_OXIBUG_TEXTDOMAIN ),
                'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', CONST_OXIBUG_TEXTDOMAIN ),
                'contact_admin'                   => __( 'Please contact the administrator of this site for help.', CONST_OXIBUG_TEXTDOMAIN ),

                'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
            ),

        );


        CLSOXB_TGMPA_Engine::instance()->tgmpa( $plugins, $config );

    }

}