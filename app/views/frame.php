<nav class="navbar navbar-default top-navbar" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= Flight::get('flight.base_url') ?>/"><i class="fa fa-gear"></i> <strong>ELEVAGE</strong></a>
    </div>

</nav>
<!--/. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation">
<div id="sideNav" href=""><i class="fa fa-caret-right"></i></div>
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">

            <li>
                <a class="active-menu" href="<?= Flight::get('flight.base_url') ?>/"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-desktop"></i>Insérer capitaux</a>
            </li>
            <li>
                <a href="<?= Flight::get('flight.base_url') ?>/possession"><i class="fa fa-qrcode"></i>Espèces possédées</a>
            </li>
            
            <li>
                <a href="#"><i class="fa fa-sitemap"></i>Acheter<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?= Flight::get('flight.base_url') ?>/achat/animal">animal</a>
                    </li>
                    <li>
                        <a href="<?= Flight::get('flight.base_url') ?>/achat/alimentation">alimentation</a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>

</nav>