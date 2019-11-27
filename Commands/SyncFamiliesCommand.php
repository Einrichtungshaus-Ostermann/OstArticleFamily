<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Article Family
 *
 * @package   OstArticleFamily
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstArticleFamily\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Enlight_Components_Db_Adapter_Pdo_Mysql as Db;

class SyncFamiliesCommand extends ShopwareCommand
{
    /**
     * ...
     *
     * @var Db
     */
    private $db;

    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * @param Db $db
     * @param array $configuration
     */
    public function __construct(Db $db, array $configuration)
    {
        parent::__construct();
        $this->db = $db;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $output->writeln('reading .pdf files');
        $output->writeln('directory: ' . rtrim($this->configuration['pdfDirectory'], "/") . "/*.pdf");

        // read every .pdf file
        $pdf = glob(rtrim($this->configuration['pdfDirectory'], "/") . "/*.pdf");

        // start the progress bar
        $progressBar = new ProgressBar($output, count($pdf));
        $progressBar->setRedrawFrequency(1);
        $progressBar->start();

        // every pdf file to remove every other which is not this one
        $pdfFiles = array();

        // loop them
        foreach ($pdf as $file) {
            // remove everything
            $file = str_replace(rtrim($this->configuration['pdfDirectory'], "/") . "/", "", $file);
            $key = strtolower(str_replace(".pdf", "", $file));

            // remove special chars
            $key = str_replace(array('"', "'"), '', $key);
            $file = str_replace(array('"', "'"), '', $file);

            // add
            $pdfFiles[] = '"' . $file . '"';

            // insert into with ignore on unique key
            $query = "
                INSERT IGNORE INTO `ost_article_families` (`id`, `key`, `file`)
                VALUES (NULL, :key, :file);
            ";
            $this->db->query($query, array( 'key' => $key, 'file' => $file ));

            // advance progress bar
            $progressBar->advance();
        }

        // done
        $progressBar->finish();
        $output->writeln('');

        // remove every obsolete pdf
        $output->writeln('removing obsolete .pdf keys within the db');

        // only if we even have some
        if (count($pdfFiles) > 0) {
            // ...
            $query = '
            DELETE FROM ost_article_families
            WHERE `file` IS NOT NULL
                AND `file` != ""
                AND `file` NOT IN(' . implode(',', $pdfFiles) . ')
        ';
            $this->db->query($query);
        }

        // ...
        $output->writeln('reading individual keys');

        // ...
        $query = '
            SELECT id, `key`
            FROM ost_article_families_keys
        ';
        $keys = Shopware()->Db()->fetchPairs($query);

        // start the progress bar
        $progressBar = new ProgressBar($output, count($keys));
        $progressBar->setRedrawFrequency(1);
        $progressBar->start();

        // loop them
        foreach ($keys as $key) {
            // remove everything
            $key = strtolower($key);
            $key = str_replace(array('"', "'"), '', $key);

            // insert into with ignore on unique key
            $query = "
                INSERT IGNORE INTO `ost_article_families` (`id`, `key`, `file`)
                VALUES (NULL, :key, :file);
            ";
            $this->db->query($query, array( 'key' => $key, 'file' => "" ));

            // advance progress bar
            $progressBar->advance();
        }

        // done
        $progressBar->finish();
        $output->writeln('');

        // ...
        $output->writeln('removing obsolete individual keys within the db');

        // remove
        $query = '
            DELETE FROM ost_article_families
            WHERE ost_article_families.id IN (
                SELECT id
                FROM (
                    SELECT id
                    FROM `ost_article_families` 
                    WHERE ost_article_families.file = ""
                        AND ost_article_families.`key` NOT IN (
                            SELECT ost_article_families_keys.`key`
                            FROM ost_article_families_keys
                        ) 
                ) AS innerFamilies
            )
        ';
        $this->db->query($query);
    }
}
