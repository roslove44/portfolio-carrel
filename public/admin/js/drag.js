dragparent = document.getElementById("dragparent");
dragthings = document.querySelectorAll(".dragthing");
baseOrder = [];

function init() {
  dragula([dragparent]);
}
init();

for (const dragthing of dragthings) {
  baseOrder.push(dragthing.getAttribute("data-project-id"));
}

function getOrder(e) {
  idOrder = [];
  dragthings = document.querySelectorAll(".dragthing"); //Recalculer le parent
  for (const dragthing of dragthings) {
    idOrder.push(dragthing.getAttribute("data-project-id"));
  }

  if (JSON.stringify(idOrder) == JSON.stringify(baseOrder)) {
    alert("Aucun Changement à apporter.");
  } else if (confirm("Voulez-vous sauvegarder les changements ?")) {
    jsonIdOrder = JSON.stringify(idOrder);
    fetch(e.getAttribute("data-href"), {
      method: "PUT",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        _token: e.dataset.token,
        _idOrder: jsonIdOrder,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Enregistrement effectué avec succès");
          location.reload();
        } else {
          alert(data.error);
        }
      });
  }
}

function deleteProject(e) {
  if (confirm(`Voulez-vous supprimer le projet : ` + e.dataset.slug + "?")) {
    fetch(e.getAttribute("data-href"), {
      method: "DELETE",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        _token: e.dataset.token,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Projet" + e.dataset.slug + "supprimé avec succès");
          location.reload();
        } else {
          alert(data.error);
        }
      });
  }
}
