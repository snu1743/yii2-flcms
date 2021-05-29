<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use fl\cms\assets\FlMainAssets;
FlMainAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="../../index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="data:image/jpeg;base64,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" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">Call me whenever you can...</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCABPAF4DAREAAhEBAxEB/8QAHQABAAICAwEBAAAAAAAAAAAAAAkLCAoBBgcCBP/EAEUQAAAFAwICAwkMCQUAAAAAAAECAwQFAAYHCAkKERITFBohMTc5UXe2uBUXU1VXWGFxdZGY1xYnRniSl7TR1WVygZOh/8QAGAEBAAMBAAAAAAAAAAAAAAAAAAECAwT/xAA3EQACAQIBBgsHBQEAAAAAAAAAAQIDETEEITJRcrESEzNBcYGRs8HS8CJSYWKSodEUQkNzk9P/2gAMAwEAAhEDEQA/AN/igFAKAUAoBQCgFAKAUAoBQCgFAQ163t+Pbr29s4uNPGpjId/21k1taVtXsrGW5iG+b1jggLsCRGGcBM28xcsRWW9y3fWtumCqHRKBw5m5BF0sc3YXjTnPRjJ9EZPXqT1PsZiD3WNs0/LHl78OWVv8TS61rtRbiavuT+iflHdY2zT8seXvw5ZW/wATS61rtQ4mr7k/on5TJvR7xBu2lro1CWPph09ZKyJcGW8iN7pc2xET+GL/ALRi3SVnWrMXnOGcT05HoRrLqIKDkF0QcKF7SuRJokIrrpFMur2x61+b/YiVKpFNyjJJYtwmlqxcUvuSS6utV+GtEOnrIOp/P8zLQGJMYpW6tdsvBW7K3XKNE7ouuDsyIFtAwqS0k+66duGMQW7OmbsyCqrpYSoIKGCcCkYuTSim28Ek29eCTf2MetvjdN0gbnURlGc0l3fdd2R+HpW1oa91Lpx/c9hKMX15MJmRgiM0blbNlJEi7WCkTrKNiiDYU0irAHXoiYncmUXHNJNPU1JNPM7Z0uZp9DWskXoVFAKAUAoBQFVfxdw8t3ieMHhLpjwScP8AcRC+jF/9AOdZyztL5vCJ3ZLJxp15LGNNSV86uuNaubOePOEP2pLpsKyblkpnViWQuC0LXm34Nc2wCLYHstAx8i76hI2MVjJI9pcq9UmKpxIn0S9IeVW4K9KPlMXlFS7zvF/yVv8Aodx7jx2l/jrVx/PK3vytpwV6UfwR+oqa3/pW/wCpllof4bzb12/9TGPtV2CZPUQ4yhjVteDW3Ub8ynDXNa5072s6bseZ90YdrYMI5dGLCz74zPq5NuVB8DdyoVcqPVGKKWfwXgisq05pxd7P56j508JTa5udfc/dxOXe2QNbof6bg/2kMRUnovq3omhyq2andzIQuCK8V+4f6R9OnqflOohh1LfI1yvTe2+6om9TVzkFAKAUAoBQFVdxd/ldrg/dgwX/AE191m9JbXhE7cn5HKP6vCqWguF/FDi30c2L6qQ9aHG8X0veemUIFAQJ8Tl5EDW99m4P9pDEVVnovq3o2ocqtmp3cyELgivFfuH+kfTp6n5TqIYdS3yNcr03tvuqJvU1c5BQCgFAKAUBVX8XcHPd4nSh4T6Y8EEDzAJ0b6IAj9ACbmP0VnLM0/m8InfkkeHCtFOzlBRu8FfjVd2J7bE4zPRPadk2da7nSfq0dObctS2oF05bqYWK3XdQ0GwjXKzcFcjAr2dVdqodAVSkUMkYhjpJHEUiTw1zW+/4Zk8lrXfsPF89Pzna+7U9EHzR9XX/AHYS/MenDXw7X5SP0lb3H20/OStbT2/dp93bcq5RxRh/Cua8YS+LMexuRJaSyeewTRsjGyd0NbWSYxwWjc8857cR26K4OLlJJv2cinJTregQ9k7+n4pGVSlKnmkmnmf7cHe2En7r7Og/DxOXkQNb32bg/wBpDEVRPRfVvRahyq2andzIQuCK8V+4f6R9OnqflOohh1LfI1yvTe2+6om9TVzkFAKAUAoBQFVdxd3e3d54Q8JdMOCjAPmMVvfZij/wIANZy0lteETuyVuNOvKLs40009TXGtPtN1PF/DnbLc/jewJyX0J2I8lJiybRlJF2fIWcEzun8hbkY8euTpoZRSRIZd0sqsYqSZEyicQIUpeQBey+Pa/ycrqSu80MX/HT17J3vubjZK+YVYX8xc6/mpSy+Pa/yRxktUP86flMvdH21boC0C3fdl+aRNOVu4Wuy+bba2hdUxC3VkaeUl7dZSyU42jVGt53lcjFuROVQRdiuyatnahkyJquDoFBKluntb3kOTlmaj1QjH7xSb6DBnicvIga3vs3B/tIYiqJ6L6t6NKHKrZqd3MhC4IrxX7h/pH06ep+U6iGHUt8jXK9N7b7qib1NXOQUAoBQCgFAVV3F3+V2uD92DBf9NfdZvSW14RO3J+Ryj+rwqloLhfxQ4t9HNi+qkPWhxvF9L3nplCBQECfE5eRA1vfZuD/AGkMRVWei+rejahyq2andzIQOCLMUuL9w8TGKUPfH05hzMIB+x+VPP8AUP3DUQw6lvka5XpvbfdUTen65L4VP+Mv96ucg65L4VP+Mv8AegPsBAQAQEBAe+Ah3wH6hoDmgFAKAreOJ/27deGpfc/nMl6etH2ozNWPVdO2HIBK9cZYnu28LYUm4hC9CykSSYh45yzUkI8zpsDxmkoo5bi4Q61EvXI9ZRrOnqd8HqWpfA6qM1GnVi8akOCvagrO08VKcXb2lnSfOWI2J2D2KxfjqMkmjhhIx1h2axfMnaRkHTN4ztqLbOmrhE4AdJdu4SURWTMAGTVIchu+UauczxfSz0GhAoCF3iFcOZXz/tB6vMSYQxzeWWcn3awxAnbFgY/gJC6LtnzxGesYTsoWJg4tFd++GPhYyRlXgN0TmQYMnTkwdWiYQrJXTXrH4GtGSjUTeFprGKxhJfucVz87XaV6+kvAvEeaFI++YjSTpq3A8HxuSZCClL3a2lp1kHJLif2y1kmME5eGuSyZ5VM8c0l5FFErNRqmcro4rkVOVIxKJNep+CR11JUKrTaknnb4M8nztqKu+FOXNFLNZZtZl77/ALxffxDuZ/htivyqqfa9cYZ8HJ9dT68l8x9Ez7xfXTJ0oHcy6PSLz56bYvly5hz5/qr8HLw09r1xg4OT66n15L5iwk2z5fUPPaCNJ8zqySvZDUhJYVtB3mdHI8J+jl9J38qgsM2S6YIY+KNFTAKAn2pkaOZnRHogdAhxNz09ernI8fxa17Z7cHNa+FjOehAoBQHHIB8IBQHNAKAUAoDjkHmD7goByDzB9wUA5B5g+4KA5oBQCgFAKAUAoBQCgFAKAUAoBQH/2Q==" alt="User Avatar" class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    John Pierce
                                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">I got your message bro</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="data:image/jpeg;base64,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" alt="User Avatar" class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">The subject goes here</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="../../index3.html" class="brand-link">
            <img src="data:image/jpeg;base64,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" alt="FreeLemur.com Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">FreeLemur.com</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="data:image/jpeg;base64,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" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>Level 1</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Collapsed Sidebar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Collapsed Sidebar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.1.0
        </div>
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">FreeLemur.com</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<?php require_once __DIR__ . '/../common/actions/templates.php'; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
