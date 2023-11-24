<?php

namespace App\Controller\Admin;

use App\Entity\Resto;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Exception\Exception;
use Nyholm\Psr7\Factory\Psr17Factory;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Provider\Nominatim\Nominatim;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LanguageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * @ORM\Entity
 * @EasyAdmin:Crud(edit={"template"="admin/resto.html.twig"})
 */
class RestoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resto::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            LanguageField::new('lang'),
            TextField::new('slogan'),
            TextField::new('adress')
                ->setFormTypeOption('attr', ['class' => 'select2'])
                ->setCustomOption('widget', 'textAutocomplete'),
            ImageField::new('logo')->setUploadDir('public' . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'uploads')->setBasePath('dist' . DIRECTORY_SEPARATOR . 'uploads'),
            TelephoneField::new('telephone'),
            EmailField::new('contactMail'),
            TextField::new('gps')->hideOnForm(),
            BooleanField::new('isReservationPossible'),
            TextEditorField::new('cgu'),
            TextEditorField::new('confidentialityPolicy'),
            TextEditorField::new('reservationPolicy'),
        ];
    }

    public function configureEvents(EntityManagerInterface $em): iterable
    {
        yield BeforeEntityPersistedEvent::class => function (BeforeEntityPersistedEvent $event) {
            $entity = $event->getEntityInstance();
            if ($entity instanceof Resto) {
                $this->updateCoordinates($entity);
            }
        };

        yield AfterEntityPersistedEvent::class => function (AfterEntityPersistedEvent $event, EntityManagerInterface $em) {
            $entity = $event->getEntityInstance();
            if ($entity instanceof Resto) {
                $em->persist($entity);
                $em->flush();
            }
        };
    }

    private function updateCoordinates(Resto $resto): void
    {
        $address = $resto->getAdress();
        $coordinates = $this->geocodeAddress($address);

        if ($coordinates !== null) {
            $resto->setGps($coordinates['latitude'] . ',' . $coordinates['longitude']);
        } else {
            // Gestion de l'erreur (adresse invalide, par exemple)
            // Vous pouvez également renvoyer une réponse d'erreur appropriée ici
        }
    }

    private function geocodeAddress(string $address): ?array
    {
        $httpClient = HttpClientInterface::class;
        $psr17Factory = new Psr17Factory();
        $streamFactory = $psr17Factory->createStream();
        $messageFactory = $psr17Factory->createResponse();
        $geocoder = new Nominatim(
            new Psr18Client(),
            $messageFactory,
            $streamFactory,
            'https://nominatim.openstreetmap.org/'
        );

        try {
            $geocode = $geocoder->geocodeQuery(GeocodeQuery::create($address))->first();
            if ($geocode) {
                return ['latitude' => (string) $geocode->getCoordinates()->getLatitude(), 'longitude' => (string) $geocode->getCoordinates()->getLongitude()];
            }
        } catch (Exception $e) {
            // Gestion de l'exception, par exemple, log ou renvoyer null en cas d'échec
        }

        return null;
    }
}
