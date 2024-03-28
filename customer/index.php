<?php
require_once "../common/header.php";

$stmt = $db->prepare("SELECT * FROM customers WHERE deleted_at is null");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_GET['did'])) {
    $statement = $db->prepare('UPDATE customers SET deleted_at=:deleted_at WHERE id=:id');
    $statement->execute([
        'deleted_at' => date('Y-m-d H:i:s'),
        'id' => $_GET['did'],
    ]);
    header("location:index.php");
}
?>

<div class="container">

    <h2>All Customers</h2>
    <div class="form-group mt-2">
        <button class="btn btn-primary "><a href="editView.php" class="text-light">ADD CUSTOMER</a></button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" class="checkbox" name="select_all" id="selectAll">
                </th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>User Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Hobbies</th>
                <th>Qualification</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer) : ?>
                <td><input type="checkbox" class="ids" name="ids[]" value="<?= $customer['id']; ?>"></td>
                <td><?= $customer['first_name']; ?></td>
                <td><?= $customer['last_name']; ?></td>
                <td><?= $customer['username']; ?></td>
                <td><?= $customer['mobile']; ?></td>
                <td><?= $customer['email']; ?></td>
                <td><?= $customer['gender']; ?></td>
                <td><?= $customer['hobbies']; ?></td>
                <td><?= $customer['qualification']; ?></td>
                <td>
                    <button class="btn btn-primary"><a href="editView.php?uid=<?= $customer['id'] ?>" class="text-light">UPDATE</a></button>
                    <button class="btn btn-danger"><a href="index.php?did=<?= $customer['id'] ?>" onClick="return confirm('Are you relly want to delete the record?');" class="text-light">DELETE</a></button>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once "../common/footer.php"; ?>

<script>
    $(document).on('click', '#selectAll', function() {
        if ($(this).prop("checked")) {
            $(".ids").prop("checked", true);
        } else {
            $(".ids").prop("checked", false);
        }
    })
</script>