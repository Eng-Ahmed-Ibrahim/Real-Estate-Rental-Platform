
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-body p-lg-17">


                    <div id="calendar"></div>


                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventDays" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Event_days')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="title">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                    <button type="button" id="save-changes" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            var bookings = @json($bookings);
            var customDays = @json($eventDays);
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay ',
                },
                events: bookings,
                selectable: true,
                selectHelper: true,



                select: function(start, end, allDays) {
                    $("#eventDays").modal('toggle')
                    $('#save-changes').click(function() {

                        let start_at = moment(start).format('M/D/YYYY')
                        let end_at = moment(end).add(1, 'days').format('M/D/YYYY')
                        let title = $('#title').val();
                        $.ajax({
                            url: "{{route('test')}}",
                            type: "POST",
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                title: title,
                                start_at: start_at,
                                end_at: end_at
                            },
                            success: function(response) {
                                console.log(response);
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        })
                    })
                },
                editable:true,
                
                
            });

        });
    </script>
