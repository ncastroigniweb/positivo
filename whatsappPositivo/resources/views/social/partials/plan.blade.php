<div class="col-sm-12 col-md-{{$col}} col-lg-{{$col}} mb-{{$col}} ">
  <div class="card-plans cardWithShadow pricingCard">
    <div class="card-body">
      <!-- <div class="imgHolderInCard">
        <img class="image-in-card"
          src="{{ asset('social') }}/img/SVG/512/rocket.svg" />
      </div> -->
      <h5 class="card-title text-center" style="color: #31b1a0; font-family: sans-serif;">{{  __($plan['name']) }}</h5>
      <p class="card-text text-center" style="font-weight: bold;">{{ __($plan['description']) }}</p>
      <img style="width: 90%;" src="{{ asset('social') }}/img/plans/{{  __($plan['image']) }}" alt="">
      <div class="price-block">
        <!--<span class="price-block-currency">{{ config('settings.cashier_currency') }}</span>
        <span class="price-block-value">{{ number_format($plan['price'] ,0,'.','.') }}</span>
        <span class="price-block-period">/{{  $plan['period'] == 1? __('whatsapp.month') :  __('whatsapp.year') }}</span>-->
      </div>
      <div class="plan_feature_list">
        <ul class="plan_features list-unstyled">
          @foreach (explode(",",$plan['features']) as $feature)
            <li>
              <p class="text-sm">
                <i class="fa fa-check-circle-o" aria-hidden="true" style="color: #31b1a0"></i> {{ __($feature) }}
              </p>
            </li>
          @endforeach
        </ul>
      </div>
      <br />
      <a href="{{ route('newrestaurant.register') }}" type="button" class="btn btn-outline-success">
        {{ __('whatsapp.start_now')}}
      </a>
    </div>
  </div>
</div>