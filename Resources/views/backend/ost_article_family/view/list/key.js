//

Ext.define('Shopware.apps.OstArticleFamily.view.list.Key', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.key-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.OstArticleFamily.view.detail.Window',
            columns: {
                key: { header: 'Schlüsselwort' }
            }
        };
    }
});
