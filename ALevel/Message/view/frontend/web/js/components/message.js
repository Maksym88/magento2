define([
        'uiComponent',
        'jquery',
        'underscore',
        'mage/template',
        'mage/url'
    ],
    /**
     * @param Component
     * @param $
     * @param _
     * @param mageTemplate
     * @param urlBuilder
     * @returns {*}
     */
    function (
    Component,
    $,
    _,
    mageTemplate,
    urlBuilder
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                messageTemplateSelector: '#messages-list-template',
                messageBlockSelector: '[data-role="messages-list"]',
                nameSelector: '#name',
                messageSelector: '#message',
            },
            /**
             * @returns {*}
             */
            initialize: function () {
                this._super();
                this.messageProvider();
                return this;
            },

            sendMessage: function () {
                let name, message, self;

                self = this;
                name = $(this.nameSelector).val();
                message = $(this.messageSelector).val();

                this.messageProvider({'name': name, 'message': message});
            },
            /**
             *
             * @param data
             */
            messageProvider: function (data = {}) {

                let self = this;
                let url = urlBuilder.build(this.url );

                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    dataType: 'json'

                }).done(function (response) {
                    console.log(response);
                    self.showMessages(response);
                }).fail(function (response) {
                    console.log(response);
                })
            },
            /**
             *
             * @param response
             */
            showMessages: function (response) {
                let messagesHtml;

                this.messageTemplate = $(this.messageTemplateSelector).html();

                if (this.messageTemplate) {
                    messagesHtml = mageTemplate(this.messageTemplate, {
                        'messages': response
                    });
                    $(this.messageBlockSelector).html(messagesHtml).show();
                }
            }
        });
    }
);