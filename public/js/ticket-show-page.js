/* MODALE PER LETTURA MOTIVO REPORT TICKET */
function openReportModal(reason) {
    const modal = document.getElementById("commentModal");
    const reasonText = document.getElementById("reportReason");

    reasonText.textContent = reason;
    modal.classList.remove("hidden");
}

function closeReportModal() {
    const modal = document.getElementById("commentModal");
    modal.classList.add("hidden");
}


/* MODALE PER PRESA IN CARICO TICKET DEL TECNICO */
document.addEventListener('DOMContentLoaded', function () {
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');
    const takeInChargeModal = document.getElementById('takeInChargeModal');

    openModalButton.addEventListener('click', function () {
        takeInChargeModal.classList.remove('hidden');
        takeInChargeModal.classList.add('flex', 'items-center', 'justify-center');
    });

    closeModalButton.addEventListener('click', function () {
        takeInChargeModal.classList.add('hidden');
        takeInChargeModal.classList.remove('flex', 'items-center', 'justify-center');
    });

    takeInChargeModal.addEventListener('click', function (event) {
        if (event.target === takeInChargeModal) {
            takeInChargeModal.classList.add('hidden');
            takeInChargeModal.classList.remove('flex', 'items-center', 'justify-center');
        }
    });
});

/* MODALE PER ASSEGNAZIONE TICKET FATTO DA ADMIN SOLO */

document.addEventListener('DOMContentLoaded', function () {
    // Elementi modale ricerca tecnico assegnazione ticket
    const openAssignTicketTechnicianSearchModalButton = document.getElementById(
        'openAssignTicketTechnicianSearchModalButton');
    const closeAssignTicketTechnicianSearchModalButton = document.getElementById(
        'closeAssignTicketTechnicianSearchModalButton');
    const assignTicketTechnicianSearchModal = document.getElementById('assignTicketTechnicianSearchModal');
    const assignTicketTechnicianSearchInput = document.getElementById('assignTicketTechnicianSearchInput');
    const assignTicketTechniciansTableBody = document.querySelector('#assignTicketTechniciansTable tbody');

    openAssignTicketTechnicianSearchModalButton.addEventListener('click', function () {
        assignTicketTechnicianSearchModal.classList.remove('hidden');
        assignTicketTechnicianSearchModal.classList.add('flex', 'items-center', 'justify-center');
    });

    closeAssignTicketTechnicianSearchModalButton.addEventListener('click', function () {
        assignTicketTechnicianSearchModal.classList.add('hidden');
        assignTicketTechnicianSearchModal.classList.remove('flex', 'items-center',
            'justify-center');
    });

    assignTicketTechnicianSearchModal.addEventListener('click', function (event) {
        if (event.target === assignTicketTechnicianSearchModal) {
            assignTicketTechnicianSearchModal.classList.add('hidden');
            assignTicketTechnicianSearchModal.classList.remove('flex', 'items-center',
                'justify-center');
        }
    });

    assignTicketTechnicianSearchInput.addEventListener('keyup', function () {
        const searchValue = assignTicketTechnicianSearchInput.value.toLowerCase();
        const technicianRows = assignTicketTechniciansTableBody.querySelectorAll('.technician-row');

        technicianRows.forEach(row => {
            const technicianName = row.querySelector('.technician-name').textContent
                .toLowerCase();
            const technicianLastname = row.querySelector('.technician-lastname').textContent
                .toLowerCase();
            const technicianEmail = row.querySelector('span.text-xs').textContent
                .toLowerCase();

            if (
                technicianName.includes(searchValue) ||
                technicianLastname.includes(searchValue) ||
                technicianEmail.includes(searchValue)
            ) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

/* MODALE PER RIMOZIONE TICKET FATTO DA ADMIN SOLO */
document.getElementById('openDeleteModalButton')?.addEventListener('click', function () {
    document.getElementById('deleteTicketModal').classList.remove('hidden');
});
document.getElementById('closeDeleteModalButton')?.addEventListener('click', function () {
    document.getElementById('deleteTicketModal').classList.add('hidden');
});

/* MODALE PER ASSEGNAZIONE REPORT TICKET */
document.getElementById('openReportTicketModalButton')?.addEventListener('click', function () {
    document.getElementById('reportTicketModal').classList.remove('hidden');
});
document.getElementById('closeReportTicketModalButton')?.addEventListener('click', function () {
    document.getElementById('reportTicketModal').classList.add('hidden');
});