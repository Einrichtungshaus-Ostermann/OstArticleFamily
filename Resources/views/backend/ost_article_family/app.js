//

Ext.define('Shopware.apps.OstArticleFamily', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.OstArticleFamily',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Key',

        'detail.Key',
        'detail.Window'
    ],

    models: [ 'Key' ],
    stores: [ 'Keys' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});