<?php
require_once "../common/header.php";

function validateForm()
{
    $errors = [];
    if (empty($_POST['first_name']) || strlen($_POST['first_name']) < 3) {
        $errors['first_name'] = 'please enter the valid name';
    }
    if (empty($_POST['last_name']) || strlen($_POST['last_name']) < 3) {
        $errors['last_name'] = 'please enter the valid last name';
    }

    if (empty($errors)) {
        return true;
    }
    return false;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $validation = validateForm();

    echo "<pre>";
    print_r($validation);
    die;


    if (!empty($_POST['customer_id'])) {
        $statement = $db->prepare("UPDATE customers SET `first_name`=:fname, `last_name`=:lname,`username`=:username,`mobile`=:mobile,`email`=:email,`gender`=:gender,`hobbies`=:hobbies,`qualification`=:qualification,`updated_at`=:updated_at WHERE id=:custid");
        $statement->execute([
            'fname' => $_POST['first_name'] ?? '',
            'lname' => $_POST['last_name'] ?? '',
            'username' => $_POST['username'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'hobbies' => implode(',', $_POST['hobbies']) ?? '',
            'qualification' => $_POST['qualification'] ?? '',
            'updated_at' => date('Y-m-d H:i:s'),
            'custid' => $_POST['customer_id'],

        ]);
    } else {
        $statement = $db->prepare('INSERT INTO customers (`first_name`, `last_name`,`username`,`mobile`,`email`,`gender`,`hobbies`,`qualification`,`created_at`,`updated_at`)
        VALUES (:fname, :lname, :username, :mobile, :email, :gender, :hobbies, :qualification, :created_at, :updated_at)');

        $statement->execute([
            'fname' => $_POST['first_name'] ?? '',
            'lname' => $_POST['last_name'] ?? '',
            'username' => $_POST['username'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'hobbies' => implode(',', $_POST['hobbies']) ?? '',
            'qualification' => $_POST['qualification'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
    }
    header("location:index.php");
}
if (!empty($_GET['uid'])) {
    $stmt = $db->prepare("SELECT * FROM customers WHERE id=:uid");
    $stmt->execute(['uid' => $_GET['uid']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $hobbies = explode(',', $customer['hobbies']);
}


?>

<h1 class>CRUD USING PDO IN PHP</h1>
<div class="container">
    <form action="editView.php" method="post">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" value="<?= $customer['first_name'] ?? '' ?>" id="first_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" value="<?= $customer['last_name'] ?? '' ?>" id="last_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="username">User Name</label>
            <input type="text" name="username" value="<?= $customer['username'] ?? '' ?>" id="username" class="form-control">
        </div>
        <div class="form-group">
            <label for="mobile">mobile</label>
            <input type="text" name="mobile" value="<?= $customer['mobile'] ?? '' ?>" id="mobile" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" value="<?= $customer['email'] ?? '' ?>" id="email" class="form-control">
        </div>

        <!-- -------------code for gender---------- -->

        <div class="form-group">
            <label>Gender</label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="male" id="male" <?= isset($customer) && $customer['gender'] == 'male' ? 'checked' : '' ?>>
                <label class="form-check-label" for="male">
                    Male
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="female" id="female" <?= isset($customer) && $customer['gender'] == 'female' ? 'checked' : '' ?>>
                <label class="form-check-label" for="female">
                    Female
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="other" id="other" <?= isset($customer) && $customer['gender'] == 'other' ? 'checked' : '' ?>>
                <label class="form-check-label" for="other">
                    Other
                </label>
            </div>
        </div>

        <!-- --end code for gender-- -->

        <div class="form-group">
            <label>Hobbies</label><br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="hobbies[]" value="singing" id="singing" <?php if (!empty($hobbies) && in_array('singing', $hobbies)) {
                                                                                                                    echo "checked";
                                                                                                                } ?>>
                <label class="form-check-label" for="singing">
                    Singing
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="hobbies[]" value="swimming" id="swimming" <?php if (!empty($hobbies) && in_array('swimming', $hobbies)) {
                                                                                                                    echo "checked";
                                                                                                                } ?>>
                <label class="form-check-label" for="swimming">
                    Swimming
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="hobbies[]" value="dancing" id="dancing" <?php if (!empty($hobbies) && in_array('dancing', $hobbies)) {
                                                                                                                    echo "checked";
                                                                                                                } ?>>
                <label class="form-check-label" for="dancing">
                    Dancing
                </label>
            </div>
        </div>


        <div class="form-group">
            <label>Qualification</label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qualification" value="High School" id="High School" <?= isset($customer) && $customer['qualification'] == 'High School' ? 'checked' : '' ?>>
                <label class="form-check-label" for="High School">
                    High School
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qualification" value="Diploma" id="Diploma" <?= isset($customer) && $customer['qualification'] == 'Diploma' ? 'checked' : '' ?>>
                <label class="form-check-label" for="Diploma">
                    Diploma
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qualification" value="Btech" id="Btech" <?= isset($customer) && $customer['qualification'] == 'Btech' ? 'checked' : '' ?>>
                <label class="form-check-label" for="Btech">
                    Btech
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qualification" value="Mtech" id="Mtech" <?= isset($customer) && $customer['qualification'] == 'Mtech' ? 'checked' : '' ?>>
                <label class="form-check-label" for="Mtech">
                    Mtech
                </label>
            </div>
        </div>


        <!-- start code for button  -->

        <div class="form-group mt-2">
            <?php if (!empty($customer['id'])) { ?>
                <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                <button name="submit" type="submit" class="btn btn-primary">UPDATE Customer</button>
            <?php } else { ?>
                <button name="submit" type="submit" class="btn btn-primary">ADD Customer</button>
            <?php } ?>
        </div>

        <!-- end code for button -->
    </form>
</div>




<?php
require_once "../common/footer.php"
?>