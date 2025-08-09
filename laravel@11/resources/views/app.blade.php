<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ddr3 P.O.S & Inventory</title>

        <!-- script -->
        @vite(['src/app.js', 'resources/css/app.css'])
    </head>
    <body>
        @if($isValidJWT && !$isSalesRep)
            <div id="app">
                <App></App>
            </div>
        @elseif($isSalesRep)
            <div id="app">
                <ice-pos></ice-pos>
            </div>
        @else
            <div id="app">
                <login></login>
            </div>
        @endif

    </body>

</html>
