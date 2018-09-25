<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Wikipedia Importer',
    'description' => 'Imports random wikipedia articles to news records in order to serve as test records for eg. a search function.',
    'category' => 'backend module',
    'author' => 'Christian Bülter',
    'author_company' => 'Pluswerk AG',
    'author_email' => 'christian.buelter@pluswerk.ag',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '0.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.4.0-9.9.99',
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Pluswerk\\WikipediaImporter\\' => 'Classes'
        ]
    ],
];