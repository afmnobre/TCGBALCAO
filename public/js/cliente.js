document.addEventListener("DOMContentLoaded", function() {
    const telefoneInput = document.getElementById("telefone");
    const statusLabel = document.getElementById("statusCliente");

    function aplicarMascara(valor) {
        let v = valor.replace(/\D/g, "");
        if (v.length > 11) v = v.substring(0, 11);

        if (v.length <= 10) {
            // Telefone fixo: (XX) XXXX-XXXX
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else {
            // Celular: (XX) XXXXX-XXXX
            v = v.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, "($1) $2-$3");
        }

        return v;
    }

    if (telefoneInput) {
        // aplica máscara inicial
        telefoneInput.value = aplicarMascara(telefoneInput.value);

        telefoneInput.addEventListener("input", function() {
            telefoneInput.value = aplicarMascara(telefoneInput.value);
        });

        telefoneInput.addEventListener("blur", function() {
            // remove máscara e pega só os dígitos
            const telefone = telefoneInput.value.replace(/\D/g, "");
            console.log("Disparando verificação para:", telefone); // debug

            if (telefone.length < 10) return; // só dispara se tiver tamanho válido

fetch("/cliente/verificarTelefone?telefone=" + telefone)
    .then(res => {
        if (!res.ok) {
            throw new Error("Erro HTTP: " + res.status);
        }
        return res.json();
    })
    .then(data => {
        console.log("Resposta:", data);
        if (data.encontrado) {
            document.querySelector("input[name='nome']").value = data.nome;
            document.querySelector("input[name='email']").value = data.email;
            statusLabel.textContent = "Cliente já cadastrado - VINCULE E SALVE";
            statusLabel.style.color = "yellow";
        } else {
            statusLabel.textContent = "Novo cliente";
            statusLabel.style.color = "green";
        }
    })
    .catch(err => console.error("Erro na requisição:", err));

        });
    }

    // aplica máscara na listagem
    document.querySelectorAll(".telefone-coluna").forEach(function(el) {
        el.textContent = aplicarMascara(el.textContent);
    });
});

