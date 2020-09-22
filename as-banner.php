<?php defined('ABSPATH') or die('No script kiddies please!');
/**
 * Plugin Name:       AS-Banner
 * Plugin URI:        https://github.com/aloisiosmelo/as-banner
 * Description:       Basic incredible AS-Banner plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aloisio Soares
 * Author URI:       https://github.com/aloisiosmelo
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       as-banner
 * Domain Path:       /languages
 */

require_once('functions.php' );
require_once('ASBanner.class.php' );

if(class_exists('ASBanner')) {
    $asBanner_obj = new ASBanner();
}
