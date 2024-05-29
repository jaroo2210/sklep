<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interaktywna lista w HTML</title>
</head>

<body>

<details>
  <summary>
    <h1>T-shirt</h1>
    <img src="images/T-shirt.jpg" alt="T-shirt" style="width:100px;height:100px;">
    <h4>Cena: 39.99zł</h4>
  </summary>
  <div>
    <label for="kolor1">Wybierz kolor:</label>
    <select id="kolor1" name="kolor">
      <option value="Czarny">Czarny</option>
      <option value="Biały">Biały</option>
      <option value="Czerwony">Czerwony</option>
      <option value="Niebieski">Niebieski</option>
    </select>
    <br>Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_ilosc1" placeholder="wpisz ilosc" autocomplete="off">
    <br><button type="button" onclick="addToCart('T-shirt', document.getElementById('kolor1').value, document.getElementsByName('f_ilosc1')[0].value, 39.99)">DODAJ</button>
  </div>
</details>
<hr>

<details>
  <summary>
    <h1>Bluza</h1>
    <img src="images/Bluza.jpg" alt="Bluza" style="width:100px;height:100px;">
    <h4>Cena: 79.99zł</h4>
  </summary>
  <div>
    <label for="kolor2">Wybierz kolor:</label>
    <select id="kolor2" name="kolor">
      <option value="Czarny">Czarny</option>
      <option value="Biały">Biały</option>
      <option value="Czerwony">Czerwony</option>
      <option value="Niebieski">Niebieski</option>
    </select>
    <br>Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_ilosc2" placeholder="wpisz ilosc" autocomplete="off">
    <br><button type="button" onclick="addToCart('Bluza', document.getElementById('kolor2').value, document.getElementsByName('f_ilosc2')[0].value, 79.99)">DODAJ</button>
  </div>
</details>
<hr>

<details>
  <summary>
    <h1>Kurtka</h1>
    <img src="images/Kurtka.jpg" alt="Kurtka" style="width:100px;height:100px;">
    <h4>Cena: 99.99zł</h4>
  </summary>
  <div>
    <label for="kolor3">Wybierz kolor:</label>
    <select id="kolor3" name="kolor">
      <option value="Czarny">Czarny</option>
      <option value="Biały">Biały</option>
      <option value="Czerwony">Czerwony</option>
      <option value="Niebieski">Niebieski</option>
    </select>
    <br>Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_ilosc3" placeholder="wpisz ilosc" autocomplete="off">
    <br><button type="button" onclick="addToCart('Kurtka', document.getElementById('kolor3').value, document.getElementsByName('f_ilosc3')[0].value, 99.99)">DODAJ</button>
  </div>
</details>
<hr>

<?php
// Sprawdzenie, czy użytkownik jest zalogowany

if (isset($_SESSION['user_type'])) {
    // Jeśli jest zalogowany, wyświetl koszyk
    echo '
    <div id="cart">
      <h2>Koszyk</h2>
      <ul id="cart-items"></ul>
      <p>Suma: <span id="cart-total">0</span> zł</p>
      <button type="button" onclick="sendToServer()">Zamów</button>
    </div>';
}
?>

<!-- Pozostała część kodu HTML -->

<script>
  function addToCart(name, color, quantity, price) {
    var item = document.createElement("li");
    var totalPrice = price * quantity; 
    item.textContent = name + " - " + color + " - " + quantity + " szt. - Cena: " + totalPrice.toFixed(2) + " zł";
    item.setAttribute("data-price", totalPrice); 
    item.setAttribute("data-unit-price", price);
    document.getElementById("cart-items").appendChild(item);
    updateTotal();
  }

  function updateTotal() {
    var total = 0;
    var items = document.querySelectorAll("#cart-items li");
    items.forEach(function(item) {
      total += parseFloat(item.getAttribute("data-price"));
    });
    document.getElementById("cart-total").textContent = total.toFixed(2);
  }

  function sendToServer() {
    var items = document.querySelectorAll("#cart-items li");
    var cartData = [];
    
    items.forEach(function(item) {
        var itemName = item.textContent.split(" - ")[0];
        var itemColor = item.textContent.split(" - ")[1];
        var itemQuantity = item.textContent.split(" - ")[2].split(" ")[0];
        var itemTotalPrice = item.getAttribute("data-price");
        var itemUnitPrice = item.getAttribute("data-unit-price"); // Pobierz cenę jednostkową
        cartData.push({
            name: itemName,
            color: itemColor,
            quantity: itemQuantity,
            totalPrice: itemTotalPrice,
            unitPrice: itemUnitPrice, // Dodaj cenę jednostkową
            userId: <?php echo $_SESSION['user_id'] ?? 'null'; ?>
        });
    });

    // Wysyłanie danych do serwera za pomocą AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "?page=infoklient", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Przekierowanie do nowej strony po otrzymaniu odpowiedzi
            window.location.href = "?page=infoklient";
        }
    };
    xhr.send(JSON.stringify(cartData));
  }
</script>

</body>
</html>
