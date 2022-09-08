<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KKGCD87');</script>
    <!-- End Google Tag Manager -->


    <!-- Google Tag Manager -->
    
    <!-- End Google Tag Manager -->

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('social')); ?>/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo e(asset('social')); ?>/img/favicon.png">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('global.site_name','WhatsMenu')); ?></title> 

    <!-- Global site tag (gtag.js) - Google Analytics -->
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    
    <?php echo $__env->yieldContent('head'); ?>
    <?php $config = (new \LaravelPWA\Services\ManifestService)->generate(); echo $__env->make( 'laravelpwa::meta' , ['config' => $config])->render(); ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="<?php echo e(asset('social')); ?>/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?php echo e(asset('social')); ?>/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo e(asset('social')); ?>/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo e(asset('social')); ?>/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="<?php echo e(asset('social')); ?>/css/argon-design-system.css?v=1.2.3" rel="stylesheet" />
    <link href="<?php echo e(asset('social')); ?>/css/custom.css?v=1.2.3" rel="stylesheet" />

    <!-- Custom CSS defined by admin -->
    <link type="text/css" href="<?php echo e(asset('byadmin')); ?>/front.css" rel="stylesheet">

    <!---- Chat CSS --------->
    <link href="<?php echo e(asset('social')); ?>/css/floating-wpp.css" rel="stylesheet" />

</head>

<body class="landing-page">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KKGCD87" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Google Tag Manager (noscript) -->

<!-- End Google Tag Manager (noscript) -->

 
  <!-- Google Tag Manager (noscript) -->

<!-- End Google Tag Manager (noscript) -->
  <!-- Navbar -->
  <?php echo $__env->make('social.partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- MAIN WRAPPER-->
  <div class="wrapper">

    <!-- HERO -->
    <?php echo $__env->make('social.partials.hero', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- FEATURES -->
    <?php echo $__env->make('social.partials.features', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- EXPLAIN -->
    <?php echo $__env->make('social.partials.explain', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- PRICING -->
    <?php echo $__env->make('social.partials.pricing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- DEMO -->
    

    <!-- Testimonials -->
    <?php echo $__env->make('social.partials.testimonials', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- FOOTER -->
    <?php echo $__env->make('social.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- MODALS -->
    <?php echo $__env->make('social.partials.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

  </div>
   <!-- END MAIN WRAPPER-->
   <!--- CHAT FLOATING -->
   <?php echo $__env->make('social.partials.floatingchat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!--   Core JS Files   -->
  <script src="<?php echo e(asset('social')); ?>/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo e(asset('social')); ?>/js/core/popper.min.js" type="text/javascript"></script>
  <script src="<?php echo e(asset('social')); ?>/js/core/bootstrap.min.js" type="text/javascript"></script>
  <script src="<?php echo e(asset('social')); ?>/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="<?php echo e(asset('social')); ?>/js/argon-design-system.min.js?v=1.2.2" type="text/javascript"></script>
  <script src="<?php echo e(asset('social')); ?>/js/floating-wpp.js?v=1.2.2" type="text/javascript"></script>

   <!-- All in one -->
   <script src="<?php echo e(asset('custom')); ?>/js/js.js?id=<?php echo e(config('config.version')); ?>s"></script>

    <!-- Custom JS defined by admin -->
    <?php echo file_get_contents(base_path('public/byadmin/front.js')) ?>

  <!-- Notify JS -->
  <script src="<?php echo e(asset('custom')); ?>/js/notify.min.js"></script>

  <!-- CKEditor -->
  <script src="<?php echo e(asset('ckeditor')); ?>/ckeditor.js"></script>
  <script>
      var USER_ID = '<?php echo e(auth()->user()&&auth()->user()?auth()->user()->id:""); ?>';
  </script>

  <script>
    window.onload = function () {
    $('#termsCheckBox').click(function () {
        $('#submitRegister').prop("disabled", !$("#termsCheckBox").prop("checked")); 
    })
    var ifUser = <?php echo json_encode( auth()->user() && auth()->user()->hasRole('admin') ? true : false); ?>;

    $('<i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none;"></i>').insertBefore(".ckedit");
    if(ifUser){
        initializeCKEditor();

        changeContentEditable(true);

        showEditBtns();
        //$(".ckedit_edit").show();

    }else{
        changeContentEditable(false);

        //$(".ckedit_edit").hide();
    }

    CKEDITOR.on('instanceReady', function(event) {
        var editor = event.editor;

        editor.on('blur', function(e) {
            //console.log(editor.getSnapshot());
            var html=editor.getSnapshot()
            var dom=document.createElement("DIV");
            dom.innerHTML=html;
            var plain_text=(dom.textContent || dom.innerText);

            var APP_URL = <?php echo json_encode(url('/')); ?>


            var locale = <?php echo json_encode(Config::get('app.locale')); ?>


            changeContent(APP_URL, locale, editor.name, plain_text)
        });
    });

    function showEditBtns(){
        $('.ckedit_btn').each(function(i, obj) {
            $(this).show();
        });
    }

    function initializeCKEditor(){
        var elements = CKEDITOR.document.find('.ckedit'),
        i = 0,
        element;

        while ( ( element = elements.getItem( i++ ) ) ) {
            //CKEDITOR.inline(element);
            CKEDITOR.inline(element, {
                removePlugins: 'link, image',
                removeButtons: 'Bold,Italic,Underline,Strike,Subscript,Superscript,RemoveFormat,Scayt,SpecialChar,About,Styles,Cut,Copy,Undo,Redo,Outdent,Indent,Table,HorizontalRule,NumberedList,BulletedList,Blockquote,Format'
            } );
        }
    }

    $(".ckedit_btn").click(function() {
        var next = $(this).next().attr('key');

        var editor = CKEDITOR.instances[next];
        editor.focus();
    });

    function changeContentEditable(bool){
        $('.ckedit').each(function(i, obj) {
            $(this).attr("contenteditable",bool);
        });
    }

    function notify(text, type){
        $.notify.addStyle('custom', {
            html: "<div><strong><span data-notify-text /></strong></div>",
            classes: {
                base: {
                    "pos ition": "relative",
                    "margin-bottom": "1rem",
                    "padding": "1rem 1.5rem",
                    "border": "1px solid transparent",
                    "border-radius": ".375rem",

                    "color": "#fff",
                    "border-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                    "background-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                },
                success: {
                    "color": "#fff",
                    "border-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                    "background-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                }
            }
            });

            $.notify(text,{
                position: "bottom right",
                style: 'custom',
                className: 'success',
                autoHideDelay: 5000,
            }
        );
    }

    function changeContent(APP_URL, locale, key, value){
        var isDemo=<?php echo env('is_demo',false)|env('IS_DEMO',false); ?>;
        if(!isDemo){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: APP_URL+'/admin/languages/'+locale,
                dataType: 'json',
                data: {
                    group: "whatsapp",
                    key: key,
                    language: locale,
                    value: value
                },
                success:function(response){
                    if(response){
                        var msg = <?php echo json_encode(__('whatsapp.success')); ?>


                        notify(msg, "success");
                    }
                }, error: function (response) {
                //alert(response.responseJSON.errMsg);
            }
        })

        }else{
          //ok
          notify("Changing strings not allowed in demo mode.", "warning");
        }
    }
    }
  </script>

  <?php if(strlen(config('settings.futy_key'))>2): ?>
  <!-- <script>
    var key='<?php echo e(config('settings.futy_key')); ?>';
    window.Futy = { key: key };
    (function (e, t) {
        var n = e.createElement(t);
        n.async = true;
        n.src = 'https://v1.widget.futy.io/js/futy-widget.js';
        var r = e.getElementsByTagName(t)[0];
        r.parentNode.insertBefore(n, r);
    })(document, 'script');    
    </script> -->
  <?php endif; ?>
</body>

</html><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/home.blade.php ENDPATH**/ ?>