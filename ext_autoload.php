<?php
$extensionPath        = t3lib_extMgm::extPath('contexts_geolocation');
$extensionClassesPath = $extensionPath . 'Classes/';

return array(
    'tx_contexts_geolocation_backend'
        => $extensionClassesPath . 'Backend.php',
    'tx_contexts_geolocation_exception'
        => $extensionClassesPath . 'Exception.php',

    // Adapter
    'tx_contexts_geolocation_adapter'
        => $extensionClassesPath . 'Adapter.php',
    'tx_contexts_geolocation_adapter_geoip'
        => $extensionClassesPath . 'Adapter/GeoIp.php',
    'tx_contexts_geolocation_adapter_netgeoip'
        => $extensionClassesPath . 'Adapter/NetGeoIp.php',

    'tx_contexts_geolocation_context_type_continent'
        => $extensionClassesPath . 'Context/Type/Continent.php',
    'tx_contexts_geolocation_context_type_country'
        => $extensionClassesPath . 'Context/Type/Country.php',
    'tx_contexts_geolocation_context_type_distance'
        => $extensionClassesPath . 'Context/Type/Distance.php',
);
?>
