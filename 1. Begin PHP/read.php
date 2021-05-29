<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = htmlspecialchars($_REQUEST['id']);
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $sql = "SELECT name, last_name, email, mobile FROM customers where id = ?";
        $stmt = new mysqli_stmt($pdo, $sql);       
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        Database::disconnect();
    }
?>
 
<?php 
  require_once 'header.php';
?>
    <div class="span10 offset1">
        <div class="row">
            <div class="alert alert-success" role="alert">
                <h3>Read a Customer</h3>
            </div>
        </div>
        <form> 
            <div class="form-group col-md-6">
                <label for="inputName">Name:</label>
                <input type="text" readonly class="form-control" id="inputName" value=<?php echo $data['name'];?>>
            </div>
            <div class="form-group col-md-6">
                <label for="inputLastName">Name:</label>
                <input type="text" readonly class="form-control" id="inputLastName" value=<?php echo $data['last_name'];?>>
            </div>
            <div class="form-group col-md-6">
                <label for="inputName">Email Address:</label>
                <input type="text" readonly class="form-control" id="inputName" value=<?php echo $data['email'];?>>
            </div>
            <div class="form-group col-md-6">
                <label for="inputName">Mobile number:</label>
                <input type="text" readonly class="form-control" id="inputName" value=<?php echo $data['mobile'];?>>
            </div>
            <div class="form-actions">
                <a class="btn btn-primary" href="index.php">Back</a>
            </div>
        </form>              
    </div>              
<?php
require_once 'footer.php';