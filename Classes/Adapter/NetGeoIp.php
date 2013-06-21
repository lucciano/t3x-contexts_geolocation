<?php
/**
 * Part of geolocation context extension.
 *
 * PHP version 5
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */

/**
 * Includes
 */
require_once "Net/GeoIP.php";

/**
 * Provides methods used in the backend by flexforms.
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Christian Weiske <christian.weiske@netresearch.de>
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
    protected $netGeoIpCountry = null;

    /**
     * Internal Net_GeoIP instance used for querying city database.
     *
     * @var Net_GeoIP
     */
    protected $netGeoIpCity = null;

    /**
     * Constructor. Protected to prevent direct instanciation.
     *
     * @param string $ip IP address
     *
     * @return void
     */
    protected function __construct($ip)
    {
        $this->netGeoIpCountry = Net_GeoIP::getInstance(
            PATH_site . 'typo3temp/GeoIP.dat/GeoIP.dat'
        );
        $this->netGeoIpCity = Net_GeoIP::getInstance(
            PATH_site . 'typo3temp/GeoIP.dat/GeoLiteCity.dat'
        );

        $this->ip = $ip;
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
        return $this->netGeoIpCountry->lookupCountryCode($this->ip);
    }

    /**
     * Get country name.
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->netGeoIpCountry->lookupCountryName($this->ip);
    }

    /**
     * Get location record.
     *
     * @return array
     */
    public function getLocation()
    {
        return $this->netGeoIpCity->lookupLocation($this->ip)->getData();
    }

    /**
     * Get country code and region. Required an non-free region database.
     *
     * @return array
     */
    public function getRegion()
    {
        // TODO
        return null; //$this->netGeoIpCity->lookupRegion($this->ip);
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
        return null; // $this->netGeoIpCountry->lookupOrg($this->ip);
    }
}
?>
