<!DOCTYPE html>
<html>
<head>
<title>Your Website</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
footer {
  background-color: #f2f2f2;
  padding: 20px 0;
  text-align: center;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 900px;
  margin: 0 auto;
}

.footer-left h4 {
  margin-bottom: 10px;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  display: inline-block;
  margin-right: 15px;
}

.footer-links li a {
  text-decoration: none;
  color: #333;
}

.stay-in-touch {
  margin-top: 10px;
}

.fab.fa-facebook-f {
  font-size: 24px;
  margin-right: 10px;
}

.copyright {
  font-size: 12px;
  margin-top: 10px;
}
</style>
</head>
<body>

<footer>
  <div class="footer-container">
    <div class="footer-left">
      <h4>Colorlib</h4>
      <ul class="footer-links">
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Press</a></li>
        <li><a href="#">Careers</a></li>
      </ul>
    </div><br>
    <div class="footer-right">
      <ul class="footer-links">
        <li><a href="#">FAQ</a></li>
        <li><a href="#">Legal</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <p class="stay-in-touch">Stay in touch</p>
      <i class="fab fa-facebook-f"></i>
      <p class="copyright">Colorlib. All Rights Reserved.</p>
    </div>
  </div>
</footer>

</body>
</html>
