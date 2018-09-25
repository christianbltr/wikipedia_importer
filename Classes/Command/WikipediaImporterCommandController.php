<?php
namespace Pluswerk\WikipediaImporter\Command;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use duzun\hQuery;

/**
 * CommandController for working with extension management through CLI/scheduler
 */
class WikipediaImporterCommandController extends CommandController
{
    /**
     * Imports wikipedia articles
     * @param int $page Page-ID to import the articles to.
     * @param int $amount Amount of articles to import.
     * return void
     */
    public function importCommand($page, $amount=10)
    {
        if (!ExtensionManagementUtility::isLoaded('news')) {
            $this->outputFormatted('Error: Extension "news" needs to be installed.');
            return;
        }
        $this->outputFormatted('Importing ' . $amount . ' wikipedia articles to page ' . $page . ':');
        for ($i=0; $i<$amount; $i++) {
            // get the document
            $doc = hQuery::fromUrl('https://en.wikipedia.org/wiki/Special:Random', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

            // get content
            $title = $doc->find('h1');
            $bodytext = $doc->find('#mw-content-text');

            // cleanup content
            $title = strip_tags($title);
            $bodytext = strip_tags($bodytext, '<p><br><h2><table><tr><td><th><tf>');
            $bodytext = preg_replace('/\s+/', ' ',$bodytext);
            $bodytext = str_replace('[edit]', '', $bodytext);

            // insert into database
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_news_model_news');

            $affectedRows = $queryBuilder
                ->insert('tx_news_domain_model_news')
                ->values([
                        'pid' => (int)$page,
                        'tstamp' => time(),
                        'crdate' => time(),
                        'title' => $title,
                        'bodytext' => $bodytext
                    ])
                ->execute();

            // output
            if ($affectedRows) $this->outputFormatted($title);
        }
    }
}