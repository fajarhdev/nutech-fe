<?php 
	session_start();
	$token = $_SESSION["jwt"];

	// User hit api

	$urlUser = "https://be-nutech.herokuapp.com/api/v1/users/get";

	$chUser = curl_init();
	curl_setopt($chUser, CURLOPT_URL, $urlUser);
	curl_setopt($chUser, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer ' . $token,
	));
	curl_setopt($chUser, CURLOPT_RETURNTRANSFER, true);

	$responseUser = curl_exec($chUser);
	curl_close($chUser);

	$resultUser = json_decode($responseUser, true);

	// Item hit api
    if($resultUser["data"]["status"] == "seller"){
        $url = "https://be-nutech.herokuapp.com/api/v1/item/get/selle?limit=15&page=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
    }

	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<title>Profile</title>
		<!-- Bootstrap CSS -->
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
			crossorigin="anonymous"
		/>
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<!-- Logo -->
				<a class="navbar-brand" href="../js/homepage.html">Logo</a>
				<!-- Category Dropdown -->
				<ul class="navbar-nav me-auto">
					<li class="nav-item dropdown">
						<a
							class="nav-link dropdown-toggle"
							href="#"
							id="navbarDropdown"
							role="button"
							data-bs-toggle="dropdown"
							aria-expanded="false"
						>
							Categories
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="#">Category 1</a></li>
							<li><a class="dropdown-item" href="#">Category 2</a></li>
							<li><a class="dropdown-item" href="#">Category 3</a></li>
						</ul>
					</li>
				</ul>
				<!-- User Info and Search Bar -->
				<div class="d-flex">
					<div class="me-3">
						<span class="fw-bold"
							><a href="profile.php"><?php echo $resultUser["data"]["name"]; ?></a></span
						>
						<br />
						<?php if ($resultUser["data"]["status"] != "seller"): ?>
						<span class="text-muted">User | <a href="#">want become seller?</a></span>
						<?php else: ?>
						<span class="text-muted">Seller</span>
						<?php endif ?>
					</div>
					<form class="d-flex">
						<input
							class="form-control me-2"
							type="search"
							placeholder="Search"
							aria-label="Search"
						/>
						<button class="btn btn-outline-success" type="submit">Search</button>
					</form>
				</div>
			</div>
		</nav>

		<!-- Main Content -->
		<!-- Profile Section -->
		<div class="row justify-content-center align-items-center my-5">
			<div class="col-auto">
				<img
					src="https://via.placeholder.com/150"
					alt="Profile Picture"
					class="rounded-circle"
				/>
			</div>
			<div class="col-auto">
				<h2 class="fw-bold mb-1">John Doe</h2>
				<p class="text-muted mb-1">Seller</p>
				<div class="d-flex">
					<button
						type="button"
						class="btn btn-outline-secondary me-3"
						data-bs-toggle="modal"
						data-bs-target="#editProfileModal"
					>
						Edit Profile
					</button>
					<button
						type="button"
						class="btn btn-outline-secondary"
						data-bs-toggle="modal"
						data-bs-target="#addProductModal"
					>
						Add Product
					</button>
				</div>
			</div>
		</div>

        <?php if ($resultUser["data"]["status"] == "seller"):?>
		<!-- Product Section -->
		<main class="container my-4">
			<h1 class="text-center">Product List</h1>
			<div class="row row-cols-1 row-cols-md-3 g-4">
				<?php
    			// assume $products is the array of products obtained from the API
    			foreach ($result["data"]["items"] as $product): ?>
				<div class="col">
					<a
						class="card h-100"
						id="product-card"
						data-bs-toggle="modal"
						data-bs-target="#buy-sell-modal"
					>
						<img
							src="<?php echo $product['filename']; ?>"
							class="card-img-top"
							alt="<?php echo $product['filename']; ?>"
						/>
						<div class="card-body">
							<h5 class="card-title"><?php echo $product['name']; ?></h5>
							<p class="card-text">Buying Price: $<?php echo $product['buying_price']; ?></p>
							<p class="card-text">Selling Price: $<?php echo $product['sell_price']; ?></p>
							<p class="card-text">
								Stock:
								<?php echo $product['stock']; ?>
							</p>
						</div>
					</a>
				</div>
				<?php endforeach?>
			</div>
			<nav aria-label="Page navigation example">
				<ul class="pagination justify-content-center mt-4" id="pagination">
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
					<p id="page-info">
						<span id="current-page">1</span> of <span id="total-pages">1</span>
					</p>
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</main>
        <?php endif;?>

		<!-- Footer -->
		<footer class="bg-light text-center text-lg-start">
			<div class="container p-4">
				<div class="row">
					<div class="col-lg-6 col-md-12 mb-4 mb-md-0">
						<h5 class="text-uppercase">Footer Content</h5>
						<p>Place some information here about your website and what it offers.</p>
					</div>
					<div class="col-lg-6 col-md-12 mb-4 mb-md-0">
						<h5 class="text-uppercase">Links</h5>
						<ul class="list-unstyled mb-0">
							<li>
								<a href="#!" class="text-dark">Link 1</a>
							</li>
							<li>
								<a href="#!" class="text-dark">Link 2</a>
							</li>
							<li>
								<a href="#!" class="text-dark">Link 3</a>
							</li>
							<li>
								<a href="#!" class="text-dark">Link 4</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
				© 2023 Your Website Name
			</div>
		</footer>

		<!-- HTML for modal component -->
		<!-- Product card -->
		<div
			class="modal fade"
			id="buy-sell-modal"
			tabindex="-1"
			aria-labelledby="buy-sell-modal-label"
			aria-hidden="true"
		>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="buy-sell-modal-label">Buy/Sell Product</h5>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Close"
						></button>
					</div>
					<div class="modal-body">
						<p>Enter your details to buy or sell this product.</p>
						<form>
							<div class="mb-3">
								<label for="name" class="form-label">Name</label>
								<input type="text" class="form-control" id="name" required />
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control" id="email" required />
							</div>
							<div class="mb-3">
								<label for="phone" class="form-label">Phone Number</label>
								<input type="tel" class="form-control" id="phone" required />
							</div>
							<div class="mb-3">
								<label for="quantity" class="form-label">Quantity</label>
								<input type="number" class="form-control" id="quantity" required />
							</div>
							<div class="mb-3">
								<label for="message" class="form-label">Message</label>
								<textarea class="form-control" id="message" rows="3"></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Edit Profile Modal -->
		<div
			class="modal fade"
			id="editProfileModal"
			tabindex="-1"
			aria-labelledby="editProfileModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Close"
						></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label for="nameInput" class="form-label">Name</label>
								<input
									type="text"
									class="form-control"
									id="nameInput"
									placeholder="Enter your name"
									required
								/>
							</div>
							<div class="mb-3">
								<label for="birthdateInput" class="form-label">Birthdate</label>
								<input type="date" class="form-control" id="birthdateInput" required />
							</div>
							<div class="mb-3">
								<label for="phoneInput" class="form-label">Phone Number</label>
								<input
									type="tel"
									class="form-control"
									id="phoneInput"
									placeholder="Enter your phone number"
									required
								/>
							</div>
							<div class="mb-3">
								<label for="emailInput" class="form-label">Email address</label>
								<input
									type="email"
									class="form-control"
									id="emailInput"
									placeholder="Enter your email"
									required
								/>
							</div>
							<div class="mb-3">
								<label for="passwordInput" class="form-label">Password</label>
								<input
									type="password"
									class="form-control"
									id="passwordInput"
									placeholder="Enter your password"
									required
								/>
							</div>
							<div class="mb-3">
								<label for="statusInput" class="form-label">Status</label>
								<input
									type="text"
									class="form-control"
									id="statusInput"
									value="Seller"
									readonly
								/>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
							Close
						</button>
						<button type="button" class="btn btn-primary">Save Changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Add Product Modal -->
		<div
			class="modal fade"
			id="addProductModal"
			tabindex="-1"
			aria-labelledby="addProductModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Close"
						></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label for="productName" class="form-label">Name</label>
								<input type="text" class="form-control" id="productName" />
							</div>
							<div class="mb-3">
								<label for="buyPrice" class="form-label">Buy Price</label>
								<input type="number" class="form-control" id="buyPrice" />
							</div>
							<div class="mb-3">
								<label for="sellPrice" class="form-label">Sell Price</label>
								<input type="number" class="form-control" id="sellPrice" />
							</div>
							<div class="mb-3">
								<label for="stock" class="form-label">Stock</label>
								<input type="number" class="form-control" id="stock" />
							</div>
							<div class="mb-3">
								<label for="picture" class="form-label">Picture</label>
								<input type="file" class="form-control" id="picture" />
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Bootstrap JavaScript -->
		<script
			src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
			integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
			crossorigin="anonymous"
		></script>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
			integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
			crossorigin="anonymous"
		></script>
		<script>
			var productCard = document.getElementById("product-card");
			productCard.addEventListener("click", function () {
				var buySellModal = document.getElementById("buy-sell-modal");
				var modal = new bootstrap.Modal(buySellModal);
				modal.show();
			});

			var closeButton = document.querySelector("#buy-sell-modal .btn-close");
			closeButton.addEventListener("click", function () {
				var buySellModal = document.getElementById("buy-sell-modal");
				var modal = bootstrap.Modal.getInstance(buySellModal);
				modal.hide();
			});
		</script>
	</body>
</html>
