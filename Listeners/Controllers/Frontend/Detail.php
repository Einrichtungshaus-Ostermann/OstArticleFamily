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

namespace OstArticleFamily\Listeners\Controllers\Frontend;

use Enlight_Event_EventArgs as EventArgs;
use Shopware_Controllers_Frontend_Detail as Controller;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Components\Compatibility\LegacyStructConverter;
use OstArticleFamily\Models\Family;

class Detail
{
    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * ...
     *
     * @var string
     */
    protected $viewDir;

    /**
     * ...
     *
     * @param string $viewDir
     */
    public function __construct(array $configuration, $viewDir)
    {
        // set params
        $this->configuration = $configuration;
        $this->viewDir = $viewDir;
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPreDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $request = $controller->Request();
        $view = $controller->View();

        // only order action
        if (strtolower($request->getActionName()) !== 'index') {
            // nothing to do
            return;
        }

        // add template dir
        $view->addTemplateDir($this->viewDir);
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPostDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $request = $controller->Request();
        $view = $controller->View();

        // only order action
        if (strtolower($request->getActionName()) !== 'index') {
            // nothing to do
            return;
        }

        // only valid article with valid attribute
        if ((!isset($view->getAssign('sArticle')['attributes']['core'])) or ((integer) $view->getAssign('sArticle')['attributes']['core']->get($this->configuration['attributeFamily']) == 0)) {
            // stop
            return;
        }

        // get the id
        $familyId = (integer) $view->getAssign('sArticle')['attributes']['core']->get($this->configuration['attributeFamily']);

        // get the hwg
        $hwg = (integer) $view->getAssign('sArticle')['attributes']['core']->get('attr2');

        // get every order number with the same family id
        $query = "
            SELECT article.id, article.ordernumber
            FROM s_articles_details AS article
                LEFT JOIN s_articles_attributes AS attribute
                    ON article.id = attribute.articledetailsID
                LEFT JOIN s_articles
                    ON article.articleID = s_articles.id
            WHERE article.kind = 1
                AND attribute." . $this->configuration['attributeFamily'] . " = :familyId
                AND attribute.attr2 " . (($hwg < 90) ? ' < 90 ' : ' >= 90 ') . "
            ORDER BY s_articles.name ASC, article.ordernumber ASC
        ";
        $numbers = Shopware()->Db()->fetchPairs($query, array( 'familyId' => $familyId ));

        /* @var $listProductService ListProductServiceInterface */
        $listProductService = Shopware()->Container()->get("shopware_storefront.list_product_service");

        /* @var $contextService ContextServiceInterface */
        $contextService = Shopware()->Container()->get("shopware_storefront.context_service");

        /* @var $legacyStructConverter LegacyStructConverter */
        $legacyStructConverter = Shopware()->Container()->get("legacy_struct_converter");

        // get all products
        $products = $listProductService->getList(
            array_values($numbers),
            $contextService->getProductContext()
        );

        // convert to legacy
        $articles = $legacyStructConverter->convertListProductStructList($products);

        // get the family
        $family = Shopware()->Models()->toArray(Shopware()->Models()->find(Family::class, $familyId));

        // add relative directory for frontend
        $family['directory'] = rtrim($this->configuration['pdfFrontendDirectory'], "/") . "/";

        // assign them
        $view->assign("ostArticleFamily", $family);
        $view->assign("ostArticleFamilyArticles", $articles);
    }
}
