<?php
 /*
  * Attend les variables globales :
  *  - $listeEquipes : liste des équipes
  *  - $listeEtapes : liste des étapes
  *  - $stats : tableau de statistiques
  * Variable optionnelle :
  *  - $personne est définie si on est dans une session identifiée
  */
  require_once(__DIR__.'/lib/fonctionsHTML.php');
  $dataPersonne ="";    // si utilisateur non authentifié, data-personne n'est pas défini

  // dé-commenter pour la question 3 :
  if (isset($personne)) // l'utilisateur est authentifié
     $dataPersonne = 'data-personne="'.htmlentities(json_encode($personne)).'"'; // l'attribut data-personne contiendra l'objet personne, en JSON
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <title>Course cycliste, équipe</title>
 <link rel="stylesheet" href="style/styleEquipe.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/action_evenements.js"></script>
 <script src="js/gestion_log.js"></script>
</head>
<?php
  echo "<body $dataPersonne>";
?>
  <h1>Course cycliste</h1>
<section id="espace_fixe">
  <nav>
    <a id="bouton_equipe" href="#section_equipe">Équipe</a>
    <a id="bouton_evenements" href="#section_evenements">Evenements</a>
    <a id="bouton_stats" href="#section_stats">Statistiques</a>
  </nav>
  <section id="section_evenements">
    <h2>Recherche d'évenements</h2>
    <form action="services/findEvenements.php" method = "get" id="form_evenements">
     <fieldset>
        <p>Catégorie </p>
        <select name="categorie">
         <?php echo categoriesToOptionsHTML($listeCategories); ?>
        </select><br />
        <p>Triés par </p>
        <p><input type="text" name="motcle" /></p><br />
        <p>Triés par </p>
        <select name="tri" id="tri">
          <option value="dateevt">Date d'evenement</option>
          <option value="datecreation">Date de création</option>
          <option value="popularite">Popularité</option>
        </select><br />
         <?php
         if (isset($personne)){
           echo "<div class=\"radio\">
                <p>Exclure ou non les évenements publiés par vous </p><br />
                <label for=\"exclure\">Oui</label>
                <input type=\"radio\" name=\"exclure\" id=\"exclure\" value=\"oui\"/><br />
                <label for=\"exclure\">Non</label>
                <input type=\"radio\" name=\"exclure\" id=\"exclure\" value=\"non\"/><br />
              </div>";
         }
         ?>

        <button type="submit" name="valid" value="envoyer">Envoyer</button>
      </fieldset>
    </form>
    <div class="resultat"></div>
  </section>


  <section id="section_allEvenements">
    <h2>Tout les évenements</h2>
    <div class="resultat">
      <p>On est le :
        <time id="date_evenements">
         <?php echo date('d/m/Y H:i:s'); ?>
       </time>
      </p>
      <?php
         echo genericTableToHTML($listeEvenements)
      ?>
    </div>
  </section>
</section>

<section id="espace_variable">
 <section class="deconnecte">
   <form method="POST" action="services/login.php"  id="form_login">
    <fieldset>
     <legend>Connexion</legend>
     <label for="login">Login :</label>
     <input type="text" name="login" id="login" required="" autofocus=""/></br>
     <label for="password">Mot de passe :</label>
     <input type="password" name="password" id="password" required="required" /></br>
     <button type="submit" name="valid">OK</button></br>
     <output  for="login password" name="message"></output>
    </fieldset>
   </form>
 </section>

 <section class="connecte">
  <img id="avatar" alt="mon avatar" src="" />
  <h2 id="titre_connecte"></h2>
  <button id="logout">Déconnexion</button>
  <div id="gestion_favoris">
    <div id="liste_favoris"></div>
    <!--
    <form method="POST" action="services/addFavori.php" name="form_add_fav">
      <fieldset>
       <legend>Ajouter un favori </legend>
       <select name="coureur" required="">
       </select>
       <button type="submit" name="valid">Ajouter</button>
       <output name="message"></output>
      </fieldset>
    </form>
    -->
  </div>
 </section>
 </section>
</body>
</html>
