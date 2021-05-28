<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>List customers</title>
</head>
 
<body>
    <div class="container"> -->
<?php 
  require_once 'header.php';
  get_header("List users");
?>
  <div class="alert alert-success" role="alert">
    <div class="row">
      <h3>PHP CRUD Grid</h3>
    </div>
    <hr>
    <div class="row">
      <?php 
        include 'database.php';
        $pdo = Database::connect();
        $sql = 'SELECT Count(*) as count FROM customers';
        $stmt = new mysqli_stmt($pdo, $sql);       
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc(); 
        $countOrder = $data['count'];
        $stmt->close();

        // Pagination.
        if ($_GET['page'] != null && is_numeric($_GET['page'])) {
          $page = htmlspecialchars($_GET['page']);
        }
        else{
          $page = 1;
        }
        $num_results_on_page = 5;
        $calc_page = ($page - 1) * $num_results_on_page;
      ?>

      <h3>Count <span class="badge badge-secondary"><?php echo $countOrder ?></span></h3>
    </div>
  </div>
  <div class="row">
    <p>
      <a href="create.php" class="btn btn-success">Create</a>
    </p>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Last name</th>
          <th>Email Address</th>
          <th>Mobile Number</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $pdo = Database::connect();
          $sql = 'SELECT id, name, last_name, email, mobile FROM customers ORDER BY id ASC LIMIT ?,?';
          $stmt = new mysqli_stmt($pdo, $sql);       
          $stmt->bind_param('ii', $calc_page, $num_results_on_page);
          $stmt->execute();
          $data = $stmt->get_result(); 
          foreach ($data as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['name'] . '</td>';
                    echo '<td>'. $row['last_name'] . '</td>';
                    echo '<td>'. $row['email'] . '</td>';
                    echo '<td>'. $row['mobile'] . '</td>';
                    echo '<td width=250>';
                    echo '<a class="btn btn-info" href="read.php?id='.$row['id'].'">Read</a>';
                    echo ' ';
                    echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
                    echo ' ';
                    echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
          }
          $stmt->close();
          Database::disconnect();
          ?>
          </tbody>
    </table>
  </div>

  <?php if (ceil($countOrder / $num_results_on_page) > 0): ?>
    <ul class="pagination">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page-1 ?>">Prev</a>
      </li>
      <?php endif; ?>

      <?php if ($page > 3): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=1">1</a>
        </li>
      <?php endif; ?>

      <?php if ($page-2 > 0): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page-2 ?>">
            <?php echo $page-2 ?>
          </a>
        </li>
      <?php endif; ?>
      
      <?php if ($page-1 > 0): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a>
        </li>
      <?php endif; ?>

      <li class="page-item active" aria-current="page">
        <a class="page-link" href="index.php?page=<?php echo $page ?>"><?php echo $page ?></a>
      </li>

      <?php if ($page+1 < ceil($countOrder / $num_results_on_page)+1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page+1 ?>">
            <?php echo $page+1 ?>
          </a>
        </li>
        <?php endif; ?>
      <?php if ($page+2 < ceil($countOrder / $num_results_on_page)+1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page+2 ?>">
            <?php echo $page+2 ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($page < ceil($countOrder / $num_results_on_page)-2): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo ceil($countOrder / $num_results_on_page) ?>">
          <?php echo ceil($countOrder / $num_results_on_page) ?>
        </a>
        </li>
      <?php endif; ?>

      <?php if ($page < ceil($countOrder / $num_results_on_page)): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page+1 ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  <?php endif;
  require_once 'footer.php';
  get_footer();
