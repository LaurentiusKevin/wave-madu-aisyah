<nav class="navbar navbar-expand-lg px-4 py-2 bg-white shadow"><a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead"><i class="fas fa-align-left"></i></a><a href="/" class="navbar-brand font-weight-bold text-uppercase text-base">Reseller Madu</a>
  <ul class="ml-auto d-flex align-items-center list-unstyled mb-0">
    
    <li class="nav-item dropdown ml-auto">
      <a id="userInfo" href="http://example.com" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
        <i class="fas fa-user"></i>
      </a>
      <div aria-labelledby="userInfo" class="dropdown-menu">
        <a href="#" class="dropdown-item">
          <strong class="d-block text-uppercase headings-font-family">
            <?php echo $_SESSION['nama_lengkap'] ?>
          </strong>
          <small>
            <?php echo $_SESSION['username'] ?>
          </small>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">Profile</a>
        <div class="dropdown-divider"></div>
        <a href="#" onclick="userLogout()" class="dropdown-item">Logout</a>
      </div>
    </li>
  </ul>
</nav>