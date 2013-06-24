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
 * Abstract base class for each adapter.
 *
 * @category   TYPO3-Extensions
 * @package    Contexts
 * @subpackage Geolocation
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
abstract class Tx_Contexts_Geolocation_Adapter
{
    /**
     * Current IP address.
     *
     * @var string
     */
    protected $ip = null;

    /**
     * Get an adapter instance.
     *
     * @param string $ip IP address
     *
     * @return Tx_Contexts_Geolocation_Adapter
     * @throws Tx_Contexts_Geolocation_Exception
     */
    public static function getInstance($ip = null)
    {
        static $instance = null;

        if ($instance === null) {
            $adapters = self::getAdapters();

            // Loop through all adapters and load the first available one
            foreach (self::getAdapters() as $adapter) {
                $class    = 'Tx_Contexts_Geolocation_Adapter_' . $adapter;
                $instance = $class::getInstance($ip);

                if ($instance !== null) {
                    break;
                }
            }

            if ($instance === null) {
                throw new Tx_Contexts_Geolocation_Exception(
                    'No installed geoip adapter found'
                );
            }
        }

        return $instance;
    }

    /**
     * Get a list of available adapters.
     *
     * @return array
     */
    protected static function getAdapters()
    {
        $adapters = array();

        foreach (new DirectoryIterator(__DIR__ . '/Adapter') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $adapters[] = $fileInfo->getBasename('.php');
        }

        sort($adapters);

        return $adapters;
    }

    /**
     * Get two-letter continent code. Returns FALSE on failure.
     *
     * @return string|false
     */
    abstract public function getContinentCode();

    /**
     * Get two or three letter country code. Returns FALSE on failure.
     *
     * @param boolean $threeLetterCode TRUE to return 3-letter country code
     *
     * @return string|false
     */
    abstract public function getCountryCode($threeLetterCode = false);

    /**
     * Get country name. Returns FALSE on failure.
     *
     * @return string|false
     */
    abstract public function getCountryName();

    /**
     * Get location record. Returns FALSE on failure.
     *
     * @return array|false
     */
    abstract public function getLocation();

    /**
     * Get country code and region. Returns FALSE on failure.
     *
     * @return array|false
     */
    abstract public function getRegion();

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range. Returns FALSE on failure.
     *
     * @return string|false
     */
    abstract public function getOrganization();
}
?>
