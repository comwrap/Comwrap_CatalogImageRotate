define([
    'jquery',
    'underscore',
    'mage/template',
    'uiRegistry',
    'jquery/ui',
    'baseImage'
], function($, _, mageTemplate, registry) {
    'use strict';

    /**
     * Formats incoming bytes value to a readable format.
     *
     * @param {Number} bytes
     * @returns {String}
     */
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'],
            i;

        if (bytes === 0) {
            return '0 Byte';
        }

        i = window.parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    return function (widget) {
        $.widget('mage.productGallery', widget, {
            options: {
                popupFlag: true
            },

            /**
             * Bind handler to elements
             * @protected
             */
            _bind: function () {
                var events = {};
                events['rotateItem'] = '_rotateItem';

                events['click [data-role=rotate-image]'] = function (event) {
                    var imageData, $imageContainer;
                    event.preventDefault();
                    this.options.popupFlag = false;
                    imageData = $(event.currentTarget).parent().parent().parent().data('imageData');
                    $imageContainer = this.findElement(imageData);
                    this.element.trigger('rotateItem', $imageContainer.data('imageData'));
                };
                this._on(events);
                this._super();
            },

            _onOpenDialog: function (e, imageData) {
                if (this.options.popupFlag === false) {
                    return;
                }
                if (imageData['media_type'] && imageData['media_type'] != 'image') { //eslint-disable-line eqeqeq
                    return;
                }
                this._showDialog(imageData);
            },

            _rotateItem: function (event, imageData) {
                if (imageData['media_type'] && imageData['media_type'] != 'image') {
                    return;
                }
                var self = this;
                var rotated = imageData.rotated,
                    old_url = imageData.url,
                    error = imageData.error;
                var $imageContainer = self.findElement(imageData);
                $imageContainer.addClass('rotated').find('.is-rotated').val(1);
                var angle = imageData.angle;
                var url = $imageContainer.find('.original-file').val();
                var postUrl = self.element.data('upload-url');
                $.ajax({
                    url: postUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {image_url: url, image_old_url: old_url, rotated: rotated, error:error, angle:angle},
                    showLoader: true
                }).done(function(response) {
                    if (response.file) {
                        var img = $imageContainer.parent().find('.image-image').val();
                        if (img === url) {
                            $imageContainer.parent().find('.image-image').val(response.file);
                        }
                        var smallImg = $imageContainer.parent().find('.image-small_image').val();
                        if (smallImg === url) {
                            $imageContainer.parent().find('.image-small_image').val(response.file);
                        }
                        var thumImg = $imageContainer.parent().find('.image-thumbnail').val();
                        if (thumImg === url) {
                            $imageContainer.parent().find('.image-thumbnail').val(response.file);
                        }
                        var swatchImg = $imageContainer.parent().find('.image-swatch_image').val();
                        if (swatchImg === url) {
                            $imageContainer.parent().find('.image-swatch_image').val(response.file);
                        }
                        $imageContainer.find('.file').val(response.file);
                        $imageContainer.find('.product-image').attr("src",response.url);
                        $imageContainer.data('imageData').file = response.file;
                        $imageContainer.data('imageData').url = response.url;
                        $imageContainer.data('imageData').rotated = 1;
                        $imageContainer.data('imageData').angle = response.angle;
                        self._updateImagesRoles();
                        self._contentUpdated();
                    }
                    self.options.popupFlag = true;
                });
            },

            /**
             * Add image
             * @param {jQuery.Event} event
             * @param {Object} imageData
             * @private
             */
            _addItem: function (event, imageData) {
                var count = this.element.find(this.options.imageSelector).length,
                    element,
                    imgElement,
                    position = count + 1,
                    lastElement = this.element.find(this.options.imageSelector + ':last');

                if (lastElement.length === 1) {
                    position = parseInt(lastElement.data('imageData').position || count, 10) + 1;
                }
                imageData = $.extend({
                    'file_id': imageData['value_id'] ? imageData['value_id'] : Math.random().toString(33).substr(2, 18),
                    'disabled': imageData.disabled ? imageData.disabled : 0,
                    'position': position,
                    sizeLabel: bytesToSize(imageData.size)
                }, imageData);

                element = this.imgTmpl({
                    data: imageData
                });

                element = $(element).data('imageData', imageData);
                var isImageType = imageData['media_type'] === 'external-video' ? false : true;
                if (isImageType) {
                    element.find('.rotate').show();
                }

                if (count === 0) {
                    element.prependTo(this.element);
                } else {
                    element.insertAfter(lastElement);
                }

                if (!this.options.initialized &&
                    this.options.images.length === 0 ||
                    this.options.initialized &&
                    this.element.find(this.options.imageSelector + ':not(.removed)').length === 1
                ) {
                    this.setBase(imageData);
                }

                imgElement = element.find(this.options.imageElementSelector);

                imgElement.on('load', this._updateImageDimesions.bind(this, element));

                $.each(this.options.types, $.proxy(function (index, image) {
                    if (imageData.file === image.value) {
                        this.element.trigger('setImageType', {
                            type: image.code,
                            imageData: imageData
                        });
                    }
                }, this));
                this._updateImagesRoles();
                this._contentUpdated();
            },
        });
        return $.mage.productGallery;
    }
});
