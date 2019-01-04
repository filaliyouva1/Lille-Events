
window.addEventListener('load',initState);
window.addEventListener('load',initLog);
window.addEventListener('load',initFav);

var currentUser = null; //objet "personne" de l'utilisateiur connecté

function initState(){ // initialise l'état de la page
  //etatDeconnecte();
  // sera à modifier en question 3
  if (document.body.dataset.personne) {
     let personne = JSON.parse(document.body.dataset.personne);
     etatConnecte(personne);
   }
   else
    //window.alert("Deconnecté");
    etatDeconnecte();
}
function processAnswer(answer){
  if (answer.status == "ok")
    return answer.result;
  else
    throw new Error(answer.message);
}

function refresh(personne){
  document.location.reload(true);
}

function etatDeconnecte() { // passe dans l'état 'déconnecté'
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=false;
    // nettoie la partie personnalisée :
    currentUser = null;
    delete(document.body.dataset.personne);
    document.querySelector('#titre_connecte').textContent='';
    document.querySelector('#liste_favoris').textContent='';
    document.querySelector('#avatar').src='';
}

function etatConnecte(personne) { // passe dans l'état 'connecté'
    currentUser = personne;
    // cache ou montre les éléments
    for (let elt of document.querySelectorAll('.deconnecte'))
       elt.hidden=true;
    for (let elt of document.querySelectorAll('.connecte'))
       elt.hidden=false;

    // personnalise le contenu
    document.querySelector('#titre_connecte').innerHTML = `${currentUser.prenom} ${currentUser.nom}`;

    fetchBlob('services/getAvatar.php?login='+currentUser.login)
    .then(changeAvatar);

    fetchFromJson('services/getFavoris',{credentials:'same-origin'})
    .then(processAnswer);
}

function initLog(){ // mise en place des gestionnaires sur le formulaire de login et le bouton logout
  document.forms.form_login.addEventListener('submit',sendLogin); // envoi
  document.forms.form_login.addEventListener('input',function(){this.message.value='';}); // effacement auto du message
  document.querySelector('#logout').addEventListener('click',sendLogout);
}

function initFav(){
  document.forms.form_add_fav.addEventListener('submit',sendAddFav);
  document.forms.form_add_fav.addEventListener('input',function(){this.message.value='';});
  sendMajFav();
  sendMajNotFav();

}
function changeAvatar(blob) {
  if (blob.type.startsWith('image/')){ // le mimetype est celui d'une image
    let img = document.getElementById('avatar');
    img.src = URL.createObjectURL(blob);
  }
}

function sendLogin(ev){ // gestionnaire de l'évènement submit sur le formulaire de login
  ev.preventDefault();
  this.message.value = '';
  let url = 'services/login.php';
  let options = {method : 'post', body : new FormData(this), credentials : 'same-origin'};
  fetchFromJson(url, options).then(processAnswer).then(etatConnecte).then(refresh);
}

function sendLogout(ev){ // gestionnaire de l'évènement click sur le bouton logout
  ev.preventDefault();
  let url = 'services/logout.php';
  let options = {credentials : 'same-origin'};
  fetchFromJson(url, options).then(processAnswer).then(etatDeconnecte).then(refresh);


}

function errorLogin(error) {
   // affiche error.message dans l'élément OUTPUT.
  document.forms.form_login.message.value = 'échec : ' + error.message;
}




//------ matériel pour la question 4 :

function afficherFavoris(liste) {
  let node;
  if (liste.length>0) {
    node = tableFavoris(liste);
    node.createCaption().textContent = "coureurs favoris";
  } else{
    node = document.createElement('p');
    node.textContent = 'Pas de coureur favori';
  }
  let cible = document.querySelector('#liste_favoris');
  cible.textContent='';
  cible.appendChild(node);
}
/*
 * liste : liste de coureurs
 * résultat : noeud DOM d'une table représentant la liste
 */
function tableFavoris(liste){
  let table = document.createElement('table');
  let row = table.createTHead().insertRow();
  row.insertCell().textContent = 'dossard';
  row.insertCell().textContent = 'nom';
  row.insertCell().textContent = 'équipe';
  row.insertCell().textContent = 'taille';
  table.createTBody();
  for (let coureur of liste){
    addFavoriToTable(coureur,table);
  }
  return table;
}

/*
 * coureur : objet représentant un coureur
 * table : noeud DOM d'une table.
 *
 * action : ajoute une ligne rprésentant le coureur
 * résultat : aucun
 */
function addFavoriToTable(coureur, table) {
  let row = table.tBodies[0].insertRow();
  row.id = coureur.dossard + "-favori";
  row.insertCell().textContent = coureur.dossard;
  row.insertCell().textContent = coureur.nom;
  row.insertCell().textContent = coureur['équipe'];
  row.insertCell().textContent = coureur.taille;
  // hors sujet
  /*
  let b = document.createElement('button');
  b.textContent = 'Supp.';
  b.classList.add('suppFav');
  b.dataset.dossard = coureur.dossard;
  b.addEventListener('click',sendSuppFav);
  row.cells[0].appendChild(b);
  */
}
