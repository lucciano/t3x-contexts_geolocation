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
class Tx_Contexts_Geolocation_Backend
{
    /**
     * Get all countries from static info tables.
     * Uses the three-letter country code as key instead of the uid.
     *
     * @param array  &$params      Additional parameters
     * @param object $parentObject Parent object instance
     *
     * @return void
     */
    public function getCountries(array &$params, $parentObject)
    {
        $arRows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            'cn_iso_3 AS code, cn_short_en AS name',
            'static_countries',
            '', 'name ASC'
        );
        $params['items'][] = array('- unknown -', '*unknown*');
        foreach ($arRows as $arRow) {
            $params['items'][] = array(
                $arRow['name'], $arRow['code']
            );
        }
    }

    /**
     * Display input field with popup map element to select a position
     * as latitude/longitude points.
     *
     * @param array  $arFieldInfo Information about the current input field
     * @param object $tceforms    Form rendering library object
     *
     * @return string HTML code
     */
    public function inputMapPosition($arFieldInfo, t3lib_tceforms $tceforms)
    {
        $flex = t3lib_div::xml2array($arFieldInfo['row']['type_conf']);
        if (is_array($flex)
            && isset($flex['data']['sDEF']['lDEF']['field_position']['vDEF'])
        ) {
             list($lat, $lon) = explode(
                 ',',
                 $flex['data']['sDEF']['lDEF']['field_position']['vDEF']
             );
             $lat = (float) trim($lat);
             $lon = (float) trim($lon);
             $jZoom = 9;
             $inputVal = $flex['data']['sDEF']['lDEF']['field_position']['vDEF'];
        } else {
            //FIXME: geoip current address
            $lat = $lon = 0;
            $jZoom = 4;
            $inputVal = '';
        }

        $jLat = json_encode($lat);
        $jLon = json_encode($lon);

        if (is_array($flex)
            && isset($flex['data']['sDEF']['lDEF']['field_distance']['vDEF'])
        ) {
            $jRadius = json_encode(
                (float) $flex['data']['sDEF']['lDEF']['field_distance']['vDEF']
            );
        } else {
            $jRadius = 10;
        }

        $input = $tceforms->getSingleField_typeInput(
            $arFieldInfo['table'], $arFieldInfo['field'],
            $arFieldInfo['row'], $arFieldInfo
        );
        preg_match('#id=["\']([^"\']+)["\']#', $input, $arMatches);
        $inputId = $arMatches[1];

        $html = <<<HTM
$input<br/>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
<!--[if lte IE 8]>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" />
<![endif]-->
<script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
<div id="map"></div>
<style type="text/css">
#map { height: 300px; }
</style>
<script type="text/javascript">
document.observe('dom:loaded', function()
{
    var map = L.map("map").setView([51.505, -0.09], 4);

    // create the tile layer with correct attribution
    var osmUrl = "http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.jpg";
    var subDomains = ['otile1','otile2','otile3','otile4'];

    var osmAttrib = 'Data, imagery and map information provided by <a href="http://open.mapquest.co.uk" target="_blank">MapQuest</a>, <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> and contributors.';
    var osm = new L.TileLayer(
        osmUrl,
        {attribution: osmAttrib, subdomains: subDomains}
    );

    // start the map in South-East England
    map.setView(new L.LatLng($jLat, $jLon), $jZoom);
    var marker = L.marker([$jLat, $jLon]).addTo(map);
    marker.dragging.enable();
    map.addLayer(osm);


    var circle = L.circle(
        [$jLat, $jLon], $jRadius * 1000,
        {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2
        }
    ).addTo(map);

    marker.on('dragend', function(e) {
        var latlng = e.target.getLatLng();
        document.getElementById('$inputId').value
            = latlng.lat + ", " + latlng.lng;
        document.getElementById('$inputId').onchange();
        circle.setLatLng(latlng);
    });

    var distanceName = document.getElementById('$inputId').name.replace(
        'field_position', 'field_distance'
    );
    document.getElementsByName(distanceName)[0].observe(
        'change', function(e) {
            circle.setRadius(e.target.value * 1000);
        }
    );
});
</script>
HTM;

        return $html;
    }

    /**
     * Check if the extension has been setup properly
     *
     * @param array  $arFieldInfo Information about the current input field
     * @param object $tceforms    Form rendering library object
     *
     * @return string HTML code with warning when extension is not setup
     */
    public function setupCheck()
    {
        if (extension_loaded('geoip')) {
            return;
        }

        t3lib_FlashMessageQueue::addMessage(
            t3lib_div::makeInstance(
                't3lib_FlashMessage',
                'The "<tt>geoip</tt>" PHP extension is not available.'
                . ' Geolocation contexts will not work.',
                'Geolocation configuration',
                t3lib_FlashMessage::ERROR
            )
        );

        return null;
    }
}
?>
