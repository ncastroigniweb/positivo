<footer class="footer notranslate">

    <div class="container">
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; <?php echo e(date('Y')); ?> <a href="" target="_blank"><?php echo e(config('global.site_name', 'mResto')); ?></a>.
          </div>
        </div>
        <div class="col-md-6">  
                
          <ul id="footer-pages" class="nav nav-footer justify-content-end">
           
            

          <?php if(config('app.isft')&&config('settings.driver_link_register_position')=="footer"): ?>
          <li class="nav-item">
              <a target="_blank" class="button nav-link nav-link-icon" href="<?php echo e(route('driver.register')); ?>"><?php echo e(__(config('settings.driver_link_register_title'))); ?></a>
            </li>
            <?php endif; ?>              
         
            
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
  
<?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/layouts/footers/front.blade.php ENDPATH**/ ?>