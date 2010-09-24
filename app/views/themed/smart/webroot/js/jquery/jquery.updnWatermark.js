/*
* jQuery Watermark Plugin
*   http://updatepanel.net/2009/04/17/jquery-watermark-plugin/
*
* Copyright (c) 2009 Ting Zwei Kuei
*
* Dual licensed under the MIT and GPL licenses.
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.opensource.org/licenses/gpl-3.0.html
*/ 
(function($) {
    $.fn.updnWatermark = function(options) {
        options = $.extend({}, $.fn.updnWatermark.defaults, options);
        return this.each(function() {
            var $input = $(this);
            var $watermark = $input.data("watermark");
            // only create watermark if title attribute exists 
            if (!$watermark && this.title) {
                var $watermark = $("<label/>")
                    .insertBefore(this)
                    .text(this.title)
                    .attr("for", this.id)
                    .addClass(options.cssClass)
                    .hide()
                    .bind("position", function() {
                        var pos = $input.position();
                        $(this).css({
                            position: "absolute",
                            left: pos.left,
                            top: pos.top
                        })
                    })
                    .bind("show", function() {
                        $(this).fadeIn("fast");
                    })
                    .bind("hide", function() {
                        $(this).hide();
                    })
                    .bind("update", function() {
                        ($input.is(":visible") ? $(this).trigger("position").show() : $(this).hide());
                    });
                $input.data("watermark", $watermark);
            }
            if ($watermark) {
                $input
                    .focus(function(ev) {
                        $watermark.trigger("hide");
                    })
                    .blur(function(ev) {
                        if (!$(this).val()) {
                            $watermark.trigger("show");
                        }
                    });
                // set initial state 
                if (!$input.val()) {
                    $watermark.trigger("position").show();
                }
            }
        });
    };
    $.fn.updnWatermark.defaults = {
        cssClass: "updnWatermark"
    };
    $.updnWatermark = {
        attachAll: function(options) {
            $.updnWatermark.all = $("input:text[title!=''],input:password[title!=''],textarea[title!='']").updnWatermark(options);
        },
        updateAll: function() {
            ($.updnWatermark.all && $.updnWatermark.all.each(function() {
                var $watermark = $(this).data("watermark");
                ($watermark && $watermark.trigger("update"));
            }));
        }
    };
})(jQuery);