<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');
$limit = 10;
$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
$results = false;
if ($query)
{
 // The Apache Solr Client library should be on the include path
 // which is usually most easily accomplished by placing in the
 // same directory as this script ( . or current directory is a default
 // php include path entry in the php.ini)
 require_once('solr-php-client-master/Apache/Solr/Service.php');
 // create a new solr service instance - host, port, and corename
 // path (all defaults in this example)
 $solr = new Apache_Solr_Service('localhost', 8983,'/solr/myexample/');
 // if magic quotes is enabled then stripslashes will be needed
 if (get_magic_quotes_gpc() == 1)
 {
 $query = stripslashes($query);
 }
 // in production code you'll always want to use a try /catch for any
 // possible exceptions emitted by searching (i.e. connection
 // problems or a query parsing error)
if($_GET['type']=='default1'){
 try
 {
 $results = $solr->search($query, 0, $limit);
 }
 catch (Exception $e)
 {
 // in production you'd probably log or email this error to an admin
 // and then show a special message to the user but for this example
 // we're going to show the full exception
 die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
 }
}
    else if($_GET['type']=='pageRank'){
        try
      {
            
            $additionalParameters = array(
            'sort' => 'pageRankFile desc',
            );
            $results = $solr->search($query, 0, $limit,$additionalParameters);
       }
 
        catch (Exception $e)
       {
        // in production you'd probably log or email this error to an admin
       // and then show a special message to the user but for this example
      // we're going to show the full exception
     die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
 }
        
        
        
        
    }
}
?>
<html>
 <head>
 <title>PHP Solr Client Example</title>
 </head>
 <body>
 <form accept-charset="utf-8" method="get" style="margin: 4%;
    text-align: center;">
 <label for="q">Search:</label>
 <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>"/>
 <select name="type">
  <option value="default1"<?php  if (isset($_GET['type'] ) && $_GET['type'] == 'default1' ) echo "selected" ; ?>>Lucene</option>
  <option value="pageRankFile" <?php  if (isset($_GET['type'] ) && $_GET['type'] == 'pageRankFile' ) echo "selected" ;?>>Page Rank</option>
  
</select>
 <input type="submit"/>
 </form>
<?php
// display results
if ($results)
{
 $total = (int) $results->response->numFound;
 $start = min(1, $total);
 $end = min($limit, $total);
    
    
    
    
    
$file = fopen('file.csv', 'r');
//$array=array();
$idUrl =array();
while (($line = fgetcsv($file)) !== FALSE) {
   //$line[0] = '1004000018' in first iteration
    
    //$array = preg_split('/,/',$line);
    $idUrl['/home/rashu/Downloads/solr-6.5.0/crawl_data'.$line[0]]=$line[1];
    
    }
fclose($file);
    
    
    
    
?>
 <div>Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
 
<?php
 // iterate result documents
 foreach ($results->response->docs as $doc)
 {
?>
 
 <table style="border: 1px; padding:20px; text-align: left">


 <tr>
 <td>
     <a href="<?php echo $doc->og_url;?>"><?php echo $doc->title;?></a>
                         
 </td> 
     </tr>

 
 <tr>
 <td>
     <a style="font-size:11px; color:green;"href="<?php echo $idUrl[$doc->id];?>"><?php echo $idUrl[$doc->id];?></a>
   
                         
 </td> 
     </tr>
 
 <tr>
 <td>
     <p style="font-size:11px; color:green;"> ID : <?php echo $doc->id;?></p>
                         
 </td> 
     </tr>
 
 
 <tr>
 
 <td>
    <?php echo ($doc->description)?  (is_string($doc->description)?  $doc->description :  $doc->description[0] ) : "Description Not Available ";?> 
                        
                         
 </td> 
     </tr>
 
 

 </table>
 
<?php
 }
?>
 
<?php
}
?>
 </body>
</html>
