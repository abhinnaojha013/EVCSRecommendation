<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent("title"); ?> - EVCS Recommendation System</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/font-awesome/css/font-awesome.min.css')); ?>">
    <script
        src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>





    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>

    <style>
        body {
            
            background-image: linear-gradient(to right bottom, rgba(217, 250, 255, 0.7), rgba(255, 213, 213, 0.7));
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 610px;

            /*--bs-body-bg-rgb: 255,255,255;*/
            /*--bs-primary-rgb: 13,110,253;*/
            /*--bd-accent-rgb: 255,228,132;*/
            /*--bd-violet-rgb: 112.520718,44.062154,249.437846;*/
            /*--bd-pink-rgb: 214,51,132;*/
            /*background-image:*/
            /*    linear-gradient(*/
            /*        180deg,*/
            /*        rgba(var(--bs-body-bg-rgb), 0.01),*/
            /*        rgba(var(--bs-body-bg-rgb), 1) 85%),*/
            /*    radial-gradient(ellipse at top left,*/
            /*    rgba(var(--bs-primary-rgb), 0.5), transparent 50%),*/
            /*    radial-gradient(ellipse at top right,*/
            /*    rgba(var(--bd-accent-rgb), 0.5), transparent 50%),*/
            /*    radial-gradient(ellipse at center right,*/
            /*    rgba(var(--bd-violet-rgb), 0.5), transparent 50%),*/
            /*    radial-gradient(ellipse at center left,*/
            /*    rgba(var(--bd-pink-rgb), 0.5), transparent 50%);*/
        }

        td, th, h1, h2, h5, h4, h5, h6, p, label, button
        {
            vertical-align: middle;
        }

        td, th, button, input[type=submit], label, p, a
        {
            font-size: 1rem;
            vertical-align: middle;
            text-transform: capitalize;
        }

        input
        {
            padding-left: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            outline-color: blue;
        }

        select
        {
            vertical-align: middle;
            font-size: 1rem;
            padding: 5px;
            outline-color: blue;
        }

        input[type=checkbox]
        {
            width: 15px;
            height: 15px;
        }

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="--bs-bg-opacity: .6;">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">

                    EVCS Recommendation System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <?php echo e(Auth::user()->name); ?>

                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"  style="color: red">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="d-flex justify-content-center">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dell\Desktop\EV project\EVCSRecommendation\resources\views/layouts/app.blade.php ENDPATH**/ ?>