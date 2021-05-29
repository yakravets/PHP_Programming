<?php
     
    include 'database.php';
    if (!empty($_POST)) {

        $errors = array();
        $name = htmlspecialchars($_POST['name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $mobile = htmlspecialchars($_POST['mobile']);
         
        // validate input
        if (empty($name)) {
            $errors['nameError'] = 'Please enter Name';
        }
        if(empty($last_name)){
            $errors['last_nameError'] = 'Please enter Last name';
        }        
        if (empty($email)) {
            $errors['emailError'] = 'Please enter Email Address';
        } 
        if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $errors['emailError'] = 'Please enter a valid Email Address';
        }
         
        if (empty($mobile)) {
            $errors['mobileError'] = 'Please enter Mobile Number';
        }

        // insert data
        if (count($errors) == 0) {
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
 
<?php 
  require_once 'header.php';
?>
    
<div class="span10 offset1">
    <div class="row">
        <div class="alert alert-success" role="alert">
            <h3>Create a Customer</h3>
        </div>
    </div>

    <form class="form-horizontal" method="POST">
        <div class="form-group col-md-6">
            <label for="inputName" class="form-label">Name:</label>
            <input type="text" class="<?php echo ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['nameError']))?"form-control is-valid":"form-control is-invalid" ?>" name="name" id="inputName" value="<?php echo !empty($name)?$name:'';?>" aria-describedby="inputNameFeedback">
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
            <label for="inputName" class="form-label">Last name:</label>
            <input type="text" class="<?php echo ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['last_nameError']))?"form-control is-valid":"form-control is-invalid" ?>" name="last_name" id="inputLastName" value="<?php echo !empty($last_name)?$last_name:'';?>" aria-describedby="inputLastNameFeedback">
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
            <label for="inputEmail" class="form-label">Email:</label>
            <input type="text"
                   class="<?php echo ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['emailError']))?"form-control is-valid":"form-control is-invalid" ?>"
                   name="email" id="inputEmail" value="<?php echo !empty($email)?$email:'';?>" aria-describedby="inputEmailFeedback">
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
            <label for="inputMobilePhone" class="form-label">Mobile number:</label>
            <input type="text" class="<?php echo ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errors['mobileError']))?"form-control is-valid":"form-control is-invalid" ?>" name="mobile" id="inputMobilePhone" value="<?php echo !empty($mobile)?$mobile:'';?>" aria-describedby="inputMobilePhoneFeedback">
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
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn btn-info" href="/">Back</a>
        </div>
    </form>
</div>

<?php
require_once 'footer.php';
