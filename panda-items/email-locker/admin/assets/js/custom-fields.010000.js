if ( !window.bizpanda ) window.bizpanda = {};

(function($){
    
    // -----------------------------------------------------------
    // Fields Editor
    // -----------------------------------------------------------
    
    $.widget( "opanda.fieldsEditor", {

        /**
         * List of available controls
         */
        _typesList: [
            {name: 'text', title: 'Text', editorName: 'text'},
            {name: 'email', title: 'Email', editorName: 'text', placeholder: window.bizpanda.res['email-field-placeholder']},
            {name: 'phone', title: 'Phone', editorName: 'text', placeholder: window.bizpanda.res['phone-field-placeholder']},
            {name: 'url', title: 'Website/URL', editorName: 'text', placeholder: 'http://'},
            {name: 'birthday', title: 'Birthday', editorName: 'text', placeholder: window.bizpanda.res['birthday-field-placeholder']}, 
            {name: 'integer', title: 'Integer', editorName: 'integer'},
            {name: 'dropdown', title: 'Dropdown', editorName: 'dropdown'},
            {name: 'checkbox', title: 'Checkbox', editorName: 'checkbox'},
            {name: 'date', title: 'Date', editorName: 'text'},
            {name: 'unsupported', title: window.bizpanda.res['unsupported'], hide: true},
            {name: 'separator', title: 'Separator', editorName: 'separator', helper: true, hide: true },
            {name: 'label', title: 'Label', editorName: 'label', helper: true, hide: true },
            {name: 'html', title: 'Custom HTML', editorName: 'html', helper: true, hide: true }
        ],
                
        /**
         * Font Awesome Icons
         */
        _fontAwesomeIcons: {
            "fa-glass":"&#xf000;","fa-music":"&#xf001;","fa-search":"&#xf002;",
            "fa-envelope-o":"&#xf003;","fa-heart":"&#xf004;","fa-star":"&#xf005;",
            "fa-star-o":"&#xf006;","fa-user":"&#xf007;","fa-film":"&#xf008;",
            "fa-th-large":"&#xf009;","fa-th":"&#xf00a;","fa-th-list":"&#xf00b;",
            "fa-check":"&#xf00c;","fa-times":"&#xf00d;","fa-search-plus":"&#xf00e;",
            "fa-search-minus":"&#xf010;","fa-power-off":"&#xf011;","fa-signal":"&#xf012;",
            "fa-cog":"&#xf013;","fa-trash-o":"&#xf014;","fa-home":"&#xf015;",
            "fa-file-o":"&#xf016;","fa-clock-o":"&#xf017;","fa-road":"&#xf018;",
            "fa-download":"&#xf019;","fa-arrow-circle-o-down":"&#xf01a;",
            "fa-arrow-circle-o-up":"&#xf01b;","fa-inbox":"&#xf01c;",
            "fa-play-circle-o":"&#xf01d;","fa-repeat":"&#xf01e;","fa-refresh":"&#xf021;",
            "fa-list-alt":"&#xf022;","fa-lock":"&#xf023;","fa-flag":"&#xf024;",
            "fa-headphones":"&#xf025;","fa-volume-off":"&#xf026;",
            "fa-volume-down":"&#xf027;","fa-volume-up":"&#xf028;","fa-qrcode":"&#xf029;",
            "fa-barcode":"&#xf02a;","fa-tag":"&#xf02b;","fa-tags":"&#xf02c;",
            "fa-book":"&#xf02d;","fa-bookmark":"&#xf02e;","fa-print":"&#xf02f;",
            "fa-camera":"&#xf030;","fa-font":"&#xf031;","fa-bold":"&#xf032;",
            "fa-italic":"&#xf033;","fa-text-height":"&#xf034;","fa-text-width":"&#xf035;",
            "fa-align-left":"&#xf036;","fa-align-center":"&#xf037;",
            "fa-align-right":"&#xf038;","fa-align-justify":"&#xf039;","fa-list":"&#xf03a;",
            "fa-outdent":"&#xf03b;","fa-indent":"&#xf03c;","fa-video-camera":"&#xf03d;",
            "fa-picture-o":"&#xf03e;","fa-pencil":"&#xf040;","fa-map-marker":"&#xf041;",
            "fa-adjust":"&#xf042;","fa-tint":"&#xf043;","fa-pencil-square-o":"&#xf044;",
            "fa-share-square-o":"&#xf045;","fa-check-square-o":"&#xf046;",
            "fa-arrows":"&#xf047;","fa-step-backward":"&#xf048;","fa-fast-backward":"&#xf049;",
            "fa-backward":"&#xf04a;","fa-play":"&#xf04b;","fa-pause":"&#xf04c;",
            "fa-stop":"&#xf04d;","fa-forward":"&#xf04e;","fa-fast-forward":"&#xf050;",
            "fa-step-forward":"&#xf051;","fa-eject":"&#xf052;","fa-chevron-left":"&#xf053;",
            "fa-chevron-right":"&#xf054;","fa-plus-circle":"&#xf055;",
            "fa-minus-circle":"&#xf056;","fa-times-circle":"&#xf057;",
            "fa-check-circle":"&#xf058;","fa-question-circle":"&#xf059;",
            "fa-info-circle":"&#xf05a;","fa-crosshairs":"&#xf05b;",
            "fa-times-circle-o":"&#xf05c;","fa-check-circle-o":"&#xf05d;",
            "fa-ban":"&#xf05e;","fa-arrow-left":"&#xf060;","fa-arrow-right":"&#xf061;",
            "fa-arrow-up":"&#xf062;","fa-arrow-down":"&#xf063;","fa-share":"&#xf064;",
            "fa-expand":"&#xf065;","fa-compress":"&#xf066;","fa-plus":"&#xf067;",
            "fa-minus":"&#xf068;","fa-asterisk":"&#xf069;","fa-exclamation-circle":"&#xf06a;",
            "fa-gift":"&#xf06b;","fa-leaf":"&#xf06c;","fa-fire":"&#xf06d;",
            "fa-eye":"&#xf06e;","fa-eye-slash":"&#xf070;","fa-exclamation-triangle":"&#xf071;",
            "fa-plane":"&#xf072;","fa-calendar":"&#xf073;","fa-random":"&#xf074;",
            "fa-comment":"&#xf075;","fa-magnet":"&#xf076;","fa-chevron-up":"&#xf077;",
            "fa-chevron-down":"&#xf078;","fa-retweet":"&#xf079;","fa-shopping-cart":"&#xf07a;",
            "fa-folder":"&#xf07b;","fa-folder-open":"&#xf07c;","fa-arrows-v":"&#xf07d;",
            "fa-arrows-h":"&#xf07e;","fa-bar-chart-o":"&#xf080;","fa-twitter-square":"&#xf081;",
            "fa-facebook-square":"&#xf082;","fa-camera-retro":"&#xf083;","fa-key":"&#xf084;",
            "fa-cogs":"&#xf085;","fa-comments":"&#xf086;","fa-thumbs-o-up":"&#xf087;",
            "fa-thumbs-o-down":"&#xf088;","fa-star-half":"&#xf089;","fa-heart-o":"&#xf08a;",
            "fa-sign-out":"&#xf08b;","fa-linkedin-square":"&#xf08c;","fa-thumb-tack":"&#xf08d;",
            "fa-external-link":"&#xf08e;","fa-sign-in":"&#xf090;","fa-trophy":"&#xf091;",
            "fa-github-square":"&#xf092;","fa-upload":"&#xf093;","fa-lemon-o":"&#xf094;",
            "fa-phone":"&#xf095;","fa-square-o":"&#xf096;","fa-bookmark-o":"&#xf097;",
            "fa-phone-square":"&#xf098;","fa-twitter":"&#xf099;","fa-facebook":"&#xf09a;",
            "fa-github":"&#xf09b;","fa-unlock":"&#xf09c;","fa-credit-card":"&#xf09d;",
            "fa-rss":"&#xf09e;","fa-hdd-o":"&#xf0a0;","fa-bullhorn":"&#xf0a1;","fa-bell":"&#xf0f3;",
            "fa-certificate":"&#xf0a3;","fa-hand-o-right":"&#xf0a4;","fa-hand-o-left":"&#xf0a5;",
            "fa-hand-o-up":"&#xf0a6;","fa-hand-o-down":"&#xf0a7;","fa-arrow-circle-left":"&#xf0a8;",
            "fa-arrow-circle-right":"&#xf0a9;","fa-arrow-circle-up":"&#xf0aa;",
            "fa-arrow-circle-down":"&#xf0ab;","fa-globe":"&#xf0ac;","fa-wrench":"&#xf0ad;",
            "fa-tasks":"&#xf0ae;","fa-filter":"&#xf0b0;","fa-briefcase":"&#xf0b1;",
            "fa-arrows-alt":"&#xf0b2;","fa-users":"&#xf0c0;","fa-link":"&#xf0c1;",
            "fa-cloud":"&#xf0c2;","fa-flask":"&#xf0c3;","fa-scissors":"&#xf0c4;",
            "fa-files-o":"&#xf0c5;","fa-paperclip":"&#xf0c6;","fa-floppy-o":"&#xf0c7;",
            "fa-square":"&#xf0c8;","fa-bars":"&#xf0c9;","fa-list-ul":"&#xf0ca;",
            "fa-list-ol":"&#xf0cb;","fa-strikethrough":"&#xf0cc;","fa-underline":"&#xf0cd;",
            "fa-table":"&#xf0ce;","fa-magic":"&#xf0d0;","fa-truck":"&#xf0d1;",
            "fa-pinterest":"&#xf0d2;","fa-pinterest-square":"&#xf0d3;",
            "fa-google-plus-square":"&#xf0d4;","fa-google-plus":"&#xf0d5;","fa-money":"&#xf0d6;",
            "fa-caret-down":"&#xf0d7;","fa-caret-up":"&#xf0d8;",
            "fa-caret-left":"&#xf0d9;","fa-caret-right":"&#xf0da;","fa-columns":"&#xf0db;",
            "fa-sort":"&#xf0dc;","fa-sort-asc":"&#xf0dd;","fa-sort-desc":"&#xf0de;",
            "fa-envelope":"&#xf0e0;","fa-linkedin":"&#xf0e1;","fa-undo":"&#xf0e2;",
            "fa-gavel":"&#xf0e3;","fa-tachometer":"&#xf0e4;","fa-comment-o":"&#xf0e5;",
            "fa-comments-o":"&#xf0e6;","fa-bolt":"&#xf0e7;","fa-sitemap":"&#xf0e8;",
            "fa-umbrella":"&#xf0e9;","fa-clipboard":"&#xf0ea;","fa-lightbulb-o":"&#xf0eb;",
            "fa-exchange":"&#xf0ec;","fa-cloud-download":"&#xf0ed;","fa-cloud-upload":"&#xf0ee;",
            "fa-user-md":"&#xf0f0;","fa-stethoscope":"&#xf0f1;","fa-suitcase":"&#xf0f2;",
            "fa-bell-o":"&#xf0a2;","fa-coffee":"&#xf0f4;","fa-cutlery":"&#xf0f5;",
            "fa-file-text-o":"&#xf0f6;","fa-building-o":"&#xf0f7;","fa-hospital-o":"&#xf0f8;",
            "fa-ambulance":"&#xf0f9;","fa-medkit":"&#xf0fa;","fa-fighter-jet":"&#xf0fb;",
            "fa-beer":"&#xf0fc;","fa-h-square":"&#xf0fd;","fa-plus-square":"&#xf0fe;",
            "fa-angle-double-left":"&#xf100;","fa-angle-double-right":"&#xf101;",
            "fa-angle-double-up":"&#xf102;","fa-angle-double-down":"&#xf103;",
            "fa-angle-left":"&#xf104;","fa-angle-right":"&#xf105;","fa-angle-up":"&#xf106;",
            "fa-angle-down":"&#xf107;","fa-desktop":"&#xf108;","fa-laptop":"&#xf109;",
            "fa-tablet":"&#xf10a;","fa-mobile":"&#xf10b;","fa-circle-o":"&#xf10c;",
            "fa-quote-left":"&#xf10d;","fa-quote-right":"&#xf10e;","fa-spinner":"&#xf110;",
            "fa-circle":"&#xf111;","fa-reply":"&#xf112;","fa-github-alt":"&#xf113;",
            "fa-folder-o":"&#xf114;","fa-folder-open-o":"&#xf115;","fa-smile-o":"&#xf118;",
            "fa-frown-o":"&#xf119;","fa-meh-o":"&#xf11a;","fa-gamepad":"&#xf11b;",
            "fa-keyboard-o":"&#xf11c;","fa-flag-o":"&#xf11d;","fa-flag-checkered":"&#xf11e;",
            "fa-terminal":"&#xf120;","fa-code":"&#xf121;","fa-reply-all":"&#xf122;",
            "fa-mail-reply-all":"&#xf122;","fa-star-half-o":"&#xf123;",
            "fa-location-arrow":"&#xf124;","fa-crop":"&#xf125;","fa-code-fork":"&#xf126;",
            "fa-chain-broken":"&#xf127;","fa-question":"&#xf128;","fa-info":"&#xf129;",
            "fa-exclamation":"&#xf12a;","fa-superscript":"&#xf12b;","fa-subscript":"&#xf12c;",
            "fa-eraser":"&#xf12d;","fa-puzzle-piece":"&#xf12e;","fa-microphone":"&#xf130;",
            "fa-microphone-slash":"&#xf131;","fa-shield":"&#xf132;","fa-calendar-o":"&#xf133;",
            "fa-fire-extinguisher":"&#xf134;","fa-rocket":"&#xf135;","fa-maxcdn":"&#xf136;",
            "fa-chevron-circle-left":"&#xf137;","fa-chevron-circle-right":"&#xf138;",
            "fa-chevron-circle-up":"&#xf139;","fa-chevron-circle-down":"&#xf13a;",
            "fa-html5":"&#xf13b;","fa-css3":"&#xf13c;","fa-anchor":"&#xf13d;",
            "fa-unlock-alt":"&#xf13e;","fa-bullseye":"&#xf140;","fa-ellipsis-h":"&#xf141;",
            "fa-ellipsis-v":"&#xf142;","fa-rss-square":"&#xf143;","fa-play-circle":"&#xf144;",
            "fa-ticket":"&#xf145;","fa-minus-square":"&#xf146;","fa-minus-square-o":"&#xf147;",
            "fa-level-up":"&#xf148;","fa-level-down":"&#xf149;","fa-check-square":"&#xf14a;",
            "fa-pencil-square":"&#xf14b;","fa-external-link-square":"&#xf14c;",
            "fa-share-square":"&#xf14d;","fa-compass":"&#xf14e;","fa-caret-square-o-down":"&#xf150;",
            "fa-caret-square-o-up":"&#xf151;","fa-caret-square-o-right":"&#xf152;",
            "fa-eur":"&#xf153;","fa-gbp":"&#xf154;","fa-usd":"&#xf155;","fa-inr":"&#xf156;",
            "fa-jpy":"&#xf157;","fa-rub":"&#xf158;","fa-krw":"&#xf159;","fa-btc":"&#xf15a;",
            "fa-file":"&#xf15b;","fa-file-text":"&#xf15c;","fa-sort-alpha-asc":"&#xf15d;",
            "fa-sort-alpha-desc":"&#xf15e;","fa-sort-amount-asc":"&#xf160;",
            "fa-sort-amount-desc":"&#xf161;","fa-sort-numeric-asc":"&#xf162;",
            "fa-sort-numeric-desc":"&#xf163;","fa-thumbs-up":"&#xf164;","fa-thumbs-down":"&#xf165;",
            "fa-youtube-square":"&#xf166;","fa-youtube":"&#xf167;","fa-xing":"&#xf168;",
            "fa-xing-square":"&#xf169;","fa-youtube-play":"&#xf16a;","fa-dropbox":"&#xf16b;",
            "fa-stack-overflow":"&#xf16c;","fa-instagram":"&#xf16d;","fa-flickr":"&#xf16e;",
            "fa-adn":"&#xf170;","fa-bitbucket":"&#xf171;","fa-bitbucket-square":"&#xf172;",
            "fa-tumblr":"&#xf173;","fa-tumblr-square":"&#xf174;","fa-long-arrow-down":"&#xf175;",
            "fa-long-arrow-up":"&#xf176;","fa-long-arrow-left":"&#xf177;",
            "fa-long-arrow-right":"&#xf178;","fa-apple":"&#xf179;","fa-windows":"&#xf17a;",
            "fa-android":"&#xf17b;","fa-linux":"&#xf17c;","fa-dribbble":"&#xf17d;",
            "fa-skype":"&#xf17e;","fa-foursquare":"&#xf180;","fa-trello":"&#xf181;",
            "fa-female":"&#xf182;","fa-male":"&#xf183;","fa-gittip":"&#xf184;",
            "fa-sun-o":"&#xf185;","fa-moon-o":"&#xf186;","fa-archive":"&#xf187;","fa-bug":"&#xf188;",
            "fa-vk":"&#xf189;","fa-weibo":"&#xf18a;","fa-renren":"&#xf18b;","fa-pagelines":"&#xf18c;",
            "fa-stack-exchange":"&#xf18d;","fa-arrow-circle-o-right":"&#xf18e;",
            "fa-arrow-circle-o-left":"&#xf190;","fa-caret-square-o-left":"&#xf191;","fa-dot-circle-o":"&#xf192;",
            "fa-wheelchair":"&#xf193;","fa-vimeo-square":"&#xf194;","fa-try":"&#xf195;","fa-plus-square-o":"&#xf196;"
        },
                
        /**
         * Creates a new widget of type Fields Editor
         */
        _create: function() {
            var self = this;
            
            // the main editor element
            this._$fieldsEditor = this.element;
            
            // an element to show any errors
            this._$error = this.element.find(".opanda-error");
            
            // a template for creating new fields
            this._$fieldTemplate = this._$fieldsEditor.find(".opanda-template").remove();
            
            // an element where to attach new created fields to
            this._$fieldHolder = this._$fieldsEditor.find(".table tbody"); 
            
            // a hidden input to save results / load results saved last time
            this._$result = $(this.options.result); 
            
            this._$btnAddField = this._$fieldsEditor.find(".opanda-add-field"); 
            
            // saves names of available field types as an array
            this._typesNames = [];
            for( var i in this._typesList ) {
                this._typesNames.push( this._typesList[i].name );
            }
            
            // makes fields shortable

            this._$fieldHolder.addClass("ui-sortable");
            this._$fieldHolder.sortable({
                placeholder: "sortable-placeholder",
                opacity: 0.7,
                handle: ".opanda-drag",
                items: "> .opanda-item",
                update: function() {
                    self.saveFields();
                }
            });

            // fills up font awesome icons

            var iconOptions = [];
            iconOptions.push({ value: '', title: ' '});

            for( var iconName in this._fontAwesomeIcons ) {
                iconOptions.push({
                    value: iconName,
                    title: "&nbsp;" + self._fontAwesomeIcons[iconName]
                });
            }
            var $iconSelectors = this._$fieldsEditor.add(this._$fieldTemplate).find(".opanda-icon-input");
            this.fillSelects( $iconSelectors, iconOptions );
            
            this._isLoading = true;

            // loads values saved last time
            this.loadSaved();
            
            // sets all fields to the loading state until the editor
            // receive information about available custom fields
            
            var $items = this._$fieldsEditor.find(".opanda-item");
            $items.addClass('opanda-loading');
            
            var $selectors = this.getLazySelectors();
            this.lockSelects( $selectors, "[ - loading - ]" );
               
            this.initColumns();
            this.initHints();
            
            // saves values on chaning on any of input elements

            this._$fieldsEditor.on("change", ".opanda-choices-editor, input, select, textarea", function(){
                self.saveFields();
            });
        },
        
        /**
         * Inits all columns.
         * @returns {undefined}
         */
        initColumns: function() {

            this.initMappingColumn();
            this.initFieldTypeColumn();
            this.initControlColumn();
        },

        /**
         * Returns true if this field row is obligate.
         */
        isObligate: function ( $fieldRow ) {
            return $fieldRow.is(".opanda-obligate");
        },

        /**
         * Returns true if this field row is email field.
         */
        isEmailField: function( $fieldRow ) {
            return $fieldRow.is(".opanda-email");                
        },

        /**
         * Returns true if this field row is loading.
         */
        isLoading: function( $fieldRow ) {
            return this._isLoading || $fieldRow.hasClass("opanda-loading");  
        },

        /**
         * Returns all the 'select' elements in the editor.
         * @returns {undefined}
         */
        getLazySelectors: function() {
            var $selectors = this._$fieldsEditor.add(this._$fieldTemplate).find(".opanda-lazy-select"); 
            return $selectors;
        },

        // --------------------------------------------------------
        // Column :: Mapping 
        // --------------------------------------------------------

        /**
         * Inits mapping fields and binds events.
         */
        initMappingColumn: function() {
            var self = this;
            
            var $list = $("#opanda_subscribe_list");

            // refresh mapping fields on initing or chaning the list
            if( $list.length > 0 && $list.is("select") ) {

                $("#opanda_subscribe_list").bind("factory-loaded change", function(){
                    
                    self._$fieldsEditor
                        .find('.opanda-item')
                        .find('.opanda-popup-hint-icon, .opanda-popup-hint').remove();
                    
                    self.refreshMappingSelectors();
                }); 
                
            } else {
                self.refreshMappingSelectors();
            }

            // updates other fields in the row on selecting another custom field to map
            $(this._$fieldsEditor).on("change", ".opanda-mapping-input", function(){
                var $fieldRow = $(this).parents(".opanda-item");
                self.onUpdatingMappingSelector( $fieldRow, true );
            });
        },

        /**
         * Refreshes selectors allowing to select a custom field to map.
         */
        refreshMappingSelectors: function( listId ) {
            var self = this;

            var setErrorStatus = function(){
                var $selectors = self.getLazySelectors();
                self.lockSelects( $selectors, window.bizpanda.res['error-state'] );
            };

            var def = self.asyncGetMappingFields( listId );

            def.done(function(data){

                if ( !data || data.error ) {
                    setErrorStatus();
                    return self.showError( ( data && data.error ) || window.bizpanda.res['unexpected-error'] );
                }
                
                self._mappingData = data;

                var $items = self._$fieldsEditor.find(".opanda-item");
                $items.removeClass('opanda-loading');
                self._isLoading = false;
                
                self.fillMappingSelectors(data);
            });

            def.fail(function(){
                setErrorStatus();
            });
        },

        /**
         * Finds and returns mapping selectors. 
         */
        getMappingSelectors: function() {
            var $selectors = this._$fieldsEditor.add(this._$fieldTemplate).find(".opanda-mapping-input");
            return $selectors;
        },

        /**
         * Sets available mapping fields.
         */
        fillMappingSelectors: function( data ) {
            var self = this;

            var $selectors = this.getMappingSelectors();
            $selectors.each(function(){
                var options = [];

                var $fieldRow = $(this).parents(".opanda-item");
                if ( self.isEmailField( $fieldRow ) ) {

                    options.push({
                        value: 'email',
                        title: 'Email', 
                        hint:  window.bizpanda.res['email-field-hint'],
                        selected: true
                    });

                } else {

                    var subOptions = [];

                    var currentValue = $(this).data('value');
                    
                    for ( var i in data ) {
                        
                        if ( !data[i].mapOptions ) continue;

                        subOptions.push({
                            value: data[i].mapOptions.id,
                            title: data[i].mapOptions.title,
                            data: data[i],
                            selected: data[i].mapOptions.id === currentValue
                        });
                    }

                    options.push({
                        title: window.bizpanda.res['service-title'],
                        items: subOptions
                    });

                    options.push({
                        title: 'Smart Fields',
                        items: [{
                            value: 'fullname', 
                            title: 'Full Name', 
                            hint: window.bizpanda.res['fullname-field-hint'], 
                            data: {
                                id: 'fullname',
                                req: true,
                                placeholder: window.bizpanda.res['fullname-field-placeholder']
                            }
                        }]
                    });
                    
                    options.push({
                        title: 'Others',
                        items: [{
                            value: 'separator', 
                            title: 'Separator'
                        },{
                            value: 'label', 
                            title: 'Text Label'
                        },{
                            value: 'html', 
                            title: 'Custom HTML'
                        }]
                    });     
                }

                self.fillSelects( $(this), options );

                var $fieldRow = $(this).parents(".opanda-item");
                self.onUpdatingMappingSelector( $fieldRow );
            });

            this.adjustMappingSelectorsWidths();
            this.saveFields();
        },

        onUpdatingMappingSelector: function( $fieldRow, onChange ) {
            
            var $mappingSelect = $fieldRow.find(".opanda-mapping-input");
            $fieldRow.find('.opanda-type .opanda-popup-hint-icon, .opanda-type .opanda-popup-hint').remove();
            
            var value = $mappingSelect.val();
            var mappingData = this.getMappingData( $fieldRow );

            var fieldOptions = $.extend( true, {}, ( mappingData && mappingData.fieldOptions ) || {} );
            var mapOptions =  $.extend( true, {}, ( mappingData && mappingData.mapOptions ) || {} );       
            var premissions =  $.extend( true, {}, ( mappingData && mappingData.premissions ) || {} );

            if ( this.isEmailField( $fieldRow ) ) {

                fieldOptions    = { req: true, type: 'email' };
                mapOptions      = { mapTo: 'email' }; 
                premissions     = { can: {changeType: true, changeReq: false} };
                
            } else if ( value === 'fullname' ) {

                fieldOptions    = { req: true, type: 'text', placeholder: window.bizpanda.res['fullname-field-placeholder'] };
                mapOptions      = { mapTo: 'text', id: "fullname", labelTitle: 'Full Name' }; 
                premissions     = { can: {changeType: true, changeReq: false} };
                
            } else if ( value === 'separator' ) {
                
                fieldOptions    = { req: false, type: 'separator' };
                mapOptions      = { mapTo: 'separator', id: "separator" }; 
                premissions     = { can: {changeType: true, changeReq: false} };
                
            } else if ( value === 'label' ) {
                
                fieldOptions    = { req: false, type: 'label' };
                mapOptions      = { mapTo: 'label', id: "label" }; 
                premissions     = { can: {changeType: true, changeReq: false} };
                
            } else if  ( value === 'html' ) {
                fieldOptions    = { req: false, type: 'html' };
                mapOptions      = { mapTo: 'html', id: "html" }; 
                premissions     = { can: {changeType: true, changeReq: false} };
            }
            
            // forces to replace the choices for dropdown list
            
          /*  if ( fieldOptions.choices ) {
                var currentFieldOptions = this.getData( $fieldRow, 'fieldOptions' );
                currentFieldOptions.choices = fieldOptions.choices;
                this.setData( $fieldRow, 'fieldOptions', currentFieldOptions );
            }

            if ( typeof premissions['can']['changeReq'] !== 'underfined' && !premissions['can']['changeReq'] ) {
                var currentFieldOptions = this.getData( $fieldRow, 'fieldOptions' );
                currentFieldOptions.req = fieldOptions.req;
                this.setData( $fieldRow, 'fieldOptions', currentFieldOptions );
            }   */

            // determines which field options the mapping input rewrites,
            // when we change the mapping input value, we will rollback changes

            //var curr = this.getData( $fieldRow, 'fieldOptions' );
            // console.log($.extend({}, curr));
            
            var previosDiff = this.getData( $fieldRow, 'mapDiffOptions' );

            if ( previosDiff ) this.addData( $fieldRow, 'fieldOptions', previosDiff, true );

            var diff = this.addData( $fieldRow, 'fieldOptions', fieldOptions, true );
            this.setData( $fieldRow, 'mapDiffOptions', diff );

            this.setData( $fieldRow, 'mapOptions', mapOptions );
            this.setData( $fieldRow, 'permissions', premissions );

            // chaning the title of the field if it was not defined

            if ( onChange ) {
                
                var fieldTitleChanged = $fieldRow.data('titleChanged');
                var currentValue = $fieldRow.find(".opanda-label-input").val();
                
                if ( fieldTitleChanged && fieldTitleChanged === currentValue ) {
                    $fieldRow.find(".opanda-label-input").val("");
                    $fieldRow.data('titleChanged', false);
                }

                var currentValue = $fieldRow.find(".opanda-label-input").val();
                if ( !currentValue && mapOptions.labelTitle )  {
                    $fieldRow.find(".opanda-label-input").val( mapOptions.labelTitle );
                    $fieldRow.data('titleChanged', mapOptions.labelTitle);
                }
                
                $fieldRow.find(".opanda-label-input").change();
            }

            this.refreshTypeSelector( $fieldRow );
            this.refreshRequiredCheckbox( $fieldRow );
        },
        
        /**
         * Makes all the mapping files the same size.
         */
        adjustMappingSelectorsWidths: function() {
            var $selectors = this.getMappingSelectors();
            $selectors.css('width', 'auto');

            if ( $selectors.length <= 2 ) return;
            
            var maxWidth = 0;
            $selectors.each(function(){
                var width = $(this).width();
                if ( maxWidth < width ) maxWidth = width;
            });

            $selectors.css('width', maxWidth + 10 + 'px');
        },

        /**
         * Recieves custom fields to map from the current email provider.
         */
        asyncGetMappingFields: function( listId ) {
           listId = listId || $("#opanda_subscribe_list").val();
           var self = this;

           var $selectors = this.getMappingSelectors();
           this._isLoading = true;
           
           var req = $.ajax({
               type: 'POST', 
               url: window.ajaxurl,
               dataType: 'json',
               data: {
                   action: 'opanda_get_custom_fields',
                   opanda_list_id: listId
               },
               error: function() {
                   self.showError( window.bizpanda.res['unexpected-error'] );
                   console.log( "Unexpected error occured during the ajax request." );
                   console.log(req.responseText);
               } 
           });

           this.lockSelects( $selectors, window.bizpanda.res['loading-state'], req );
           
           return req;
        },
            
        // --------------------------------------------------------
        // Column :: Field Type 
        // --------------------------------------------------------
        
        initFieldTypeColumn: function() {
            var self = this;

            // refresh the options editor if it's active

            this._$fieldsEditor.on("change apichange", ".opanda-type-input", function(){
                var $fieldRow = $(this).parents(".opanda-item");
                self.onUpdatingTypeSelector( $fieldRow );
            });  

            // hides the options editor on shorting 

            this._$fieldHolder.on('sortstop', function(){
                self.hideOptions( null );
            });
        },

        /**
         * Returns a current field type of the field.
         */
        getFieldType: function( $fieldRow ) {
            return $fieldRow.find(".opanda-type-input").val();
        },
        
        getMappingData: function( $fieldRow ) {
            var value = $fieldRow.find(".opanda-mapping-input").val();
            if ( !this._mappingData ) return null;

            for( var i in this._mappingData ) {
                if ( ( this._mappingData[i].mapOptions['id'] + "" ) !== value ) continue;
                return this._mappingData[i];
            }
        },
        
        /**
         * Returns the input type info by its name.
         */
        getFieldTypeInfo: function( typeName ) {

            for( var i in this._typesList ) {
                if ( this._typesList[i]['name'] !== typeName ) continue;
                return this._typesList[i];
            }
        },

        /**
         * Refreshes options of the given Field Type selector.
         */
        refreshTypeSelector: function( $fieldRow ) {
            
            var self = this;
            var options = [];
            
            var mapOptions = this.getData( $fieldRow, 'mapOptions' );
            var permissions = this.getData( $fieldRow, 'permissions' );      

            var mapTo = mapOptions['mapTo'] || 'text';

            if ( mapTo !== 'any' && !$.isArray( mapTo ) ) {
                mapTo = [mapTo];
            }

            for( var i in self._typesList ) {

                if ( mapTo !== 'any' && $.inArray( self._typesList[i].name, mapTo ) === -1 ) continue;
                if ( self._typesList[i].hide && $.inArray( self._typesList[i].name, mapTo ) === -1 ) continue;

                var option = {
                    value: self._typesList[i].name,
                    title: self._typesList[i].title
                };

                options.push(option);
            } 
            
            var $select = $fieldRow.find(".opanda-type-input");
            
            // if the type list is empty, it means that this custom field is not supported

            if ( options.length === 0 ) {
                var unsupported = this.getFieldTypeInfo('unsupported');
                var option = {
                    value: unsupported.name,
                    title: unsupported.title,
                    hint: window.bizpanda.res['unsupported-type']
                };
                options.push(option);
                $select.addClass('opanda-unsupported');
            } else {
                $select.removeClass('opanda-unsupported');
            }

            this.fillSelects( $select, options );
            
            var previousFieldType = $fieldRow.data('fieldType');
            if ( !previousFieldType ) $fieldRow.data('fieldType', $select.val());

            var canChangeType = !permissions['can'] ? true : permissions['can']['changeType'];
            var canNotice = !permissions['notices'] ? false : permissions['notices']['changeType'];

            if ( !canChangeType ) { 
                $select.attr('disabled', 'disabled'); 
                canNotice && $select.attr('title', canNotice); 
            }
            else {
                $select.removeAttr('disabled');
                $select.removeAttr('title');
            }

            $select.trigger('apichange');
        },
        
        onUpdatingTypeSelector: function( $fieldRow ) {
            
            var fieldType = this.getFieldType( $fieldRow );
            if ( !fieldType ) return false;
            
            var fieldOptions = this.getData( $fieldRow, 'fieldOptions' ); 
            fieldOptions.type = fieldType;
            
            $fieldRow.addClass('opanda-type-' + fieldType);
            
            var fieldTypeInfo = this.getFieldTypeInfo( fieldType );
            
            // hides the fields which are not used by field type selector
            
            if ( fieldTypeInfo.helper ) {
                $fieldRow.find('.opanda-icon-input, .opanda-label-input, .opanda-required-input').hide(); 
            } else {
                $fieldRow.find('.opanda-icon-input, .opanda-label-input, .opanda-required-input').show();   
            }
            
            var typeFieldOptions = {};
            
            if ( 'checkbox' === fieldType ) {
                if ( !fieldOptions.description ) typeFieldOptions.description = window.bizpanda.res['checkbox-default-description'];
                if ( typeof fieldOptions.onValue === 'undefined' ) typeFieldOptions.onValue = 1;
                if ( typeof fieldOptions.offValue === 'undefined' ) typeFieldOptions.offValue = 0;       
            }
 
            if ( 'label' === fieldType && !fieldOptions.text )
                typeFieldOptions.text = window.bizpanda.res['label-default-text'];
            
            if ( !fieldOptions.placeholder && fieldTypeInfo.placeholder )
                typeFieldOptions.placeholder = fieldTypeInfo.placeholder;
            
            // determines which field options the mapping input rewrites,
            // when we change the mapping input value, we will rollback changes
            
            var previosDiff = this.getData( $fieldRow, 'typeDiffOptions' );
            if ( previosDiff ) this.addData( $fieldRow, 'fieldOptions', previosDiff, true );

            var diff = this.addData( $fieldRow, 'fieldOptions', typeFieldOptions );
            this.setData( $fieldRow, 'typeDiffOptions', diff );
            
            this.adjustTypeSelectorsWidths();

            // shows editor if it was visible on updating the type selector
            
            if ( !$fieldRow.hasClass('opanda-options-active') ) return;
            this.showOptions( $fieldRow, true );
        },
        
        /**
         * Refreshes options of the given Required checkox.
         */
        refreshRequiredCheckbox: function( $fieldRow ) {
            var $required = $fieldRow.find(".opanda-required-input");
            
            var fieldOptions = this.getData( $fieldRow, 'fieldOptions' );
            var mapOptions = this.getData( $fieldRow, 'mapOptions' );   
            var permissions = this.getData( $fieldRow, 'permissions' );

            var canChangeReq = !permissions['can'] ? true : permissions['can']['changeReq'];
            var canNotice = !permissions['notices'] ? false : permissions['notices']['changeReq'];

            if ( fieldOptions.req ) $required.attr('checked', 'checked');
            else $required.removeAttr('checked');

            if ( !canChangeReq || mapOptions.req ) { 
                $required.attr('disabled', 'disabled');
                $required.attr('title', canNotice);
            } else {
                $required.removeAttr('disabled');
                $required.removeAttr('title');
            }
        },
        
        /**
         * Finds and returns mapping selectors. 
         */
        getTypeSelectors: function() {
            var $selectors = this._$fieldsEditor.add(this._$fieldTemplate).find(".opanda-type-input");
            return $selectors;
        },
        
        /**
         * Makes all the mapping files the same size.
         */
        adjustTypeSelectorsWidths: function() {
            var $selectors = this.getTypeSelectors();
            $selectors.css('width', 'auto');

            if ( $selectors.length <= 2 ) return;
            
            var maxWidth = 0;
            $selectors.each(function(){
                var width = $(this).width();
                if ( maxWidth < width ) maxWidth = width;
            });

            $selectors.css('width', maxWidth + 16 + 'px');
        },
        
        // --------------------------------------------------------
        // Column :: Controls
        // --------------------------------------------------------

        /**
         * Inits control elements of fields.
         */
        initControlColumn: function() {
            var self = this;

            // add field

            this._$btnAddField.click(function(){
                var $fieldRow = self.addField( null, null, true );
                return false;
            });

            // remove field

            this._$fieldsEditor.on("click", ".opanda-remove", function(){
                var $fieldRow = $(this).parents(".opanda-item");
                if ( $fieldRow.lenght === 0 ) return false;
                self.removeFiled( $fieldRow );
                return false;
            });

            // configure field

            this._$fieldsEditor.on("click", ".opanda-configure", function(){

                var $fieldRow = $(this).parents(".opanda-item");
                if ( $fieldRow.hasClass('opanda-options-active') ) {
                    self.hideOptions();
                } else {
                    self.showOptions( $fieldRow );
                }

                return false;
            });       
        },

        // --------------------------------------------------------
        // Options Editors
        // --------------------------------------------------------

        /**
         * Shows options editor for the given field row.
         */
        showOptions: function ( $fieldRow, force ){

            var fieldType = this.getFieldType( $fieldRow );
            if ( !fieldType ) return false;

            if ( $fieldRow.hasClass('opanda-options-active') && !force ) return;
            $fieldRow.addClass('opanda-padding-bottom');

            this.hideOptions( function(){
                $fieldRow.addClass('opanda-options-active');
            });

            var $editor = this.getOptionsEditor( $fieldRow );
            if ( !$editor ) return false;

            $editor.insertAfter( $fieldRow );
            $editor.find(".opanda-container").hide().slideDown(300);
        },

        /**
         * Hides all visible options.
         */
        hideOptions: function( callback ) {
            var self = this;

            var $editors = this._$fieldsEditor.find(".opanda-field-options");
            if ( $editors.length === 0 ) return callback && callback();

            $editors.find(".opanda-container").slideUp(300, function(){
                 $editors.remove();

                 self._$fieldsEditor
                    .find(".opanda-options-active")
                    .removeClass("opanda-padding-bottom")
                    .removeClass("opanda-options-active");

                callback && callback();
            });
        },

        /**
         * Returns the option editor ready to use.
         */
        getOptionsEditor: function( $fieldRow ) {

            var $tr = $("<tr class='opanda-field-options'></tr>");
            var $td = $("<td colspan='7'></td>").appendTo($tr);
            var $container = $("<div class='opanda-container'></div>").appendTo($td);

            var fieldType = this.getFieldType( $fieldRow );
            if ( !fieldType ) return false;

            var fieldTypeInfo = this.getFieldTypeInfo( fieldType );
            var editorName = fieldTypeInfo.editorName ? fieldTypeInfo.editorName : 'unsupported';

            var $controls = this._$fieldsEditor.find(".opanda-options-templates .opanda-" + editorName + "-options");
            if ( $controls.length === 0 ) $controls = this._$fieldsEditor.find(".opanda-options-templates .opanda-unsupported-options");

            $container.append( $controls.clone() );

            this.initOptionsEditor( $fieldRow, $container );
            return $tr;
        },

        /**
         * Loads values and binds events.
         */
        initOptionsEditor: function( $fieldRow, $container ) {
            var self = this;
            
            var fieldType = this.getFieldType( $fieldRow )
            var fieldTypeInfo = this.getFieldTypeInfo( fieldType );
            var editorName = fieldTypeInfo.editorName ? fieldTypeInfo.editorName : 'unsupported';
            
            var fieldOptions = this.getData( $fieldRow, 'fieldOptions' );
            var permissions = this.getData( $fieldRow, 'permissions' );  

            if ( 'dropdown' === editorName ) {

                var canChangeDropdown = !permissions['can'] ? true : permissions['can']['changeDropdown'];
                var canNotice = !permissions['canNotice'] ? false : permissions['canNotice']['changeDropdown'];

                $container.find(".opanda-choices-editor").choicesEditor({
                    canChange: canChangeDropdown
                });
                
                // shows notices that it's not possible to edit the field
                if ( !canChangeDropdown && canNotice ) {
                    var $notice = $container.find(".opanda-can-notice").show();
                    $notice.find(".alert").html(canNotice);
                }
                
                $container.find(".opanda-title-input").val( fieldOptions.title );
                $container.find(".opanda-choices-editor").choicesEditor('setChoices', fieldOptions && fieldOptions.choices );
                
            } else if ( 'text' === editorName ) {
                
                var canChangeMask =  !permissions['can'] ? true : permissions['can']['changeMask'];
                if ( !canChangeMask ) $container.find(".opanda-mask-input").attr('disabled', 'disabled');
                                
                if ( 'birthday' === fieldType ) {
                    if ( !fieldOptions.mask ) fieldOptions.mask = '99/99';
                } 
                
                this.setValueForSelect( $container.find(".opanda-icon-position-input"), fieldOptions.iconPosition );
                $container.find(".opanda-placeholder-input").val( fieldOptions.placeholder );
                $container.find(".opanda-title-input").val( fieldOptions.title );
                $container.find(".opanda-mask-input").val( fieldOptions.mask );
                
                $container.find(".opanda-mask-placeholder-input").val( fieldOptions.maskPlaceholder || "" ); 
                
            } else if ( 'integer' === editorName ) {

                this.setValueForSelect( $container.find(".opanda-icon-position-input"), fieldOptions.iconPosition );
                $container.find(".opanda-placeholder-input").val( fieldOptions.placeholder );
                $container.find(".opanda-title-input").val( fieldOptions.title );

                if ( fieldOptions.min ) $container.find(".opanda-min-input").val( parseInt( fieldOptions.min ) );
                if ( fieldOptions.max ) $container.find(".opanda-max-input").val( parseInt( fieldOptions.max ) );
                
            } else if ( 'checkbox' === editorName ) {

                if ( fieldOptions.description ) {
                    $container.find(".opanda-description-input").val( fieldOptions.description );
                }                    

                if ( fieldOptions.markedByDefault ) {
                    $container.find(".opanda-marked-by-default-input").attr('checked', 'checked');
                }

                $container.find(".opanda-marked-value-input").val(fieldOptions.onValue);
                $container.find(".opanda-unmarked-value-input").val(fieldOptions.offValue);
                
            } else if ( 'label' === editorName ) {

                if ( fieldOptions && fieldOptions.text ) {
                    $container.find(".opanda-text-input").val( fieldOptions.text );
                }
                
            } else if ( 'html' === editorName ) {

                if ( fieldOptions && fieldOptions.html ) {
                    $container.find(".opanda-html-input").val( fieldOptions.html );
                }
            }

            $container.find(".opanda-hide").click(function(){
                self.hideOptions();
                return false;
            });

            $container.on("change", ".opanda-choices-editor, input, select, textarea", function(){
                var values = self.getOptionsEditorValuesToSave( $container, editorName );
                self.addData( $fieldRow, 'fieldOptions', values, true );
            });
        },

        /**
         * Returns options to save.
         */
        getOptionsEditorValuesToSave: function( $container, editorName ) {
            var values = {};

            if ( 'text' === editorName ) {

                values.iconPosition = $container.find(".opanda-icon-position-input").val();
                values.placeholder =  $container.find(".opanda-placeholder-input").val();
                values.title =  $container.find(".opanda-title-input").val();
                values.mask =  $container.find(".opanda-mask-input").val(); 
                values.maskPlaceholder =  $container.find(".opanda-mask-placeholder-input").val();     
                
            } else if ( 'integer' === editorName ) {

                values.iconPosition = $container.find(".opanda-icon-position-input").val();
                values.placeholder =  $container.find(".opanda-placeholder-input").val();
                values.title =  $container.find(".opanda-title-input").val();   
                values.min =  parseInt( $container.find(".opanda-min-input").val() );
                values.max =  parseInt( $container.find(".opanda-max-input").val() );
                
            } else if ( 'dropdown' === editorName ) {
                
                values.title =  $container.find(".opanda-title-input").val();
                values.choices = $container.find(".opanda-choices-editor").choicesEditor('getChoices');

            } else if ( 'checkbox' === editorName ) {

                values.description = $container.find(".opanda-description-input").val();
                values.markedByDefault = $container.find(".opanda-marked-by-default-input").is(":checked");
                values.onValue = $container.find(".opanda-marked-value-input").val();
                values.offValue = $container.find(".opanda-unmarked-value-input").val(); 

            } else if ( 'label' === editorName ) {

                values.text = $container.find(".opanda-text-input").val();

            } else if ( 'html' === editorName ) {

                values.html = $container.find(".opanda-html-input").val();

            }

            return values;
        },

        // --------------------------------------------------------
        // Selectors :: Helper Methods
        // --------------------------------------------------------

        /**
         * Fills up a given $select element with the specified options.
         */
        fillSelects: function( $selects, options ) {
            var self = this;

            $selects.each(function(){
                var $select = $(this);

                $selects.html("").removeAttr('disabled');
                var currentValue = $selects.data('value');

                // checks if options have any selected values
                var hasSelected = false;
                for ( var i in options ) {
                    if ( !options[i].selected ) continue;

                    hasSelected = true;
                    break;
                }

                var addOption = function( option, $holder ) {

                    if ( option.items ) {

                        var $optgroup = $("<optgroup></optgroup>");
                        $optgroup.attr("label", option.title);

                        for ( var i in option.items ) {
                            addOption( option.items[i], $optgroup );
                        }

                        $optgroup.appendTo($holder);
                        return;
                    }

                    var $option = $("<option></option>")
                        .attr('value', option.value)
                        .data('map', option.data)
                        .data('data', option.data)
                        .data('hint', option.hint)
                        .html(option.title)
                        .appendTo($holder);

                    if ( option.disabled ) {
                        $option.attr('disabled', 'disabled');

                    } else {

                        if ( option.selected || ( !hasSelected && option.value == currentValue ) ) {
                            $option.attr('selected', 'selected');
                            $option.data('value', option.value);
                        } 
                    }
                };

                // first adds only enabled options
                for ( var i in options ) {
                    var option = options[i];

                    if ( option.disabled ) continue;
                    addOption( option, $select );
                }

                // then adds only disabled options
                for ( var i in options ) {
                    var option = options[i];

                    if ( !option.disabled ) continue;
                    addOption( option, $select );
                }

                var $iconCurrent = null;
                var $hintCurrent = null;

                var showHint = function( $select ){
                    var $option = $select.find(":selected");
                    var hint = $option.data('hint');

                    if ( $iconCurrent ) {
                        $iconCurrent.remove();
                        $hintCurrent.remove();
                        $iconCurrent = null;
                        $hintCurrent = null;
                    }

                    if ( !hint ) return;

                    $iconCurrent = $("<div class='opanda-popup-hint-icon'></div>");
                    $hintCurrent = $("<div class='opanda-popup-hint'></div>").html(hint);

                    $select.after( $iconCurrent );
                    $iconCurrent.after( $hintCurrent );

                    self.initHint( $iconCurrent, $hintCurrent );
                };

                $select.change(function(){
                    showHint( $(this) );
                });

                showHint( $select );
            });
        },

        /**
         * Sets the given state for selectors.
         */
        lockSelects: function( $selects, text, $def ) {

            $selects.each(function(){
                var $select = $(this);
                $select.html("").attr('disabled', 'disabled');

                var $option = $("<option value=''></option>").html(text);
                $option.appendTo($select);
            });

            if ( !$def ) return;

            $def.always(function(){
                $selects.each(function(){
                    $(this).html("").removeAttr('disabled', 'disabled');
                });
            });

            $def.error(function(){
                $selects.each(function(){
                    $(this).html("[ - error - ]");
                });
            });     
        },

        /**
         * Sets a value for a given select.
         */
        setValueForSelect: function( $select, value ) {
            
            $select.find("option").removeAttr('selected').each(function(){
                var optionValue = $(this).attr('value');
                if ( optionValue != value ) return;
                $(this).attr('selected',' selected');
                $select.data('value', value);
                return false;
            });
        },

        // --------------------------------------------------------
        // Managing Fields
        // --------------------------------------------------------

        /**
         * Creates html for a new field.
         */
        createField: function( data ) {

            var $blank = this._$fieldTemplate.clone(true, true);
            $blank.removeClass('opanda-template').addClass('opanda-item');

            if ( data ) {
                $blank.find(".opanda-label-input").val(data.serviceOptions.label);
                $blank.find(".opanda-mapping-input").val(data.mapOptions.id).data('value', data.mapOptions.id);
                
                $blank.find(".opanda-type-input").val(data.fieldOptions.type).data('value', data.fieldOptions.type);
                
                if ( data.fieldOptions.req ) $blank.find(".opanda-required-input").attr('checked', 'checked');
                $blank.find(".opanda-icon-input").val(data.fieldOptions.icon);

                if ( data.serviceOptions.sysname ) { 
                    $blank.addClass('opanda-obligate'); 
                    $blank.addClass('opanda-' + data.serviceOptions.sysname );                         
                    $blank.data('sysname', data.serviceOptions.sysname ); 
                }

                this.setData( $blank, 'fieldOptions', data.fieldOptions );
                this.setData( $blank, 'mapOptions', data.mapOptions );
                this.setData( $blank, 'permissions', data.permissions );
                
            } else {
                
                this.setData( $blank, null, {} );
            }

            return $blank;
        },

        /**
         * Prepend a new field at the beggining.
         */ 
        prependField: function( data, skipEffects, saveFields ) {

            var $fieldRow = this.createField( data );
            $fieldRow.prependTo( this._$fieldHolder ); 

            if ( skipEffects ) $fieldRow.show();
            else $fieldRow.hide().fadeIn(300);
            
            saveFields && this.onUpdatingMappingSelector( $fieldRow );
            saveFields && this.saveFields();
        },

        /**
         * Adds a new field at the end.
         */
        addField: function( data, skipEffects, saveFields ) {

            var $fieldRow = this.createField( data );
            $fieldRow.appendTo( this._$fieldHolder ); 
            
            if ( skipEffects ) $fieldRow.show();
            else $fieldRow.hide().fadeIn(300);
            
            saveFields && this.onUpdatingMappingSelector( $fieldRow );
            saveFields && this.saveFields();
            this.adjustMappingSelectorsWidths();
        },

        /**
         * Removes the specified field.
         */
        removeFiled: function( $fieldRow ) {
            if ( this.isObligate( $fieldRow ) ) return;
            var self = this;

            $fieldRow.fadeOut(200, function(){
                $fieldRow.remove();
                self.saveFields();
            });
        },

        // --------------------------------------------------------
        // Saving & Loading
        // --------------------------------------------------------
        
        getData: function( $fieldRow, scope ) {
            var storeData = $fieldRow.data('store');
            if ( !storeData ) storeData = {};
            if ( scope ) return storeData[scope] || {};
            return storeData;
        },
        
        setData: function ( $fieldRow, scope, data ) {
            if ( !scope ) return $fieldRow.data('store', data );
            
            var storeData = $fieldRow.data('store');
            if ( !storeData ) storeData = {};
            storeData[scope] = data;
            $fieldRow.data('store', storeData );
        },
        
        addData: function( $fieldRow, scope, data, rewrite ) {
            var storeData = this.getData( $fieldRow, scope );

            var diff = {};
            if ( rewrite ) {
                for ( var i in data ) {
                    if ( !data.hasOwnProperty(i) ) continue;
                    if ( typeof storeData[i] !== 'undefined' ) {
                        diff[i] = storeData[i];
                    } else { 
                        diff[i] = null;
                    }
                }   
            } else {
                for ( var i in data ) {
                    if ( !data.hasOwnProperty(i) ) continue;
                    if ( typeof storeData[i] !== 'undefined' ) continue;
                    diff[i] = null;
                }
            }

            storeData = ( rewrite ) 
                ? $.extend( true, storeData, data ) 
                : $.extend( true, data, storeData );
                
            for ( var i in storeData ) {
                if ( !storeData.hasOwnProperty(i) ) continue;
                if ( storeData[i] === null ) delete storeData[i];
            }
                
            this.setData( $fieldRow, scope, storeData );
            return diff;
        },

        /**
         * Loads saved values and creates fields.
         */
        loadSaved: function() {
            var self = this;

            var strjson = this._$result.val();
            var hasEmail = false;

            if ( strjson ) {
                
                var data = $.parseJSON(strjson);

                for ( var i = 0; i < data.length; i++ ) {
                    if ( !data[i].serviceOptions ) continue;
                    if ( 'email' === data[i].serviceOptions.sysname ) { hasEmail = true; }
                    this.addField( data[i], true );
                }
            }
            

            // adds an email field if it was missed

            if ( !hasEmail ) {

                this.prependField( {

                    fieldOptions: {
                        id: 'email',
                        type: 'email',
                        req: true,
                        icon: 'fa-envelope-o',
                        iconPosition: 'right',
                        placeholder: window.bizpanda.res['email-field-placeholder']
                    },
                    serviceOptions: {
                        sysname: 'email',
                        label: 'Email',
                        mapping: 'email' 
                    },
                    mapOptions: { mapTo: 'email' },
                    permissions: { changeType: true, changeReq: false }

                }, true );
            }

            var $fields = this._$fieldsEditor.find(".opanda-item");

            // obligate fields

            $fields.each(function(){
                var $fieldRow = $(this);
                if ( !self.isObligate( $fieldRow ) ) return;

                $fieldRow.find(".opanda-remove").addClass("disabled");
            });

            // email field

            $fields.each(function(){
                var $fieldRow = $(this);
                if ( !self.isEmailField( $fieldRow ) ) return;

                var $mappingInput = $fieldRow.find(".opanda-mapping-input");
                $mappingInput.html("<option value='email'>Email</option>");

                var $requiredInput = $fieldRow.find(".opanda-required-input").attr("checked", "checked");
                $requiredInput.attr("disabled", "disabled");
            });

            this.saveFields( true );
        },

        /**
         * Saves the current fields and their order to the hidden field.
         */
        saveFields: function( ignoreChangeEvent ) {
            var self = this;

            var allData = [];
            var fields = [];
            
            var index = 0;
            
            this._$fieldHolder.find(".opanda-item").each(function(){
                index++;
                var $fieldRow = $(this);
                
                var fieldOptions = $.extend( true, {}, self.getData( $fieldRow, 'fieldOptions' ) );

                var serviceOptions = {};
                var mapOptions = self.getData( $fieldRow, 'mapOptions' );
                var permissions = self.getData( $fieldRow, 'permissions' );
                
                fields.push( fieldOptions );

                var sysname = $fieldRow.data('sysname');
                var label = $fieldRow.find(".opanda-label-input").val();
                if ( !label ) label = '(no label)';
   
                var id = sysname ? sysname : ( "cf" + index );
                if ( mapOptions.id === 'fullname' ) id = 'fullname';
 
                fieldOptions.id = id;
                fieldOptions.icon = $fieldRow.find(".opanda-icon-input").val();
                fieldOptions.req = $fieldRow.find(".opanda-required-input").is(":checked");
 
                var serviceOptions = {
                    id: id,
                    sysname: sysname,     
                    label: label
                };

                allData.push({
                    fieldOptions: fieldOptions,
                    serviceOptions: serviceOptions,
                    mapOptions: mapOptions,
                    permissions: permissions
                });
            });
  
            var strjson = JSON.stringify( allData );
            this._$result.val(strjson).data('fields', fields);
            if ( !ignoreChangeEvent ) this._$result.change();
        },
        
        getFields: function() {
            return this._result;
        },

        // --------------------------------------------------------
        // Helpers
        // --------------------------------------------------------

        /**
         * Converts some text to the slug.
         */
        normilize: function(str) {
            var $slug = '';
            var trimmed = $.trim(str);
            $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            return $slug.toLowerCase();
        },

        /**
         * Shows an error.
         */
        showError: function( error ) {
            this._$error.find(".opanda-error-text").html(error);
            this._$error.fadeIn(300);
        },

        // --------------------------------------------------------
        // Hints
        // --------------------------------------------------------

        initHints: function() {
            var self = this;

            $("[data-popup-hint]").each(function(){
                var $item = $(this);
                var $content = $( $item.data('popup-hint') );

                self.initHint( $item, $content );
            });
        },

        initHint: function( $item, $content ) {

            var timeToHide = 400;
            var timerStep = 200;
            var timer = null;
            var timerStopped = true;

            var runHiddingTimer = function() {
                timerStopped = false;
                tickHiddingTimer();
            };

            var tickHiddingTimer = function() {
                if ( timerStopped ) return;
                timer = setTimeout(function(){

                    timeToHide = timeToHide - timerStep;
                    if ( timeToHide > 0 ) { return tickHiddingTimer(); }
                    if ( timerStopped ) return;

                    $content.hide();
                }, timerStep);
            };

            var stopHiddingTimer = function() {
                timerStopped = true;
                timer && clearTimeout(timer);
                timer = null;
            };

            var resetHiddingTimer = function() {
                timeToHide = 400;
            };

            $item.hover(function(){
                $(".opanda-popup-hint").hide();
                $content.show();
                stopHiddingTimer();
            }, function(){
                resetHiddingTimer();
                runHiddingTimer();
            });

            $content.hover(function(){
                stopHiddingTimer();
            }, function(){
                resetHiddingTimer();
                runHiddingTimer();
            });

            $(document).bind("click.popuphint", function(){
                stopHiddingTimer();
                $content.hide();
            });

            $($item).add($content).bind("click.popuphint", function(e){
                e.stopPropagation();
            }); 
        }
    });
    
    // -----------------------------------------------------------
    // Fields Editor
    // -----------------------------------------------------------
    
    $.widget( "opanda.choicesEditor", {
    
        _create: function() {
            
            var self = this;

            var canChangeDropdown = this.options.canChange;
            
            if ( !canChangeDropdown ) {
                this.element.find("input").attr('disabled', 'disabled');
                this.element.find(".btn").attr('disabled', 'disabled');
                return false;
            }

            var $add = this.element.find(".opanda-add-choice");

            $add.click(function(){
                self.addChoice();
                return false;
            });

            this.element.on("click", ".opanda-choice-remove", function(){
                var $item = $(this).parents(".opanda-choice-item");
                self.removeChoice( $item );
                return false;
            });
        },
        
        addChoice: function( value ) {

            var $holder = this.element.find(".opanda-choices-holder");
            var $template = this.element
                .find(".opanda-choice-item-template").clone()
                .removeClass("opanda-choice-item-template");

            var $blank = $template.clone().hide()
            $holder.append($blank);

            if ( title ) $blank.find('.opanda-choise-value-input').val(value);

            $blank.fadeIn(300);
        },

        removeChoice: function( $item ) {
            var self = this;
            
            $item.fadeOut(200, function(){
                $item.remove();
                self.element.trigger("change");
            });
        },

        setChoices: function( choices ) {
            
            if ( !choices ) {
              // this.addChoice('First Choice');
              // this.addChoice('Second Choice');
              // this.addChoice('Third Choice');
            } else {
                for( var i in choices ) {
                    this.addChoice(choices[i]);
                }   
            }
        },

        getChoices: function() {
            var choices = [];

            this.element.find(".opanda-choices-holder .opanda-choice-item").each(function(){
                var $item = $(this);
                choices.push( $item.find(".opanda-choise-value-input").val() );
            });

            return choices;
        }
    });
    
    $(function(){
        $("#opanda-fields-editor").fieldsEditor({
            result: '#opanda_fields'
        });
    });
    
})(jQuery);

