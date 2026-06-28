const jsdom = require('jsdom');
const { JSDOM } = jsdom;
const html = \<form id='doc15Form'>
  <input type='hidden' id='courseType' value='theory'>
  <span id='reportTypeTitle'></span>
  <span id='lblTestMax'></span>
  <span id='lblAssignMax'></span>
  <span id='lblAttMax'></span>
  <span id='lblCIA'></span>
  <div class='student-row'>
    <input class='t-in' value='10' data-max='20'>
    <input class='t-in' value='2' data-max='20'>
    <input class='t-in' value='0' data-max='20'>
    <input class='t-in' value='2' data-max='20'>
    <span class='t-avg'></span>
    <input class='a-in' value='5' data-max='10'>
    <input class='a-in' value='6' data-max='10'>
    <input class='a-in' value='7' data-max='10'>
    <input class='a-in' value='2' data-max='10'>
    <span class='a-avg'></span>
    <input class='att-in' value='0'>
    <span class='cia-tot'></span>
  </div>
</form>\;
const dom = new JSDOM(html);
const document = dom.window.document;

function recalculateAll() {
    const type = document.getElementById('courseType').value;
    const maxTest = type === 'theory' ? 20 : 15;
    const maxAssign = type === 'theory' ? 20 : 45;
    const maxAtt = type === 'theory' ? 10 : 15;
    const maxCIA = type === 'theory' ? 50 : 75;

    document.getElementById('reportTypeTitle').innerText = type === 'theory' ? 'Theory' : 'Practical';
    
    document.getElementById('lblTestMax').innerText = '(' + maxTest + ')';
    document.getElementById('lblAssignMax').innerText = '(' + maxAssign + ')';
    document.getElementById('lblAttMax').innerText = '(' + maxAtt + ')';
    document.getElementById('lblCIA').innerText = '(' + maxCIA + ')';

    document.querySelectorAll('.student-row').forEach(row => {
        let tPercents = [];
        row.querySelectorAll('.t-in').forEach(input => {
            let v = Math.round(parseFloat(input.value));
            let m = parseFloat(input.dataset.max) || 20;
            if (!isNaN(v) && m > 0) tPercents.push((v / m) * 100);
        });
        tPercents.sort((a,b) => b-a);
        let tAvg = 0;
        if (tPercents.length >= 2) tAvg = (tPercents[0] + tPercents[1]) / 2;
        else if (tPercents.length === 1) tAvg = tPercents[0];
        
        let tFinal = Math.round(tAvg * (maxTest / 100));
        row.querySelector('.t-avg').innerText = tFinal;

        let aPercents = [];
        row.querySelectorAll('.a-in').forEach(input => {
            let v = Math.round(parseFloat(input.value));
            let m = parseFloat(input.dataset.max) || 10;
            if (!isNaN(v) && m > 0) aPercents.push((v / m) * 100);
        });
        aPercents.sort((a,b) => b-a);
        let aAvg = 0;
        if (aPercents.length >= 2) aAvg = (aPercents[0] + aPercents[1]) / 2;
        else if (aPercents.length === 1) aAvg = aPercents[0];
        
        let aFinal = Math.round(aAvg * (maxAssign / 100));
        row.querySelector('.a-avg').innerText = aFinal;

        let attIn = row.querySelector('.att-in');
        let attVal = Math.round(parseFloat(attIn.value)) || 0;
        if (attVal > maxAtt) { attVal = maxAtt; attIn.value = maxAtt; }

        row.querySelector('.cia-tot').innerText = tFinal + aFinal + attVal;
    });
}
try { recalculateAll(); console.log('T-AVG:', document.querySelector('.t-avg').innerText); } catch (e) { console.error(e); }

