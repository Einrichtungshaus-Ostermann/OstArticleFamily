
Ext.define('Shopware.apps.OstArticleFamily.model.Key', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'OstArticleFamily',
            detail: 'Shopware.apps.OstArticleFamily.view.detail.Key'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'key', type: 'string' }
    ]
});

