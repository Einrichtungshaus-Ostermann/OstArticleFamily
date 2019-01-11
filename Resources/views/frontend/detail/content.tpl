
{* file to extend *}
{extends file="parent:frontend/detail/content.tpl"}

{* set namespace *}
{namespace name="frontend/ost-article-family/detail/content"}



{* tab navigation *}
{block name="frontend_detail_index_tabs_cross_selling"}

    {* do we have an article family? *}
    {if is_array( $ostArticleFamilyArticles ) && count( $ostArticleFamilyArticles ) > 0}
        <div>
            <h3>Artikel Modell-Familie</h3>
            <div class="tab--container" data-tab-id="relatedgdfdfg" style="border: 1px solid #dadae5; border-radius: 3px; background-clip: padding-box;">
                <div class="tab--header">
                    <a href="#" class="tab--title" title="Artikel Modell-Familie">
                        Artikel Modell-Familie
                    </a>
                </div>
                <div class="tab--content content--related">
                    <div class="related--content">
                        {include file="frontend/_includes/product_slider.tpl" articles=$ostArticleFamilyArticles}
                    </div>
                </div>
            </div>
        </div>
        <br /><br />
    {/if}

    {* smarty parent *}
    {$smarty.block.parent}

{/block}


