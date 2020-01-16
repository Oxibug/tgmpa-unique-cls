<?php
/**
 *
 * @author  Oxibug
 * @version 1.0.0
 */
class CLSOXB_TGMPA_Engine {

    /**
     * An instance of the class
     *
     * @var     CLSOXB_TGMPA_Engine
     * @since   1.0.0
     *
     */
    private static $_instance = null;


    /**
     * Instantiate Class
     *
     * @return  CLSOXB_TGMPA_Engine
     *
     * @since   1.0.0
     *
     */
    public static function instance() {

        if( is_null( self::$_instance ) ) {

            self::$_instance = new self;

        }

        return self::$_instance;

    }



	/**
     * Helper function to register a collection of required plugins.
     *
     * @since 2.0.0
     * @api
     *
     * @param array $plugins An array of plugin arrays.
     * @param array $config  Optional. An array of configuration values.
     */
	public function tgmpa( $plugins, $config = array() ) {

		$instance = call_user_func( array( get_class( $GLOBALS[ CLSOXB_TGMPA_Includes::TGMPA_MAIN_PREFIX ] ), 'get_instance' ) );

		foreach ( $plugins as $plugin ) {
			call_user_func( array( $instance, 'register' ), $plugin );
		}

		if ( ! empty( $config ) && is_array( $config ) ) {
			// Send out notices for deprecated arguments passed.
			if ( isset( $config['notices'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.2.0', 'The `notices` config parameter was renamed to `has_notices` in TGMPA 2.2.0. Please adjust your configuration.' );
				if ( ! isset( $config['has_notices'] ) ) {
					$config['has_notices'] = $config['notices'];
				}
			}

			if ( isset( $config['parent_menu_slug'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.4.0', 'The `parent_menu_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.' );
			}
			if ( isset( $config['parent_url_slug'] ) ) {
				_deprecated_argument( __FUNCTION__, '2.4.0', 'The `parent_url_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.' );
			}

			call_user_func( array( $instance, 'config' ), $config );
		}

	}

}