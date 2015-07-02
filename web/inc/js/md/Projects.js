/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.define('MyDesktop.Projects', {
    extend: 'Ext.ux.desktop.Module',

    uses: [
        'Ext.tree.Panel',
        'Ext.tree.View',
        'Ext.layout.container.Anchor',
        'Ext.layout.container.Border',

        'MyDesktop.ProjectFileModel'
    ],

    id:'projects-win',
	line: 0,

    init : function(){
        this.launcher = {
            text: 'Projects',
            iconCls:'icon-grid'
        };
    },

    createWindow : function() {
		var me = this;
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('projects-win');
        if(!win) {

			me.tree = me.createTree();
			me.file = me.createFile();
			me.lines = me.createLines();
			me.tabs = me.createTabs();

            win = desktop.createWindow({
                id: 'projects-win',
                title:'Projects',
				jumpTo: function(file, line) {
					me.line = line;
					var record = me.tree.getStore().getNodeById(file);
					me.tree.getSelectionModel().select(record);
					var parent = record;

					while(parent.parentNode) {
						parent = parent.parentNode;
						me.tree.expandNode(parent);
					}

					me.tree.getView().focusNode(record);

					if('grid' in me) {
						me.grid.getSelectionModel().select(+line);
						me.grid.getView().focusRow(+line);
					}
				},
                width:740,
                height:480,
                iconCls: 'icon-grid',
                animCollapse:false,
                constrainHeader:true,
                layout: 'border',
                items: [
					me.tree,
					me.tabs
                ]
            });
        }
        return win;
    },

	createLines: function() {
		var me = this;

		var lines = new Ext.panel.Panel({
			title: 'Lines',
			region: 'center',
			layout: 'fit'
		});

		return lines;
	},

	createTabs: function() {
		var me = this;

		var tabs = new Ext.tab.Panel({
			region: 'center',
			layout: 'fit',
			items: [
				me.lines,
				me.file
			]
		});

		return tabs;
	},

	createFile : function() {
		var me = this;

		var file = new Ext.panel.Panel({
			title: 'Source',
			region: 'center',
			layout: 'fit',
			loader: {
				url: 'ajax/file_get.php',
				renderer: 'html',
				loadMask: true
			}
		});

		return file;
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
                model: 'MyDesktop.ProjectFileModel',
				proxy: {
					type: 'ajax',
					url: 'ajax/file_list.php',
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

		if(record.hasChildNodes()) {
			return;
		}

		me.file.getLoader().load({params: {filename: record.data.id}});
        var me = this;
		var lines = record.data.lines;

		me.lines.removeAll();
		me.grid = Ext.create('Ext.grid.Panel', {
			store: {
				type: 'json',
				fields: [
				   { name: 'line', type: 'int' },
				   { name: 'code', type: 'string' }
				],
				data: lines,
				autoLoad: true
			},
            listeners: {
                afterrender: { fn: this.setInitialLinesSelection, delay: 100 },
				scope: me
			},
			columns: [
				{
					text: "Line",
					width: 80,
					dataIndex: 'line'
				},
				{
					text: "Code",
					width: 140,
					flex: 1,
					renderer: function(str) { return "<pre style=\"margin:0;\">"+Ext.util.Format.htmlEncode(str)+"</pre>"; },
					dataIndex: 'code'
				}
			]
		});

		me.lines.add(me.grid);
    },

    setInitialLinesSelection: function () {
		this.grid.getSelectionModel().select(+this.line);
		this.grid.getView().focusRow(+this.line);
	},

    setInitialSelection: function () {
        var me = this;

    }
});
