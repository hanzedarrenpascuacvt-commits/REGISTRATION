document.querySelectorAll(".logout").forEach((logoutLink) => {
  logoutLink.addEventListener("click", (event) => {
    event.preventDefault();

    if (typeof Swal === "undefined") {
      if (window.confirm("Are you sure you want to log out?")) {
        window.location.href = logoutLink.href;
      }
      return;
    }

    Swal.fire({
      title: "Log out?",
      text: "Are you sure you want to end your session?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes, log out",
      cancelButtonText: "Stay here",
      confirmButtonColor: "#a83e35",
      cancelButtonColor: "#68432d",
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = logoutLink.href;
      }
    });
  });
});

document.addEventListener("click", (event) => {
  const link = event.target.closest("a[data-confirm], a[data-edit]");
  if (!link || typeof Swal === "undefined") {
    if (link && link.matches("a[data-confirm]")) {
      if (!window.confirm(link.dataset.confirm)) {
        event.preventDefault();
      }
    }
    return;
  }

  if (link.dataset.confirm) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: link.dataset.confirm,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#a83e35",
      cancelButtonColor: "#68432d",
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = link.href;
      }
    });
    return;
  }

  event.preventDefault();
  showEditDialog(link);
});

function showEditDialog(link) {
  const isCategory = link.dataset.edit === "category";
  const fields = isCategory
    ? `
      <label class="swal-form-field" for="edit-name">Category name</label>
      <input id="edit-name" class="swal2-input" maxlength="100">
      <label class="swal-form-field" for="edit-description">Description</label>
      <input id="edit-description" class="swal2-input" maxlength="255">
    `
    : `
      <label class="swal-form-field" for="edit-name">Item name</label>
      <input id="edit-name" class="swal2-input" maxlength="100">
      <label class="swal-form-field" for="edit-price">Price</label>
      <input id="edit-price" class="swal2-input" type="number" min="0" step="0.01">
      <label class="swal-form-field" for="edit-details">Details</label>
      <input id="edit-details" class="swal2-input" maxlength="255">
    `;

  Swal.fire({
    title: isCategory ? "Edit category" : "Edit item",
    html: `<div class="swal-edit-form">${fields}</div>`,
    showCancelButton: true,
    confirmButtonText: "Save changes",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#68432d",
    cancelButtonColor: "#75685f",
    focusConfirm: false,
    didOpen: () => {
      document.getElementById("edit-name").value = link.dataset.name || "";
      if (isCategory) {
        document.getElementById("edit-description").value = link.dataset.description || "";
      } else {
        document.getElementById("edit-price").value = link.dataset.price || "";
        document.getElementById("edit-details").value = link.dataset.details || "";
      }
    },
    preConfirm: () => {
      const name = document.getElementById("edit-name").value.trim();
      if (!name) {
        Swal.showValidationMessage("Please enter a name.");
        return false;
      }

      if (isCategory) {
        return {
          name,
          description: document.getElementById("edit-description").value.trim()
        };
      }

      const price = document.getElementById("edit-price").value;
      if (price === "" || !Number.isFinite(Number(price)) || Number(price) < 0) {
        Swal.showValidationMessage("Please enter a valid price.");
        return false;
      }

      return {
        name,
        price,
        details: document.getElementById("edit-details").value.trim()
      };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      submitEdit(link, result.value, isCategory);
    }
  });
}

function submitEdit(link, values, isCategory) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "core/handleForms.php";

  const fields = isCategory
    ? {
        category_id: link.dataset.id,
        name: values.name,
        description: values.description,
        updateCategoryBtn: "1"
      }
    : {
        item_id: link.dataset.id,
        category_id: link.dataset.categoryId,
        name: values.name,
        price: values.price,
        details: values.details,
        updateItemBtn: "1"
      };

  if (!isCategory && link.dataset.fromIndex === "1") {
    fields.from_index = "1";
  }

  Object.entries(fields).forEach(([name, value]) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
  });

  document.body.appendChild(form);
  form.submit();
}
