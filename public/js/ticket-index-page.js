document.addEventListener('DOMContentLoaded', () => {
    const reportFilter = document.getElementById('reportFilter');
    const reopenFilter = document.getElementById('reopenFilter');
    const dateFilter = document.getElementById('dateFilter');
    const userSearch = document.getElementById('userSearch');
    const ticketContainer = document.getElementById('ticketContainer');

    function filterTickets() {
        const reportValue = reportFilter?.value;
        const dateValue = parseInt(dateFilter?.value);
        const nameQuery = userSearch?.value.toLowerCase().trim();
        const today = new Date();
        const reopenValue = reopenFilter?.value;

        let hasVisibleTickets = false;

        Array.from(ticketContainer.children).forEach(card => {
            const isReopened = card.dataset.isReopened === 'true';
            const isReported = card.dataset.isReported === 'true';
            const ticketDate = new Date(card.dataset.date);
            const diffDays = Math.floor((today - ticketDate) / (1000 * 60 * 60 * 24));
            const userFullName = (card.dataset.userfullname || '').toLowerCase();

            let show = true;

            // Filtro report
            if (reportValue === 'reported' && !isReported) show = false;
            if (reportValue === 'not_reported' && isReported) show = false;

            // Filtro riapertura
            if (reopenValue === 'reopened' && !isReopened) show = false;
            if (reopenValue === 'not_reopened' && isReopened) show = false;

            // Filtro data
            if (!isNaN(dateValue) && diffDays > dateValue) show = false;

            // Filtro nome/cognome
            if (nameQuery && !userFullName.includes(nameQuery)) show = false;

            card.style.display = show ? 'flex' : 'none';
            if (show) {
                hasVisibleTickets = true;
            }
        });

        const noTicketsMessage = ticketContainer.querySelector('.col-span-full.p-8.text-center');
        if (noTicketsMessage) {
            if (hasVisibleTickets) {
                noTicketsMessage.style.display = 'none';
            } else {
                noTicketsMessage.style.display = 'block';
            }
        } else if (!hasVisibleTickets) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'col-span-full p-8 text-center text-gray-600 dark:text-gray-400';
            messageDiv.innerHTML = '<p class="text-lg">Non ci sono ticket aperti che corrispondono ai criteri di filtro.</p>';
            ticketContainer.appendChild(messageDiv);
        }
    }

    // Event listeners
    reportFilter?.addEventListener('change', filterTickets);
    dateFilter?.addEventListener('change', filterTickets);
    userSearch?.addEventListener('input', filterTickets);
    reopenFilter?.addEventListener('change', filterTickets);


    filterTickets();
});