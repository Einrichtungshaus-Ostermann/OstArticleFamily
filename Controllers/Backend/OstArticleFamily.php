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

use OstArticleFamily\Models\Family\Key;

class Shopware_Controllers_Backend_OstArticleFamily extends Shopware_Controllers_Backend_Application
{
    /**
     * ...
     *
     * @var string
     */
    protected $model = Key::class;

    /**
     * ...
     *
     * @var string
     */
    protected $alias = 'key';

    /**
     * ...
     *
     * @return array
     */
    public function getWhitelistedCSRFActions()
    {
        // return all actions
        return array_values(array_filter(
            array_map(
                function ($method) { return (substr($method, -6) === 'Action') ? substr($method, 0, -6) : null; },
                get_class_methods($this)
            ),
            function ($method) { return  !in_array((string) $method, ['', 'index', 'load', 'extends'], true); }
        ));
    }

    /**
     * ...
     *
     * @throws Exception
     */
    public function preDispatch()
    {
        // ...
        $viewDir = $this->container->getParameter('ost_article_family.view_dir');
        $this->get('template')->addTemplateDir($viewDir);
        parent::preDispatch();
    }



}
