<?php

########################################################################
# Extension Manager/Repository config file for ext "contexts_geolocation".
#
# Auto generated 07-06-2013 16:06
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'TYPO3 contexts: Geolocation',
    'description' => 'Use the user\'s geographic location as context',
    'category' => 'misc',
    'author' => 'Christian Weiske, Marian Pollzien, Rico Sonntag',
    'author_email' => 'typo3.org@netresearch.de',
    'shy' => '',
    'dependencies' => 'contexts,static_info_tables',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => 'typo3temp/contexts_geolocation/',
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'lockType' => '',
    'author_company' => 'Netresearch GmbH & Co.KG',
    'version' => '0.2.2',
    'constraints' => array(
        'depends' => array(
            'typo3' => '4.5.0-6.1.99',
            'contexts' => '0.2.0-',
            'static_info_tables' => '2.0.0-',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
    '_md5_values_when_last_written' => 'a:19:{s:9:"ChangeLog";s:4:"1aa1";s:16:"ext_autoload.php";s:4:"e2ed";s:16:"ext_contexts.php";s:4:"665f";s:12:"ext_icon.gif";s:4:"e433";s:17:"ext_localconf.php";s:4:"fef1";s:14:"ext_tables.php";s:4:"4aa3";s:19:"Classes/Backend.php";s:4:"694f";s:34:"Classes/Context/Type/Continent.php";s:4:"b2e4";s:32:"Classes/Context/Type/Country.php";s:4:"0e96";s:33:"Classes/Context/Type/Distance.php";s:4:"3fd9";s:36:"Configuration/flexform/Continent.xml";s:4:"dcd7";s:34:"Configuration/flexform/Country.xml";s:4:"6c20";s:35:"Configuration/flexform/Distance.xml";s:4:"2b25";s:23:"Documentation/Index.rst";s:4:"1487";s:37:"Documentation/Images/cfg-distance.png";s:4:"4945";s:39:"Resources/Private/Language/flexform.xml";s:4:"3d3b";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"e31a";s:14:"doc/manual.sxw";s:4:"4611";s:44:"pi/class.tx_contextsgeolocation_position.php";s:4:"e058";}',
    'suggests' => array(
    ),
);

?>