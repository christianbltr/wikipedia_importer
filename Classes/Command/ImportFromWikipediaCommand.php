<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Christianbltr\WikipediaImporter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use duzun\hQuery;

class ImportFromWikipediaCommand extends Command
{
    protected function configure()
    {
        $this->setDescription('Imports random wikipedia articles to TYPO3.')
            ->setHelp(
                'Imports random wikipedia articles to TYPO3 as news records in order to serve as test records for eg. a search function.'
                . LF . 'If you want to get more detailed information, use the --verbose option.'
            )
            ->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page to import to (news sysfolder).')
            ->addOption('number_of_records', 'c', InputOption::VALUE_OPTIONAL, 'Number of articles to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $amount = 1;
        if ($input->getOption('number_of_records')) {
            $amount = intval($input->getOption('number_of_records'));
        }

        if (!$input->getOption('page')) {
            $io->writeln('Error: No page specified (use --page option).');
            return self::FAILURE;
        } else {
            $page = intval($input->getOption('page'));
        }

        if (!ExtensionManagementUtility::isLoaded('news')) {
            $io->writeln('Error: Extension "news" needs to be installed.');
            return selft::FAILURE;
        }

        $io->writeLn('Importing ' . $amount . ' wikipedia articles to page ' . $page . ':');
        for ($i=0; $i<$amount; $i++) {
            // get the document
            $doc = hQuery::fromUrl('https://en.wikipedia.org/wiki/Special:Random', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

            // get content
            $title = $doc->find('h1');
            $content = $doc->find('#mw-content-text');

            // Clean up title
            $title = strip_tags($title);

            // Clean up content
            // Remove style tag including content
            $content = $this->strip_tags_content($content, '<style>', true);
            // Remove attributes like "class" and "style"
            // https://stackoverflow.com/questions/3026096/remove-all-attributes-from-html-tags
            $content = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $content);
            // Remove most of the tags
            $content = strip_tags($content, '<p><br><h2><table><tr><td><th><tf>');
            // Remove multiple spaces
            $content = preg_replace('/\s+/', ' ',$content);
            // Remove content
            $content = str_replace('[edit]', '', $content);

            // add copyright
            $content .=
                '<br />This article uses material from the Wikipedia article '
                . '<a href="' . $doc->baseURI() . '">"' . $title . '"</a>'
                . ', which is released under the <a href="https://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-Share-Alike License 3.0</a>.';

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
                    'datetime' => time(),
                    'title' => $title,
                    'bodytext' => $content,
                    'related_links' => 0
                ])
                ->executeStatement();

            // output
            if ($affectedRows) $io->writeln($title . ' (' . $doc->baseURI() . ')');
        }

        return self::SUCCESS;
    }

    /**
     * Strips HTML tags including content
     * https://stackoverflow.com/a/65536384/6865338
     *
     * @param string $text
     * @param string $tags
     * @param bool $invert
     * @return array|string|null
     */
    public function strip_tags_content(string $text, string $tags = '', bool $invert = FALSE): array|string|null
    {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique((array)$tags[1]);

        if(count($tags) > 0) {
            if (!$invert) {
                return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            }
            else {
                return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
            }
        } else if (!$invert) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }
}
