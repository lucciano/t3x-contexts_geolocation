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
     * @throws Exception
     */
    public static function getInstance($ip)
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
                throw new Exception('No installed geoip adapter found');
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
     * Get two letter country code.
     *
     * @return string
     */
    abstract public function getCountryCode();

    /**
     * Get country name.
     *
     * @return string
     */
    abstract public function getCountryName();

    /**
     * Get location record.
     *
     * @return array
     */
    abstract public function getLocation();

    /**
     * Get country code and region.
     *
     * @return array
     */
    abstract public function getRegion();

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range.
     *
     * @return string
     */
    abstract public function getOrganization();
}
?>
