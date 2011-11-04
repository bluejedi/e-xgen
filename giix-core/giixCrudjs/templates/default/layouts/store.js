jun.<?php echo $this->modelClass; ?>store = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        jun.<?php echo $this->modelClass; ?>store.superclass.constructor.call(this, Ext.apply({
            storeId: '<?php echo $this->modelClass; ?>StoreId',
            url: '<?php echo $this->getModule()->getName();?>/<?php echo $this->modelClass; ?>/?output=json',           
            root: 'results',
            totalProperty: 'total',
            fields: [                
                <?php
                $count = 0;
                foreach ($this->tableSchema->columns as $column) {
                     echo "{name:'" . $column->name ."'},\n";
                }                
                ?>                
            ]
        }, cfg));
    }
});
jun.rzt<?php echo $this->modelClass; ?> = new jun.<?php echo $this->modelClass; ?>store();
jun.rzt<?php echo $this->modelClass; ?>.load();
