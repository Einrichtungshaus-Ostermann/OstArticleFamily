
{* file to extend *}
{extends file="parent:frontend/detail/content.tpl"}

{* set namespace *}
{namespace name="frontend/ost-article-family/detail/content"}



{* tab navigation *}
{block name="frontend_detail_index_tabs_cross_selling"}

    {* do we have an article family? *}
    {if is_array( $ostArticleFamilyArticles ) && count( $ostArticleFamilyArticles ) > 0}
        {include file="frontend/ost-article-family/product-slider.tpl"}
    {/if}

    {* smarty parent *}
    {$smarty.block.parent}

{/block}
