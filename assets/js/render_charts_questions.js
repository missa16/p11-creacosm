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


let listeCanvas = document
    .querySelectorAll('.chartQuestion');
listeCanvas.forEach((canva) => {
    makeChart(canva, 'bar', canva.dataset.question);
});