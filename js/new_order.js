window.onload = () => {

    let price = document.getElementById('price').value;
    price = price.substring(0, price.length - 2);

    let summary = document.getElementById('summary');
    showSummary("Karta", price);

    let paymentSelect = document.getElementById('payment_type');
    paymentSelect.addEventListener('change', () => {
        showSummary(paymentSelect.value, price);
    });


    let cancelButton = document.getElementById('cancel');
    cancelButton.addEventListener('click', () => {
       window.location = "index.php";
    });

};

function showSummary(value, price){
    switch (value){
        case "Karta":
            summary.innerHTML = "W tym momencie do zapłaty: " + price + " zł";
            break;
        case "Gotówka":
            summary.innerHTML = "W tym momencie do zapłaty: 0.00 zł";
            break;
        case "Podzielona":
            let newPrice = parseFloat(price);
            newPrice += 0.01;
            newPrice = newPrice / 2;
            summary.innerHTML = "W tym momencie do zapłaty: " + newPrice + ".00 zł";
            break;
    }
}