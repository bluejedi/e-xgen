jun.configstore = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        jun.configstore.superclass.constructor.call(this, Ext.apply({
            storeId: 'config',
            //url: 'index.php/site/user',
            //autoLoad: true,
            //reader:jun.myReader,
            root: 'results',
            totalProperty: 'total',
            fields: [
                
                <?php
                $count = 0;
                foreach ($this->tableSchema->columns as $column) {
                        if (++$count == 7)
                                echo "\t\t/*\n";                        
                        
                                echo "{name:" . $this->generateGridViewColumn($this->modelClass, $column)."},\n";

                }
                if ($count >= 7)
                        echo "\t\t*/\n";
                ?>
                
            ]
        }, cfg));
    }
});
