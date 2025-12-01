<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Statistiques</title>
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link href="{{asset('assets/fontawesome/css/all.css')}}" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="{{ asset('css/welcome_style.css') }}" rel="stylesheet">



    </head>
    <body class="container-fluid" style>
        {{--<div class="container-fluid h-100" id="app" style="background: rgba(255, 255, 255, 0.2)">--}}
        <div class="container-fluid h-100" id="app">
            <div class="row" >
                <div class="col-md-7 text-white d-flex d-flex-column align-items-center justify-content-between h-100 left" id="intro">
                    {{--<div class="row h-100" style="background: rgba(251, 255, 251, 0.5)">--}}
                    <div class="row d-flex-column justify-content-between align-items-center">
                        <div class="marge-gauche-20">
                            <<div style="text-align: center; padding: 20px;">
                                <img class="logo" src="{{asset('images/armoirie_ci_fond_transparent.png')}}" 
                                    alt="Description de l'image" 
                                    style="width: 50%; max-width: 400px; height: auto; display: block; margin: 0 auto;">
                            </div>
                            <hr>
                            <p class="marge-gauche-20">
                                <h5 class="col-md-12"><span class="text-white small">{{__('DSESF ')}}</span></h5>
                                <h3 class="col-md-12"><strong class="text-orange small">{{__('Direction de la Strategie, des Etudes et Statistiques Fiscales de la DGI')}}</strong> <div class="text-italique"> {{__('Suivi et Exécution des Tâches')}} </div></h3>
                            </p>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </div>

                <div class="col-md-5 right" >
                    {{--<div class="d-flex flex-column justify-content-center h-100" id="header" >--}}
                    <div class="d-flex justify-content-center h-100 " id="header" >


                        <div class="col-md-12 row ">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 ">

                                <h6 class="display-6" >

                                    <div class="form-group col-md-12">

                                        <p class="text-center ">
                                                {{--<img class="logo" src="{{asset('img/Edr2.jpg')}}" alt="" width="350px">--}}
                                                {{-- <img class="logo" src="{{asset('images/logo-MFP.png')}}" alt="" width="180px"> --}}
                                                <img class="logo" src="{{asset('images/DGI.png')}}" alt="" width="180px">
                                        </p>

                                       {{--
                                        <label for="">{{__('lang.libelleLang')}}</label>
                                        <select class="form-control col-md-12" name="laLangue" id="laLangue">
                                            <option value="fr" {{(session('locale') && session('locale')==='fr')?'selected':''}}><a href="{{route('changeLangue','fr')}}">{{__('lang.langFr')}}</a></option>
                                            <option value="en" {{(session('locale') && session('locale')==='en')?'selected':''}}><a href="{{route('changeLangue','en')}}">{{__('lang.langEn')}}</a></option>
                                        </select>
                                        --}}
                                    </div>
                                    <div class="form-group col-md-12">
                                            <hr>
                                    </div>
                                </h6>
                                <h3 class="form-group text-center">{{__('Login')}}</h3>
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p> <small>{{ $message }}</small></p>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}" class="shadow-lg p-3 mb-5 bg-white rounded">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email" class="col-md-12 col-form-label">{{ __('email') }}</label>

                                        <div class="col-md-12">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-md-12 col-form-label">{{ __('password') }}</label>

                                        <div class="col-md-12">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 o ffset-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('remenber') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-sm form-control couleur-orange">
                                            {{--<button type="submit" class="btn btn-sm form-control bg-success text-white">--}}
                                                {{ __('login') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link text-danger" href="{{ route('password.request') }}">
                                                    {{ __('Mot de passe oublie') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2"></div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/pdf.js/1.8.349/pdf.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
         <!-- Scripts -->
         <script src="{{ asset('js/app.js') }}" defer></script>
        <script>
             $(document).ready(function() {
                $("select#laLangue").change(function(){
                var selectedCountry = $(this).children("option:selected").val();

                var url = "{{URL('/changeLangue')}}";
                var urlRedirect = "{{URL('/')}}";
                var dltUrl = url+"/"+selectedCountry;
                //alert("You have selected the country - " + dltUrl);
                jQuery.ajax({
                    url: dltUrl,
                    method: 'GET',
                    data: {

                    },
                    success: function(data) {
                        dataToObj = JSON.parse(data);
                        window.location.href = urlRedirect;
                        //alert(dataToObj.success);
                        //alert(dataToJson.message+' , '+dataToJson.success);

                        /*if(dataToObj.success)
                        {
                            currentElement.remove();
                            var message = $('#messageDiplome');
                            var successHtml = '<div class="alert alert-success">'+
                            //'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></</strong> '+ dataToObj.message +
                            '</div>';

                            $(message).html(successHtml);
                        }*/
                    }
                    });
            });
            });
        </script>

    </body>
</html>