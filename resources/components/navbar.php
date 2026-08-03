<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-solid fa-dolly"></i>
        </div>
        <div class="sidebar-brand-text mx-3">StoreTrack</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $active == "dashboard" ? "active" : "" ?>">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>

    <li class="nav-item <?= $active == "movements" ? "active" : "" ?>">
        <a class="nav-link" href="movements.php">
            <i class="fa-solid fa-people-carry-box"></i>
            <span>Movements</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Catalog
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item <?= $active == "products" ? "active" : "" ?>">
        <a class="nav-link" href="products.php">
            <i class="fa-solid fa-boxes-packing"></i>
            <span>Products</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item <?= $active == "categories" ? "active" : "" ?>">
        <a class="nav-link" href="categories.php">
            <i class="fa-solid fa-folder-plus"></i>
            <span>Categories</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item <?= $active == "unities" ? "active" : "" ?>">
        <a class="nav-link" href="unities.php">
            <i class="fa-solid fa-tag"></i>
            <span>Unities</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../routes/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>