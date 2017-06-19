;
(function($) {
    $.fn.scrollsync = function(options) {
        var settings = $.extend({ targetSelector: ':first', axis: 'xy' }, options || {});

        function scrollHandler(event) {
            if (event.data.xaxis) { event.data.followers.scrollLeft(event.data.target.scrollLeft()); }
            if (event.data.yaxis) { event.data.followers.scrollTop(event.data.target.scrollTop()); }
        }
        settings.target = this.filter(settings.targetSelector).filter(':first');
        settings.followers = this.not(settings.target);
        settings.xaxis = (settings.axis == 'xy' || settings.axis == 'x') ? true : false;
        settings.yaxis = (settings.axis == 'xy' || settings.axis == 'y') ? true : false;
        if (!settings.xaxis && !settings.yaxis) return;
        settings.target.bind('scroll', settings, scrollHandler);
    };
})(jQuery);
