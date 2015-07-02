/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.define('MyDesktop.Trace', {
    extend: 'Ext.ux.desktop.Module',

    uses: [
        'Ext.tree.Panel',
        'Ext.tree.View',
        'Ext.layout.container.Anchor',
        'Ext.layout.container.Border',

        'MyDesktop.TraceLineModel'
    ],

    id:'trace-win',
	filename: null,

    createWindow : function(filename) {
		var me = this;
		me.filename = filename;
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('trace-win');
        if(!win) {

			me.tree = me.createTree();
			me.info = me.createInfo();

            win = desktop.createWindow({
                id: 'trace-win',
                title:'Trace',
                width:740,
                height:480,
                iconCls: 'icon-grid',
                animCollapse:false,
                constrainHeader:true,
                layout: 'border',
                items: [
					me.tree,
					me.info
                ]
            });
        }
        return win;
    },

	createInfo : function() {
		var me = this;

		var info = new Ext.panel.Panel({
			title: 'Info',
			region: 'center',
			layout: 'fit'
		});

		return info;
	},

    createTree : function() {
        var me = this;

        var tree = new Ext.tree.Panel({
            rootVisible: false,
            lines: true,
            autoScroll: true,
            width: 200,
            region: 'west',
            split: true,
            minWidth: 150,
            listeners: {
                afterrender: { fn: this.setInitialSelection, delay: 100 },
                select: this.onSelect,
                scope: this
            },
            store: new Ext.data.TreeStore({
                model: 'MyDesktop.TraceLineModel',
				proxy: {
					type: 'ajax',
					url: 'ajax/trace_get.php',
					extraParams: {
						filename: me.filename
					},
					reader: {
						type: 'json',
						root: 'children',
						idProperty: 'id'
					}
				},
				autoLoad: true
            })
        });

        return tree;
    },

    onSelect: function (tree, record) {
        var me = this;
		var info = record.data.info;
		var data = [];

		for(var k in info) {
			data.push({name: k, value: info[k]});
		}

		me.info.removeAll();
		var grid = Ext.create('Ext.grid.Panel', {
			store: {
				type: 'json',
				fields: [
				   { name: 'name', type: 'string' },
				   { name: 'value', type: 'string' }
				],
				data: data,
				autoLoad: true
			},
			columns: [
				new Ext.grid.RowNumberer(),
				{
					text: "Name",
					width: 140,
					dataIndex: 'name'
				},
				{
					text: "Value",
					flex: 1,
					dataIndex: 'value'
				}
			]
		});
		me.info.add(grid);
		if(info.type === 'assign' || info.type === 'call') {
			var desktop = this.app.getDesktop();
			var win = desktop.getWindow('projects-win');

			if(win) {
				win.jumpTo(info.file, info.line);
			}
		}
    },

    setInitialSelection: function () {
        var me = this;

    }
});
