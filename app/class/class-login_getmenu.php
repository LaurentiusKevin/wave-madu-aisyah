<?php
$data = '<ul class="sidebar-menu list-unstyled"><li class="sidebar-list-item"><a href="/" class="sidebar-link text-muted active"><i class="o-home-1 mr-3 text-gray"></i><span>Home</span></a></li>';
// GET MENU GROUP
$sql = "SELECT * FROM ms_menugroup 
        WHERE id_menugroup IN 
          (SELECT DISTINCT(id_menugroup) 
          FROM ms_menu 
          WHERE id_menu IN 
            (SELECT id_menu 
            FROM ms_permission 
            WHERE username='$username' AND aktif='1'))
        ORDER BY group_order ASC";
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  $data .= '<li class="sidebar-list-item"><a href="#" data-toggle="collapse" data-target="#'.$row['target'].'" aria-expanded="false" aria-controls="'.$row['target'].'" class="sidebar-link text-muted"><i class="'.$row['icon'].'  mr-3 text-gray"></i><span>'.$row['nama'].'</span></a>';
  $data .= '<div id="'.$row['target'].'" class="collapse"><ul class="sidebar-menu list-unstyled border-left border-primary border-thick">';

  // GET MENU
  $idMenuGroup = $row['id_menugroup'];
  $sqlMenu = "SELECT * FROM ms_menu 
              WHERE id_menu IN 
                (SELECT id_menu FROM ms_permission 
                WHERE username='$username' AND aktif='1') 
              AND id_menugroup='$idMenuGroup' 
              ORDER BY menu_order";
  $queryMenu = mysqli_query($conn,$sqlMenu);
  while ($rowMenu = mysqli_fetch_array($queryMenu)) {
    $data .= '<li class="sidebar-list-item"><a href="'.$rowMenu['link'].'" class="sidebar-link text-muted pl-lg-5">'.$rowMenu['nama'].'</a></li>';
  }
  $data .= '</ul></div></li>';
}
$data .= '</ul>';
?>