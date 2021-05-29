<?php
  require_once 'header.php';
?>
<div class="span10 offset1">
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
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
          $page = htmlspecialchars($_GET['page']);
        }
        else{
          $page = 1;
        }
        $num_results_on_page = 5;
        $calc_page = ($page - 1) * $num_results_on_page;
      ?>

      <h3>Count <span class="badge bg-secondary"><?php echo $countOrder ?></span></h3>
    </div>
  </div>
  <div>
    <div class="buttons">
      <a href="create.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Create</a>
    </div>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Img</th>
          <th>Name</th>
          <th>Last name</th>
          <th>Email Address</th>
          <th>Mobile Number</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $pdo = Database::connect();
          $sql = 'SELECT id, name, last_name, email, mobile, image_url FROM customers ORDER BY id ASC LIMIT ?, ?';
          $stmt = new mysqli_stmt($pdo, $sql);       
          $stmt->bind_param('ii', $calc_page, $num_results_on_page);
          $stmt->execute();
          $data = $stmt->get_result(); 
          foreach ($data as $row) {
            echo '<tr>';
            echo "<td width=105><div class='profile_photo'>";
            $picture = !is_null($row['image_url'])?$row['image_url']:"empty.jpg";
            echo "<img src='img/$picture' class='img-thumbnail profile_photo'>";
            echo "</div></td>";
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['mobile'] . '</td>';
            echo '<td width=250>';
            echo '<a class="btn btn-success" href="read.php?id='.$row['id'].'"><i class="bi bi-eye"></i></a>';
            echo ' ';
            echo '<a class="btn btn-warning" href="update.php?id='.$row['id'].'"><i class="bi bi-pencil-square"></i></a>';
            echo ' ';
            echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'"><i class="bi bi-trash-fill"></i></a>';
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
</div>

<?php endif;
require_once 'footer.php';

