<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>CSV Solutions - {{{ $page_title }}}</title>
  <meta name="description" content="Map, Merge and Transform your spreadsheet (excel, csv) to the template you prefer. Convert mapped spreadsheet to Excel and CSV formats.">
  <meta name="keywords" content="merge spreadsheet, map spreadsheet, map csv, merge csv, merge columns, template mapping, template csv mapping">
  <meta name="author" content="Purinda Gunasekara">

  <!-- CSS (Disabled Local CSS) -->
  <link rel="stylesheet" href="{{ URL::to('static/css/style.css') }}" type="text/css" media="screen" />
  <link rel="stylesheet" href="{{ URL::to('static/css/jquery.datatables.css') }}" type="text/css" media="screen" />

  <!-- Custom Theme  -->
  <link rel="stylesheet" href="{{ URL::to('static/bootstrap/css/bootstrap.min.css') }}" type="text/css" media="screen" />

  <!-- Globals -->
  <script type="text/javascript">
      window.BaseUrl = "{{ URL::to('/') }}";
      var Auth = {};
    @if(Auth::check())
      Auth.Check = true;
    @else
      Auth.Check = false;
    @endif
  </script>

  <!-- Javascripts -->
  <script type="text/javascript" src="{{ URL::to('static/js/jquery-2.1.0.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('static/bootstrap/js/bootstrap.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('static/js/jquery.datatables.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('static/js/jquery.datatables.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('static/js/jquery.serializeobject.js') }}"></script>
  <script type="text/javascript" src="{{ URL::to('static/js/csvfix.js') }}"></script>
</head>
<body>
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">CSV Solutions</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
<!--           <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">File<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#"><span class="glyphicon glyphicon-th"></span> CSV Viewer</a></li>
              <li class="divider"></li>
              <li><a href="#"><span class="glyphicon glyphicon-filter"></span> Map/Merge Fields</a></li>
            </ul>
          </li>
-->
          <li><a href="resources">Resources</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
          @if(!Auth::check())
            <a href="#" class="dropdown-toggle user" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><b class="caret"></b></a>
          @else
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"> </span> {{ Auth::user()->name }}<b class="caret"></b></a>
          @endif
            <ul class="dropdown-menu">
              @if(!Auth::check())
              <li><a href="#" data-toggle="modal" data-target="#sign-in-dialog"><span class="glyphicon glyphicon-pencil"></span> Sign In</a></li>
              <li><a href="#" data-toggle="modal" data-target="#registration-dialog"><span class="glyphicon glyphicon-hand-up"></span> Register</a> </li>
              @else
              <li><a href="#" class="btn-logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a> </li>
              <li class="divider"></li>
              <li><a id="btn-show-mappings-dlg" data-toggle="modal" data-target="#mappings-list-dialog"><span class="glyphicon glyphicon-th-list"></span> Mappings</a></li>
              @endif
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <!-- User registration -->
  <div class="modal fade" id="registration-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Register</h4>
        </div>
        <div class="modal-body">
            <div class="message-area">
              <!-- fill based on runtime/user errors -->
            </div>
            <div class="form-group">
              <label for="register-name-input">Name</label>
              <input type="text" class="form-control name-input" id="register-name-input" placeholder="Your name">
            </div>
            <div class="form-group">
              <label for="register-email-input">Email address</label>
              <input type="email" class="form-control email-input" id="register-email-input" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="register-password-input">Password</label>
              <input type="password" class="form-control password-input" id="register-password-input" placeholder="Password">
            </div>
            <div class="form-group">
              <label for="register-password-input">Verify Password</label>
              <input type="password" class="form-control password-verify-input" id="register-password-verify-input" placeholder="Re-enter Password">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-register">Register</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Login -->
  <div class="modal fade" id="sign-in-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Sign-in</h4>
        </div>
        <div class="modal-body">
            <div class="message-area">
              <!-- fill based on runtime/user errors -->
            </div>
            <div class="form-group">
              <label for="email-input">Email address</label>
              <input type="email" class="form-control email-input" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="password-input">Password</label>
              <input type="password" class="form-control password-input" placeholder="Password">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox"> Remember my details
              </label>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-signin">Sign in</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Mappings list -->
  <div class="modal fade" id="mappings-list-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Data Mappings</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Page content -->
  @yield('content')
</body>


<!-- Google Analytics -->
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49756209-1', 'csvfix.com');
  ga('send', 'pageview');

</script>

</html>
