
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="contactez-nous">
        <h1>Contactez-nous</h1>
            <p>Un problème, une question, envie de nous envoyer un message? Veuillez utiliser ce formulaire pour prendre contact avec nous !</p>
              <form action="/page-traitement-donnees" method="post">
                <div>
                    <label for="nom">Votre nom</label>
                    <input type="text" id="nom" name="nom" placeholder="" required>
                </div>
                <div>
                    <label for="email">Votre e-mail</label>
                    <input type="email" id="email" name="email" placeholder="" required>
                </div>
                <div>
                    <label for="message">Votre message</label>
                    <textarea id="message" name="message" placeholder="Bonjour, je vous contacte car...." required></textarea>
                </div>
                <div>
                    <button type="submit">Envoyer mon message.</button>
                </div>
              </form>
    </div>
    <footer>
        <?php
        require_once "../publicView/src/footer.php"
        ?>
    </footer>

    <script src="js/script.js"></script>
       
</body>
</html>
