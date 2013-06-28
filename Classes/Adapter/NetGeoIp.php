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
 * Provides an adapter to the PEAR class "Net_GeoIP".
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 * @uses       http://pear.php.net/package/Net_GeoIP/
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
    private function __construct($ip = null)
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
     * Prevent cloning of class.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Returns TRUE if extension is available with in PEAR.
     *
     * @return boolean
     *
     * TODO USer PEAR_Registry if possible
     */
    protected static function checkPear()
    {
        // Try to include PEAR extension, Suppress E_WARNING message
        $result = @include_once "Net/GeoIP.php";

        return (bool) $result;
    }

    /**
     * Get instance of class. Returns null if the Net_GeoIP class is
     * not available.
     *
     * @param string $ip IP address
     *
     * @return Tx_Contexts_Geolocation_Adapter_NetGeoIp|null
     */
    public static function getInstance($ip = null)
    {
        if (self::checkPear() && class_exists('Net_GeoIP', true)) {
            return new self($ip);
        }

        return null;
    }

    /**
     * Get two-letter continent code. Returns FALSE on failure.
     *
     * @return string|false
     */
    public function getContinentCode()
    {
        // @TODO Currently not available within Net_GeoIP
        return false;
    }

    /**
     * Get two or three letter country code. Returns FALSE on failure.
     *
     * @param boolean $threeLetterCode TRUE to return 3-letter country code
     *
     * @return string|false
     */
    public function getCountryCode($threeLetterCode = false)
    {
        try {
            // Net_GeoIP provides no method to return 3-letter-code
            if ($threeLetterCode) {
                $data = $this->geoLiteCity->lookupLocation($this->ip)->getData();
                return $data['countryCode3'];
            }

            return $this->geoLiteCountry->lookupCountryCode($this->ip);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get country name. Returns FALSE on failure.
     *
     * @return string|false
     */
    public function getCountryName()
    {
        try {
            return $this->geoLiteCountry->lookupCountryName($this->ip);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get location record. Returns FALSE on failure.
     *
     * @return array|false
     */
    public function getLocation()
    {
        try {
            return $this->geoLiteCity->lookupLocation($this->ip)->getData();
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get country code and region. Required an non-free region database.
     * Returns FALSE on failure.
     *
     * @return array|false
     */
    public function getRegion()
    {
        // @TODO $this->geoLiteRegion->lookupRegion($this->ip);
        return false;
    }

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range. Requires an non-free organisation database.
     * Returns FALSE on failure.
     *
     * @return string|false
     */
    public function getOrganization()
    {
        // @TODO $this->geoLiteOrg->lookupOrg($this->ip);
        return false;
    }
}
?>
