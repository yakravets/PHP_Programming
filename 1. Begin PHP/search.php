<?php

    require_once 'header.php';
    require_once 'database.php';

    if(!isset($_GET['q'])){
        header('Location: /index.php');
        exit();
    }

    $q = $_GET['q'];
    if(!isset($_GET['page'])){
        $page = 1;
    }
    else{
        $page = htmlspecialchars(($_GET['page']));
    };

    $num_results_on_page = 5;
    $calc_page = ($page - 1) * $num_results_on_page;
    $pattern = '%'.$q.'%';

    $pdo = Database::connect();
    $sql = 'SELECT Count(*) as count FROM customers where name like ? or last_name Like ?';
    $stmt = new mysqli_stmt($pdo, $sql);
    $stmt->bind_param('ss',$pattern, $pattern);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $count_rows = $data['count'];
    $stmt->close();
?>

<div class="alert alert-success" role="alert">
    <h4 class="alert-heading"><i class="bi bi-info-circle-fill btn-lg"></i>Result search: `<i><?php echo $q?></i>`</h4>
    <hr>
    <p><i class="bi bi-search"></i> <span class="badge bg-secondary">Count: <?php echo $count_rows?></span></p>
</div>

<?php
    $pdo = Database::connect();
    $sql = 'SELECT * from customers where name like ? or last_name Like ? LIMIT ?,?';
    $stmt = new mysqli_stmt($pdo, $sql);
    $stmt->bind_param('ssii',$pattern, $pattern, $calc_page, $num_results_on_page);
    $stmt->execute();
    $data = $stmt->get_result();

    if($count_rows > 0){ ?>
        <div>
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
                        foreach ($data->fetch_all() as $row) {
                            echo '<tr>';
                            echo "<td width=105><div class='profile_photo'>";
                            $picture = !is_null($row[5])?$row[5]:"empty.jpg";
                            echo "<img src='img/$picture' class='img-thumbnail profile_photo'>";
                            echo "</div></td>";
                            echo '<td>' . $row[1] . '</td>';
                            echo '<td>' . $row[2] . '</td>';
                            echo '<td>' . $row[3] . '</td>';
                            echo '<td>' . $row[4] . '</td>';
                            echo '<td width=250>';
                            echo '<a class="btn btn-success" href="read.php?id='.$row[1].'"><i class="bi bi-eye"></i></a>';
                            echo ' ';
                            echo '<a class="btn btn-warning" href="update.php?id='.$row[1].'"><i class="bi bi-pencil-square"></i></a>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="delete.php?id='.$row[1].'"><i class="bi bi-trash-fill"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
              </tbody>
            </table>
        </div>

        <?php
            $stmt->close();
            Database::disconnect();

            $countPages = ceil($count_rows / $num_results_on_page);
            $delimiter = min($countPages, 10);
            if ($countPages > 0): ?>
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="search.php?page=<?php echo $page-1 ?>&q=<?php echo $q ?>">Prev</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($countPages >= 1): ?>
                        <li class="page-item <?php if($page == 1) echo "active"; ?>">
                            <a class="page-link" href="search.php?page=1&q=<?php echo $q ?>">1</a>
                        </li>
                    <?php endif; ?>

                    <?php for($i = 2; $i <= $delimiter; $i++): ?>
                        <li class="page-item <?php if($i == $page) echo "active"; ?>">
                            <a class="page-link" href="search.php?page=<?php echo $i ?>&q=<?php echo $q ?>"><?php echo $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if($countPages > $delimiter): ?>

                        <li class="page-item <?php if($i == $page) echo "active"; ?>">
                            <a class="page-link" href="search.php?page=<?php echo $delimiter + 1 ?>&q=<?php echo $q ?>">...</a>
                        </li>

                    <?php endif;?>

                        <li class="page-item <?php if($countPages == $page) echo "active"; ?>">
                            <a class="page-link" href="search.php?page=<?php echo $countPages ?>&q=<?php echo $q ?>"><?php echo $countPages ?></a>
                        </li>

                    <?php if ($page < $countPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="search.php?page=<?php echo $page+1 ?>&q=<?php echo $q ?>">Next</a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item">
                        <a class="page-link" href="/">Back to list</a>
                    </li>
                </ul>
            <?php endif;
            } else
            { ?>
                <div class="alert alert-warning" role="alert">
                    <strong>
                        <i class="bi bi-info-square"></i> Not found...(((
                    </strong>
                </div>
            <?php
            }
