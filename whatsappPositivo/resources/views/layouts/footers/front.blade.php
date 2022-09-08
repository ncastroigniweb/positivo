<footer class="footer notranslate">

    <div class="container">
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; {{ date('Y') }} <a href="" target="_blank">{{ config('global.site_name', 'mResto') }}</a>.
          </div>
        </div>
        <div class="col-md-6">  
                
          <ul id="footer-pages" class="nav nav-footer justify-content-end">
           
            {{-- <li v-for="page in pages" class="nav-item" v-cloak>
                <a :href="'/pages/' + page.id" class="nav-link">@{{ page.title }}</a>
            </li> --}}
{{-- 
            @if (!config('settings.single_mode')&&config('settings.restaurant_link_register_position')=="footer")
            <li class="nav-item">
              <a  target="_blank" class="button nav-link nav-link-icon" href="{{ route('newrestaurant.register') }}">{{ __(config('settings.restaurant_link_register_title')) }}</a>
            </li>
          @endif --}}
          @if (config('app.isft')&&config('settings.driver_link_register_position')=="footer")
          <li class="nav-item">
              <a target="_blank" class="button nav-link nav-link-icon" href="{{ route('driver.register') }}">{{ __(config('settings.driver_link_register_title')) }}</a>
            </li>
            @endif              
         
            
          </ul>
          <table>
            <tr>
              <td>
                <a id="facebook" href="" target="_blank"class="btn-icon-only rounded-circle btn btn-facebook" data-toggle="tooltip" data-original-title="Like us">
                  <span class="btn-inner--icon"><i class="fa fa-facebook"></i></span>
                </a> 
              </td>
              <td>
                <a id="instagram" href="" target="_blank" class="btn-icon-only rounded-circle btn btn-instagram" data-toggle="tooltip" data-original-title="Like us">
                  <span class="btn-inner--icon"><i class="fa fa-instagram"></i></span>
                </a> 
              </td>
            </tr>
          </table>          
        </div>
      </div>
    </div>
  </footer>
  
