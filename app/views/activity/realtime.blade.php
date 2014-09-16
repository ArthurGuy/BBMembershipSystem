<script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>

<div class="page-header">
    <h1>Realtime Activity Log</h1>
</div>

<h3>Door Access</h3>
<div id="member-grid" class="member-grid">
    <div class="row">

    </div>
</div>


<script type="text/javascript">
    // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
        window.console.log(message);
    };

    var pusher = new Pusher('76cf385da8c9087f9d68');
    var activityChannel = pusher.subscribe('activity');
    activityChannel.bind('main-door', function(data) {
        if (data.response == 200) {
            $('#member-grid').find('.row').prepend('<div class="col-sm-6 col-md-6 col-lg-6"><div class="thumbnail"><img src="'+data.user_image+'" width="500" height="500" /><div class="caption"><h3>'+data.user_name+'</h3>'+data.time+'</div></div></div>');
        }
    });
</script>
