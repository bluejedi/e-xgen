jun.home = new jun.homePanel({title:'home'});
    //new Ext.Panel({title:'home',html:'<p style="padding:25px">welcome to the jungle</p>'});

jun.TabsUi = Ext.extend(Ext.TabPanel, {
    activeTab: 0,
    region: 'center',
    frame: true,
    enableTabScroll: true,
    initComponent: function() {
        
         this.items = [
               jun.home               
            ];
           
        jun.TabsUi.superclass.initComponent.call(this);
        this.on('load', this.onActivate, this);
    },
    onActivate : function(p)
    {
        
       
    },
    loadClass : function(href){
       
        var id = 'docs-' + href;
  
        var tab = this.getComponent(id);
       
        var obj = eval(href);
                
        if(tab){
            this.setActiveTab(tab);
        }else{
      
            var p = this.add(new obj({
                id: id,
                closable: true
            }));
            this.setActiveTab(p);
        }
    }
});