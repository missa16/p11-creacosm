

// Fonction generatrice de chart
import Chart from "chart.js/auto";

function makeChart(chartHTML, typeChart, titreGraphe){
    let chartCtx = chartHTML.getContext('2d');
    let chartDATA = JSON.parse(chartHTML.dataset.chart)
    new Chart(chartCtx, {
        type : typeChart,
        data: chartDATA,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: titreGraphe
                }
            },
            backgroundColor : ['rgb(75,140,75)','rgb(255, 99, 132)'],
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        },
    });
}
const chartAgeHTML = document.getElementById('ageChart');
makeChart(chartAgeHTML,'bar','Age moyens des sondés');

// Graphique des formations
const chartFormationHTML = document.getElementById('formationChart');
makeChart(chartFormationHTML,'bar','Activité professionnelle des sondés');

// Graphique des genres
const chartGenreHTML = document.getElementById('genreChart');
makeChart(chartGenreHTML,'bar','Genre des sondés');
// const select = document.getElementById("stats");
// const chartFormationHTML = document.getElementById('formationChart');
// const chartAgeHTML = document.getElementById('ageChart');
// const chartGenreHTML = document.getElementById('genreChart');
//
// makeChart(chartGenreHTML,'bar','Genre des sondés');
// makeChart(chartFormationHTML,'bar','Activité professionnelle des sondés');
// makeChart(chartAgeHTML,'bar','Age moyens des sondés');

// window.onload = (event) => selectVal(select.value);

// select.addEventListener("change", e => {
//     let value = e.target.value;
//     selectVal(value)
// })

// function toggleVisibilityForSiblings(element) {
//     let siblings = [...element.parentNode.children].filter(e => e != element);
//     siblings.forEach(s => s.style.display="none");
// }

// function selectVal(val){
//     let activeElement;
//
//     switch (val) {
//         case 'age':
//             activeElement = chartAgeHTML;
//             break;
//         case 'formation':
//             activeElement = chartFormationHTML;
//             break;
//         case 'genre':
//             activeElement = chartGenreHTML;
//         break;
//     }
//
//     activeElement === null ? activeElement.style.display="none" : activeElement.style.display="block";
//     toggleVisibilityForSiblings(activeElement);
// }

// Graphique classique des questions

let listTypeGraphe=['bar','line']

let listeCanvas = document
    .querySelectorAll('.chartQuestion');
listeCanvas.forEach((canva) => {
    makeChart(canva, 'bar', canva.dataset.question);
});