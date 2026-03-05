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
    'version' => '3.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-14.99.99',
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Christianbltr\\WikipediaImporter\\' => 'Classes'
        ]
    ],
];
