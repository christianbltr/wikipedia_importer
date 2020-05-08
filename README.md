# WikipediaImporter for TYPO3

Imports random wikipedia articles to news records in order to serve as test records for eg. a search function.

Implemented as a TYPO3 Command.

Usage (with typo3_console installed):

vendor/bin/typo3cms wikipedia_importer:importfromwikipedia --page PAGE_ID -c NUMBER_OF_ARTICLES

example:

vendor/bin/typo3cms wikipedia_importer:importfromwikipedia --page 123 -c 100

Can also be used as a scheduler task (select "Execute console commands").