<?php include 'header2.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Heavenly Cravings Cake Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>


        nav {
            color: #E30B5D;
            padding: 10px;
            text-align: center;
        }

        section {
            padding: 40px 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .welcome-text {
            max-width: 600px;
            text-align: left;
        }

        .welcome-images img {
            width: 50%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .team-members {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .team-member {
            margin: 15px;
            max-width: 200px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .team-member:hover {
            transform: scale(1.05);
        }

        p {
            text-align: justify;
        }
    </style>
</head>

<body>


    <section>
        <div class="welcome-text">
            <h2>Welcome to Heavenly Cravings Cake Company</h2>
            <p>Heavenly Cravings Cake Company is a dessert icon, known for its irresistibly delicious cakes.
                With a rich history of excellence and innovation, the company is now introducing a club membership
                system to enhance connections with its loyal customers. At Heavenly Cravings, we believe that every celebration deserves a slice of joy, and that's why we pour our passion into creating decadent, delightful cakes that not only satisfy your cravings but also elevate your special moments. Our journey began with a simple goal: to craft cakes that not only taste heavenly but also reflect the unique essence of your celebrations.</p>
            <p>Explore our menu and satisfy your sweet cravings.</p>
        </div>
        <div class="welcome-images">
            <img src="images\about1.jpeg" alt="Cake5 ">
            
        </div>
    </section>

    <section>
        <h2>Meet Our Team</h2>
        <div class="team-members">
            <div class="team-member">
                <img src="images\aishah.jpg" alt="Person 1" width="150px">
                <h3>SITI NURAISHAH BINTI KAMARUDIN</h3>
                <p>2021828912</p>
                <p>PROJECT MANAGER</p>
            </div>
            <div class="team-member">
                <img src="images\azma.jpg" alt="Person 2" width="150px">
                <h3>NUR AZMATUN FARWIZAH BINTI FARID</h3>
                <p>2021829744</p>
                <p>WEB DEVELOPER</p>
            </div>
            <div class="team-member">
                <img src="images\aina.jpg" alt="Person 3" width="150px">
                <h3>NURUL AINA BT ABDUL HALIM</h3>
                <p>2021829644</p>
                <p>MARKETING SPECIALIST</p>
            </div>
            <div class="team-member">
                <img src="person4.jpg" alt="Person 4" width="150px">
                <h3>NUR ALIA</h3>
                <p>2021485546</p>
                <p>UI/UX DESIGNER</p>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-3">Contact</h4>
                    <p class="mb-1"><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</p>
                    <p class="mb-1"><i class="fa fa-phone-alt me-2"></i>+012 345 67890</p>
                    <p class="mb-1"><i class="fa fa-envelope me-2"></i>info@example.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-3">Opening</h4>
                    <h5 class="text-light fw-normal mb-1">Monday - Saturday</h5>
                    <p>09AM - 09PM</p>
                    <h5 class="text-light fw-normal mb-1">Sunday</h5>
                    <p>10AM - 08PM</p>
                </div>

                <!-- Newsletter Signup -->
                <div class="col-lg-4 col-md-6">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-3">Newsletter</h4>
                    <div class="position-relative mx-auto" style="max-width: 300px;">
                        <input class="form-control border-primary w-100 py-2 ps-3 pe-4 mb-2" type="text"
                            placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 w-100">SignUp</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Copyright Notice -->
                <div class="col-md-6 text-center text-md-start mb-2">
                    &copy; 2024 Heavenly Cravings Cake Company, All Right Reserved.
                </div>

                <!-- Footer Menu -->
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a class="text-light me-2" href="#">Home</a>
                        <a class="text-light me-2" href="#">Cookies</a>
                        <a class="text-light me-2" href="#">Help</a>
                        <a class="text-light me-2" href="#">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>

<?php include 'footer.php'; ?>