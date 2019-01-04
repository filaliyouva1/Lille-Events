
window.addEventListener('load',initEtape);

function initEtape(){
  document.forms.form_etape.addEventListener('submit',sendFormEtape);
}

function sendFormEtape(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/getArrivee.php?';
  let options = {method : 'get', body : new FormData(this), credentials : 'same-origin'};
  fetchFromJson(url)
  .then(processAnswer)
  .then(displayArrivee, displayErrorEtape);
}


function displayArrivee(classement){
  let node;
  if (classement.length>0) {
    node = listToTable(classement);
  } else {
    node = document.createElement('p');
    node.textContent = 'Pas de résultats';
  }
  let cible  = document.querySelector('section#section_etape>div.resultat');
  cible.textContent='';
  cible.appendChild(node);
}


function displayErrorEtape(error){
  let p = document.createElement('p');
  p.textContent = error.message;
  let cible  = document.querySelector('section#section_etape>div.resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);
}
