<?php
$servername = "localhost";
$username = "wordpressuser";
$password = "wordpressuser";

try {
    $conn = new PDO("mysql:host=$servername;dbname=wordpress", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }


$filename = 'test.csv';

// The nested array to hold all the arrays
$the_big_array = []; 

// Open the file for reading
if (($h = fopen("{$filename}", "r")) !== FALSE) 
{
  // Each line in the file is converted into an individual array that we call $data
  // The items of the array are comma separated
  while (($data = fgetcsv($h, 1000, ";")) !== FALSE) 
  {
    // Each individual array is being pushed into the nested array
    $the_big_array[] = $data;		
  }

  // Close the file
  fclose($h);
}

// Display the code in a readable format
echo "<pre>";
var_dump($the_big_array);
echo "</pre>";

//creer la categorie Marques AUTO 

$stmt0 = $conn->prepare("INSERT INTO wp_terms (name) VALUES (?)");
$stmt0->execute( ["Marques Auto"]);
$id_cat_AUTO = $conn->lastInsertId();

$stmt2 = $conn->prepare("INSERT INTO wp_term_taxonomy (	term_taxonomy_id,term_id,taxonomy,parent) VALUES (?,?,?,?)");
$stmt2->execute([$id_cat_AUTO,$id_cat_AUTO,"category",0] );


$id_cat_parent=0;
$id_model=0;

for ($i=1; $i <=sizeof($the_big_array) ; $i++) { 
	
	for ($j=0; $j <2 ; $j++) { 
		
    if($i==1 and $j==0){
     
     $firt_cat=$the_big_array[$i][0];

     $stmt0 = $conn->prepare("INSERT INTO wp_terms (name) VALUES (?)");
     $stmt0->execute( [$firt_cat]);
     $id_cat_parent = $conn->lastInsertId();

     $stmt2 = $conn->prepare("INSERT INTO wp_term_taxonomy (term_taxonomy_id,term_id,taxonomy,parent) VALUES (?,?,?,?)");
     $stmt2->execute([$id_cat_parent,$id_cat_parent,"category",$id_cat_AUTO] );



    }
    if($i==1 and $j==1){
     
     $model=$the_big_array[$i][1];

     $stmt0 = $conn->prepare("INSERT INTO wp_terms (name) VALUES (?)");
     $stmt0->execute( [$model]);
     $id_model = $conn->lastInsertId();

     $stmt2 = $conn->prepare("INSERT INTO wp_term_taxonomy (term_taxonomy_id,term_id,taxonomy,parent) VALUES (?,?,?,?)");
     $stmt2->execute([$id_model,$id_model,"category",$id_cat_parent] );

     

    }

    if($i>1 and $j==0){
     

      $firt_cat=$the_big_array[$i][0];
      $prev_cat=$the_big_array[$i-1][0];

      if($firt_cat !=$prev_cat){


      $firt_cat=$the_big_array[$i][0];

     $stmt0 = $conn->prepare("INSERT INTO wp_terms (name) VALUES (?)");
     $stmt0->execute( [$firt_cat]);
     $id_cat_parent = $conn->lastInsertId();

     $stmt2 = $conn->prepare("INSERT INTO wp_term_taxonomy (term_taxonomy_id,term_id,taxonomy,parent) VALUES (?,?,?,?)");
     $stmt2->execute([$id_cat_parent,$id_cat_parent,"category",$id_cat_AUTO] );
     

      }
}

     if($i>1 and $j==1){
     


     $model=$the_big_array[$i][1];

     $stmt0 = $conn->prepare("INSERT INTO wp_terms (name) VALUES (?)");
     $stmt0->execute( [$model]);
     $id_model = $conn->lastInsertId();

     $stmt2 = $conn->prepare("INSERT INTO wp_term_taxonomy (term_taxonomy_id,term_id,taxonomy,parent) VALUES (?,?,?,?)");
     $stmt2->execute([$id_model,$id_model,"category",$id_cat_parent] );
     

      }



	}
}
?>
