jun.<?php echo $this->modelClass; ?>Grid=Ext.extend(Ext.grid.GridPanel ,{        
	title:"<? echo $this->modelClass;?>",
        id:'docs-jun.<?php echo $this->modelClass; ?>Grid',
	width:400,
	height:250,
        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	columns:[
        <?php
        $count = 0;
        foreach ($this->tableSchema->columns as $column) {
                if (++$count == 7)
                        echo "\t\t/*\n";
                ?>
                {
			<?echo "header:'" . $column->name ."',\n";?>
			sortable:true,
			resizable:true,                        
                        <?echo "dataIndex:'" . $column->name ."',\n";?>
                        <?echo $this->generateColumnGrid($modelClass, $column) ."\n";?>
			width:100
		},
                <?
                
        }
        if ($count >= 7)
                echo "\t\t*/\n";
        ?>
		
	],
	initComponent: function(){
	this.store = jun.rzt<?php echo $this->modelClass;?>;
        this.bbar = {
            items: [
           {
            xtype: 'paging',
            store: this.store,
            displayInfo: true,
            pageSize: 10
           }]
        };
            
           this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        text: 'Add',
                        ref: '../btnAdd'
                    },
                    {
                        xtype: 'button',
                        text: 'Edit',
                        ref: '../btnEdit'
                    },                    
                    {
                        xtype: 'button',
                        text: 'Delete',
                        ref: '../btnDelete'
                    }
                ]
            };
		jun.<?php echo $this->modelClass; ?>Grid.superclass.initComponent.call(this);
	        this.btnAdd.on('Click', this.loadForm, this);
                this.btnEdit.on('Click', this.loadEditForm, this);
                this.btnDelete.on('Click', this.deleteRec, this);
                this.getSelectionModel().on('rowselect', this.getrow, this);
	},
        
        getrow: function(sm, idx, r){
            this.record = r;

            var selectedz = this.sm.getSelections();
        },
        
        loadForm: function(){
            var form = new jun.<?php echo $this->modelClass; ?>Win({modez:0});
            form.show();
        },
        
        loadEditForm: function(){
            
            var selectedz = this.sm.getSelected();
            
            //var dodol = this.store.getAt(0);
             if(selectedz == ""){
                 Ext.MessageBox.alert("Warning","Anda belum memilih Jenis Pelayanan");
                 return;
             }
            var idz = selectedz.json.id;
            var form = new jun.<?php echo $this->modelClass; ?>Win({modez:1, id:idz});
            form.show(this);
            form.formz.getForm().loadRecord(this.record);
        },
        
        deleteRec : function(){
            Ext.MessageBox.confirm('Pertanyaan','Apakah anda yakin ingin menghapus data ini?', this.deleteRecYes, this);
        },
        
        deleteRecYes : function(){
        
            var record = this.sm.getSelected();

            // Check is list selected
            if(record == ""){
                Ext.MessageBox.alert("Warning","Anda Belum Memilih Jenis Pelayanan");
                return;
            }

            Ext.Ajax.request({
                waitMsg: 'Please Wait',
                url: '<?php echo $this->getModule()->getName();?>/<?php echo $this->modelClass; ?>/delete/id/' + record.json.id,
                //url: 'index.php/api/<?php echo $this->modelClass; ?>/delete/' + record[0].json.nosjp,
                method: 'POST',
                
                success: function(response){
                  jun.rzt<?php echo $this->modelClass; ?>.reload();
                  Ext.Msg.alert('Pelayanan', 'Delete Berhasil');

                },
                failure: function(response){
                  Ext.MessageBox.alert('error','could not connect to the database. retry later');
                  }
             });
        
        }
})
