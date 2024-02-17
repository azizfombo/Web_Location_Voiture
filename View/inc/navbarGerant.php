<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#"><h2>Rent</h2>a<h2>Car</h2></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../View/pageUsers.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../View/pageGerantUsers.php">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../View/pageCaissiereVoiture.php">Vehicules</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../View/pageGerantStats.php">Statistiques</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../View/pageCaissiere.php">Clients</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="../Traitement/logout.php" method="post">
        <button class="btn btn-danger my-2 my-sm-0" type="submit">Déconnexion</button>
    </form>
  </div>
</nav>