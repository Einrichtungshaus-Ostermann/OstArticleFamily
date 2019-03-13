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
use Shopware\Components\Model\ModelManager;
use OstArticleFamily\Models\Family;

class SyncArticlesCommand extends ShopwareCommand
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
     * @var ModelManager
     */
    private $modelManager;

    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * @param Db $db
     * @param ModelManager $modelManager
     * @param array $configuration
     */
    public function __construct(Db $db, ModelManager $modelManager, array $configuration)
    {
        parent::__construct();
        $this->db = $db;
        $this->modelManager = $modelManager;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $output->writeln('setting families for articles');
        
        // ...
        $families = $this->modelManager->getRepository(Family::class)->findAll();

        // reset every attribute
        $query = "
            UPDATE s_articles_attributes
            SET " . $this->configuration['attributeFamily'] . " = 0
        ";
        $this->db->query($query);

        // start the progress bar
        $progressBar = new ProgressBar($output, count($families));
        $progressBar->setRedrawFrequency(1);
        $progressBar->start();

        /* @var $family Family */
        foreach ($families as $family) {
            // update attribute for every article containing the name
            $query = "
                UPDATE s_articles_attributes
                SET " . $this->configuration['attributeFamily'] . " = :id
                WHERE articleID IN (
                    SELECT id
                    FROM s_articles
                    WHERE name LIKE :key
                )
            ";
            $this->db->query($query, array( 'id' => $family->getId(), 'key' => "%" . $family->getKey() . "%" ));

            // advance progress bar
            $progressBar->advance();
        }

        // done
        $progressBar->finish();
        $output->writeln('');
    }
}
