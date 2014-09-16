<script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>

<div class="page-header">
    <h1>Realtime Activity Log</h1>
</div>

<h3>Door Access</h3>
<div id="member-grid">
    <div class="row">

    </div>
</div>

<table class="table" id="door-activity-log">
    <thead>
        <tr>
            <td>User ID</td>
            <td>Response</td>
            <td>Key Fob</td>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<h3>Other</h3>
<table class="table" id="other-activity-log">
    <thead>
        <tr>
            <td>Status</td>
            <td>User ID</td>
            <td>Response</td>
            <td>Key Fob</td>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script type="text/javascript">
    // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
        window.console.log(message);
    };

    var pusher = new Pusher('76cf385da8c9087f9d68');
    var activityChannel = pusher.subscribe('activity');
    activityChannel.bind('main-door', function(data) {
        $('#door-activity-log').find('tbody').append("<tr><td>"+data.user_id+"</td><td>"+data.response+"</td><td>"+data.key_fob_id+"</td></tr>");
        $('#member-grid').find('.row').append('<div class="col-sm-6 col-md-4 col-lg-3"><div class="thumbnail"><img src="'+data.user_image+'" width="200" height="200" /><div class="caption"><strong>'+data.user_name+'</strong></div></div></div>');
    });
    activityChannel.bind('status', function(data) {
        $('#other-activity-lo').find('tbody').append("<tr><td>status</td><td>"+data.user_id+"</td><td>"+data.response+"</td><td>"+data.key_fob_id+"</td></tr>");
    });
</script>
