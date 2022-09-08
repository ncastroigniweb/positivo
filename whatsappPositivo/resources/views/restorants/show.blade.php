@extends('layouts.front', ['class' => ''])

@section('extrameta')
<title>{{ $restorant->name }}</title>
<meta property="og:image" content="{{ $restorant->logom }}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="590">
<meta property="og:image:height" content="400">
<meta name="og:title" property="og:title" content="{{ $restorant->name }}">
<meta name="description" content="{{ $restorant->description }}">
@endsection

@section('content')
<?php
    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }
?>
@include('restorants.partials.modals')

    <section class="section-profile-cover section-shaped grayscale-05 d-none d-md-none d-lg-block d-lx-block">
      <!-- Circles background -->
      <img class="bg-image" loading="lazy" src="{{ $restorant->coverm }}" style="width: 100%;">
      <!-- SVG separator -->
      <div class="separator separator-bottom separator-skew">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </section>

    <nav class="web-menu nav flex-column tabbable sticky" style="top: 50%; padding-left: 10px; position: fixed;">
                
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Nosotros" href="https://{{$_SERVER['SERVER_NAME']}}/blog/{{ $restorant->id }}-nosotros">
            <i class="fa fa-users" aria-hidden="true"></i>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Contáctenos" href="https://{{$_SERVER['SERVER_NAME']}}/blog/{{ $restorant->id }}-cont-ctenos">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
            </svg>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Preguntas frecuentes" href="https://{{$_SERVER['SERVER_NAME']}}/blog/{{ $restorant->id }}-preguntas-frecuentes">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question" viewBox="0 0 16 16">
            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Ubicación" href="https://{{$_SERVER['SERVER_NAME']}}/blog/{{ $restorant->id }}-ubicaci-n">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
        </a>
    </nav>
    

    <section class="section pt-lg-0 mb--5 mt--9 d-none d-md-none d-lg-block d-lx-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white"  <?php if($restorant->description){echo 'style="border-bottom: 1px solid #f2f2f2;"';} ?> >
                        <h1  hidden style="margin-bottom: 10px;" class="display-3 text-white notranslate" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;">{{ $restorant->name }}</h1>                     
                        <p class="display-4" style="margin-top: 160px">{{ $restorant->description }}</p>
                        <p><i class="ni ni-watch-time"></i> @if(!empty($openingTime))<span class="closed_time">{{__('Opens')}} {{ $openingTime }}</span>@endif @if(!empty($closingTime))<span class="opened_time">{{__('Opened until')}} {{ $closingTime }}</span> @endif |   @if(!empty($restorant->address))<i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($restorant->address) }}"><span class="notranslate">{{ $restorant->address }}</span></a>  | @endif @if(!empty($restorant->phone)) <i class="ni ni-mobile-button"></i> <a href="tel:{{$restorant->phone}}">{{ $restorant->phone }} </a> @endif</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @include('partials.flash')
                </div>
                @if (auth()->user()&&auth()->user()->hasRole('admin'))
                    @include('restorants.admininfo')
                @endif
            </div>
        </div>

    </section>
    <section class="section section-lg d-md-block d-lg-none d-lx-none" style="padding-bottom: 0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.flash')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">
                        <h1 class="display-3 text notranslate" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;">{{ $restorant->name }}</h1>
                        <p class="display-4 text">{{ $restorant->description }}</p>
                        <p><i class="ni ni-watch-time"></i> @if(!empty($openingTime))<span class="closed_time">{{__('Opens')}} {{ $openingTime }}</span>@endif @if(!empty($closingTime))<span class="opened_time">{{__('Opened until')}} {{ $closingTime }}</span> @endif   @if(!empty($restorant->address))<i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($restorant->address) }}">{{ $restorant->address }}</a>  | @endif @if(!empty($restorant->phone)) <i class="ni ni-mobile-button"></i> <a href="tel:{{$restorant->phone}}">{{ $restorant->phone }} </a> @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-lg-0" id="restaurant-content" style="padding-top: 0px">
        <input type="hidden" id="rid" value="{{ $restorant->id }}"/>
        <div class="container container-restorant">

            
            
            @if(!$restorant->categories->isEmpty())
        <nav class="tabbable sticky" style="top: {{ config('app.isqrsaas') ? 64:88 }}px;">
                <ul class="nav nav-pills bg-white mb-2">
                    <li class="nav-item nav-item-category ">
                        <a class="nav-link  mb-sm-3 mb-md-0 active" data-toggle="tab" role="tab" href="">{{ __('All categories') }}</a>
                    </li>
                    @foreach ( $restorant->categories as $key => $category)
                        @if(!$category->items->isEmpty())
                            <li class="nav-item nav-item-category" id="{{ 'cat_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                                <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" role="tab" id="{{ 'nav_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}" href="#{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">{{ $category->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                
            </nav>

            
            @endif

            


            @if(!$restorant->categories->isEmpty())
            @foreach ( $restorant->categories as $key => $category)
                @if(!$category->aitems->isEmpty())
                <div id="{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}" class="{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                    <h1>{{ $category->name }}</h1><br />
                </div>
                @endif
                <div class="row {{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                    @foreach ($category->aitems as $item)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="strip">
                                @if(!empty($item->image))
                                <figure>
                                    <a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)"><img src="{{ $item->logom }}" loading="lazy" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                                </figure>
                                @endif
                                <div class="res_title"><b><a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)">{{ $item->name }}</a></b></div>
                                <div class="res_description">{{ $item->short_description}}</div>
                                <div class="res_mimimum">@money($item->price, config('settings.cashier_currency'),config('settings.do_convertion'))</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                        <br/><br/><br/>
                        <div class="text-center" style="opacity: 0.2;">
                            <img src="https://www.jing.fm/clipimg/full/256-2560623_juice-clipart-pizza-box-pizza-box.png" width="200" height="200"></img>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Check if is installed -->
            @if (isset($doWeHaveImpressumApp)&&$doWeHaveImpressumApp)
                
                <!-- Check if there is value -->
                @if (strlen($restorant->getConfig('impressum_value',''))>5)
                    <h3>{{$restorant->getConfig('impressum_title','')}}</h3>
                    <?php echo $restorant->getConfig('impressum_value',''); ?>
                @endif
            @endif
            
        </div>

        @if(  !(isset($canDoOrdering)&&!$canDoOrdering)   )
            <div onClick="openNav()" class="callOutShoppingButtonBottom icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
                <i class="ni ni-cart"></i>
            </div>

            <div style="background-color: #20bf55; position: fixed;  bottom: 0px; left: 20px; z-index: 1000;" class="icon icon-shape text-white rounded-circle shadow mb-4">
                <a class="text-white" target="_blank" title="Whastapp" href="https://api.whatsapp.com/send?phone={{$restorant->phone}}">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                </a>            
            </div>
        @endif

    </section>
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-header bg-transparent pb-2">
                            <h4 class="text-center mt-2 mb-3">{{ __('Call Waiter') }}</h4>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="post" action="{{ route('call.waiter') }}">
                                @csrf
                                @include('partials.fields',$fields)
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4">{{ __('Call Now') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@if ($showGoogleTranslate&&!$showLanguagesSelector)
    @include('googletranslate::buttons')
@endif
@if ($showLanguagesSelector)
    @section('addiitional_button_1')
        <div class="dropdown web-menu">
            <a href="#" class="btn btn-neutral dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                <!--<img src="{{ asset('images') }}/icons/flags/{{ strtoupper(config('app.locale'))}}.png" /> --> {{ $currentLanguage }}
            </a>
            <ul class="dropdown-menu" aria-labelledby="">
                @foreach ($restorant->localmenus()->get() as $language)
                    @if ($language->language!=config('app.locale'))
                        <li>
                            <a class="dropdown-item" href="?lang={{ $language->language }}">
                                <!-- <img src="{{ asset('images') }}/icons/flags/{{ strtoupper($language->language)}}.png" /> --> {{$language->languageName}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endsection
    @section('addiitional_button_1_mobile')
        <div class="dropdown mobile_menu">
           
            <a type="button" class="nav-link  dropdown-toggle" data-toggle="dropdown"id="navbarDropdownMenuLink2">
                <span class="btn-inner--icon">
                  <i class="fa fa-globe"></i>
                </span>
                <span class="nav-link-inner--text">{{ $currentLanguage }}</span>
              </a>
            <ul class="dropdown-menu" aria-labelledby="">
                @foreach ($restorant->localmenus()->get() as $language)
                    @if ($language->language!=config('app.locale'))
                        <li>
                            <a class="dropdown-item" href="?lang={{ $language->language }}">
                               <!-- <img src="{{ asset('images') }}/icons/flags/{{ strtoupper($language->language)}}.png" /> ---> {{$language->languageName}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endsection
@endif

@section('js')
    <script>
        var CASHIER_CURRENCY = "<?php echo  config('settings.cashier_currency') ?>";
        var LOCALE="<?php echo  App::getLocale() ?>";
        var IS_POS=false;
    </script>
    <script src="{{ asset('custom') }}/js/order.js"></script>
    @include('restorants.phporderinterface') 
    @if ($showGoogleTranslate&&!$showLanguagesSelector)
        @include('googletranslate::scripts')
    @endif
     <script>
        var cpId ='{{$restorant->id}}';
        
        var rut= "https://{{$_SERVER['SERVER_NAME']}}/blog/{{ $restorant->id }}";
        $('#pg-nosotros').click(function(){
            sessionStorage.setItem("cpId", cpId);            
            window.location.href = rut+'-nosotros';           

        }); 
        $('#pg-cont-ctenos').click(function(){
            sessionStorage.setItem("cpId", cpId);             
            window.location.href = rut+'-cont-ctenos';
            
        });
        $('#pg-preguntas-frecuentes').click(function(){
            sessionStorage.setItem("cpId", cpId);             
            window.location.href = rut+'-preguntas-frecuentes';
        });
        $('#pg-ubicaci-n').click(function(){
            sessionStorage.setItem("cpId", cpId);            
            window.location.href = rut+'-ubicaci-n';
        });
    </script> 

    <script>
        $(document).ready(function(){
            var networks = JSON.parse(JSON.stringify(<?= $networks ?>)); 
            $('#facebook').attr('href',networks['Facebook']);
            $('#instagram').attr('href',networks['Instagram']);                       
        });           
    </script>

@endsection

@if ($showGoogleTranslate&&!$showLanguagesSelector)
    @section('head')
        <!-- Style  Google Translate -->
        <link type="text/css" href="{{ asset('custom') }}/css/gt.css" rel="stylesheet">
    @endsection
@endif