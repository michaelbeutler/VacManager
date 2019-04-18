
/**
* Theme: Velonic Admin Template
* Author: Coderthemes
* Full calendar page
*/

!function ($) {
    "use strict";

    var CalendarPage = function () { };

    CalendarPage.prototype.init = function () {

        //checking if plugin is available
        if ($.isFunction($.fn.fullCalendar)) {
            /* initialize the external events */
            $('#external-events .fc-event').each(function () {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });

            });

            /* initialize the calendar */

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                editable: false,
                weekNumbers: true,
                eventLimit: false, // allow "more" link when too many events
                droppable: false, // this allows things to be dropped onto the calendar !!!
                eventClick: function (info) {
                    console.log(info);
                    $('tr').each(function (index, element) {
                        $(element).removeClass('active');
                    });
                    $('#crequest' + info.id).addClass('active');
                },
                eventSources: [

                    // your event source
                    {
                        url: './includes/new/vacation.inc.php?action=FULLCALENDAR&view=EMPLOYER', // use the `url` property
                    }

                ]
            });

        }
        else {
            alert("Calendar plugin is not installed");
        }
    },
        //init
        $.CalendarPage = new CalendarPage, $.CalendarPage.Constructor = CalendarPage
}(window.jQuery),

    //initializing 
    function ($) {
        "use strict";
        $.CalendarPage.init()
    }(window.jQuery);
