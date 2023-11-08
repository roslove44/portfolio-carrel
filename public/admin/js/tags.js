let baseTag = {
  name: "",
  id: "",
  target: "",
};

let updatedTag = {
  name: "",
  id: "",
  target: "",
};

let flash = document.querySelector("#flash");

function editTag(e) {
  target = e.parentNode.parentNode.querySelector("[data-editTag]");
  target.setAttribute("contentEditable", true);

  // Placez le curseur à la fin du texte
  target.focus();
  setCursorAtEnd(target);

  // On initialise les objets afin de les comparer après changement
  baseTag.name = target.textContent.replace(/\s/g, "");
  baseTag.id = target.getAttribute("data-editTag");
  baseTag.target = target;

  updatedTag.name = target.textContent.replace(/\s/g, "");
  updatedTag.id = target.getAttribute("data-editTag");
  updatedTag.target = target;

  // Ecouter quand la valeur du champ change (updatedTag)
  target.addEventListener("input", function () {
    //on set la  nouvelle  valeur de    newTag
    updatedTag.name = target.textContent.replace(/\s/g, "");
    updatedTag.id = target.getAttribute("data-editTag");
  });
  target.addEventListener("keydown", function (e) {
    // Vérifiez si la touche enfoncée est la barre d'espace (code 32)
    if (e.keyCode === 32) {
      e.preventDefault();
    }

    // Vérifiez si la touche enfoncée est la touche ENTER (code 13)
    if (e.keyCode === 13) {
      e.preventDefault();
      confirmMessage =
        "Voulez-vous sauvegarder les modifications ? \n " +
        "Changement : " +
        baseTag.name +
        " en " +
        updatedTag.name;
      if (updatedTag.name !== baseTag.name) {
        if (!window.confirm(confirmMessage)) {
          // L'utilisateur a annulé, donc on recharge la page.
          window.location.reload();
        } else {
          sendToDb();
        }
      } else {
        alert("Aucune Modification à apporter.");
        target.removeAttribute("contentEditable");
      }
    }
  });

  target.addEventListener("blur", function () {
    // Le contenu éditable a perdu le focus donc on recharge la page.
    confirmMessage =
      "Voulez-vous sauvegarder les modifications ? \n " +
      "Changement : " +
      baseTag.name +
      " en " +
      updatedTag.name;
    if (updatedTag.name !== baseTag.name) {
      if (!window.confirm(confirmMessage)) {
        // L'utilisateur a annulé, donc on recharge la page.
        window.location.reload();
      } else {
        sendToDb();
      }
    } else {
      alert("Aucune Modification à apporter.");
      target.removeAttribute("contentEditable");
    }
    console.log("L'élément a perdu le focus");
  });
}

function setCursorAtEnd(element) {
  // Pour mettre le curseur à la fin
  let textNode = element.firstChild;
  let range = document.createRange();
  let sel = window.getSelection();

  if (textNode) {
    range.setStart(textNode, textNode.length);
    range.collapse(true);
    sel.removeAllRanges();
    sel.addRange(range);
  }
}

function sendToDb() {
  editTagAjaxUrl = updatedTag.target.getAttribute("data-href");
  if (updatedTag.target === baseTag.target) {
    target.removeAttribute("contentEditable");
  }
  fetch(editTagAjaxUrl, {
    method: "PUT",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      _token: updatedTag.target.dataset.token,
      _updatedName: updatedTag.name,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.success) {
        alert(data.error);
      } else {
        flash.innerHTML = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 100001">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="/assets/images/logo black.png" class="rounded me-2 w-25" alt="logo ak58graph">
                        <strong class="me-auto">ak58graph</strong>
                        <small class="text-success">now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="location.reload();"></button>
                    </div>
                    <div class="toast-body text-white bg-dark">
                        &#10003; Tag Modifié avec succès
                    </div>
                </div>
            </div>
        `;
      }
    });
}

function deleteTag(e) {
  deleteTagAjaxUrl = e.getAttribute("data-href");
  if (confirm("Voulez-vous supprimer ce tag ?")) {
    fetch(deleteTagAjaxUrl, {
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
        if (!data.success) {
          alert(data.error);
        } else {
          flash.innerHTML = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 100001">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="/assets/images/logo black.png" class="rounded me-2 w-25" alt="logo ak58graph">
                        <strong class="me-auto">ak58graph</strong>
                        <small class="text-success">now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="location.reload();"></button>
                    </div>
                    <div class="toast-body text-white bg-dark">
                        &#10003; Tag Supprimé avec succès
                    </div>
                </div>
            </div>
        `;
          e.parentElement.parentElement.remove();
        }
      });
  }
}
