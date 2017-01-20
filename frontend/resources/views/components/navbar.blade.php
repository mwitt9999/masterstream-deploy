<?php  $user = \Auth::user(); ?>
<nav class="navbar navbar-dark navbar-fixed-top bg-inverse">
    <button type="button" class="navbar-toggler hidden-sm-up" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" aria-label="Toggle navigation"></button>
    <a class="navbar-brand" href="#">Masterstream Deploy</a>
    <div id="navbar">
        <form class="form-inline float-xs-right" action="/logout" method="get">
            <button class="btn btn-outline-info" type="submit">Logout</button>
        </form>
        <nav class="nav navbar-nav float-lg-right mr-1">
            <a class="nav-item nav-link" href="#">Settings</a>
            <a class="nav-item nav-link" href="#">Profile</a>
            <a class="nav-item nav-link align-middle text-white"> Hello, {{ $user->first_name }}</a>
        </nav>
    </div>
</nav>
