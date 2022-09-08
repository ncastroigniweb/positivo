<div id="product" class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-8 mx-auto text-center">
          <h3 class="display-3 ckedit" key="explain_maintitle" id="explain_maintitle">{{ __('whatsapp.explain_maintitle') }}</h3>
          <p class="lead ckedit" key="explain_mainsubtitle" id="explain_mainsubtitle">{{ __('whatsapp.explain_mainsubtitle') }}</p>
          </div>
        </div>
        <div class="row align-items-center mt-5">
          <div class="col-md-7">
            @foreach ($processes as $key => $process)
              <div class="info info-horizontal info-hover-primary mt-5">
                <div class="description pl-4">
                  <h5 class="title">{{ $process->title }}</h5>
                  <p>{{ $process->description }}</p>
                  <a href="{{ $process->link }}" class="text-info">{{ $process->link_name }}</a>
                </div>
              </div>
            @endforeach
          </div>


          <!-- seccion de pricing  -->
          <div class="col-md-5">
            <img class="img-fluid" src="{{ asset('social') }}/img/pc.png" />
          </div>
        </div>
      </div>
    </div>