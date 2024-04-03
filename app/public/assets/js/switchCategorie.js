// Récupere les inputs
document.querySelectorAll("input[data-switch-categorie-id]").forEach((input) => {
    // ecouter les changement sur les checkbox
      input.addEventListener("change", async (e) => {
      // récupérer l'id de l'categories a modifier
      const id = e.target.dataset.switchCategorieId;
      const response = await fetch(`/admin/categories/${id}/switch`);
  
      if (response.ok) {
          const data = await response.json();
          const card = e.target.closest('.card');
          const label = e.target.parentElement.querySelector('label');
  
          if (data.enable) {
              card.classList.replace('border-danger', 'border-success');
              label.innerText = 'Actif';
              label.classList.replace('text-danger', 'text-success');
  
  
          } else {
              card.classList.replace('border-success', 'border-danger');
              label.innerText = 'Inactif';
              label.classList.replace('text-success', 'text-danger');
          }
          
          }
      });
  });