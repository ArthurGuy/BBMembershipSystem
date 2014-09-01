<div class="page-header">
    <h1>Member Storage Boxes</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Size</th>
            <th>Member</th>
        </tr>
    </thead>
@foreach ($storageBoxes as $box)
    <tbody>
        <tr>
            <td>{{ $box->id }}</td>
            <td>{{ $box->size }}</td>
            <td>{{ $box->user->name or 'Available' }}</td>
        </tr>
    </tbody>
@endforeach
</table>