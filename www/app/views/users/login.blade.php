@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p>
                    <strong>Error!</strong> Check the following before retrying!
                </p>

                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{ Form::open(array('url' => 'login', 'class' => 'form-horizontal')) }}
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="{{{ Input::old('username') }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Keep me logged in
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="submit" class="btn btn-default">Sign in</button>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ URL::to('register') }}" class="btn btn-success">Register</a>
                    </div>
                </div>
            {{ Form::close() }}

        </div><!-- .col-md-6 -->

    </div><!-- .row -->
</div>
@stop
