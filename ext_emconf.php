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
    'version' => '0.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Pluswerk\\WikipediaImporter\\' => 'Classes'
        ]
    ],
];