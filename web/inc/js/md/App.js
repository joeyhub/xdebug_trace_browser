/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.define('MyDesktop.App', {
    extend: 'Ext.ux.desktop.App',

    requires: [
        'Ext.window.MessageBox',

        'Ext.ux.desktop.ShortcutModel',

        'MyDesktop.Traces',
        'MyDesktop.Settings',
        'MyDesktop.Trace',
        'MyDesktop.Projects'
    ],

    init: function() {
        this.callParent();
    },

    getModules : function(){
        return [
            new MyDesktop.Traces(),
            new MyDesktop.Projects(),
            new MyDesktop.Trace()
        ];
    },

    getDesktopConfig: function () {
        var me = this, ret = me.callParent();

        return Ext.apply(ret, {
            contextMenuItems: [
                { text: 'Change Settings', handler: me.onSettings, scope: me }
            ],

            shortcuts: Ext.create('Ext.data.Store', {
                model: 'Ext.ux.desktop.ShortcutModel',
                data: [
                    { name: 'Traces', iconCls: 'grid-shortcut', module: 'traces-win' },
                    { name: 'Projects', iconCls: 'grid-shortcut', module: 'projects-win' }
                ]
            }),

            wallpaper: 'inc/custom/bug.jpg',
            wallpaperStretch: true
        });
    },

    // config for the start menu
    getStartConfig : function() {
        var me = this, ret = me.callParent();

        return Ext.apply(ret, {
            title: 'Debug Log Interface',
            iconCls: 'user-suit',
            height: 300,
            toolConfig: {
                width: 100,
                items: [
                    {
                        text:'Settings',
                        iconCls:'settings',
                        handler: me.onSettings,
                        scope: me
                    }
                ]
            }
        });
    },

    getTaskbarConfig: function () {
        var ret = this.callParent();

        return Ext.apply(ret, {
            quickStart: [
                { name: 'Traces', iconCls: 'icon-grid', module: 'traces-win' },
                { name: 'Projects', iconCls: 'icon-grid', module: 'projects-win' }
            ],
            trayItems: [
                { xtype: 'trayclock', flex: 1 }
            ]
        });
    },

    onTrace: function () {
        var dlg = new MyDesktop.Trace({
            desktop: this.desktop
        });
        dlg.show();
    },

    onProjects: function () {
        var dlg = new MyDesktop.Projects({
            desktop: this.desktop
        });
        dlg.show();
    },

    onSettings: function () {
        var dlg = new MyDesktop.Settings({
            desktop: this.desktop
        });
        dlg.show();
    }
});
