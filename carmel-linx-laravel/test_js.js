let tPercents = [];
let inputs = [10, 2, 0, 2];
inputs.forEach(v => {
    let m = 20;
    if (!isNaN(v) && m > 0) tPercents.push((v / m) * 100);
});
tPercents.sort((a,b) => b-a);
let tAvg = 0;
if (tPercents.length >= 2) tAvg = (tPercents[0] + tPercents[1]) / 2;
else if (tPercents.length === 1) tAvg = tPercents[0];
let tFinal = Math.round(tAvg * (20 / 100));
console.log(tFinal);
