<?php



function fetchInstrumentHome(pdo $dbConnect):array { 

        $sql= $dbConnect->query('SELECT i.id, i.title , LEFT(i.description,200)as shortIntro, p.imageMini,p.name,p.imageMiddle,p.imageFull FROM instrument i LEFT JOIN picture p ON i.id = p.id_instrument WHERE i.visible = "1" GROUP BY i.id LIMIT 10');
        $dataInstrumentHome= $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
        return $dataInstrumentHome;
}
function fetchInstrumentAdminAdd(pdo $dbConnect):array { 

        $sql= $dbConnect->query('SELECT i.id, i.title FROM instrument i ');
        $dataInstrumentAdminAdd= $sql->fetchAll(PDO::FETCH_OBJ);
        $sql->closeCursor();
        return $dataInstrumentAdminAdd;
}
function truncate (string $text): string{
    // fonction qui trouve un numérique qui est la dernière sous chaine dans une chaine pour remplacer $cut : " "
    $cut = strrpos($text, ' ');
    return substr ($text, 0,$cut);
}

function fetchDetailInstrument (pdo $dbConnect, int $idInstrument):array{
    
    $dbConnect->beginTransaction();
    $sql = $dbConnect->query("SELECT i.id as idInstrument, i.title, i.description, i.history, i.intro, i.technics,i.visible,i.date as dateArticle, c.id as idCategory,c.namecategory as nameCategory
    FROM instrument i 
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id 
    WHERE i.id=$idInstrument;");
    $sql2 = $dbConnect->query("SELECT GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname, GROUP_CONCAT(m.bornDate SEPARATOR '||')as musicianBorn,GROUP_CONCAT(m.deathDate SEPARATOR '||')as musicianDeath
    FROM instrument i
    LEFT JOIN musician m 
    ON i.id=m.id_instrument 
    WHERE i.id=$idInstrument;");
    $sql3 = $dbConnect->query("SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription,GROUP_CONCAT(s.date SEPARATOR '||')as soundDate
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    WHERE i.id=$idInstrument;");
    $sql4 = $dbConnect->query("SELECT GROUP_CONCAT(p.id) AS idPicture,  GROUP_CONCAT(p.name SEPARATOR '||')as pictureName, GROUP_CONCAT(p.description SEPARATOR '||') as pictureDescription,GROUP_CONCAT(p.imageMini SEPARATOR '||') as pictureMini ,GROUP_CONCAT(p.imageMiddle SEPARATOR '||') as pictureMiddle ,GROUP_CONCAT(p.imageFull SEPARATOR '||') as pictureFull ,GROUP_CONCAT(p.dateFetch SEPARATOR '||')as pictureDateFetch,GROUP_CONCAT(p.date SEPARATOR '||')as pictureDate
    FROM instrument i
    LEFT JOIN picture p 
    ON i.id = p.id_instrument  
    WHERE i.id=$idInstrument
    GROUP BY i.id;");

$assetInstru = $sql->fetch(PDO::FETCH_ASSOC);
$assetInstru2 = $sql2->fetch(PDO::FETCH_ASSOC);
$assetInstru3 = $sql3->fetch(PDO::FETCH_ASSOC);
$assetInstru4 = $sql4->fetch(PDO::FETCH_ASSOC);


    array_push($assetInstru, $assetInstru2);


    array_push($assetInstru, $assetInstru3);


    array_push($assetInstru, $assetInstru4);


    $instrument = $assetInstru + $assetInstru[0];
    $instrument = $instrument + $assetInstru[1];
    $instrument = $instrument + $assetInstru[2];

unset($instrument[0]);
unset($instrument[1]);
unset($instrument[2]);

    

    
    $dbConnect->commit();
    
 
    //var_dump($instrument);

    $sql->closeCursor();
    $sql2->closeCursor();
    $sql3->closeCursor();
    $sql4->closeCursor();
    
    #var_dump($instrument);
    return $instrument;
}



function fetchAllInstrument (pdo $dbConnect) :array{
    

    $dbConnect->beginTransaction();

    $sql = $dbConnect->query("SELECT i.id as idInstrument, i.title,LEFT(i.description,500)as shortdescription , i.history, i.intro, i.technics,i.visible,i.date as dateArticle,
    GROUP_CONCAT(c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory
    FROM instrument i  
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id
    WHERE i.visible = '1'
    GROUP BY i.id");
    $nbRow = $sql->rowCount();
    $sql2 = $dbConnect->query("SELECT GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname,GROUP_CONCAT(m.bornDate SEPARATOR '||')as musicianBorn,GROUP_CONCAT(m.deathDate SEPARATOR '||')as musicianDeath
    FROM instrument i
    LEFT JOIN musician m 
    ON i.id=m.id_instrument
    GROUP BY m.id_instrument
        ;");
   
    $sql3 = $dbConnect->query("SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription,GROUP_CONCAT(s.date SEPARATOR '||')as soundDate
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    WHERE i.visible = '1'
    GROUP BY i.id
    ;");
    $sql4 = $dbConnect->query("SELECT p.id AS idPicture,  p.name as pictureName, p.description  as pictureDescription,p.imageMini  as pictureMini,p.imageMiddle  as pictureMiddle,p.imageFull  as pictureFull ,p.date as pictureDateTake,p.date as pictureDate
    FROM instrument i
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    WHERE i.visible = '1' AND p.orientation = 'l'
    GROUP BY i.id
    ;");
    

    
    $assetInstru = $sql->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru3 = $sql3->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru4 = $sql4->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru2[$i])) {
            $assetInstru[$i][] = $assetInstru2[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru3[$i])) {
            $assetInstru[$i][] = $assetInstru3[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru4[$i])) {
            $assetInstru[$i][] = $assetInstru4[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru[$i][0]) && isset($assetInstru[$i][1]) && isset($assetInstru[$i][2])) {
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][0];
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][1];
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][2];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru[$i][0]) && isset($assetInstru[$i][1]) && isset($assetInstru[$i][2])) {
            unset($assetInstru[$i][0]);
            unset($assetInstru[$i][1]);
            unset($assetInstru[$i][2]);
        }
    }



   /* echo "datasInstru";
    var_dump($assetInstru);
    echo "datasInstru2";
    var_dump($assetInstru[0][0]);
    echo "datasInstru2";
    var_dump($assetInstru[0][1]);
    echo "datasInstru2";
    var_dump($assetInstru[0][2]);*/

    $dbConnect->commit();
    $sql->closeCursor();
    $sql2->closeCursor();
    $sql3->closeCursor();
    $sql4->closeCursor();

    return $assetInstru;
}


function fetchAllInstrumentAdmin (pdo $dbConnect) :array{
    

    $dbConnect->beginTransaction();

    $sql = $dbConnect->query("SELECT i.id as idInstrument, i.title,LEFT(i.description,1200)as shortdescription , i.history, i.intro, i.technics,i.visible,i.date as dateArticle,
    GROUP_CONCAT(c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory
    FROM instrument i  
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id
    GROUP BY i.id");
    $nbRow = $sql->rowCount();
    $sql2 = $dbConnect->query("SELECT GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname,GROUP_CONCAT(m.bornDate SEPARATOR '||')as musicianBorn,GROUP_CONCAT(m.deathDate SEPARATOR '||')as musicianDeath
    FROM instrument i
    LEFT JOIN musician m 
    ON i.id=m.id_instrument
    GROUP BY m.id_instrument
    
        ;");
   
    $sql3 = $dbConnect->query("SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription,GROUP_CONCAT(s.date SEPARATOR '||')as soundDate
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    GROUP BY i.id
    
    ;");
    $sql4 = $dbConnect->query("SELECT p.id AS idPicture,  p.name as pictureName, p.description  as pictureDescription,p.imageMini  as pictureMini,p.imageMiddle  as pictureMiddle,p.imageFull  as pictureFull ,p.date as pictureDateTake,p.date as pictureDate
    FROM instrument i
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    GROUP BY i.id
    
    ;");
    

    
    $assetInstru = $sql->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru3 = $sql3->fetchAll(PDO::FETCH_ASSOC);
    $assetInstru4 = $sql4->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru2[$i])) {
            $assetInstru[$i][] = $assetInstru2[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru3[$i])) {
            $assetInstru[$i][] = $assetInstru3[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru4[$i])) {
            $assetInstru[$i][] = $assetInstru4[$i];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru[$i][0]) && isset($assetInstru[$i][1]) && isset($assetInstru[$i][2])) {
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][0];
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][1];
            $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][2];
        }
    }
    for ($i=0;$i<$nbRow;$i++){
        if (isset($assetInstru[$i][0]) && isset($assetInstru[$i][1]) && isset($assetInstru[$i][2])) {
            unset($assetInstru[$i][0]);
            unset($assetInstru[$i][1]);
            unset($assetInstru[$i][2]);
        }
    }



   /* echo "datasInstru";
    var_dump($assetInstru);
    echo "datasInstru2";
    var_dump($assetInstru[0][0]);
    echo "datasInstru2";
    var_dump($assetInstru[0][1]);
    echo "datasInstru2";
    var_dump($assetInstru[0][2]);*/

    $dbConnect->commit();
    $sql->closeCursor();
    $sql2->closeCursor();
    $sql3->closeCursor();
    $sql4->closeCursor();

    return $assetInstru;
}


function updateInstrument(pdo $dbConnect,string $title,string $intro, string|null $description, string $history,string $technics,string $visible,int $idInstrumentUpdate){

    
    $title = htmlspecialchars(strip_tags(trim($title)), ENT_QUOTES);
    $intro = htmlspecialchars(strip_tags(trim($intro)), ENT_QUOTES);
    $description = htmlspecialchars(strip_tags(trim($description)), ENT_QUOTES);
    $history = htmlspecialchars(strip_tags(trim($history)), ENT_QUOTES);
    $technics = htmlspecialchars(strip_tags(trim($technics)), ENT_QUOTES);
    $visible= (int) htmlspecialchars(strip_tags(trim($visible)), ENT_QUOTES);
  

    $sql = $dbConnect->prepare("UPDATE instrument SET title=?,intro=?,description=?,history=?,technics=?,visible=? WHERE id = $idInstrumentUpdate");
    $sql->bindParam(1, $title ,PDO::PARAM_STR);
    $sql->bindParam(2, $intro ,PDO::PARAM_STR);
    $sql->bindParam(3,$description,PDO::PARAM_STR);
    $sql->bindParam(4, $history ,PDO::PARAM_STR);
    $sql->bindParam(5, $technics ,PDO::PARAM_STR);
    $sql->bindParam(6,$visible,PDO::PARAM_INT);
   
    try{
      
        $sql->execute();
    }catch(Exception $e){
        return $e = throw new Exception ( "Problème lors de l'ajout veuillez recommencer");

    }
}
function addInstrument(pdo $dbConnect,string $title,string $intro, string|null $description, string $history,string $technics,string $visible){

    
    $title = htmlspecialchars(strip_tags(trim($title)), ENT_QUOTES);
    $intro = htmlspecialchars(strip_tags(trim($intro)), ENT_QUOTES);
    $description = htmlspecialchars(strip_tags(trim($description)), ENT_QUOTES);
    $history = htmlspecialchars(strip_tags(trim($history)), ENT_QUOTES);
    $technics = htmlspecialchars(strip_tags(trim($technics)), ENT_QUOTES);
    $visible= (int) htmlspecialchars(strip_tags(trim($visible)), ENT_QUOTES);
    

    $sql = $dbConnect->prepare("INSERT INTO instrument (title,intro,description,history,technics,visible) VALUES (?,?,?,?,?,?)");
    $sql->bindParam(1, $title ,PDO::PARAM_STR);
    $sql->bindParam(2, $intro ,PDO::PARAM_STR);
    $sql->bindParam(3,$description,PDO::PARAM_STR);
    $sql->bindParam(4, $history ,PDO::PARAM_STR);
    $sql->bindParam(5, $technics ,PDO::PARAM_STR);
    $sql->bindParam(6,$visible,PDO::PARAM_INT);
    try{
        $sql->execute();
        $lastId = $dbConnect->lastInsertId();
        return $lastId;
    }catch(Exception $e){
        return $e = throw new Exception ( "Problème lors de l'ajout veuillez recommencer");

    }
}

function updateInstrumentHasCategory( pdo $dbConnect, string $idInstrument, string $category){
    $category = (int) htmlspecialchars(strip_tags(trim($category)), ENT_QUOTES);
    $idInstrument = (int) htmlspecialchars(strip_tags(trim($idInstrument)), ENT_QUOTES);


    $sql = $dbConnect->prepare("UPDATE category_has_instrument category_id=?, instrument_id=? WHERE id =$idInstrument ");


    $sql->bindParam(1, $category ,PDO::PARAM_INT);
    $sql->bindParam(2, $idInstrument ,PDO::PARAM_INT);


    try{
        $sql->execute();
    }catch(Exception $e){
        return $e =  throw new Exception ("Problème lors de l'ajout veuillez recommencer");

    }
}
function addInstrumentHasCategory( pdo $dbConnect, string $lastId, string $category){
    $category = (int) htmlspecialchars(strip_tags(trim($category)), ENT_QUOTES);
    $lastId = (int) htmlspecialchars(strip_tags(trim($lastId)), ENT_QUOTES);


    $sql = $dbConnect->prepare("INSERT INTO category_has_instrument (category_id, instrument_id) VALUES (?,?)");


    $sql->bindParam(1, $category ,PDO::PARAM_INT);
    $sql->bindParam(2, $lastId ,PDO::PARAM_INT);


    try{
        $sql->execute();
    }catch(Exception $e){
        return $e =  throw new Exception ("Problème lors de l'ajout veuillez recommencer");

    }
}


function deleteInstrument(pdo $dbConnect, int $idInstrumentDelete){
    $sql= $dbConnect->prepare('DELETE FROM instrument WHERE id='.$idInstrumentDelete.'');
    $sql->execute();
    header("Location:./");
    return "Projet bien effacé";
}




function search (pdo $dbConnect, string $recherche): array {
    try {
    
        $all = $dbconnect->query('SELECT * FROM instrument WHERE id AND title AND description AND history AND technics AND visible AND intro AND date LIKE "%'.$recherche.'%" ORDER BY id DESC '); 
        $all2 = $dbconnect->query('SELECT * FROM category WHERE id AND namecateory LIKE "%'.$recherche.'%" ORDER BY id DESC '); 
        $all3= $dbconnect->query('SELECT * FROM musicien WHERE id AND firstname AND lastname AND biography AND bornDate AND deathDate AND id_instrument AND date LIKE "%'.$recherche.'%" ORDER BY id DESC '); 
        $all4 = $dbconnect->query('SELECT * FROM picture WHERE id AND name AND imageMini AND imageMiddle AND imageFull AND description AND date AND dateFetch AND orientation AND id_instrument LIKE "%'.$recherche.'%" ORDER BY id DESC '); 
        $all5 = $dbconnect->query('SELECT * FROM sound WHERE id AND name AND description AND audio AND date AND id_instrument  LIKE "%'.$recherche.'%" ORDER BY id DESC '); 
        $assetInstru = $all->fetchAll(PDO::FETCH_ASSOC);
        $assetInstru2 = $all2->fetchAll(PDO::FETCH_ASSOC);
        $assetInstru3 = $all3->fetchAll(PDO::FETCH_ASSOC);
        $assetInstru4 = $all4->fetchAll(PDO::FETCH_ASSOC);
        $assetInstru = $assetInstru + $assetInstru2;
        $assetInstru = $assetInstru + $assetInstru3;
        $assetInstru = $assetInstru + $assetInstru4;
        unset($assetInstru2);
        unset($assetInstru3);
        unset($assetInstru4);
        return $assetInstru;
        var_dump($assetInstru);
    } catch (Exception $e) {
       die("une érreur a été trouve : ".$e->getMessage()); 
    }
}



/*function fetchAllInstrument (pdo $dbConnect){
    

    $dbConnect->beginTransaction();
   
    $sql = $dbConnect->query("   SELECT s.id AS idSound , s.name  as soundName, s.audio as sound, s.description  as soundDescription, 
    p.id AS idPicture,  p.name as pictureName, p.description  as pictureDescription,p.imageMini  as picture ,
    m.id as idMusician, m.firstname  as musicianFirstname, m.biography as musicianBio,  m.lastname as musicianLastname,
    (SELECT i.id as idInstrument, i.title, i.description, i.history, i.intro, i.technics,i.visible, 
    c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory
    FROM instrument i  
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id) as assetInstrument
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    LEFT JOIN musician m 
    ON i.id=m.id_instrument
    ;");
    $datasAllInstrument = $sql->fetchAll(PDO::FETCH_ASSOC);

    
    


    $dbConnect->commit();

    
    
   
   
   
    return $datasAllInstrument;
}








/*function fetchAllInstrument (pdo $dbConnect){
    

    $dbConnect->beginTransaction();
   
    $sql = $dbConnect->prepare("SELECT i.id as idInstrument, i.title, i.description, i.history, i.technics,i.visible, c.id as idCategory,c.namecategory as nameCategory, s.id AS idSound ,s.name as sound, p.id AS idPicture, p.name as picture ,m.id as idMusician, m.firstname as musicianFirstname, m.lastname as musicianLastname

    FROM instrument i 
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id 
    LEFT JOIN sound s 
    ON  i.id = s.instrument_id 
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    LEFT JOIN musician m 
    ON i.id=m.id_instrument ;");
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
    $datasAllInstrument = $sql->fetchAll();

    


    $dbConnect->commit();

    
    
   
   
   
    return $datasAllInstrument;
}


/*function foreachInstrument(object $object) {
    $data=[];
    foreach($object as $item){
        $datas= new Instrument();
        $datas->idInstrument = $item->idInstrument;
        $datas->title = $item->title;
        $datas->technics = $item->technics;
        $datas->description = $item->description;
        $datas->visible = $item->visible;
        $datas->idCategory = $item->idCategory;
        $datas->nameCategory = $item->nameCategory;
        $datas->musicianFirstname = $item->musicianFirstname;
        $datas->musicianLastname = $item->musicianLastname;
        $datas->audio = $item->audio;
        $datas->picture = $item->picture;
        $datas->idAudio = $item->idAudio;
        $datas->idPicture = $item->idPicture;
        $datas->idMusician = $item->idMusician;
        $data[]=$datas;
    }
    return $data;

}
/*SELECT c.id,c.namecategory, ih.instrument_id,ih.category_id,i.id,i.title,i.description,i.history,i.technics,i.visible FROM category c INNER JOIN category_has_instrument ih ON c.id=ih.category_id INNER JOIN instrument i ON c.id = i.id where c.id=?;

SELECT i.id, i.title, i.description, i.history, i.technics,i.visible,i.category_id,s.id AS idSound ,s.name,s.instrument_id, p.id AS idPicture, p.name,p.id_instrument,m.id as idMusician, m.firstname,m.lastname,m.id_instrument
FROM instrument i
INNER JOIN sound s
ON i.id = s.instrument_id
INNER JOIN picture p 
ON i.id = p.id_instrument
INNER JOIN musician m 
ON i.id=m.id_instrument



function fetchAllInstrument (pdo $dbConnect, int $countInstrument){
   
for ($i=0; $i <=$countInstrument; $i++){
    $dbConnect->beginTransaction();
    $dataAllInstrument = new Instrument();
    $sql = $dbConnect->prepare("SELECT i.id as idInstrument, i.title, i.description, i.history, i.technics,i.visible,i.category_id,ihc.instrument_id, ihc.category_id,c.id as idCategory,c.namecategory as nameCategory
    FROM instrument i 
    INNER JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    INNER JOIN category c 
    ON ihc.category_id=c.id ;");
    $datassql = $sql->setFetchMode(PDO::FETCH_CLASS, 'Instrument') ;
    $datassql = $sql->execute();
    $sql->fetch();

    $sql2 = $dbConnect->prepare("SELECT i.id as idInstrument, s.id AS idSound ,s.name as sound,s.instrument_id, p.id AS idPicture, p.name as picture ,p.id_instrument,m.id as idMusician, m.firstname as musicianFirstname, m.lastname as musicianLastname,m.id_instrument 
    FROM instrument i 
    LEFT JOIN sound s 
    ON  i.id = s.instrument_id 
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    LEFT JOIN musician m 
    ON i.id=m.id_instrument ;");
     $datassql= $sql2->setFetchMode(PDO::FETCH_INTO, $dataAllInstrument) ;
     $sql2->execute();
    $sql2->fetch();
    $dbConnect->commit();
$datasAllInstrument[]=$dataAllInstrument;
}
    
   
   
   
    return $datasAllInstrument;



        $sql = $dbConnect->prepare("SELECT i.id as idInstrument, i.title, i.description, i.history, i.intro, i.technics,i.visible, 
    GROUP_CONCAT(c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory,    
    (SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription, 
    GROUP_CONCAT(p.id) AS idPicture,  GROUP_CONCAT(p.name SEPARATOR '||')as pictureName, GROUP_CONCAT(p.description SEPARATOR '||') as pictureDescription,GROUP_CONCAT(p.imageMini SEPARATOR '||') as picture ,
    GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    LEFT JOIN picture p 
    ON i.id = p.id_instrument 
    LEFT JOIN musician m 
    ON i.id=m.id_instrument) as assetInstrument
    FROM instrument i 
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id;");

/*$musician = [];
array_push($musician,explode("||",$instruments[1]->musicianLastname));
array_push($musician,explode("||",$instruments[1]->musicianFirstname));
array_push($musician,explode("||",$instruments[1]->musicianBio));
var_dump($musician);

/*$audio = [];
array_push($audio,explode("||",$instruments[0]->soundName));
array_push($audio,explode("||",$instruments[0]->soundDescription));
array_push($audio,explode("||",$instruments[0]->$sound));
var_dump($audio);

$picture = [];
array_push($picture,explode("||",$instruments[0]->pictureName));
array_push($picture,explode("||",$instruments[0]->pictureDescription));
array_push($picture,explode("||",$instruments[0]->picture));
var_dump($picture);*/




    