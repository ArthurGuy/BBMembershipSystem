@extends('layouts.main')

@section('meta-title')
Member Roles
@stop
@section('page-title')
Member Roles
@stop

@section('content')


<script type="text/x-handlebars" data-template-name="application">
    <h1>App name: @{{appName}}</h1>
    @{{outlet}}
</script>

<script type="text/x-handlebars" data-template-name="roles">


    <section id="rolesapp">

        <section id="main">

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @{{#each controller}}
                        <tr>
                            <td>@{{ id }}</td>
                            <td>@{{name}}</td>
                            <td>@{{#link-to 'role' this}}@{{ name }}@{{/link-to}}</td>
                            <td><button @{{action 'deleteRole' this}}>Delete</button></td>
                        </tr>
                    @{{else}}
                    None
                    @{{/each}}
                </tbody>
            </table>

            <h4>Create a new Role</h4>
            @{{input type="text" value=newRoleName action="createRole"}}


        </section>

    </section>


</script>


<script type="text/x-handlebars" data-template-name="role">
    <h1>Name: @{{name}}</h1>
    <h2>Users</h2>
    @{{#each users}}
        <li>@{{given_name}}</li>
    @{{else}}
        No Users
    @{{/each}}

    @{{outlet}}
</script>

<script type="text/x-handlebars" data-template-name="createRoleMember">
    <label>User name</label>
    @{{input value=name}}
</script>


@stop