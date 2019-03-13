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

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Exception;

class Update
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
     * @var InstallContext
     */
    protected $context;

    /**
     * ...
     *
     * @var string
     */
    protected $pluginDir;

    /**
     * ...
     *
     * @param Plugin         $plugin
     * @param InstallContext $context
     * @param string         $pluginDir
     */
    public function __construct(Plugin $plugin, InstallContext $context, $pluginDir)
    {
        // set params
        $this->plugin = $plugin;
        $this->context = $context;
        $this->pluginDir = $pluginDir;
    }

    /**
     * ...
     */
    public function install()
    {
        // install updates
        $this->update('0.0.0');
    }

    /**
     * ...
     *
     * @param string $version
     */
    public function update($version)
    {
        // switch old version
        switch ($version) {
            case '0.0.0':
            case '1.0.0':
            case '1.0.1':
                $this->updateSql('1.1.0');
        }
    }

    /**
     * ...
     *
     * @param string $version
     */
    private function updateSql($version)
    {
        // get the sql query for this update
        $sql = @file_get_contents(rtrim($this->pluginDir, '/') . '/Setup/Update/update-' . $version . '.sql');

        // execute the query and catch any db exception and ignore it
        try {
            Shopware()->Db()->exec($sql);
        } catch (Exception $exception) {
        }
    }
}
