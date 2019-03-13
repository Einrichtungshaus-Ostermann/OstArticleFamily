//

Ext.define('Shopware.apps.OstArticleFamily.store.Keys', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'OstArticleFamily'
        };
    },

    model: 'Shopware.apps.OstArticleFamily.model.Key'
});