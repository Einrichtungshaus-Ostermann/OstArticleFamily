
{* file to extend *}
{extends file="frontend/listing/product-box/box-product-slider.tpl"}

{* add "box--slider" to the box class *}
{block name="frontend_listing_box_article"}
    {$productBoxLayout = 'slider box--dvsn-article-family'}
    {$smarty.block.parent}
{/block}

{* add buy or detail button*}
{block name="frontend_listing_box_article_buy"}
    <div class="product--btn-container">
        {if $sArticle.allowBuyInListing}
            {include file="frontend/listing/product-box/button-buy.tpl"}
        {else}
            {include file="frontend/listing/product-box/button-detail.tpl"}
        {/if}
    </div>
{/block}
