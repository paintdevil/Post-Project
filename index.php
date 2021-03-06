<?php

include_once './config.php';
include_once './class/dbKlasse.php';
include_once './class/Html.php';
include_once './class/Fehlermeldung.php';
include_once './class/VnName.php';

//Html::printValues($_POST);

$vn = new VnName();
$error = new Fehlermeldung();

if (key_exists('senden', $_POST))
{
    unset($update);
    $vorname    = $_POST['vorname'];
    $nachname   = $_POST['nachname'];
    $id         = $_POST['update_id'];
    $vornamen   = $_POST['vornamen'];
    $nachnamen  = $_POST['nachnamen'];
    
    $update     = '';
    
    for ($i = 0; $i < count($id); $i++)
    {
        $test = $vn->updateUser($id[$i], $vornamen[$i], $nachnamen[$i]);
        
        if ($test == TRUE)
        {
            $update = $test;
        }
    }
    
    Html::printValues($del_string);
    
    if(!$update)
    {
    
        if (strlen($vorname) == 0)
        {
            $error->addMessage('Vorname fehlt.');
        }
        if (strlen($nachname) == 0)
        {
            $error->addMessage('Nachname fehlt.');
        }

                /* 
                     *Gibt es Vorname und Nachname Kombi schon in Datenbank ?
                    */

        if ($vn->checkUser($vorname, $nachname) == TRUE)
        {
            $error->addMessage('User vorhanden');
        }

            /* Neuer User in Datenbank schreiben*/

        if (!$error->hasMesasage())
        {
            $vn->setVorname($vorname);
            $vn->setNachname($nachname);
            $success = $vn->newUser();
            if(!$success)
            {
                $error->addMessage('Nicht geklappt ... NSA ist informiert !!!');
            }

        }
    }
}

if (key_exists('del', $_POST))
{
    if (isset($_POST['delete_id']))
    {
        $del_id = $_POST['delete_id'];
        for ($i = 0; $i < count($del_id); $i++)
        {
            $vn->delete($del_id[$i]);
        }
    }
    else
    {
        $error->addMessage('Bitte wählen Sie welch(e) Datensätze gelöscht werden sollen');
    }
}


$vnNamen = $vn->getAll();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>POST - Projekt V 1.0</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
                $error->printMessage();
        ?>
        <form method="POST" action="index.php">
            
            <table>
                <thead>
                    <tr>   
                        <th>Vorname</th>
                        <th>Nachname</th>
                        <th>    </th>
                        <th><input type="submit" name="senden"   value="Save"></th>
                        <th><input type="submit" name="del"   value="Del"></th>
                    </tr>
                    <tr>
                        <th height="15px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="vorname" value="" size="30" /></td>
                        <td><input type="text" name="nachname" value="" size="30" /></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th height="15px"></th>
                    </tr>
                    
                    <?php

                    for ($i = 0; $i < count($vnNamen); $i++)
                    {
                        echo    "<tr>";
                        echo    '<td><input type="text" name="vornamen[]" size="30" value="' . $vnNamen[$i]->getVorname() . '" /></td>';
                        echo    '<td><input type="text" name="nachnamen[]" size="30" value="' . $vnNamen[$i]->getNachname() .'" /></td>';
                        echo    '<td></td>';
                        echo    '<td><input type="hidden" name="update_id[]" value="' . $vnNamen[$i]->getId() . '" /></td>';
                        echo    '<td><input type="checkbox" name="delete_id[]" value="' . $vnNamen[$i]->getId() . '" /></td>';
                        echo    '</tr>';
                    }
                    ?>
                    
                </tbody>
            </table>
            
        </form>
       
    </body>
</html>
