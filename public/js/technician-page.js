document.addEventListener('DOMContentLoaded', function () {

    // Elementi Aggiungi Tecnico
    const openCreateNewTechnicianModalButton = document.getElementById('openCreateNewTechnicianModalButton');
    const createNewTechnicianModal = document.getElementById('createNewTechnicianModal');
    const closeCreateNewTechnicianModal = document.getElementById('closeCreateNewTechnicianModal');
    const cancelCreateNewTechnicianButton = document.getElementById('cancelCreateNewTechnicianButton');


    //Elementi Rimuovi Tecnico
    const openRemoveTechnicianModalButtons = document.querySelectorAll('.openRemoveTechnicianModalButton');
    const closeRemoveTechnicianModalButton = document.getElementById('closeRemoveTechnicianModalButton');
    const removeTechnicianModal = document.getElementById('removeTechnicianModal');
    const technicianNameToRemove = document.getElementById('technicianNameToRemove');
    const hiddenTechnicianIdToRemove = document.getElementById('hiddenTechnicianIdToRemove');
    const cancelRemoveTechnicianButton = document.getElementById('cancelRemoveTechnicianButton');


    // Elementi Aggiungi Admin
    const openAddAdminModalButton = document.getElementById('openAddAdminModalButton');
    const closeAddAdminModalButton = document.getElementById('closeAddAdminModal');
    const addAdminModal = document.getElementById('addAdminModal');
    const adminSearchInput = document.getElementById('adminSearchInput');
    const adminAddTableBody = document.querySelector('#adminAddTable tbody');

    // Elementi Rimuovi Admin
    const openRemoveAdminModalButtons = document.querySelectorAll('.openRemoveAdminModalButton');
    const removeAdminModal = document.getElementById('removeAdminModal');
    const closeRemoveAdminModalButton = document.getElementById('closeRemoveAdminModalButton');
    const cancelRemoveAdminButton = document.getElementById('cancelRemoveAdminButton');
    const adminNameToRemove = document.getElementById('adminNameToRemove');
    const hiddenAdminIdToRemove = document.getElementById('hiddenAdminIdToRemove');

    // Elementi Search Bar Tecnici
    const technicianSearchBar = document.getElementById('technicianSearchBar');
    const techniciansTableBody = document.getElementById('techniciansTableBody');

    // Elementi nuovo Modale ex tecnici
    const openExTechniciansModalButton = document.getElementById('openExTechniciansModalButton');
    const exTechniciansModal = document.getElementById('exTechniciansModal');
    const closeExTechniciansModal = document.getElementById('closeExTechniciansModal');
    const exTechnicianSearchInput = document.getElementById('exTechnicianSearchInput');
    const exTechniciansTableBody = document.querySelector('#exTechniciansTable tbody');


    // mostra o nasconde i modali
    function openModal(modalElement) {
        modalElement.classList.remove('hidden');
        modalElement.classList.add('flex', 'items-center', 'justify-center');
    }

    function closeModal(modalElement) {
        modalElement.classList.add('hidden');
        modalElement.classList.remove('flex', 'items-center', 'justify-center');
    }

    // Aggiungi Tecnico
    openCreateNewTechnicianModalButton.addEventListener('click', () => openModal(createNewTechnicianModal));
    closeCreateNewTechnicianModal.addEventListener('click', () => closeModal(createNewTechnicianModal));
    cancelCreateNewTechnicianButton.addEventListener('click', () => closeModal(createNewTechnicianModal));
    createNewTechnicianModal.addEventListener('click', (event) => {
        if (event.target === createNewTechnicianModal) {
            closeModal(createNewTechnicianModal);
        }
    });


    // Rimuovi Tecnico
    openRemoveTechnicianModalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const technicianId = this.dataset.id;
            const technicianName = this.dataset.nome;
            const technicianCognome = this.dataset.cognome;
            technicianNameToRemove.textContent = `${technicianName} ${technicianCognome}`;
            hiddenTechnicianIdToRemove.value = technicianId;
            openModal(removeTechnicianModal);
        });
    });
    closeRemoveTechnicianModalButton.addEventListener('click', () => closeModal(removeTechnicianModal));
    cancelRemoveTechnicianButton.addEventListener('click', () => closeModal(removeTechnicianModal));
    removeTechnicianModal.addEventListener('click', (event) => {
        if (event.target === removeTechnicianModal) {
            closeModal(removeTechnicianModal);
        }
    });


    // Aggiungi Admin
    openAddAdminModalButton.addEventListener('click', () => openModal(addAdminModal));
    closeAddAdminModalButton.addEventListener('click', () => closeModal(addAdminModal));
    addAdminModal.addEventListener('click', (event) => {
        if (event.target === addAdminModal) {
            closeModal(addAdminModal);
        }
    });


    // Rimuovi Admin
    openRemoveAdminModalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const adminId = this.dataset.id;
            const adminName = this.dataset.nome;
            const adminCognome = this.dataset.cognome;
            adminNameToRemove.textContent = `${adminName} ${adminCognome}`;
            hiddenAdminIdToRemove.value = adminId;
            openModal(removeAdminModal);
        });
    });
    closeRemoveAdminModalButton.addEventListener('click', () => closeModal(removeAdminModal));
    cancelRemoveAdminButton.addEventListener('click', () => closeModal(removeAdminModal));
    removeAdminModal.addEventListener('click', (event) => {
        if (event.target === removeAdminModal) {
            closeModal(removeAdminModal);
        }
    });


    // Funzionalità di ricerca per i tecnici
    technicianSearchBar.addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = techniciansTableBody.querySelectorAll('.technician-row');

        rows.forEach(row => {
            const name = row.querySelector('.technician-name').textContent.toLowerCase();
            const lastname = row.querySelector('.technician-lastname').textContent.toLowerCase();
            const email = row.querySelector('.technician-email').textContent.toLowerCase();

            if (name.includes(searchValue) || lastname.includes(searchValue) || email.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Funzionalità di ricerca per aggiungere admin
    adminSearchInput.addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = adminAddTableBody.querySelectorAll('.technician-row');

        rows.forEach(row => {
            const name = row.querySelector('.technician-name').textContent.toLowerCase();
            const lastname = row.querySelector('.technician-lastname').textContent.toLowerCase();
            const email = row.querySelector('.text-xs').textContent.toLowerCase();

            if (name.includes(searchValue) || lastname.includes(searchValue) || email.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Nuovo modale per gli ex tecnici
    openExTechniciansModalButton.addEventListener('click', () => openModal(exTechniciansModal));
    closeExTechniciansModal.addEventListener('click', () => closeModal(exTechniciansModal));
    exTechniciansModal.addEventListener('click', (event) => {
        if (event.target === exTechniciansModal) {
            closeModal(exTechniciansModal);
        }
    });

    // Funzionalità di ricerca per gli ex tecnici nel nuovo modale
    exTechnicianSearchInput.addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = exTechniciansTableBody.querySelectorAll('.technician-row');

        rows.forEach(row => {
            const name = row.querySelector('.technician-name').textContent.toLowerCase();
            const lastname = row.querySelector('.technician-lastname').textContent.toLowerCase();
            const email = row.querySelector('.text-xs').textContent.toLowerCase();

            if (name.includes(searchValue) || lastname.includes(searchValue) || email.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});