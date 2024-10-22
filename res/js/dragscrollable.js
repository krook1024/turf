;
(function($) {
    $.fn.dragscrollable = function(options) {
        var settings = $.extend({ dragSelector: '>:first', acceptPropagatedEvent: true, preventDefault: true }, options || {});
        var dragscroll = {
            mouseDownHandler: function(event) {
                if (event.which != 1 || (!event.data.acceptPropagatedEvent && event.target != this)) {
                    return false; }
                event.data.lastCoord = { left: event.clientX, top: event.clientY };
                $.event.add(document, "mouseup", dragscroll.mouseUpHandler, event.data);
                $.event.add(document, "mousemove", dragscroll.mouseMoveHandler, event.data);
                if (event.data.preventDefault) { event.preventDefault();
                    return false; }
            },
            mouseMoveHandler: function(event) {
                var delta = { left: (event.clientX - event.data.lastCoord.left), top: (event.clientY - event.data.lastCoord.top) };
                event.data.scrollable.scrollLeft(event.data.scrollable.scrollLeft() - delta.left);
                event.data.scrollable.scrollTop(event.data.scrollable.scrollTop() - delta.top);
                event.data.lastCoord = { left: event.clientX, top: event.clientY }
                if (event.data.preventDefault) { event.preventDefault();
                    return false; }
            },
            mouseUpHandler: function(event) { $.event.remove(document, "mousemove", dragscroll.mouseMoveHandler);
                $.event.remove(document, "mouseup", dragscroll.mouseUpHandler);
                if (event.data.preventDefault) { event.preventDefault();
                    return false; } }
        }
        this.each(function() {
            var data = { scrollable: $(this), acceptPropagatedEvent: settings.acceptPropagatedEvent, preventDefault: settings.preventDefault }
            $(this).find(settings.dragSelector).bind('mousedown', data, dragscroll.mouseDownHandler);
        });
    };
})(jQuery);
