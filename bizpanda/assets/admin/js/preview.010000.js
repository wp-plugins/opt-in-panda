if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.preview ) window.bizpanda.preview = {};

(function($){
    
    window.bizpanda.preview = {
        
        _forms: {},
        
        refresh: function( url, name, options, callback ) {

            if ( !$("iframe[name=" + name + "]").length ) return;
            
            // removes previos forms
            if ( this._forms[name] ) {
                $("iframe[name='" + name + "']")[0].contentWindow.setOptions( options );
                return;
            }
            
            var $form = $("<form method='post'></form>")
                                    .attr('target', name)
                                    .attr('action', url);
                            
            options = this._encodeOptions( options );

            this._createField($form, 'options', JSON.stringify( options ));
            this._createField($form, 'name', name);
            this._createField($form, 'url', url);
            this._createField($form, 'callback', callback);
            
            $form.appendTo( $("body") );
            $form.submit();
            
            // saves a form to remove in the next time
            this._forms[name] = $form;
        },

        _createField: function( $form, name, value ){
            $("<input type='hidden' />")
                .attr('name', name)
                .attr('value', value)
                .appendTo($form);
        },
           
        _encodeOptions: function( options ) {
            for( var optionName in options ) {
                if ( !$.isPlainObject(options[optionName])) continue;
                                   
                if ( typeof options[optionName] === 'object' ) {
                    options[optionName] = this._encodeOptions( options[optionName] );
                } else {
                    if ( options[optionName] ) {
                        options[optionName] = encodeURI( options[optionName] );
                    }
                }
            }
            return options;
        }
    }

})(jQuery);
