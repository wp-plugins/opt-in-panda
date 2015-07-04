if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.subscriptionOptions ) window.bizpanda.subscriptionOptions = {};

(function($){
    
    window.bizpanda.subscriptionOptions = {
        
        init: function( item ) {
            var self = this;
            
            this.item = $('#opanda_item').val();
            if ( 'email-locker' !== this.item ) return;

            $("#opanda_form_type").change(function(){
                var value = $(this).val();

                if ( value === 'custom-form' ) {
                    $("#opanda-email-form-options").hide();
                    $("#opanda-custom-form-options").fadeIn();     
                    
                    $("#opanda-fields-editor").fieldsEditor("adjustMappingSelectorsWidths");
                } else {
                    $("#opanda-email-form-options").fadeIn(300);
                    $("#opanda-custom-form-options").hide(); 
                }
                
            }).change();

            $("#opanda_subscribe_allow_social").change(function(){
                var value = $(this).is(":checked");
                if ( value ) $("#social-buttons-options").fadeIn();
                else $("#social-buttons-options").hide();
            }).change();
            
            $.bizpanda.filters.add('opanda-preview-options', function( options ){
                var extraOptions = self.getSubscriptionOptions();
                return $.extend(true, options, extraOptions);
            });
            
            $("#opanda_fields").change(function(){
                var data = $.parseJSON( $("#opanda_fields").val() );
                $("#opanda_fields").data('value', data);
            }).change();
        },

        getSubscriptionOptions: function() {

            var connectButtons = [];
            if ( $("#factory-checklist-opanda_subscribe_social_buttons-facebook").is(":checked") ) connectButtons.push('facebook');
            if ( $("#factory-checklist-opanda_subscribe_social_buttons-google").is(":checked") ) connectButtons.push('google'); 
            if ( $("#factory-checklist-opanda_subscribe_social_buttons-linkedin").is(":checked") ) connectButtons.push('linkedin');  

            var groups = ( $("#opanda_subscribe_allow_social").is(":checked") && connectButtons.length && $("#opanda_form_type").val() !== 'custom-form' )
                ? ['subscription', 'connect-buttons']
                : ['subscription'];

            var optinMode = $('#opanda_subscribe_mode').val();

            var options = {

                groups: {
                    order: groups
                },

                terms: window.opanda_terms,
                privacyPolicy: window.opanda_privacy_policy,

                connectButtons: {

                    order: connectButtons,

                    text: {
                        message: $("#opanda_subscribe_social_text").val()
                    },

                    facebook: {
                        appId: window.opanda_facebook_app_id,
                        actions: ['subscribe']
                    },
                    google: {
                        clientId: window.opanda_google_client_id,
                        actions: ['subscribe']
                    },
                    linkedin: {
                        actions: ['subscribe'],
                        apiKey: window.opanda_linkedin_api_key
                    }
                },

                subscription: {
                    form: {
                        preview: true,
                        buttonText: $("#opanda_button_text").val(),
                        noSpamText: $("#opanda_after_button").val(),
                        type: $("#opanda_form_type").val(),
                        fields: $("#opanda_fields").data('fields')
                    }
                },

                subscribeActionOptions: {

                    campaignId: $("#opanda_subscribe_list").length ? $("#opanda_subscribe_list").val() : null,
                    service: window.opanda_subscription_service_name,
                    doubleOptin: $.inArray( optinMode, ['quick-double-optin', 'double-optin'] > -1),
                    confirm: $.inArray( optinMode, ['double-optin'] > -1)
                }
            };

            return options;
        }
    };
    
    $(function(){
        window.bizpanda.subscriptionOptions.init();
    });
    
})(jQuery);

