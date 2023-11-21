<?php

namespace App\Controller;

use App\Entity\TwigVars;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    private $requestStack;
    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    #[Route('/about', name: 'app_about')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupérez le nom du contrôleur actuel
        $currentController = $this->requestStack->getCurrentRequest()->get('_controller');

        $controllerWithoutIndex = str_replace('::index', '', $currentController);

        $currentController = $controllerWithoutIndex;

        $controllerWithoutStart = str_replace('App\Controller\\', '', $currentController);

        $currentController = $controllerWithoutStart;

        // Récupérez les variables Twig pour le contrôleur actuel depuis la base de données
        $twigVars = $this->em->getRepository(TwigVars::class)->findBy(['controller' => $currentController]);

        // Créez un tableau associatif pour stocker les variables Twig
        $twigVariables = [];

        // Parcourez les variables Twig et stockez-les dans le tableau
        foreach ($twigVars as $twigVar) {
            $variableName = $twigVar->getName();

            $twigVariables[$variableName] = $this->getTwigVarValue($twigVar->getController(), $twigVar->getName());
        }


        // Passez le tableau des variables Twig à la vue
        return $this->render('baseTemplate/home/index.html.twig', [
            'twigVariables' => $twigVariables,
        ]);
    }

    // Fonction pour obtenir la valeur d'une variable Twig à partir du contrôleur
    private function getTwigVarValue(string $controller, string $variableName): string
    {
        // Récupérez la valeur depuis la base de données en fonction du contrôleur et du nom de la variable
        $twigVar = $this->em->getRepository(TwigVars::class)->findOneBy([
            'controller' => $controller,
            'name' => $variableName,
        ]);

        // Vérifiez si la variable a été trouvée dans la base de données
        if ($twigVar) {
            // Si oui, retournez la valeur correspondante
            return $twigVar->getValue();
        } else {
            // Sinon, retournez une valeur par défaut ou une chaîne indiquant que la valeur n'a pas été trouvée
            return "Valeur introuvable pour la variable $controller.$variableName";
        }
    }

    // Fonction pour définir une valeur dans un tableau multidimensionnel en fonction de la clé imbriquée
    private function setNestedArrayValue(array &$array, string $keys, $value): void
    {
        $keys = explode('.', $keys);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }
}
