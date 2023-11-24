<?php

namespace App\Controller\Admin;

use App\Entity\TwigVars;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class AdminTemplateGenerator extends AbstractController
{
    
    #[Route('/admin/generate', name: 'admin_generate')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les templates installés
        $templatePath = $this->getParameter('kernel.project_dir') . '/templates';
        $templateFolders = $this->getTemplateFolders($templatePath);

        // Construisez le formulaire avec le champ select
        $form = $this->createFormBuilder()
            ->add('template', ChoiceType::class, [
                'choices' => array_combine($templateFolders, $templateFolders),
                'label' => 'Select Template',
            ])
            ->add('submit', SubmitType::class, [
                
            ])
            ->getForm();

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez la valeur sélectionnée dans le champ select
            $selectedTemplate = $form->get('template')->getData();

            // Chargez le fichier config.json depuis le dossier du template sélectionné
            try {
                $configPath = $templatePath . DIRECTORY_SEPARATOR . $selectedTemplate . DIRECTORY_SEPARATOR . 'config.json';
                $configContent = $this->loadConfigFile($configPath);
            } catch (FileNotFoundException $e) {
                // Gestion de l'exception si le fichier n'est pas trouvé
                $configContent = [];
            }

            $this->saveTwigVars($selectedTemplate, $configContent, $entityManager);

            // Mettez à jour la valeur du template
            $this->updateEnvFile($selectedTemplate);

            // Redirigez vers la page d'administration EasyAdmin
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/generate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getTemplateFolders(string $templatePath): array
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in($templatePath);

        $templateFolders = [];
        foreach ($finder as $dir) {
            $templateFolders[] = $dir->getRelativePathname();
        }

        return $templateFolders;
    }

    private function loadConfigFile(string $configPath): array
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists($configPath)) {
            throw new FileNotFoundException("Le fichier $configPath n'a pas été trouvé.");
        }

        $configContent = json_decode(file_get_contents($configPath), true);

        return $configContent ?? [];
    }

    private function updateEnvFile(string $selectedTemplate): void
    {
        $envFilePath = $this->getParameter('kernel.project_dir') . '/.env';

        // Read the current content of the .env file
        $currentEnvContent = file_get_contents($envFilePath);

        // Create a replacement string for the database parameters
        $replaceString = sprintf(
            'TEMPLATE=%s',
            $selectedTemplate,
        );

        // Replace the DATABASE_URL line in the .env file
        $updatedEnvContent = preg_replace('/^TEMPLATE=.*$/m', $replaceString, $currentEnvContent);

        // Write the updated content to the .env file
        file_put_contents($envFilePath, $updatedEnvContent);
    }

    private function saveTwigVars(string $selectedTemplate, array $configContent, EntityManagerInterface $entityManager): void
    {

        $this->deleteOldTwigVars($entityManager);

        foreach ($configContent as $controller => $vars) {
            foreach ($vars as $varName) {
                $twigVar = new TwigVars();
                $twigVar->setController($controller);
                $twigVar->setName($varName);

                $entityManager->persist($twigVar);
            }
        }

        $entityManager->flush();
    }

    private function deleteOldTwigVars(EntityManagerInterface $entityManager): void
    {
        $oldTwigVars = $entityManager->getRepository(TwigVars::class)->findAll();

        foreach ($oldTwigVars as $oldTwigVar) {
            $entityManager->remove($oldTwigVar);
        }

        $entityManager->flush();
    }
}
