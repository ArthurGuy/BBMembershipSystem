@extends('layouts.bare')

@section('meta-title')
    Build Brighton API Docs
@stop

@section('page-title')
    Build Brighton API Docs
@stop

@section('content')

    <link href='vendor/swaggervel/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>

    <div class="swagger-section">
        <nav id='header' class="navbar navbar-fixed-top">
            <div class="swagger-ui-wrap">
                <a id="logo" href="https://bbms.buildbrighton.com">Build Brighton API Docs</a>
                <form id='api_selector'>
                    <div class='input'><input placeholder="ApiKey" id="input_apiKey" name="apiKey" type="text"/></div>
                </form>
            </div>
        </nav>
        <div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
        <div id="swagger-ui-container" class="swagger-ui-wrap"></div>
    </div>

@stop

@section('footer-js')
    <script src='vendor/swaggervel/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/underscore-min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/backbone-min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/swagger-ui.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/marked.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/swagger-oauth.js' type='text/javascript'></script>
    <script type="text/javascript">

        $(function () {
            var swaggerDocUrl = "{!! $urlToDocs !!}";

            window.swaggerUi = new SwaggerUi({
                url: swaggerDocUrl,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                validatorUrl: null,
                docExpansion: "list",
                apisSorter: "alpha",
                showRequestHeaders: false,
                onComplete: function (swaggerApi, swaggerUi) {

                    console.log("Loaded SwaggerUI");

                    $('pre code').each(function (i, e) {
                        hljs.highlightBlock(e)
                    });

                    addApiKeyAuthorization();
                },
                onFailure: function (data) {
                    console.log("Unable to Load SwaggerUI");
                }
            });

            function addApiKeyAuthorization() {
                var key = encodeURIComponent($('#input_apiKey')[0].value);
                if (key && key.trim() != "") {
                    var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("ApiKey", key, "header");
                    window.swaggerUi.api.clientAuthorizations.add("api_key", apiKeyAuth);
                    console.log("added key " + key);
                }
            }

            $('#input_apiKey').change(addApiKeyAuthorization);

            window.swaggerUi.load();

        });
    </script>
@stop