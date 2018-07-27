<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript">
            (function(document, navigator, standalone) {
                if ((standalone in navigator) && navigator[standalone]) {
                    var curnode, location=document.location, stop=/^(a|html)$/i;
                    document.addEventListener('click', function(e) {
                        curnode=e.target;
                        while (!(stop).test(curnode.nodeName)) {
                            curnode=curnode.parentNode;
                        }
                        if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
                            e.preventDefault();
                            location.href = curnode.href;
                        }
                    },false);
                }
            })(document,window.navigator,'standalone');
        </script>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="HandheldFriendly" content="true" />
        <meta name="description" content="{% block description %}{% endblock %}">
        <meta name="keywords" content="{% block keywords %}{% endblock %}">
        <title>Reddit Toolbox Mobile</title>

        <meta name="theme-color" content="blue">
        <meta name="mobile-web-app-capable" content="yes">

        <meta name="apple-mobile-web-app-title" content="SRTR">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <meta name="msapplication-navbutton-color" content="blue">
        <meta name="msapplication-TileColor" content="blue">
        <meta name="msapplication-TileImage" content="ms-icon-144x144.png">
        <meta name="msapplication-config" content="browserconfig.xml">
        <meta name="application-name" content="SRTR">

        <meta name="msapplication-tooltip" content="Tooltip Text">
        <meta name="msapplication-starturl" content="/">
        <meta name="msapplication-tap-highlight" content="no">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> 
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>

        <script>
            jQuery.fn.visible = function() {
                return this.css('visibility', 'visible');
            };

            jQuery.fn.invisible = function() {
                return this.css('visibility', 'hidden');
            };

            jQuery.fn.visibilityToggle = function() {
                return this.css('visibility', function(i, visibility) {
                    return (visibility == 'visible') ? 'hidden' : 'visible';
                });
            };

            !(function($) {
                var toggle = $.fn.toggle;
                $.fn.toggle = function() {
                    var args = $.makeArray(arguments),
                        lastArg = args.pop();

                    if (lastArg == 'visibility') {
                        return this.visibilityToggle();
                    }

                    return toggle.apply(this, arguments);
                };
            })(jQuery);

            function escapeRegExp(str) {
                return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
            }

            function replaceAll(str, find, replace) {
                return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
            }
        </script>

        <script src="/service-worker.js"></script>
        <link rel="manifest" href="/manifest.json">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ urlFor('home') }}">
                    <img src="/logo.svg" width="30" height="30" class="d-inline-block align-top" alt="">
                    Mobile Toolbox for Reddit
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">User Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ urlFor('remove') }}">Remove</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
            
        {% include 'templates/partials/messages.php' %}

        {% block content %}{% endblock %}
    </body>
</html>