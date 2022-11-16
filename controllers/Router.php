<?php

class Router{

    private $_ctrl;
    private $_view;

    public function routeReq(){

        try{

            spl_autoload_register(function($class){
                require_once('models/' .$class. '.php');
            });

            //On crée une variable $url (type str) vide de tout contenu
            $url = '';

            // On verifie si les données $_GET de l'url sont collectées dans le cas où une adresse url est trouvée
            if(isset($_GET['url']))
            {
                //On scinde la variable url (str) en plusieurs segments disctincts et grâce à FILTER SANITIZE on enlève tous les caractères non conformes à une adresse url
                $url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

                //On crée une variable $controller dans laquelle on va insérer la première itération de $url en contrôlant son format str avec strtolower (pour tout mettre en minuscules) et ucfirst (met le premier caractère en majusucle)
                $controller = ucfirst(strtolower($url[0]));
                //On crée une variable $controllerClass qui contient une concaténation du nom de fichier Controller et le fichier qui lui sera attribué, par exemple : Si le fichier récupéré se nomme "acCueil" alors la variable $controller le transformera en "Accueil" et viendra le concaténer à Controller, ce qui donnera ControllerAccueil.php
                $controllerClass = "Controller".$controller;
                //On crée une variable $controllerFile qui de la mêm façon va concaténer le nom du fichier précédent (dans mon exemple ControllerAccueil.php) à son nom de dossier "controllers" afin de créer le bon chemin ou path jusqu'au fichier souhaité 
                $controllerFile = "controllers/".controllerClass.".php";

                //SI le fichier en question EXISTE, on l'inclut avec require_once (différent de require car si le fichier est déjà inclu alors on ne l'inclut pas une deuxième fois) dans l'attribut privé $_ctrl, dans lequel on crée une nouvel objet $controllerClass qui aura en paramètre le fameux url
                if(file_exists($controllerFile)){
                    require_once($controllerFile);
                    $this->_ctrl = new $controllerClass($url);
                }
                //Sinon on affiche la classe de base Exception avec le message 'Page introuvable'
                else
                    throw new Exception('Page introuvable');
            }
            //Si les données $_GET ne sont pas collectées, on force le chemin ou path manuellement
            else{
                require_once('Controllers/ControllerAccueil.php');
                $this->ctrl = new ControllerAccueil($url);

            }
        }

        catch(Exception $e){

            $errorsMsg = $e->getMessage();
            require_once('views/viewError.php');

        }
    }
}