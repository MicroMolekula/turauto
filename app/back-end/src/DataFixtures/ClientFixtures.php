<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use Monolog\DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $client_data = [
            ['Васильева', 'Вероника', 'Александровна', 'vasilieva2000@gmail.com', '6368123216', '6106501869', 'B B1', '2018-01-03', '7741800001', '12345678'],
            ['Виноградов', 'Герман', 'Иванович', 'vinogradovger1995@gmail.com', '8134837467', '3676274470', 'B B1', '2015-11-23', '4930955631', 'qwe123'],
            ['Баранов', 'Сергей', 'Гордеевич', 'baranov1999@gmail.com', '4413177176', '6487042030', 'B B1', '2015-08-12', '1643564484', 'abc231'],
            ['Орлов', 'Тимур', 'Павлович', 'orlov1972@gmail.com', '2063964130', '4439760343', 'B B1 D1', '1992-02-24', '1060850342', '3212444'],
            ['Гаврилов', 'Илья', 'Даниилович', 'gavrilov1985@gmail.com', '1312859695', '9585781240', 'B B1 D1', '2005-07-01', '9852534651', 'ddd111'],
            ['Полякова', 'Эмилия', 'Макаровна', 'polyakova1998@gmail.com', '6552789723', '3417495534', 'B B1', '2018-09-19', '4345101858', '3213ddawd'],
            ['Старостин', 'Артём', 'Александрович', 'starostin1988@gmail.com', '5945062097', '7980492945', 'B B1', '2008-04-01', '5793329599', '12323aaa'],
            ['Зайцева', 'Дарья', 'Артёмовна', 'zaiceva1996@gmail.com', '3654109021', '6955878345', 'B B1', '2016-06-17', '6954340407', '3231adadw'],
            ['Гусев', 'Никита', 'Александрович', 'gusev1960@gmail.com', '4939797394', '3108264529', 'B B1 D1', '1980-05-13', '7192002202', '13123abpa'],
            ['Захаров', 'Александр', 'Артёмович', 'zaharov2001@gmail.com', '2854601291', '8963584720', 'B B1', '2019-07-21', '3839098522', 'dddaw1233'],
            ['Львова', 'Полина', 'Тимофеевна', 'livova1991@gmail.com', '9432119278', '5702203840', 'B B1', '2013-09-22', '8809989065', '31234'],
            ['Никулин', 'Семён', 'Андреевич', 'nikulin1990@gmail.com', '5235120083', '6714476454', 'B B1', '2008-05-15', '1883224141', 'ssaw122'],
        ];
        foreach($client_data as $item){
            $client = new Client();
            $client->setCltSurname($item[0])
                   ->setCltName($item[1])
                   ->setCltMidlename($item[2])
                   ->setCltEmail($item[3])
                   ->setCltPassportDetails($item[4])
                   ->setCltNumDrvLic($item[5])
                   ->setCltDrvLicCategory($item[6])
                   ->setCltDrvLicDate(DateTimeImmutable::createFromFormat('Y-m-d', $item[7]))
                   ->setCltPhone($item[8]);
            $password = $this->hasher->hashPassword($client, $item[9]);
            $client->setCltPassword($password);
            $manager->persist($client);
        }
        $manager->flush();
    }
}