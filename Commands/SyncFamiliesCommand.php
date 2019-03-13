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

        // read every .pdf file
        $pdf = glob(rtrim($this->configuration['pdfDirectory'], "/") . "/*.pdf");

        // start the progress bar
        $progressBar = new ProgressBar($output, count($pdf));
        $progressBar->setRedrawFrequency(1);
        $progressBar->start();

        // loop them
        foreach ($pdf as $file) {
            // remove everything
            $file = str_replace(rtrim($this->configuration['pdfDirectory'], "/") . "/", "", $file);
            $key = strtolower(str_replace(".pdf", "", $file));

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
    }
}
