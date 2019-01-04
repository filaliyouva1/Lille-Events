
window.addEventListener('load',initEvenements);

function initEvenements(){
  document.forms.form_evenements.addEventListener('submit',sendFormEvenements);
}


function sendFormEvenements(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/findEvenements.php?';
  if(document.body.dataset.personne){
    let personne = JSON.parse(document.body.dataset.personne);
    let login = personne.login;
    url = url + 'login='+ login +'&';
  }
  url = url + formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayEvenements, displayErrorEvenements);
}

function processAnswer(answer){
  if (answer.status == "ok")
    return answer.result;
  else
    throw new Error(answer.message);
}

/*function displayEvenements(evenements){
  let p = document.createElement('p');
  if(evenements.length > 0){
  window.alert("NONE");
 }
  p.innerHTML =
    `<span>${evenements.auteur}</span>
     <span>Maillot : ${evenements.titre}</span>
     <span>Directeur : ${evenements.categorie}</span>
    `;
  let cible  = document.querySelector('section#section_evenements>div.resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);
}*/
function displayEvenements(eventInfo){
	let cible = document.querySelector("section#section_evenements>div.resultat");
	let res;
	if(eventInfo.length > 0){
		res = listToTable(eventInfo);
		}
	else{
		res = document.createElement('p');
		res.textContent = 'Pas de resultats';
		}
	cible.textContent = '';
	cible.appendChild(res);
}


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

function displayErrorEvenements(error){
  let p = document.createElement('p');
  p.textContent = error.message;
  let cible  = document.querySelector('section#section_evenements>div.resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);
}
