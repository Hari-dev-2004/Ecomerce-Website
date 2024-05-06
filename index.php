<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Query the database to check if any address-related fields are empty for the logged-in user
    $sql = "SELECT * FROM users WHERE id = $user_id AND (address = '' OR city = '' OR state = '' OR zip = '')";
    $result = $conn->query($sql);
    
    // If any fields are empty, display the message and link to the profile.php page
    if ($result->num_rows > 0) {
        echo '<div style="background-color: #ffcccc; padding: 10px; text-align: center;">';
        echo 'Please <a href="editaddress.php">add your address</a> to complete your profile.';
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Premium Fashion Brand</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .slideshow-container {
            position: relative;
            max-width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
            height: 600px;
        }

        .slides {
            display: flex;
            width: 300%;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .slide {
            width: 33.33%;
            flex-shrink: 0;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            color: white;
            text-align: center;
            width: 80%;
        }

        .hero h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: black;
        }

        .hero p {
            font-size: 1.5rem;
        }

        .featured-products {
            background-color: white;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 50px 10%;
            background-image: url("products/backgroundc.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            padding: 40px 0;
        }

        .product {
            width: 20%;
            margin-bottom: 80px;
            text-align: center;
        }

        .product img {
            width: 100%;
            height: auto;
            max-height: 300px;
        }

        .add-to-cart {
            text-align: center;
        }

        .btn {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: solid;
        }

        .btn:hover {
            background-color: #b4afb4;
        }

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="slideshow-container">
            <div class="slides">
                <div class="slide">
                    <img src="products/slide1.jpg" alt="Slideshow Image 1">
                </div>
                <div class="slide">
                    <img src="products/slide2.jpg" alt="Slideshow Image 2">
                </div>
                <div class="slide">
                    <img src="products/slide3.jpg" alt="Slideshow Image 3">
                </div>
            </div>
            <section class="hero">
                <h2></h2>
                <p>Check out our latest fashion styles</p>
            </section>
        </div>

        <section class="featured-products">
            <h2>Featured Products</h2>
            <?php
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 6";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row_count = 0;
                while ($row = $result->fetch_assoc()) {
                    if ($row_count % 3 == 0) {
                        echo "<div style='display:flex; justify-content:space-between; width: 100%;'>";
                    }
                    echo "<div class='product'>";
                    echo "<img src='products/" . $row['image'] . "' alt='" . $row['name'] . "'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>₹" . $row['price'] . "</p>";
                    echo "<a href='add_to_cart.php?id=" . $row['id'] . "' class='btn'>Add to Cart</a>";
                    echo "</div>";
                    if ($row_count % 3 == 2) {
                        echo "</div>";
                    }
                    $row_count++;
                }
            } else {
                echo "No products found.";
            }

            $conn->close();
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/typewriter-effect@latest/dist/core.min.js"></script>
    <script>
        const heroHeading = document.querySelector('.hero h2');
        const typewriter = new Typewriter(heroHeading, {
            loop: true,
            delay: 75,
        });

        typewriter
            .pauseFor(1000)
            .typeString('Embrace elegance, embody style with HRM: Where fashion meets sophistication.')
            .pauseFor(2000)
            .deleteAll()
            .start();

        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlides() {
            slides.forEach(slide => slide.style.transform = `translateX(-${slideIndex * 100}%)`);
            slideIndex++;
            if (slideIndex >= slides.length) {
                slideIndex = 0;
            }
            setTimeout(showSlides, 3000);
        }

        showSlides();
    </script>
</body>
</html>