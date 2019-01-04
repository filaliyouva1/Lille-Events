
window.addEventListener('load',initStats);

function initStats(){
  document.querySelector('#maj_stats').addEventListener('click',loadStats);
}

function loadStats(){
  fetchFromJson('services/getStats.php')
  .then(processAnswerWithTime)
  .then(displayStats);
}

function processAnswerWithTime(answer){
  if (answer.status == "ok")
    return {time: answer.time, result: answer.result};
  else
    throw new Error(answer.message);
}


function displayStats(res){
  table = listToTable(res.result);
  let p = document.createElement('p');
  p.innerHTML = `mis à jour : <time>${res.time}</time>`;
  
  let cible  = document.querySelector('section#section_stats>div.resultat');
  cible.textContent='';
  cible.appendChild(p);
  cible.appendChild(table);
}

