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

        if(count($errors) == 0 && $_FILES['profile_photo']['name'] != ''){
            require_once 'random.php';
            $new_name = uuid() . '.' . explode('/', $_FILES['profile_photo']['type'])[1];
            if(!move_uploaded_file($_FILES['profile_photo']['tmp_name'], 'img/' . $new_name)){
                $new_name = null;
            }
        }

        // insert data
        if (count($errors) == 0) {
            $pdo = Database::connect();
            $sql = "INSERT INTO customers (name, last_name, email, mobile, image_url) values(?, ?, ?, ?, ?)";
            $stmt = new mysqli_stmt($pdo, $sql);       
            $stmt->bind_param('sssss', $name, $last_name, $email, $mobile, $new_name);
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

    <form class="form-horizontal" method="POST" enctype="multipart/form-data">
        <div class="form-group col-12">
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

        <div class="form-group col-12">
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

        <div class="form-group col-12">
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

        <div class="form-group col-12">
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

        <div class="form-group col-12">
            <img src="img/upload.png" width="150" height="100" id="user_photo">
<!--            <label for="user_photo d-block" class="form-label">User photo:</label>-->
<!--            <button id="user_photo" class="btn btn-primary"><i class="bi bi-paperclip"></i> Attach photo</button>-->
            <input type="file" id="inputFile" class="form-control d-none" aria-label="file example" name="profile_photo" accept="image/*">
            <div class="invalid-feedback">Example invalid form file feedback</div>
        </div>

        <div class="modal" id="modal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <img src="" class="card-img" id="photo_for_cropp" width="100%">
                            </div>
                            <div class="col-md-4">
                                <div class="preview ml-4"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="btnCropped" class="btn btn-primary">Обрізати</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn btn-info" href="/">Back</a>
        </div>
    </form>
</div>
<script src="js/cropper.js"></script>
<script src="js/add.js"></script>
<?php
require_once 'footer.php';
