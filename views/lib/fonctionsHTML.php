<?php
/* argument : une liste (table) de coureurs.
*    chaque élément de la liste est une table associative avec les clés 'equipe','dossard', 'nom'
*  résultat : chaîne contenant le code HTML d'une table présentant ces infos
*/
function tableQ1cToHTML($table){
    $res = '<table><thead><tr><td>équipe</td><td>dossard</td><td>nom</td></tr></thead><tbody>';
    foreach ($table as $coureur) {
        $res .= "<tr><td>{$coureur['equipe']}</td><td>{$coureur['dossard']}</td><td>{$coureur['nom']}</td></tr>";
    }
    $res .= '</tbody></table>';
    return $res;
}

function genericTableToHTML($table){
    if (count($table)==0){
        return "<p>table vide</p>";
    }
    $res = "<table><thead><tr>";
    foreach($table[0] as $attName=>$value){
        $res .="<td>{$attName}</td>";
    }
    $res .= "</tr></thead>\n<tbody>";
    foreach ($table as $ligne) {
        $res .= "<tr><td>". implode("</td><td>",$ligne). "</td></tr>\n";
    }
    $res .= "</tbody></table>\n";
    return $res;
}

/*
 * argument :  table associative avec les clés 'nom', 'couleur' 'directeur'
 * résultat : représentation HTML de ces informations (div contenant 3 span, par exemple)
 **/
function equipeToHTML($infoEquipe){
    return "<div><span>Équipe : {$infoEquipe['nom']}</span>, <span>couleur : {$infoEquipe['couleur']}</span>, <span>directeur : {$infoEquipe['directeur']}</span></div>";
}
function coureursToHTML($table){
    $res = '<table><thead><tr><td>dossard</td><td>nom</td></tr></thead><tbody>';
    foreach ($table as $coureur) {
        $res .= "<tr><td>{$coureur['dossard']}</td><td>{$coureur['nom']}</td></tr>";
    }
    $res .= '</tbody></table>';
    return $res;
}
function equipesToOptionsHTML($liste){
    $res="";
    foreach($liste as $info){
        $res .= "<option value='{$info['nom']}'>{$info['nom']} ({$info['couleur']})</option>\n";
    }
    return $res;
}
function categoriesToOptionsHTML($liste){
    $res="";
    foreach($liste as $info){
        $res .= "<option value='{$info['nom']}'>{$info['nom']} </option>\n";
    }
    return $res;
}
function coureursToOptionsHTML($liste){
    $res="";
    foreach($liste as $info){
        $res .= "<option value='{$info['dossard']}'>{$info['nom']} ({$info['dossard']})</option>\n";
    }
    return $res;
}

function etapesToOptionsHTML($liste){
    $res="";
    foreach($liste as $info){
        $res .= "<option value='{$info['numero']}'>{$info['numero']} - {$info['nom']}</option>\n";
    }
    return $res;
}

function etapesToHTML($table){
    $res = '<table><thead><tr><td>numéro</td><td>nom</td></tr></thead><tbody>';
    foreach ($table as $etape) {
        $res .= "<tr><td>{$etape['numero']}</td><td>{$etape['nom']}</td></tr>";
    }
    $res .= '</tbody></table>';
    return $res;
}


?>
