<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Send the data to the API endpoint
    $url = 'https://be-nutech.herokuapp.com/api/v1/auth/signup';
    $data = array(
        'name' => $name,
        'birthdate' => $birthdate,
        'phone' => $phone,
        'email' => $email,
        'password' => $password
    );
    $data_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_json))
    );

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['data']['msg'] == 'Berhasil Login') {
        $_SESSION['jwt'] = $result['data']['token'];
        header("Location: homepage.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
			crossorigin="anonymous"
		/>
		<title>Sign Up</title>
	</head>
	<body>
		<div class="d-flex align-items-center" style="height: 100vh">
			<div class="container mx-auto">
				<!-- content here -->
				<div class="row">
					<div class="col-5">
						<img src="#" alt="" srcset="" />
					</div>
					<div class="col-7">
						<form action="" method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label for="name" class="form-label">Name</label>
								<input type="text" class="form-control" id="name" name="name" required />
								<div class="invalid-feedback">Please enter your name.</div>
							</div>
							<div class="mb-3">
								<label for="birthdate" class="form-label">Birthdate</label>
								<input
									type="date"
									class="form-control"
									id="birthdate"
									name="birthdate"
									required
								/>
								<div class="invalid-feedback">Please enter your birthdate.</div>
							</div>
							<div class="mb-3">
								<label for="phone" class="form-label">Phone Number</label>
								<input type="tel" class="form-control" id="phone" name="phone" required />
								<div class="invalid-feedback">Please enter a valid phone number.</div>
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email address</label>
								<input
									type="email"
									class="form-control"
									id="email"
									name="email"
									placeholder="abc@mail.com"
									required
								/>
								<div class="invalid-feedback">Please enter a valid email address.</div>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input
									type="password"
									class="form-control"
									id="password"
									name="password"
									required
								/>
								<div class="invalid-feedback">Please enter a password.</div>
							</div>
							<button type="submit" class="btn btn-primary">Sign Up</button>
						</form>
					</div>
				</div>
			</div>
		</div>

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
	</body>
</html>
