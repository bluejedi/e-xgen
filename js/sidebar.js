Ext.namespace('jun');
jun.TreeUi = Ext.extend(Ext.tree.TreePanel, {
    title: 'Sidebar',
    useArrows:true,
    region: 'west',
    split: true,
    //collapsible: false,
    rootVisible: false,
    floatable: false,
    //dataUrl:'http://localhost/june/index.php/site/tree/',
    width: 240,
    initComponent: function() {
        
        this.root = {
            text: 'Menu'
        };
        /*
        this.loader = {

        };*/
        jun.TreeUi.superclass.initComponent.call(this);
    }
});