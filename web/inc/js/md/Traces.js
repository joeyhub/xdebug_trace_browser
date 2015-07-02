/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.define('MyDesktop.Traces', {
    extend: 'Ext.ux.desktop.Module',

    requires: [
        'Ext.data.ArrayStore',
        'Ext.util.Format',
        'Ext.grid.Panel',
        'Ext.grid.RowNumberer'
    ],

    id:'traces-win',

    init : function(){
        this.launcher = {
            text: 'Traces',
            iconCls:'icon-grid'
        };
    },

    createWindow : function(){
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('traces-win');
        if(!win){
			this.grid = this.createGrid();
            win = desktop.createWindow({
                id: 'traces-win',
                title:'Traces',
                width:740,
                height:480,
                iconCls: 'icon-grid',
                animCollapse:false,
                constrainHeader:true,
                layout: 'fit',
                items: [
					this.grid
                ],
                tbar:[{
                    text:'Open Selected Trace',
                    tooltip:'Open Selected Trace File',
                    iconCls:'application_go',
					scope: this,
					handler: this.openTrace
                }]
            });
        }
        return win;
    },

	createGrid: function() {
		var grid = Ext.create('Ext.grid.Panel', {
			border: false,
			store: new Ext.data.ArrayStore({
				proxy: {
					type: 'ajax',
					url: 'ajax/trace_list.php',
					reader: {
						type: 'json',
						root: 'traces',
						idProperty: 'name'
					}
				},
				fields: [
				   { name: 'name', type: 'string' },
				   { name: 'mtime', type: 'string' },
				   { name: 'size', type: 'int' }
				],
				'autoLoad': true
			}),
			columns: [
				new Ext.grid.RowNumberer(),
				{
					text: "Name",
					flex: 1,
					sortable: true,
					dataIndex: 'name'
				},
				{
					text: "Last Modified",
					width: 140,
					sortable: true,
					renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s'),
					dataIndex: 'mtime'
				},
				{
					text: "Size",
					width: 140,
					sortable: true,
					renderer: Ext.util.Format.fileSize,
					dataIndex: 'size'
				}
			]
		});
		return grid;
	},

	openTrace: function() {
		var sm = this.grid.getSelectionModel();
		var sel = sm.getSelection();

		if(!sel.length) {
			return;
		}

		var filename = sel[0].getData().name;
        var module = this.app.getModule('trace-win'),
            window;

        if (module) {
            window = module.createWindow(filename);
            window.show();
        }
	}
});

