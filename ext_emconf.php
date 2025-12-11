<?php

$EM_CONF['wikipedia_importer'] = [
    'title' => 'Wikipedia Importer',
    'description' => 'Imports random wikipedia articles to TYPO3 as news records in order to serve as test records for eg. a search function.',
    'category' => 'misc',
    'author' => 'Christian Bülter',
    'author_company' => '',
    'author_email' => 'christian.buelter@web.de',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-13.99.99',
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Christianbltr\\WikipediaImporter\\' => 'Classes'
        ]
    ],
];
