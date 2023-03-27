import Chart from "chart.js/auto";


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
                y: {
                    type: 'linear',
                    min: 0,
                    max: 50
                }
            },
        },
    });
}

// globales questions
let listeCanvas = document
    .querySelectorAll('.chartQuestion');
listeCanvas.forEach((canva) => {
    makeChart(canva,'pie', canva.dataset.question);
});

// globales questions formation
let listeCanvas2 = document
    .querySelectorAll('.chartQuestionFormation');
listeCanvas2.forEach((canva) => {
    makeChart(canva,'bar', canva.dataset.question);
});

// globales questions age
let listeCanvas3 = document
    .querySelectorAll('.chartQuestionAge');
listeCanvas3.forEach((canva) => {
    makeChart(canva,'bar', canva.dataset.question);
});


// globales questions age
let listeCanvas4 = document
    .querySelectorAll('.chartQuestionGenre');
listeCanvas4.forEach((canva) => {
    makeChart(canva,'bar', canva.dataset.question);
});


