
{* set namespace *}
{namespace name="frontend/ost-article-family/product-slider"}



{* our slider *}
{block name="ost-article-family--container"}
    <div class="ost-article-family">
        {if $ostArticleFamily.file != ""}
            {block name="ost-article-family--pdf-download"}
                <div class="pdf-download" style="position: absolute; right: 0; padding-right: 4px;">

                    {* create filename with directory *}
                    {assign var="image" value="frontend/_public/src/img/icon--pdf-family.png"}
                    {assign var="pdf" value="{$ostArticleFamily.directory}{$ostArticleFamily.file}"}

                    {* the actual link *}
                    <a href="{$pdf}" title="{s name="pdf-download-title"}Typenplan als .pdf laden{/s}" target="_blank" style="cursor: pointer;"><img style="width: 44px; height: 44px;" src="{link file=$image}"></a>

                </div>
            {/block}
        {/if}
        {block name="ost-article-family--slider-title"}
            <div class="title">{s name="slider-title-with-name"}Modell-Familie: {$ostArticleFamily.key|ucwords}{/s}</div>
        {/block}
        {block name="ost-article-family--slider"}
            {include file="frontend/_includes/product_slider.tpl" articles=$ostArticleFamilyArticles productBoxLayout="dvsn-article-family"}
        {/block}
    </div>
{/block}
