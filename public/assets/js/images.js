let links = document.querySelectorAll("[data-delete]");
// on boucle sur les liens
for (let link of links) {
    // on met un écouteur d'évènements
    link.addEventListener("click", function(e) {
        // on empêche la navigation
        e.preventDefault();

        // on demande la confirmation de suppression
        if(confirm("Voulez-vous supprimer cette image ?")){
            fetch(this.getAttribute("href"),{
                method: "DELETE",
                headers: {
                    "X-Requested-With":"XMLHttpRequest",
                    "Content-Type":"application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(response => response.json())
            .then(data => {
                if(data.success){
                    this.parentElement.remove();
                }else{
                    alert(data.error);
                }
            })
        }
    });
}