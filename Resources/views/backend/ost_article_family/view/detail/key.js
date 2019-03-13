//

Ext.define('Shopware.apps.OstArticleFamily.view.detail.Key', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'OstArticleFamily',
            fieldSets: [{
                title: null,
                layout: 'fit',
                fields: {
                    key: 'Schlüsselwort'
                }

                }]
        };
    }
});