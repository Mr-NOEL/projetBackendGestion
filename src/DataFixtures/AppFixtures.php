<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vetement;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Type
        echo "chargement des fixtures pour l'entité Type \n***\n\n";
        $this->load_Type($manager);

        // Vetement
        echo "chargement des fixtures pour l'entité Vetement \n***\n\n";
        $this->load_Vetement($manager);

        //User
        echo "chargement des fixtures pour l'entité User \n***\n\n";
        $this->loadUsers($manager);
        $manager->flush();
    }
    public function loadUsers(ObjectManager $manager)
    {
        echo " \n\nles utilisateurs : \n";

        $admin = new User();
        $password = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN'])
            ->setUsername('admin')->setEmail('admin@gmail.com')->setIsActive('1');
        $manager->persist($admin);
        echo $admin."\n";
    }

    public function load_Type(ObjectManager $manager){
        $types=[
            ['libelle' =>  'Pantalon'],
            ['libelle' => 'Short'],
            ['libelle' => 'Chemise'],
            ['libelle' => 'Veste'],
            ['libelle' => 'Tee-Shirt'],
            ['libelle' => 'Chaussette'],
            ['libelle' => 'Sous-vetements']
        ];
        foreach($types as $type)
        {
            $new_type = new Type();
            $new_type ->setLibelle($type['libelle']);

            $manager->persist($new_type);
            $manager->flush();
        }
    }
    public function load_Vetement(ObjectManager $manager){
        $vetements=[
            ['descriptif' => 'Jeans noir Lee' ,  'prix_de_base' => '55' ,  'taille' => '40'  ,  'date_achat' => '2017-01-15','type_id' =>  'Pantalon'],
            ['descriptif' => 'Pantalon velour bleu NafNaf' ,  'prix_de_base' => '65'  ,  'taille' => '38'  ,  'date_achat' => '2017-01-15','type_id' =>  'Pantalon'],
            ['descriptif' => 'Bermuda Rouge Puma' ,  'prix_de_base' => '35'  ,  'taille' => '38'  ,  'date_achat' => '2017-01-15','type_id' => 'Short'],
            ['descriptif' => 'Veste Cuir Noir' ,  'prix_de_base' => '235'  ,  'taille' => '38'  ,  'date_achat' => '2017-01-15','type_id' => 'Veste'],
            ['descriptif' => 'Chaussette Montagne Salomon ' ,  'prix_de_base' => '15'  ,  'taille' => '40'  ,  'date_achat' => '2017-01-15','type_id' => 'Chaussette'],
            ['descriptif' => 'Slip arena' ,  'prix_de_base' => '15'  ,  'taille' => '40'  ,  'date_achat' => '2017-01-15','type_id' => 'Sous-vetements'],
        ];
        foreach($vetements as $vetement)
        {
            $new_vetement = new Vetement();
            $new_vetement->setDescriptif($vetement['descriptif']);
            $new_vetement->setPrixDeBase($vetement['prix_de_base']);
            $new_vetement->setTaille($vetement['taille']);
            $new_vetement_date=\DateTime::createFromFormat('Y-m-d',$vetement['date_achat']);
            $new_vetement->setDateAchat($new_vetement_date);
            $new_vetement->setType($manager->getRepository(Type::class)->findOneBy(['libelle'=>$vetement['type_id']]));
            $manager->persist($new_vetement);
            $manager->flush();
        }
    }

}
