<?php
    require 'database.php';
    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = htmlspecialchars($_REQUEST['id']);
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
         
        // delete data
        $pdo = Database::connect();
        $sql = "DELETE FROM customers  WHERE id = ?";

        $stmt = new mysqli_stmt($pdo, $sql);       
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        Database::disconnect();
        header("Location: /");
    }
?>
 
 <?php 
require_once 'header.php';
?>

<div class="span10 offset1">
    <div class="row">
        <div class="alert alert-success" role="alert">
            <h3>Delete a Customer</h3>
        </div>
    </div>
    <form class="form-horizontal" action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id;?>"/>
        <p class="alert alert-error">Are you sure to delete ?</p>
        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Yes</button>
            <a class="btn" href="/">No</a>
        </div>
    </form>
</div>

<?php
require_once 'footer.php';
