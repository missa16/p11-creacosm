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


// Graphique des ages
const chartAgeHTML = document.getElementById('ageChart');
makeChart(chartAgeHTML,'bar','Age moyens des sondés');

// Graphique des formations
const chartFormationHTML = document.getElementById('formationChart');
makeChart(chartFormationHTML,'bar','Activité professionnelle des sondés');

// Graphique des genres
const chartGenreHTML = document.getElementById('genreChart');
makeChart(chartGenreHTML,'bar','Genre des sondés');


// Graphique classique des questions







