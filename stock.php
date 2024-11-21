<?php
session_start();
include_once 'db/function.php';

$function = new DBFunctions();
$products = $function->select('products', '*');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'addproduct') {
        $data = [
            'name' => $_POST['productName'],
            'stock' => $_POST['productStock'],
            'description' => $_POST['productDescription'],
            'category' => $_POST['productCategory'],
            'supplier' => $_POST['productSupplier']
        ];

        // Handle the file upload
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/images/';
            $imageName = basename($_FILES['productImage']['name']);
            $imagePath = $uploadDir . $imageName;

            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $imagePath)) {
                $data['image'] = $imageName;  // Save the filename to the database
            } else {
                echo "Failed to upload image.";
                exit;
            }
        } else {
            echo "Image upload error.";
            exit;
        }

        if ($function->insert('products', $data)) {
            echo "<script>alert('Item added successfully'); window.location.href = 'stock.php';</script>";
        } else {
            echo "Failed to add item.";
        }
    } elseif ($action === 'editproduct') {
        $productId = $_POST['productId'];
        $data = [
            'name' => $_POST['productName'],
            'stock' => $_POST['productStock'],
            'description' => $_POST['productDescription'],
            'category' => $_POST['productCategory'],
            'supplier' => $_POST['productSupplier']
        ];

        // Check if a new image file is uploaded
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/images/';
            $imageName = basename($_FILES['productImage']['name']);
            $imagePath = $uploadDir . $imageName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $imagePath)) {
                $data['image'] = $imageName; // Update image name in data
            } else {
                echo "Failed to upload image.";
                exit;
            }
        }

        // Define conditions for the update
        $conditions = ['id' => $productId];

        if ($function->update('products', $data, $conditions)) {
            echo "<script>alert('Item updated successfully'); window.location.href = 'stock.php';</script>";
        } else {
            echo "Failed to update item.";
        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks - Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="content p-4">
        <h2>Stocks</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addItemModal">Add Item</button>


        <table id="stocksTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image"
                                width="50" height="50"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['supplier']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editItemModal"
                                        data-id="<?php echo htmlspecialchars($product['id']); ?>"
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                        data-stock="<?php echo htmlspecialchars($product['stock']); ?>"
                                        data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                        data-category="<?php echo htmlspecialchars($product['category']); ?>"
                                        data-supplier="<?php echo htmlspecialchars($product['supplier']); ?>"
                                        onclick="populateEditModal(this)">Edit</a>

                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Edit Modal -->
        <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="stock.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="editproduct">
                            <input type="hidden" name="productId" id="editProductId">

                            <div class="form-group">
                                <label for="editProductName">Product Name</label>
                                <input type="text" class="form-control" id="editProductName" name="productName"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editProductStock">Stock</label>
                                <input type="number" class="form-control" id="editProductStock" name="productStock"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editProductDescription">Description</label>
                                <textarea class="form-control" id="editProductDescription" name="productDescription"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editProductCategory">Category</label>
                                <input type="text" class="form-control" id="editProductCategory" name="productCategory"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editProductSupplier">Supplier</label>
                                <input type="text" class="form-control" id="editProductSupplier" name="productSupplier"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editProductImage">Upload New Image (optional)</label>
                                <input type="file" class="form-control-file" id="editProductImage" name="productImage">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <script>
            function populateEditModal(element) {
                // Populate modal fields with data from the selected row
                document.getElementById('editProductId').value = element.getAttribute('data-id');
                document.getElementById('editProductName').value = element.getAttribute('data-name');
                document.getElementById('editProductStock').value = element.getAttribute('data-stock');
                document.getElementById('editProductDescription').value = element.getAttribute('data-description');
                document.getElementById('editProductCategory').value = element.getAttribute('data-category');
                document.getElementById('editProductSupplier').value = element.getAttribute('data-supplier');
            }
        </script>


    </div>
    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="stock.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="addproduct">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="productStock">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" required>
                        </div>
                        <div class="form-group">
                            <label for="productDescription">Description</label>
                            <textarea class="form-control" id="productDescription" name="productDescription"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="productCategory">Category</label>
                            <input type="text" class="form-control" id="productCategory" name="productCategory"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="productSupplier">Supplier</label>
                            <input type="text" class="form-control" id="productSupplier" name="productSupplier"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="productImage">Image</label>
                            <input type="file" class="form-control-file" id="productImage" name="productImage" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#stocksTable').DataTable();
        });
    </script>
</body>

</html>