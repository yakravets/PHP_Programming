<?php
     
    include 'database.php';
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $emailError = null;
        $last_name = null;
        $mobileError = null;
         
        // keep track post values
        $name = htmlspecialchars($_POST['name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $mobile = htmlspecialchars($_POST['mobile']);
         
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
        if(empty($last_name)){
            $last_nameError = 'Please enter Last name';
        }        
        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } 
        if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $sql = "INSERT INTO customers (name, last_name, email, mobile) values(?, ?, ?, ?)";
            $stmt = new mysqli_stmt($pdo, $sql);       
            $stmt->bind_param('ssss', $name, $last_name, $email, $mobile);
            $stmt->execute();
            $stmt->close();
            Database::disconnect();
            header("Location: index.php");
        }
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>Create</title>
</head>
 
<body>
<div class="container">
     
     <div class="span10 offset1">
        <div class="row">
            <div class="alert alert-success" role="alert">
                <h3>Create a Customer</h3>
            </div>
        </div>
  
         <form class="form-horizontal" action="create.php" method="post">
            <div class="form-group col-md-6">
                <label for="inputName">Name:</label>
                <input type="text" class="form-control" name="name" id="inputName" value=<?php echo !empty($name)?$name:'';?>>
                <?php if (!empty($nameError)): ?>
                    <span class="help-inline"><?php echo $nameError;?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group col-md-6">
                <label for="inputName">Last name:</label>
                <input type="text" class="form-control" name="last_name" id="inputName" value=<?php echo !empty($last_name)?$last_name:'';?>>
                <?php if (!empty($last_nameError)): ?>
                    <span class="help-inline"><?php echo $last_nameError;?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group col-md-6">
                <label for="inputEmail">Email:</label>
                <input type="text" class="form-control" name="email"id="inputEmail" value=<?php echo !empty($email)?$email:'';?>>
                <?php if (!empty($emailError)): ?>
                    <span class="help-inline"><?php echo $emailError;?></span>
                <?php endif; ?>
            </div>

            <div class="form-group col-md-6">
                <label for="inputMobilePhone">Mobile number:</label>
                <input type="text" class="form-control" name="mobile"id="inputMobilePhone" value=<?php echo !empty($mobile)?$mobile:'';?>>
                <?php if (!empty($mobileError)): ?>
                    <span class="help-inline"><?php echo $mobileError;?></span>
                <?php endif; ?>
            </div>  
           <div class="form-actions">
               <button type="submit" class="btn btn-success">Create</button>
               <a class="btn btn-info" href="index.php">Back</a>
             </div>
         </form>
     </div>
      
    </div>
  </body>
</html>
