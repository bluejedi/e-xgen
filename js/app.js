jun.sidebar = new jun.TreeUi({
    dataUrl:'index.php/site/tree/'
});

jun.sidebar.on('click', function(node, e){
     if(node.isLeaf()){
        e.stopEvent();
        jun.mainPanel.loadClass(node.id);
     }
});

jun.mainPanel = new jun.TabsUi();

jun.ViewportUi = Ext.extend(Ext.Viewport, {
    layout: 'border',
    initComponent: function() {
        this.items = [
        {
            xtype: 'box',            
            region: 'north',
            //html: '<div id="header"><h1>RS ol</h1><span id=usrlogin></span></div>',
            applyTo:'header',
            height: 30
        },
        jun.sidebar,
        jun.mainPanel,
        {
            xtype: 'container',
            autoEl: 'div',
            region: 'south',
            height: 20
        }
        ];
        jun.ViewportUi.superclass.initComponent.call(this);
    }
});

Ext.onReady(function() {
    var hideMask = function () {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').fadeOut({
            remove:true
            //callback : firebugWarning
        });
    }

    hideMask.defer(250);
    Ext.QuickTips.init();

    var myViewport = new jun.ViewportUi({
        //renderTo: Ext.getBody()
    });
    //var logz = Ext.get('usrlogin')
    //logz.highlight();
   
            //jun.rztUser.load();
            
            //jun.rztSjp.load();
            //jun.rztRrawat.load();
            //jun.rztKlsrawat.load();

    
});
