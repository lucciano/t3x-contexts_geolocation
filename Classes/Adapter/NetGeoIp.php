<?php
/**
 * Part of geolocation context extension.
 *
 * PHP version 5
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */

/**
 * Includes
 */
require_once "Net/GeoIP.php";

/**
 * Provides an adapter to the PEAR class "Net_GeoIP".
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
class Tx_Contexts_Geolocation_Adapter_NetGeoIp
    extends Tx_Contexts_Geolocation_Adapter
{
    /**
     * Internal Net_GeoIP instance used for querying country database.
     *
     * @var Net_GeoIP
     */
    protected $geoLiteCountry = null;

    /**
     * Internal Net_GeoIP instance used for querying city database.
     *
     * @var Net_GeoIP
     */
    protected $geoLiteCity = null;

    /**
     * Constructor. Protected to prevent direct instanciation.
     *
     * @param string $ip IP address
     *
     * @return void
     */
    protected function __construct($ip)
    {
        // Get extension configuration
        $extConfig = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_geolocation']
        );

        // Get path to geolite database
        $dbPath = PATH_site . ltrim($extConfig['geoLiteDatabase'], '/');

        $this->geoLiteCountry = Net_GeoIP::getInstance($dbPath . 'GeoIP.dat');
        $this->geoLiteCity    = Net_GeoIP::getInstance($dbPath . 'GeoLiteCity.dat');
        $this->ip             = $ip;
    }

    /**
     * Get instance of class. Returns null if the Net_GeoIP class is
     * not available.
     *
     * @param string $ip IP address
     *
     * @return Tx_Contexts_Geolocation_Adapter_NetGeoIp|null
     */
    public static function getInstance($ip)
    {
        if (class_exists('Net_GeoIP', true)) {
            return new self($ip);
        }

        return null;
    }

    /**
     * Get two letter country code.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->geoLiteCountry->lookupCountryCode($this->ip);
    }

    /**
     * Get country name.
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->geoLiteCountry->lookupCountryName($this->ip);
    }

    /**
     * Get location record.
     *
     * @return array
     */
    public function getLocation()
    {
        return $this->geoLiteCity->lookupLocation($this->ip)->getData();
    }

    /**
     * Get country code and region. Required an non-free region database.
     *
     * @return array
     */
    public function getRegion()
    {
        // TODO
        return null; //$this->geoLiteCity->lookupRegion($this->ip);
    }

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range. Requires an non-free organisation database.
     *
     * @return string
     */
    public function getOrganization()
    {
        // TODO
        return null; // $this->geoLiteCountry->lookupOrg($this->ip);
    }
}
?>
