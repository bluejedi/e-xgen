Ext.<?php echo $this->class2id($this->modelClass); ?>Grid=Ext.extend(Ext.grid.GridPanel ,{
xtype:"grid",
	title:"My Grid",
	width:400,
	height:250,
	columns:[
        <?php
        $count = 0;
        foreach ($this->tableSchema->columns as $column) {
                if (++$count == 7)
                        echo "\t\t/*\n";
                ?>
                {
			header:"Column 1",
			sortable:true,
			resizable:true,
                        <?echo "dataIndex:" . $this->generateGridViewColumn($this->modelClass, $column).",\n";?>			
			width:100
		},
                <?
                
        }
        if ($count >= 7)
                echo "\t\t*/\n";
        ?>
		
	],
	initComponent: function(){
		this.tbar=[
			
		]
		Ext.MyGrid.superclass.initComponent.call(this);
	}
})
