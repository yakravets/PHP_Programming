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
        $errors = array();

        // keep track post values
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        if (empty($name)) {
            $errors['nameError'] = 'Please enter Name';
        }

        if (empty($last_name)) {
            $errors['last_nameError'] = 'Please enter Last name';
        }

        if (empty($email)) {
            $errors['emailError'] = 'Please enter Email Address';
        } else if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $errors['emailError'] = 'Please enter a valid Email Address';
        }
         
        if (empty($mobile)) {
            $errors['mobileError'] = 'Please enter Mobile Number';
        }
         
        // update data
        if (count($errors) == 0) {
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

<?php 
  require_once 'header.php';
?>

<div class="span10 offset1">
    <div class="row">
        <div class="alert alert-success" role="alert">
            <h3>Update a Customer</h3>
        </div>
    </div>

    <form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
        <div class="form-group col-md-6">
            <label for="inputName">Name:</label>
            <input type="text"
                   class="<?php echo (($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['nameError'])) || !empty($name))?"form-control is-valid":"form-control is-invalid" ?>"
                   name="name"
                   id="inputName"
                   value=<?php echo !empty($name)?$name:'';?>>
            <?php if (isset($errors['nameError'])): ?>
                <div id="inputNameFeedback" class="invalid-feedback">
                    <?php echo $errors['nameError'];?>
                </div>
            <?php else: { ?>
                <div id="inputNameFeedback" class="valid-feedback">
                    Looks good!
                </div>
            <?php } endif; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="inputName">Last name:</label>
            <input type="text"
                   class="<?php echo (($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['last_nameError'])) || !empty($last_name))?"form-control is-valid":"form-control is-invalid" ?>"
                   name="last_name"
                   id="inputName"
                   value=<?php echo !empty($last_name)?$last_name:'';?> >
            <?php if (isset($errors['last_nameError'])): ?>
                <div id="inputLastNameFeedback" class="invalid-feedback">
                    <?php echo $errors['last_nameError'];?>
                </div>
            <?php else: { ?>
                <div id="inputLastNameFeedback" class="valid-feedback">
                    Looks good!
                </div>
            <?php } endif; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="inputEmail">Email:</label>
            <input type="text"
                   class="<?php echo (($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['emailError'])) || !empty($email))?"form-control is-valid":"form-control is-invalid" ?>"
                   name="email"
                   id="inputEmail"
                   value=<?php echo !empty($email)?$email:'';?>>
            <?php if (isset($errors['emailError'])): ?>
                <div id="inputEmailFeedback" class="invalid-feedback">
                    <?php echo $errors['emailError'];?>
                </div>
            <?php else: { ?>
                <div id="inputEmailFeedback" class="valid-feedback">
                    Looks good!
                </div>
            <?php } endif; ?>
        </div>

        <div class="form-group col-md-6">
            <label for="inputMobilePhone">Mobile number:</label>
            <input type="text"
                   class="<?php echo (($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['mobileError'])) || !empty($mobile))?"form-control is-valid":"form-control is-invalid" ?>"
                   name="mobile"
                   id="inputMobilePhone"
                   value=<?php echo !empty($mobile)?$mobile:'';?>>
            <?php if (isset($errors['mobileError'])): ?>
                <div id="inputMobilePhoneFeedback" class="invalid-feedback">
                    <?php echo $errors['mobileError'];?>
                </div>
            <?php else: { ?>
                <div id="inputMobilePhoneFeedback" class="valid-feedback">
                    Looks good!
                </div>
            <?php } endif; ?>
        </div>
      <div class="form-actions">
          <button type="submit" class="btn btn-success">Update</button>
          <a class="btn btn-info" href="/">Back</a>
        </div>
    </form>
</div>
                 
<?php
require_once 'footer.php';