
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    function showPanel(panelIndex) {
        tabPanels.forEach(panel => {
            panel.classList.remove('active');
        });
        tabButtons.forEach(button => {
            button.classList.remove('active');
        });
        tabPanels[panelIndex].classList.add('active');
        tabButtons[panelIndex].classList.add('active');
    }

    tabButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            showPanel(index);
        });
    });
