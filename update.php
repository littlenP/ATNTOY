<?php
include "connect.php";
$get_categories = "SELECT * FROM category";
$categories = mysqli_query($conn, $get_categories);

$categoriesArr = [];
if ($categories) {
    while ($row = mysqli_fetch_assoc($categories)) {
        $categoriesArr[$row['id']] = $row['cate_name'];
    }
}
$get_products = "SELECT * FROM products";
$products = mysqli_query($conn, $get_products);

$currentURL = $_SERVER['REQUEST_URI'];
$parts = explode('/', $currentURL);
$id = end($parts);

$getProductById = "SELECT * FROM products WHERE id='$id'";
$currentProduct = mysqli_query($conn, $getProductById);

if ($currentProduct && mysqli_num_rows($currentProduct) > 0) {
    $productData = mysqli_fetch_assoc($currentProduct);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $thumbnail = $_FILES['thumbnail'];
    $thumbnailName = $thumbnail["name"];
    $thumbnailImpName = $thumbnail["tmp_name"];
    $thumbnailPath = "assets/img" . $thumbnailName;
    move_uploaded_file($thumbnailImpName, $thumbnailPath);

    $sql = "UPDATE products SET prod_name =? ,price=?,category_id=?,thumbnail=? WHERE id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $title, $price, $category, $thumbnailPath, $id);


    if ($stmt->execute()) {
        header("Location:../index.php");
        exit();
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
</head>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">ATN Toy</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./create.php">New</a>
                    </li>


                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <form class="row container  " style="margin-left: auto; margin-right: auto;" method="POST" enctype="multipart/form-data">

        <h5 style="text-align: center;">Update a product</h5>
        <?php foreach ($currentProduct as $product) { ?>
            <div class="col-12 mb-3">
                <label for="product_name" class="form-label">Product name</label>
                <input type="text" class="form-control" id="product_name" name="name" placeholder="Input product name" value="<?php echo $product["prod_name"]?>">
            </div>

            <div class="col-12 mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Input the price of the product" value="<?php echo $product["price"]?>">
            </div>
            <div class="col-12 mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option selected disabled value="<?php echo $product["category_id"] ?>">
                        <?php echo "#".$categoriesArr[$product["category_id"]] ?>
                    </option>
                    <?php foreach ($categories as $category) { ?>
                        <option class="text-dark" value="<?php echo $category["id"] ?>"><?php echo "#" . $category["cate_name"] ?></option>
                    <?php    } ?>
                </select>
                <!-- disabled la khong chinh sua duoc -->
            </div>
            <div class="col-12 mb-3">
                <label for="img" class="form-label">Image</label>
                <input type="file" class="form-control" id="product_name" name="thumbnail" value="<?php echo $product["thumbnail"] ?>" >
            </div>
        <?php } ?>
<div class="d-flex justify-content-center mt-3 gap-3">
            <button type="submit" class="btn btn-success ">Update </button>
            <a href="/index.php" class="btn btn-secondary " type="button">Back to products</a>
        </div>

    </form>

</body>

</html>