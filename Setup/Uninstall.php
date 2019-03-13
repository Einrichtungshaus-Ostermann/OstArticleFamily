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

namespace OstArticleFamily\Setup;

use Doctrine\ORM\Tools\SchemaTool;
use OstArticleFamily\Models;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\UninstallContext;
use Exception;

class Uninstall
{
    /**
     * Main bootstrap object.
     *
     * @var Plugin
     */
    protected $plugin;

    /**
     * ...
     *
     * @var UninstallContext
     */
    protected $context;

    /**
     * ...
     *
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * ...
     *
     * @var CrudService
     */
    protected $crudService;

    /**
     * ...
     *
     * @var array
     */
    protected $models = [
        Models\Family::class,
        Models\Family\Key::class
    ];

    /**
     * ...
     *
     * @param Plugin           $plugin
     * @param UninstallContext $context
     * @param ModelManager     $modelManager
     * @param CrudService      $crudService
     */
    public function __construct(Plugin $plugin, UninstallContext $context, ModelManager $modelManager, CrudService $crudService)
    {
        // set params
        $this->plugin = $plugin;
        $this->context = $context;
        $this->modelManager = $modelManager;
        $this->crudService = $crudService;
    }

    /**
     * ...
     *
     * @throws \Exception
     */
    public function uninstall()
    {
        // ...
        $this->uninstallModels();
    }

    /**
     * ...
     */
    private function uninstallModels()
    {
        // get entity manager
        $em = $this->modelManager;

        // get our schema tool
        $tool = new SchemaTool($em);

        // ...
        $classes = array_map(
            function ($model) use ($em) {
                return $em->getClassMetadata($model);
            },
            $this->models
        );

        // remove them
        try {
            $tool->dropSchema($classes);
        } catch (Exception $exception) {
        }
    }
}
