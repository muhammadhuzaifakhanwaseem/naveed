<?php
    $contact = content('contact.content');
    $footersociallink = element('footer.element');
?>

<!-- header-section start  -->
<header class="header">
    <div class="header-bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">

                <a class="site-logo site-title" href="<?php echo e(route('home')); ?>">
                    <?php if(@$general->logo): ?>
                        <img class="img-fluid rounded sm-device-img text-align"
                            src="<?php echo e(getFile('logo', @$general->logo)); ?>" width="100%" alt="pp">
                    <?php else: ?>
                        <?php echo e(__('No Logo Found')); ?>

                    <?php endif; ?>
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="mainNavbar">
                    <ul class="nav navbar-nav sp_main_menu me-auto">
                        <li class="nav-item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>"><a class="nav-link"
                                href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo e(route('investmentplan')); ?>"><?php echo e(__('Investment Plans')); ?></a>
                        </li>

                        <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="nav-item"><a class="nav-link"
                                    href="<?php echo e(route('pages', $page->slug)); ?>"><?php echo e(__($page->name)); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('allblog')); ?>"><?php echo e(__('Blog')); ?></a>
                        </li>

                    </ul>
                    <div class="navbar-action">
                        <select class="changeLang me-3" aria-label="Default select example">
                            <?php $__currentLoopData = $language_top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($top->short_code); ?>"
                                    <?php echo e(session('locale') == $top->short_code ? 'selected' : ''); ?>>
                                    <?php echo e(__(ucwords($top->name))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if(Auth::user()): ?>
                            <a class="btn main-btn btn-sm"
                                href="<?php echo e(route('user.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                        <?php else: ?>
                            <a class="text-white me-3" href="<?php echo e(route('user.login')); ?>"><?php echo e(__('Login')); ?></a>
                            <a href="<?php echo e(route('user.register')); ?>" class="btn main-btn btn-sm">Sign up <i
                                    class="las la-long-arrow-alt-right ms-2"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header-bottom end -->
</header>
<!-- header-section end  -->



<!----------------------Bottom Navigation------------------------------>
<style>
    .bottom_nav {
        overflow: hidden;
        background-color: #1c1c1ce0;
        position: fixed;
        bottom: 0;
        left: 50%;
        width: 95%;
        height: 70px;
        border-top-left-radius: 16px;
        display: flex;
        backdrop-filter: blur(5px);
        justify-content: space-around;
        align-items: center;
        z-index: 999;
        border-top-right-radius: 16px;
        transform: translateX(-50%);
    }

    .bottom_nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #ccc;
        font-size: 9px;
        flex-grow: 1;
        transition: all 0.3s ease;
    }

    .bottom_nav a img {
        width: 17px;
        height: 17px;
        margin-bottom: 4px;
    }

    .bottom_nav a.active {
        color: white;
        font-weight: bold;
    }

    .bottom_nav a.active img {
        background: linear-gradient(135deg, #65b96b, #e73433, #194668);
        border-radius: 50%;
        padding: 5px;
    }

    .bottom_nav a:hover {
        color: white;
    }

    @media  screen and (min-width: 769px) {
        .bottom_nav {
            display: none;
        }
    }

    @media  screen and (max-width: 768px) {
        .bottom_nav {
            display: flex;
        }
    }
</style>

<div class="bottom_nav">
    <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/home.png" alt="Home Icon">
        <?php echo e(__('Home')); ?>

    </a>
    <a class="nav-link <?php echo e(request()->routeIs('investmentplan') ? 'active' : ''); ?>"
        href="<?php echo e(route('investmentplan')); ?>">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/money-bag.png" alt="Investment Icon">
        <?php echo e(__('Investment')); ?>

    </a>
    <a class="nav-link <?php echo e(request()->is('about') ? 'active' : ''); ?>" href="<?php echo e(route('pages', 'about')); ?>">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/info.png" alt="About Icon">
        <?php echo e(__('About')); ?>

    </a>
    <a class="nav-link <?php echo e(request()->is('contact') ? 'active' : ''); ?>" href="<?php echo e(route('pages', 'contact')); ?>">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/phone.png" alt="Contact Icon">
        <?php echo e(__('Contact')); ?>

    </a>
    <?php if(Auth::user()): ?>
        <a class="nav-link" href="<?php echo e(route('user.dashboard')); ?>">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/dashboard.png" alt="Dashboard Icon">
            <?php echo e(__('Dashboard')); ?>

        </a>
    <?php else: ?>
        <a href="<?php echo e(route('user.login')); ?>">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/login-rounded-right.png" alt="Login Icon">
            <?php echo e(__('Login')); ?>

        </a>
    <?php endif; ?>
</div>


<!--Floating Whatsapp ICon-->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .whatsapp-float:hover {
            background-color: #1ebe5d;
            transform: scale(1.1);
        }
    </style>
    <a href="https://wa.me/+923285412930" target="_blank" class="whatsapp-float">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!--End Floating Whatsapp icon--><?php /**PATH /home/u145197596/domains/opecfuture.com/public_html/core/resources/views/theme4/layout/header.blade.php ENDPATH**/ ?>