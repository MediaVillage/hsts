(function($){

    /**
     * Menu Item Settings
     */
    var MenuItem = Backbone.Model.extend({
        url: ajaxurl,
        defaults: {
            'id': 0,
            'title': '',
            'menu_id': '',
            'depth': 0,
            'columns': 3
        },
        save: function() {
            var $params = {
                emulateJSON: true, 
                data: { 
                    action: 'rf_menu_item_settings', 
                    data : this.toJSON() 
                } 
            };
            return Backbone.sync( 'create', this, $params );
        }
    });


    /**
     * Menu Item Model
     */
    var Widget = Backbone.Model.extend({
        defaults: {
            'ID': '',
            'title': '',
            'menu_item_parent': ''
        }
    });

    /**
     * The child menu items
     */
    var Widgets = Backbone.Collection.extend({
        model: Widget,
        url: ajaxurl,
        parse: function(response) {
            _.each(response, (element) => {
                if ( _.has(element, 'children') ) {
                    var children = Object.keys(element.children).map((key) => { return element.children[key]; });
                    element.children = new Widgets(children, {
                        parse: true
                    });
                }
            });
            return response;
        }
    });


    /**
     * Individual Menu Item View
     */
    var WidgetView = Backbone.View.extend({
        tagName: 'div',
        //className: 'widget',
        attributes: {
            "class": 'widget',
            "data-columns": "1"
        },
        template: require('../templates/menu-item.template.html'),
        events: {

        },
        initialize: function() {
            this.columns = 1;
        },
        render: function() {
            this.$el.html( this.template( this.model.toJSON() ));
            return this;
        }
    });


    /**
     * Menu Item Group View
     */
    var WidgetListView = Backbone.View.extend({
        initialize: function() {

        }
    });

    /**
     * Menu item settings
     */
    var RFMenuItemSettings = Backbone.View.extend({
        className: 'rf-menu-settings-overlay',
        template: require('../templates/menu-item-settings.template.html'),
        events: {
            'click': 'close',
            'click .menu-settings-close': 'close',
            'change select[name="num_columns"]': 'changeColumns'
        },
        initialize: function() {
            this.widgets = new Widgets();
            this.listenTo(this.widgets, 'add', this.addOne);

            // Render the view
            this.render();

            // Fetch the menu items
            this.widgets.fetch({
                data: {
                    action: 'rf_menu_items',
                    menu_id: this.model.get('menu_id'),
                    menu_item_id: this.model.get('id')
                }
            })
        },
        render: function() {
            this.$el.html( this.template( this.model.toJSON() ) );

            this.$el.find('.widgets').sortable();

            return this;
        },
        addOne: function(model) {
            var view = new WidgetView({model: model});
            this.$el.find('.widgets').append(view.render().el);
        },
        changeColumns: function(e) {
            var columns = $(e.target).val();

            // this.model.save({columns: columns});

            this.$el.find('.widgets').removeClass((index, className) => {
                return (className.match(/columns\-/) || []).join(' ');
            });
        },
        close: function(e) {
            if(e.target !== e.currentTarget) return;
            this.remove();
        }
    });

    /**
     * Setup the admin menu
     */
    var RFAdminMenu = Backbone.View.extend({
        el: '#menu-to-edit',
        initialize: function() {
            this.$el.children().each(this.setupButtons);
        },
        setupButtons: function() {
            var menu_item = $(this);
            var menu_id = $('input#menu').val();
            var title = menu_item.find('.menu-item-title').text();
            var id = parseInt(menu_item.attr('id').match(/[0-9]+/)[0], 10);

            var button = $("<span>").addClass("button button-small")
                .html('Settings')
                .on('click', function(e) {
                    e.preventDefault();
                    var depth = menu_item.attr('class').match(/\menu-item-depth-(\d+)\b/)[1];

                    var model = new MenuItem({id, title, depth, menu_id});
                    var view = new RFMenuItemSettings({model: model});
                    $('body').append(view.render().el);
                });
            $('.item-title', menu_item).append(button);
        }
    });

    // Setup admin functionality for menu
    $(function(){
        new RFAdminMenu();
    });
})(jQuery);