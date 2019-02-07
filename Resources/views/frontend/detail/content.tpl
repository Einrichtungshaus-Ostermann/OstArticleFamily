
{* file to extend *}
{extends file="parent:frontend/detail/content.tpl"}

{* set namespace *}
{namespace name="frontend/ost-article-family/detail/content"}



{* tab navigation *}
{block name="frontend_detail_index_tabs_cross_selling"}

    {* do we have an article family? *}
    {if is_array( $ostArticleFamilyArticles ) && count( $ostArticleFamilyArticles ) > 0}
        <div class="ost-article-family">
            <div class="title">Artikel Modell-Familie</div>
            {include file="frontend/_includes/product_slider.tpl" articles=$ostArticleFamilyArticles}
        </div>
    {/if}

    {* smarty parent *}
    {$smarty.block.parent}

{/block}
