
window.addEventListener('load',initEquipe);

function initEquipe(){
  document.forms.form_equipe.addEventListener('submit',sendFormEquipe);
}

function sendFormEquipe(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/getInfoEquipe.php?'+formDataToQueryString(new FormData(this));
  window.alert(url);
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayInfoEquipe, displayErrorEquipe);
}

function processAnswer(answer){
  if (answer.status == "ok")
    return answer.result;
  else
    throw new Error(answer.message);
}

function displayInfoEquipe(equipeInfo){
  let p = document.createElement('p');
  p.innerHTML =
    `<span>${equipeInfo.nom}</span>
     <span>Maillot : ${equipeInfo.couleur}</span>
     <span>Directeur : ${equipeInfo.directeur}</span>
    `;
  let cible  = document.querySelector('section#section_equipe>div.resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);

  sendCoureursRequest(equipeInfo.nom);  //question ultérieure
}

function displayErrorEquipe(error){
  let p = document.createElement('p');
  p.innerHTML = error.message;
  let cible  = document.querySelector('section#section_equipe>div.resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);
}


// --------
/*
 * list : un tableau usuel non vide, donc chaque élément est un objet simple
 * résultat : une table (objet DOM) représentant les données de la table.
 *            les en-têtes de colonnes sont les noms d'attributs des objets
 */
function listToTable(list){
  let table = document.createElement('table');
  let row = table.createTHead().insertRow();
  for (let x of Object.keys(list[0]))
    row.insertCell().textContent = x;
  let body = table.createTBody();
  for (let line of list){
    let row = body.insertRow();
    for (let x of Object.values(line))
      row.insertCell().textContent = x;
  }
  return table;
}
//------------

function sendCoureursRequest(equipe) {
  let url = 'services/getCoureurs.php?equipe='+equipe;
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayCoureurs);
}

function displayCoureurs(coureurs){
  let table = coureursToTable(coureurs);
  //let table = listToTable(coureurs);
  let cible = document.querySelector('section#section_equipe>div.resultat');
  cible.appendChild(table);
}

function coureursToTable(coureurs){
  let table = document.createElement('table');
  table.createTHead().insertRow().innerHTML="<td>Dossard</td><td>Nom</td>";
  let body = table.createTBody();
  for (let coureur of coureurs){
    body.insertRow().innerHTML = `<td>${coureur.dossard}</td><td>${coureur.nom}</td>`;
  }
  return table;
}
