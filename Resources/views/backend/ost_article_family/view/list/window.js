//

Ext.define('Shopware.apps.OstArticleFamily.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.key-list-window',
    height: 450,
    title : 'Typenpläne Schlüsselwörter',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.OstArticleFamily.view.list.Key',
            listingStore: 'Shopware.apps.OstArticleFamily.store.Keys'
        };
    }
});