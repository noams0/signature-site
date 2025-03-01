document.addEventListener("DOMContentLoaded", function() {
    fetch("api.php?action=get_signatures")
        .then(res => res.json())
        .then(data => {
            let list = document.getElementById("signatories");
            data.forEach(s => {
                let li = document.createElement("li");
                li.textContent = s.name;
                list.appendChild(li);
            });
        });

    document.getElementById("signature-form").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("name", document.getElementById("name").value);
        formData.append("email", document.getElementById("email").value);

        fetch("api.php?action=request_signature", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById("response").textContent = data.success || data.error;
            });
    });
});
