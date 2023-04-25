<?php



function fetchInstrumentHome(pdo $dbConnect):array { 

        $sql= $dbConnect->query('SELECT i.id, i.title , LEFT(i.description,200)as shortIntro, p.image,p.name FROM instrument i LEFT JOIN picture p ON i.id = p.id_instrument LIMIT 10');
        $dataInstrumentHome= $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
        return $dataInstrumentHome;
}
function truncate (string $text): string{
    // fonction qui trouve un numérique qui est la dernière sous chaine dans une chaine pour remplacer $cut : " "
    $cut = strrpos($text, ' ');
    return substr ($text, 0,$cut);
}

function fetchDetailInstrument (pdo $dbConnect, int $idInstrument):array{
    
    $dbConnect->beginTransaction();
    $sql = $dbConnect->query("SELECT i.id as idInstrument, i.title, i.description, i.history, i.intro, i.technics,i.visible, c.id as idCategory,c.namecategory as nameCategory
    FROM instrument i 
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id 
    WHERE i.id=$idInstrument;");
    $sql2 = $dbConnect->query("SELECT GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname
    FROM instrument i
    LEFT JOIN musician m 
    ON i.id=m.id_instrument 
    WHERE i.id=$idInstrument;");
    $sql3 = $dbConnect->query("SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    WHERE i.id=$idInstrument;");
    $sql4 = $dbConnect->query("SELECT GROUP_CONCAT(p.id) AS idPicture,  GROUP_CONCAT(p.name SEPARATOR '||')as pictureName, GROUP_CONCAT(p.description SEPARATOR '||') as pictureDescription,GROUP_CONCAT(p.image SEPARATOR '||') as picture 
    FROM instrument i
    LEFT JOIN picture p 
    ON i.id = p.id_instrument  
    WHERE i.id=$idInstrument;");

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

    return $instrument;
}








function fetchAllInstrument (pdo $dbConnect) :array{
    

    $dbConnect->beginTransaction();

    $sql = $dbConnect->query("SELECT i.id as idInstrument, i.title,LEFT(i.description,500)as shortdescription , i.history, i.intro, i.technics,i.visible, 
    GROUP_CONCAT(c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory
    FROM instrument i  
    LEFT JOIN category_has_instrument ihc 
    ON i.id= ihc.instrument_id 
    LEFT JOIN category c 
    ON ihc.category_id=c.id
    GROUP BY i.id");
    $nbRow = $sql->rowCount();
    $sql2 = $dbConnect->query("SELECT GROUP_CONCAT(m.id) as idMusician, GROUP_CONCAT(m.firstname SEPARATOR '||') as musicianFirstname, GROUP_CONCAT(m.biography SEPARATOR '||')as musicianBio,  GROUP_CONCAT(m.lastname SEPARATOR '||')as musicianLastname
    FROM instrument i
    LEFT JOIN musician m 
    ON i.id=m.id_instrument
    GROUP BY m.id_instrument
        ;");
   
    $sql3 = $dbConnect->query("SELECT GROUP_CONCAT(s.id) AS idSound , GROUP_CONCAT(s.name SEPARATOR '||') as soundName, GROUP_CONCAT(s.audio SEPARATOR '||')as sound, GROUP_CONCAT(s.description SEPARATOR '||') as soundDescription
    FROM instrument i
    LEFT JOIN sound s 
    ON  i.id = s.id_instrument
    GROUP BY i.id
    ;");
    $sql4 = $dbConnect->query("SELECT GROUP_CONCAT(p.id) AS idPicture,  GROUP_CONCAT(p.name SEPARATOR '||')as pictureName, GROUP_CONCAT(p.description SEPARATOR '||') as pictureDescription,GROUP_CONCAT(p.image SEPARATOR '||') as picture 
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
        array_push($assetInstru[$i], $assetInstru2[$i]);
    }
    for ($i=0;$i<10;$i++){
        array_push($assetInstru[$i], $assetInstru3[$i]);
    }
    for ($i=0;$i<$nbRow;$i++){
        array_push($assetInstru[$i], $assetInstru4[$i]);
    }
    for ($i=0;$i<$nbRow;$i++){
       $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][0];
       $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][1];
       $assetInstru[$i] = $assetInstru[$i]+ $assetInstru[$i][2];
    }
    for ($i=0;$i<$nbRow;$i++){
       unset($assetInstru[$i][0]);
       unset($assetInstru[$i][1]);
       unset($assetInstru[$i][2]);
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

/*function fetchAllInstrument (pdo $dbConnect){
    

    $dbConnect->beginTransaction();
   
    $sql = $dbConnect->query("   SELECT s.id AS idSound , s.name  as soundName, s.audio as sound, s.description  as soundDescription, 
    p.id AS idPicture,  p.name as pictureName, p.description  as pictureDescription,p.image  as picture ,
    m.id as idMusician, m.firstname  as musicianFirstname, m.biography as musicianBio,  m.lastname as musicianLastname,
    (SELECT i.id as idInstrument, i.title, i.description, i.history, i.intro, i.technics,i.visible, 
    GROUP_CONCAT(c.id SEPARATOR '||') as idCategory,GROUP_CONCAT(c.namecategory) as nameCategory
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
    GROUP_CONCAT(p.id) AS idPicture,  GROUP_CONCAT(p.name SEPARATOR '||')as pictureName, GROUP_CONCAT(p.description SEPARATOR '||') as pictureDescription,GROUP_CONCAT(p.image SEPARATOR '||') as picture ,
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




    