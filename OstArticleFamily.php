<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Article Family
 *
 * Adds support for article families. The families are generated out of the names
 * of "typenplan" .pdf files and read from within a directory via console command.
 * Every other article from within the same family is shown in the article details.
 *
 * 1.0.0
 * - initial release
 *
 * 1.0.1
 * - changed name of configuration
 *
 * 1.1.0
 * - added backend app for additional family keys
 * - added .pdf download to article slider
 *
 * 1.1.1
 * - added blocks to template
 *
 * 1.1.2
 * - added plugin configuration for frontend pdf directory
 * - fixed article family .pdf download icon
 *
 * 1.1.3
 * - moved product-slider to separate template
 *
 * 1.1.4
 * - added snippets to template
 * - added sorting by name
 *
 * 1.1.5
 * - fixed searching for keys within the article name
 *
 * 1.2.0
 * - added add-to-basket button configuration to family articles
 *
 * 1.2.1
 * - added family key to the slider title
 *
 * 1.2.2
 * - removed arrow from buttons within the slider
 *
 * 1.2.3
 * - fixed removal of obsolete families
 *
 * @package   OstArticleFamily
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstArticleFamily;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OstArticleFamily extends Plugin
{
    /**
     * ...
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        // set plugin parameters
        $container->setParameter('ost_article_family.plugin_dir', $this->getPath() . '/');
        $container->setParameter('ost_article_family.view_dir', $this->getPath() . '/Resources/views/');

        // call parent builder
        parent::build($container);
    }

    /**
     * Activate the plugin.
     *
     * @param Context\ActivateContext $context
     */
    public function activate(Context\ActivateContext $context)
    {
        // clear complete cache after we activated the plugin
        $context->scheduleClearCache($context::CACHE_LIST_ALL);
    }

    /**
     * Install the plugin.
     *
     * @param Context\InstallContext $context
     *
     * @throws \Exception
     */
    public function install(Context\InstallContext $context)
    {
        // install the plugin
        $installer = new Setup\Install(
            $this,
            $context,
            $this->container->get('models'),
            $this->container->get('shopware_attribute.crud_service')
        );
        $installer->install();

        // update it to current version
        $updater = new Setup\Update(
            $this,
            $context,
            $this->getPath() . '/'
        );
        $updater->install();

        // call default installer
        parent::install($context);
    }

    /**
     * Update the plugin.
     *
     * @param Context\UpdateContext $context
     */
    public function update(Context\UpdateContext $context)
    {
        // update the plugin
        $updater = new Setup\Update(
            $this,
            $context,
            $this->getPath() . '/'
        );
        $updater->update($context->getCurrentVersion());

        // call default updater
        parent::update($context);
    }

    /**
     * Uninstall the plugin.
     *
     * @param Context\UninstallContext $context
     *
     * @throws \Exception
     */
    public function uninstall(Context\UninstallContext $context)
    {
        // uninstall the plugin
        $uninstaller = new Setup\Uninstall(
            $this,
            $context,
            $this->container->get('models'),
            $this->container->get('shopware_attribute.crud_service')
        );
        $uninstaller->uninstall();

        // clear complete cache
        $context->scheduleClearCache($context::CACHE_LIST_ALL);

        // call default uninstaller
        parent::uninstall($context);
    }
}
