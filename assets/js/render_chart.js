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










// const select = document.getElementById("stats");
// select.addEventListener("change", e => {
//     let value = e.target.value
//     selectVal(value)
// })
// console.log(select)
// function selectVal(val){
//     switch (val) {
//         case 'age':
// const chartAgeHTML = document.getElementById('ageChart');
// makeChart(chartAgeHTML,'bar','Age moyens des sondés');
//             break;
//         case 'formation':
//             const chartFormationHTML = document.getElementById('formationChart');
//             makeChart(chartFormationHTML,'bar','Activité professionnelle des sondés');
//             break;
//         case 'genre':
//            // const chartGenreHTML = document.getElementById('genreChart');
//  makeChart(chartGenreHTML,'bar','Genre des sondés');
//         default:
//
//     }
// }




// // Graphique des ages
// const chartAgeHTML = document.getElementById('ageChart');
// makeChart(chartAgeHTML,'bar','Age moyens des sondés');
//
// // Graphique des formations
//const chartFormationHTML = document.getElementById('formationChart');
//makeChart(chartFormationHTML,'bar','Activité professionnelle des sondés');

// // Graphique des genres
// const chartGenreHTML = document.getElementById('genreChart');
// makeChart(chartGenreHTML,'bar','Genre des sondés');


// Graphique classique des questions


let listeCanvas = document
    .querySelectorAll('.chartQuestion');
listeCanvas.forEach((canva) => {
    makeChart(canva, 'bar', canva.dataset.question);
});


// Chart avec deux variables

function makeChartMix(chartHTML, typeChart, titreGraphe){
    let chartCtx = chartHTML.getContext('2d');
    let chartDATA = JSON.parse(chartHTML.dataset.chart)
    console.log(chartDATA)




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
            backgroundColor : ['rgb(75,140,75)','rgb(255, 99, 132)','rgb(85,128,189)'],
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

let listeCanvasGenre = document
    .querySelectorAll('.chartQuestionGenre');
listeCanvasGenre.forEach((canva) => {
    makeChartMix(canva, 'bar', canva.dataset.question);
});


let listeCanvasFormation = document
    .querySelectorAll('.chartQuestionFormation');
listeCanvasFormation.forEach((canva) => {
    makeChartMix(canva, 'bar', canva.dataset.question);
});

let listeCanvasAge = document
    .querySelectorAll('.chartQuestionAge');
listeCanvasAge.forEach((canva) => {
    makeChartMix(canva, 'bar', canva.dataset.question);
});





// test d'un graphique mixe
// let ctx = document.getElementById('mixChart').getContext('2d');
// let myChart = new Chart(ctx, {
//     type: 'bar',
//     data: {
//         labels: ['Cream', 'Liquid', 'Powder'],
//         datasets: [{
//             label: 'Yes',
//             data: [10, 12, 6],
//             backgroundColor: 'rgba(54, 162, 235, 0.2)',
//             borderColor: 'rgba(54, 162, 235, 1)',
//             borderWidth: 1
//         }, {
//             label: 'No',
//             data: [5, 8, 9],
//             backgroundColor: 'rgba(255, 99, 132, 0.2)',
//             borderColor: 'rgba(255, 99, 132, 1)',
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: {
//             yAxes: [{
//                 ticks: {
//                     beginAtZero: true
//                 }
//             }]
//         }
//     }
// });