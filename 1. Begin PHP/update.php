<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = htmlspecialchars($_REQUEST['id']);
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $last_nameError = null;
        $emailError = null;
        $mobileError = null;
         
        // keep track post values
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
         
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter Last name';
            $valid = false;
        }

        if (empty($last_name)) {
            $last_nameError = 'Please enter Name';
            $valid = false;
        }

        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $sql = "UPDATE customers  set name = ?, last_name = ?, email = ?, mobile = ? WHERE id = ?";
            
            $stmt = new mysqli_stmt($pdo, $sql);       
            $stmt->bind_param('ssssi', $name, $last_name, $email, $mobile, $id);
            $stmt->execute();
            $stmt->close();
            
            Database::disconnect();
            header("Location: index.php");
        }
    } else {
        $pdo = Database::connect();
        $sql = "SELECT * FROM customers where id = ?";
        $stmt = new mysqli_stmt($pdo, $sql);       
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        $name = $data['name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $mobile = $data['mobile'];
        Database::disconnect();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <div class="alert alert-success" role="alert">
                            <h3>Update a Customer</h3>
                        </div>
                    </div>
             
                    <form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
                        <div class="form-group col-md-6">
                            <label for="inputName">Name:</label>
                            <input type="text" class="form-control" name="name" id="inputName" value=<?php echo !empty($name)?$name:'';?>>
                            <?php if (!empty($nameError)): ?>
                                <span class="help-inline"><?php echo $nameError;?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputName">Last name:</label>
                            <input type="text" class="form-control" name="last_name" id="inputName" value=<?php echo !empty($last_name)?$last_name:'';?> required>
                            <?php if (!empty($last_nameError)): ?>
                                <span class="help-inline"><?php echo $last_nameError;?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail">Email:</label>
                            <input type="text" class="form-control" name="email" id="inputEmail" value=<?php echo !empty($email)?$email:'';?>>
                            <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $emailError;?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputMobilePhone">Mobile number:</label>
                            <input type="text" class="form-control" name="mobile" id="inputMobilePhone" value=<?php echo !empty($mobile)?$mobile:'';?>>
                            <?php if (!empty($mobileError)): ?>
                                <span class="help-inline"><?php echo $mobileError;?></span>
                            <?php endif; ?>
                        </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn btn-info" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
        
