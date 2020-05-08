# WikipediaImporter for TYPO3

Imports random wikipedia articles to news records in order to serve as test records for eg. a search function.

Implemented as a TYPO3 Command Controller.

Usage (with typo3_console installed):

vendor/bin/typo3cms wikipediaimporter:import PAGE-ID NUMBER-OF-ARTICLES[optional]

vendor/bin/typo3cms wikipediaimporter:import 8 20

Can also be used as a scheduler task (select "Extbase CommandController Task").