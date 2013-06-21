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
 * Provides methods used in the backend by flexforms.
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
class Tx_Contexts_Geolocation_Adapter_GeoIp
    extends Tx_Contexts_Geolocation_Adapter
{
    /**
     * Constructor. Protected to prevent direct instanciation.
     *
     * @param string $ip IP address
     *
     * @return void
     */
    protected function __construct($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get instance of class. Returns null if geoip extension is
     * not available.
     *
     * @param string $ip IP address
     *
     * @return Tx_Contexts_Geolocation_Adapter_GeoIp|null
     */
    public static function getInstance($ip)
    {
        if (extension_loaded('geoip')) {
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
        return geoip_country_code_by_name($this->ip);
    }

    /**
     * Get country name.
     *
     * @return string
     */
    public function getCountryName()
    {
        return geoip_country_name_by_name($this->ip);
    }

    /**
     * Get location record.
     *
     * @return array
     */
    public function getLocation()
    {
        $data = geoip_record_by_name($this->ip);

        // Map data
        return array(
            'continentCode' => $data['continent_code'],
            'countryCode'   => $data['country_code'],
            'countryCode3'  => $data['country_code3'],
            'countryName'   => $data['country_name'],
            'region'        => $data['region'],
            'city'          => $data['city'],
            'postalCode'    => $data['postal_code'],
            'latitude'      => $data['latitude'],
            'longitude'     => $data['longitude'],
            'dmaCode'       => $data['dma_code'],
            'areaCode'      => $data['area_code'],
        );
    }

    /**
     * Get country code and region.
     *
     * @return array
     */
    public function getRegion()
    {
        return geoip_region_by_name($this->ip);
    }

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range.
     *
     * @return string
     */
    public function getOrganization()
    {
        return geoip_org_by_name($this->ip);
    }

    /**
     * Get continent code.
     *
     * @return string
     */
    public function getContinentCode()
    {
        return geoip_continent_code_by_name();
    }
}
?>
