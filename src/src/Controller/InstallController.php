<?php

namespace App\Controller;


use App\Entity\Resto;
use App\Entity\User;
use App\Form\DatabaseConfigType;
use App\Console\CustomApplication;
use App\Form\InstallAdminType;
use App\Form\InstallConfigType;
use App\Form\MailerConfigType;
use App\Service\DatabaseService;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Runtime\Runner\Symfony\ConsoleApplicationRunner;

class InstallController extends AbstractController
{
    private KernelInterface $kernel;
    private MailerInterface $mailer;

    public function __construct(KernelInterface $kernel, MailerInterface $mailer)
    {
        // Vérifiez si l'installation est déjà effectuée
        if ($_ENV['INSTALL'] === 'TRUE') {
            // Redirigez vers un autre chemin (par exemple, la page d'accueil)
            throw $this->createNotFoundException('Installation already completed');
        }

        $this->kernel = $kernel;
        $this->mailer = $mailer;
    }
    #[Route('/install', name: 'install_index')]
    public function index(): Response
    {
        return $this->render('install/index.html.twig');
    }

    #[Route('/install/check-requirements', name: 'install_check-requirements')]
    public function checkRequirements(): Response
    {
        // Récupérer les paramètres
        $phpVersion = phpversion();
        $intlExtension = extension_loaded('intl');
        $curlExtension = extension_loaded('curl');
        $ctypeExtension = extension_loaded('ctype');
        $iconvExtension = extension_loaded('iconv');
        $pdoExtension = extension_loaded('pdo');

        $phpMinVersion = '8.1.0';
        $intlRequired = true; // Changez à false si intl n'est pas requis
        $curlRequired = true; // Changez à false si cURL n'est pas requis
        $ctypeRequired = true; // Changez à false si cURL n'est pas requis
        $iconvRequired = true; // Changez à false si cURL n'est pas requis
        $pdoRequired = true; // Changez à false si cURL n'est pas requis

        $nextLinkEnabled = true;

        // Vérifier que la version minimale est bien respectée
        if ($phpMinVersion > $phpVersion) {
            $nextLinkEnabled = false;
        }

        // Vérifier que intl est bien installée
        if (extension_loaded('intl') === false) {
            $nextLinkEnabled = false;
        }

        if (extension_loaded('curl') === false) {
            $nextLinkEnabled = false;
        }

        if (extension_loaded('ctype') === false) {
            $nextLinkEnabled = false;
        }

        if (extension_loaded('iconv') === false) {
            $nextLinkEnabled = false;
        }

        if (extension_loaded('pdo') === false) {
            $nextLinkEnabled = false;
        }

        // Générer le tableau
        $results = $this->areRequirementsMet();

        // Passez les résultats et l'état du lien à la vue
        return $this->render('_default/install/check-requirements.html.twig', [
            'results' => $results,
            'nextLinkEnabled' => $nextLinkEnabled,
        ]);
    }

    private function areRequirementsMet(): array
    {
        // Récupérer les paramètres
        $phpVersion = phpversion();
        $intlExtension = extension_loaded('intl');
        $curlExtension = extension_loaded('curl');
        $ctypeExtension = extension_loaded('ctype');
        $iconvExtension = extension_loaded('iconv');
        $pdoExtension = extension_loaded('pdo');

        $phpMinVersion = '8.2.0';
        $intlRequired = true; // Changez à false si intl n'est pas requis
        $curlRequired = true; // Changez à false si cURL n'est pas requis
        $ctypeRequired = true; // Changez à false si cURL n'est pas requis
        $iconvRequired = true; // Changez à false si cURL n'est pas requis
        $pdoRequired = true; // Changez à false si cURL n'est pas requis

        $results = [];

        // Vérification de la version PHP
        $results[] = ['Version PHP', $phpMinVersion, $phpVersion, version_compare($phpVersion, $phpMinVersion, '>=')];

        // Vérification de l'extension intl si requise
        $results[] = ['Extension intl', 'Installed', $intlExtension ? 'Installed' : 'Non present', $intlRequired ? $intlExtension : true];

        // Vérification de l'extension cURL si requise
        $results[] = ['Extension cURL', 'Installed', $curlExtension ? 'Installed' : 'Non present', $curlRequired ? $curlExtension : true];

        $results[] = ['Extension CType', 'Installed', $ctypeExtension ? 'Installed' : 'Non present', $ctypeRequired ? $ctypeExtension : true];

        $results[] = ['Extension Iconv', 'Installed', $iconvExtension ? 'Installed' : 'Non present', $iconvRequired ? $iconvExtension : true];

        $results[] = ['Extension PDO', 'Installed', $pdoExtension ? 'Installed' : 'Non present', $pdoRequired ? $pdoExtension : true];

        return $results;
    }

    /**
     * @throws Exception
     */
    #[Route('/install/database', name: 'install_database')]
    public function installDatabase(Request $request, KernelInterface $kernel, DatabaseService $databaseService): Response
    {
        $form = $this->createForm(DatabaseConfigType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            // Tester les paramètres de connexion
            $result = $databaseService->testConnectionParameters($data);

            // Faire quelque chose en fonction du résultat
            if ($result['success']) {
                $this->updateEnvFile($data);

                // Appliquer automatiquement les migrations
                $this->doctrineMigrationsMigrate($kernel);

                $this->addFlash("success", "Database set up completed");

                return $this->redirectToRoute("install_mailer");
            } else {
                // Les paramètres de connexion sont invalides, renvoyer un message d'erreur
                $this->addFlash("error", "Please verify yout database connect informations");
            }

            return $this->redirectToRoute("install_database");
        }

        // Pass the form to the view
        return $this->render('_default/install/database.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function updateEnvFile(array $data): void
    {
        $envFilePath = $this->getParameter('kernel.project_dir') . '/.env';

        // Read the current content of the .env file
        $currentEnvContent = file_get_contents($envFilePath);

        // Create a replacement string for the database parameters
        $replaceString = sprintf(
            'DATABASE_URL="%s://%s:%s@%s:%s/%s?serverVersion=%s&charset=utf8"',
            $data['sgbd'],
            $data['user'],
            $data['password'],
            $data['host'],
            $data['port'],
            $data['database'],
            $data['version'] . ($data['mariadb'] ? '-MariaDB' : '')
        );

        // Replace the DATABASE_URL line in the .env file
        $updatedEnvContent = preg_replace('/^DATABASE_URL=.*$/m', $replaceString, $currentEnvContent);

        // Write the updated content to the .env file
        file_put_contents($envFilePath, $updatedEnvContent);
    }

    #[Route('/install/mailer', name: 'install_mailer')]
    public function installMailer(Request $request, MailerService $mailerService, MailerInterface $mailer): Response
    {
        $form = $this->createForm(MailerConfigType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Connection succeeded, update the .env file
            $this->updateMailerEnvFile($data);

            $tester = new MailerService($mailer);

            if ($tester->testSmtpConnection($form->get('adress')->getData(), $form->get('port')->getData(), $form->get('user')->getData(), $form->get('password')->getData(), $form->get('mailSender')->getData())) {
                $this->addFlash('success', 'Mailer successfully configurated. A Test EMail have been sent.');
                // Redirect the user to another page or do what you want
                return $this->redirectToRoute('install_config');
            }
            $this->addFlash('error', 'Please verify the informations about your SMTP server.');
            return $this->redirectToRoute('install_mailer');
        }

        // Pass the form to the view
        return $this->render('_default/install/mailer.html.twig', [
            'form' => $form->createView()
        ]);
    }


    private function updateMailerEnvFile(array $data): void
    {
        $envFilePath = $this->getParameter('kernel.project_dir') . '/.env';

        // Read the current content of the .env file
        $currentEnvContent = file_get_contents($envFilePath);

        if ($data['protocol'] === "smtp") {
            $adress = sprintf('%s:%s@%s:%s',
                $data['user'],
                $data['password'],
                $data['adress'],
                $data['port'],
            );
        } else {
            $adress = 'default';
        }

        // Create a replacement string for the database parameters
        $replaceString = sprintf(
            'MAILER_DSN="%s://%s"',
            $data['protocol'],
            $adress,
        );

        // Replace the DATABASE_URL line in the .env file
        $updatedEnvContent = preg_replace('/^MAILER_DSN=.*$/m', $replaceString, $currentEnvContent);

        // Write the updated content to the .env file
        file_put_contents($envFilePath, $updatedEnvContent);
    }

    #[Route('/install/config', name: 'install_config')]
    public function installConfig(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InstallConfigType::class);

        $config = new Resto();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $config->setName($form->get('name')->getData());
            $config->setEmailSender($form->get('emailSender')->getData());
            $config->setLang($form->get('lang')->getData());

            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('install_admin');
        }

        // Pass the form to the view
        return $this->render('_default/install/config.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/install/admin', name: 'install_admin')]
    public function installAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(InstallAdminType::class);

        $user = new User();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setEmail($form->get('email')->getData());
            $user->setPassword($userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $user->setIsVerified(1);
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('install_finish');
        }

        // Pass the form to the view
        return $this->render('_default/install/admin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/install/success', name: 'install_finish')]
    public function installFinish(): Response
    {
        // Pass the form to the view
        return $this->render('_default/install/success.html.twig', [
        ]);
    }

    /**
     * @throws Exception
     */
    private function doctrineMigrationsMigrate(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            // (optional) pass options to the command
            '--no-interaction' => true,
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }
}
