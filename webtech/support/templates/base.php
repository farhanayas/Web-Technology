<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cartista</title>
    <link rel="stylesheet" type="text/css" href="staticfiles/hw1.css">
    <link rel="stylesheet" type="text/css" href="staticfiles/clients_style.css">
    <link rel="stylesheet" type="text/css" href="staticfiles/footer_base.css">
    <link rel="stylesheet" type="text/css" href="staticfiles/admin_login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>&lt;Cartista&gt;</h1>
        <nav>
            <!--
            {% include 'nav.html' %}
            {% block authnav %}
                <a href="/login/">Login/Logout</a>
            {% endblock %}
            -->
            <a href="/cartista/login_block.php">Login/Logout</a>
            <?php include 'nav.html';?>

        </nav>
    </header>
    <main>

    </main>
    <div class="footer_base">
      <p>Cartista Beta - Developed by Team G8- Built with ❤️ in AIUB</p>
    </div>
</body>
</html>