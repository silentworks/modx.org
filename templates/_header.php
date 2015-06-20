<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MODX Community Slack</title>
    <link rel="stylesheet" href="assets/css/foundation.min.css"/>
    <link rel="stylesheet" href="assets/css/app.css"/>
    <script src="assets/js/vendor/modernizr.js"></script>

    <script>
        function init() {
            var imgDefer = document.getElementsByTagName('img');
            for (var i = 0; i < imgDefer.length; i++) {
                if (imgDefer[i].getAttribute('data-src')) {
                    imgDefer[i].setAttribute('src', imgDefer[i].getAttribute('data-src'));
                }
            }
        }
        window.onload = init;
    </script>
</head>

<body>
<div class="container">