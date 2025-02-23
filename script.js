// Envoyer une signature
function sign() {
    let name = document.getElementById("name").value.trim();
    if (!name) {
        document.getElementById("message").innerText = "Veuillez entrer un nom.";
        return;
    }

    fetch("api.php", {
        method: "POST",
        body: JSON.stringify({ name }),
        headers: { "Content-Type": "application/json" }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("message").innerText = "Merci pour votre signature !";
                document.getElementById("name").value = "";
                loadSignatures(); // Recharger la liste
            } else {
                document.getElementById("message").innerText = data.error || "Erreur inconnue.";
            }
        });
}

// Charger les signatures
function loadSignatures() {
    fetch("api.php")
        .then(response => response.json())
        .then(data => {
            let list = document.getElementById("signatures");
            list.innerHTML = "";
            data.forEach(sign => {
                let li = document.createElement("li");
                li.innerText = `${sign.name} - ${new Date(sign.created_at).toLocaleDateString()}`;
                list.appendChild(li);
            });
        });
}

// Charger les signatures au chargement de la page
document.addEventListener("DOMContentLoaded", loadSignatures);
