<?php $rank = array(); ?>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme">
    <link rel="stylesheet" href="css/bootstrap-theme.min">
    <script src="js/jquery-1.11.0.min.js"></script>
</head>
<body style="margin: 3%">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Quick VTU</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a id="home" href="#">Home</a></li>
      <li><a id="r" href="#">Rank</a></li>
      <li><a href="#">Page 2</a></li> 
      <li><a href="#">Page 3</a></li> 
    </ul>
  </div>
</nav>
<section id="main">

    <form method="post">
    <strong>Give your range:</strong><br><br>
    <input type="text" placeholder="from (eg. 1pe13cs085)" name="rollF" /> -
    <input type="text" placeholder="to (eg. 1pe13cs115)" name="rollL" /><br><br>
        <input type="submit" name="submit">






    <?php
        set_time_limit (50);

    if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST")
    { 
            
            include'simple_html_dom.php';
            $url = "http://results.vtu.ac.in";
            $temp1 = $_POST['rollF']; $temp2 = $_POST['rollL'];
            $rollF = substr($temp1,7);
            $rollL = substr($temp2,7);
            $col1 = substr($temp1,0,7);
            $col2 = substr($temp2,0,7);
            $size = $rollL - $rollF;
            $check =  ++$rollF;

            if($size>0 && $col1===$col2){
                    if((strlen($check))<3)
                    {
                        $s = 100-(--$rollF);
                    }
                    else{
                        $s=0;
                         --$rollF;
                        /*$rollF = substr($rollF,1);*/
                    }


                    
                 echo '<div class="row">';
                    echo '<div class="col-lg-8">';
                    for($i=0;$i<=$size;$i++)
                    {   
                        if($s>0)
                        {
                            $s--;
                            $postData = array(
                                    'rid' => $col1.'0'.$rollF++,// here.. 1pe13cs'0'
                                    'submit' => 'abc'
                                    );
                        }
                        else
                        {
                            $postData = array(
                                    'rid' => $col1.$rollF++,
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

                   foreach($dom->find('table[cellpadding="4"]') as $article)
                    {
                    echo $article;
                    } 

                        $k=0;
                        foreach($dom->find('table tbody tr td table tbody tr td table tbody tr td table tbody tr td table tbody tr td') as $abc)
                        {
                            $k++;
                            if($k==17)
                            {   
                                if($abc->plaintext === "")
                                    $rank[$col1.($rollF-1)] = 0;
                                else 
                                    $rank[$col1.($rollF-1)] = intval(str_replace(" ", "", $abc->plaintext)); 
                                
                            }//save this to db

                        }


                        /*->children(1)->children(1)->children(1)->children(4)->children(1)->children(1)->children(4*/
                  /* foreach($dom->find('table[id=""]') as $article)
                    {
                    echo $article;
                    }*/

                    curl_close($ch);
                    echo "<hr><hr><br>";
                    }
                 echo "</div>";



                    /*echo "<div class='col-lg-6' style='border-left: solid'>";
                        echo '<div class="container">';
                            arsort($rank); $no = 0;
                            echo "Rank__________USN____________Marks________Percentage<br><br>";
                            foreach($rank as $key => $marks)
                            {
                                $no++; $perc = round(($marks/900)*100,2);
                                echo "{$no}__________{$key}__________{$marks}____________{$perc}% <br>";
                            }
                     echo "</div>";
                     echo "</div>";*/
                     echo "</div>";


            }else{
                    echo "<br><br><br><hr>";
                    echo "<strong>There is some error, reason could be:</strong>";
                    echo "<ul>";
                    echo "<li>range : is not in increasing order";
                    echo "<li>usn : college code or branch code are not same";
                    echo "</ul>";
                    echo "<hr>";
                    $size = 0;
                    $col1=$col2='';

                    }
    }
    ?>
    </form>
    </section>
    
    <section id="rank">
        <table class="table table-bordered">
    <thead>
      <tr>
        <th>Rank</th>
        <th>USN</th>
        <th>Marks</th>
        <th>Percentage(%)</th>
      </tr>
    </thead>
    <tbody>
     
          <?php
             arsort($rank); $no = 0;
             foreach($rank as $key => $marks)
                            {
                                $no++; $perc = round(($marks/900)*100,2);
                                echo '<tr>';
                                echo "<td>{$no}</td>";
                                echo "<td>{$key}</td>";
                                echo "<td>{$marks}</td>";
                                echo "<td>{$perc}%</td>";
                                echo '</tr>';
                            }
          ?>
    </tbody>
    </table>
    </section>
    
    <script>
        $(document).ready(function(){
           
            $('#rank').hide();
            $('#r').click(function(){
                $('#rank').show();
                $('#main').hide();
            });
            $('#home').click(function(){
                $('#rank').hide();
                $('#main').show();
            });
            
        });
    </script>
    
    </body>
    </html>