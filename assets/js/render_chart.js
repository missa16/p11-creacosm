import {Chart} from "chart.js";

// Fonction generatrice de chart
function makeChart(chartHTML,typeChart, titreGraphe){
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


/// Faire des graphes mixtes
// function makeChartMix(chartHTML,typeChart, titreGraphe){
//     let chartCtx = chartHTML.getContext('2d');
//     let chartDATA = JSON.parse(chartHTML.dataset.chart)
//     //console.log(chartDATA);
//
//     let tabDataset = chartDATA['datasets'];
//     let labels = chartDATA['labels'];
//     let datasets =[];
//
//     for (let i=0; i<tabDataset.length; i++ ){
//         console.log("donnes"+i);
//         console.log(i+tabDataset[i]['label']);
//         console.log(i+tabDataset[i]['data']);
//     }
//
//
//     for (let i = 0; i<tabDataset.length;i++) {
//         let dataset ={
//             label: tabDataset[i]['label'],
//             data: tabDataset[i]['data'],
//             backgroundColor: 'rgba(54, 162, 235, 0.2)',
//             borderColor: 'rgba(54, 162, 235, 1)',
//             borderWidth: 1
//         };
//         datasets.push(dataset);
//     }
//     console.log(datasets);
//
//
//     new Chart(chartCtx, {
//         type: typeChart,
//         data: {
//             labels: labels,
//             datasets: datasets
//         },
//         options: {
//             scales: {
//                 yAxes: [{
//                     ticks: {
//                         beginAtZero: true
//                     }
//                 }]
//             }
//         }
//     });
// }

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

let listTypeGraphe=['bar','line']

let listeCanvas = document
    .querySelectorAll('.chartQuestion');
listeCanvas.forEach((canva) => {
    makeChart(canva, 'bar', canva.dataset.question);
});





// let listeCanvas2 = document
//     .querySelectorAll('.chartQuestionMix');
//
// listeCanvas2.forEach((canva) => {
//         makeChartMix(canva, 'bar', canva.dataset.question);
// });





// test d'un graphique mixe
let ctx = document.getElementById('mixChart').getContext('2d');
let myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Cream', 'Liquid', 'Powder'],
        datasets: [{
            label: 'Yes',
            data: [10, 12, 6],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'No',
            data: [5, 8, 9],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});