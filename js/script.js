document.addEventListener('DOMContentLoaded', function () {
    // Função para atualizar o total do carrinho
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value, 10);
            const price = parseFloat(input.closest('.produto-carrinho').querySelector('.increment-btn').dataset.preco.replace('.', '').replace(',', '.'));
            total += quantity * price;
        });
        document.getElementById('total-value').innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }

    // Diminuir quantidade
    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', function () {
            const quantityInput = this.nextElementSibling;
            let currentQuantity = parseInt(quantityInput.value, 10);
            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
                updateTotal();
            }
        });
    });

    // Aumentar quantidade
    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', function () {
            const quantityInput = this.previousElementSibling;
            let currentQuantity = parseInt(quantityInput.value, 10);
            quantityInput.value = currentQuantity + 1;
            updateTotal();
        });
    });

    // Atualizar a página após a remoção do produto
    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const form = this.closest('form');
            form.submit();
        });
    });

    // Atualiza o total ao carregar a página
    updateTotal();
    
});
