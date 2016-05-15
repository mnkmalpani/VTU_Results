<form method="post">
<strong>Give your range:</strong><br><br>
<input type="text" placeholder="from(eg. 1pe13cs085)" name="rollF" /> -
<input type="text" placeholder="to(eg. 1pe13cs115)" name="rollL" /><br><br>
<input type="submit" name="submit">


<?php

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST")
{ 
include'simple_html_dom.php';
$url = "http://results.vtu.ac.in";
$temp1 = $_POST['rollF']; $temp2 = $_POST['rollL'];
$rollF = substr($temp1,7);
$rollL = substr($temp2,7);
$size = $rollL - $rollF;
$check =  ++$rollF;

if((strlen($check))<3)
{
    $s = 100-(--$rollF);
}
else{
    $s=0;
     --$rollF;
    /*$rollF = substr($rollF,1);*/
}


//and me save kr rha hun last three like '085' but increasae krta hun to 86 aata h..like 086 ni aata..thats why i did this
for($i=0;$i<=$size;$i++)
{   
    if($s>0)
    {
        $s--;
        $postData = array(
                'rid' => '1pe13cs0'.$rollF++,// yaha 0 lagaya h.. 1pe13cs'0'
                'submit' => 'abc'
                );
    }
    else
    {
        $postData = array(
                'rid' => '1pe13cs'.$rollF++,
                'submit' => 'abc'
                );
    }
$ch = curl_init();

curl_setopt_array($ch, array(
           CURLOPT_URL => $url, 
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_POST => true,
           CURLOPT_POSTFIELDS => $postData
           ));
$output = curl_exec($ch);
//print_r($output);
$dom = str_get_html($output);
echo '<div style="border:black solid 1px;">';
foreach($dom->find('table[cellpadding="4"]') as $article)
{
echo $article;
}
foreach($dom->find('table[id=""]') as $article)
{
echo $article;
}
echo "</div><br><br>";
    echo "<hr><hr><br><br><br>";
curl_close($ch);
}
}

?>
</form>