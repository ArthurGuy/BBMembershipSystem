
@if (Notification::hasMessage())
<div class="snackBar snackBar-{{ Notification::getLevel() }}">
    {{ Notification::getMessage() }}
    @if (Notification::hasDetails())
    <ul>
        @foreach(Notification::getDetails()->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
</div>
<script>
    setTimeout(function () {
            $('.snackBar').fadeOut();
    }, 3000);
    $('.snackBar').on('click', function() {
        $(this).fadeOut();
    });
</script>
@endif



