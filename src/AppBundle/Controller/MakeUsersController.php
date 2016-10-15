<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use AppBundle\Entity\User;
use AppBundle\Entity\Status;
use AppBundle\Form\EditUserType;

/**
 * @Route("database-install", name="app")
 */
class MakeUsersController extends Controller
{
    /**
    * @Route("/all", name="all")
    */
    public function indexAction(Request $request)
    {
        //$this->installUsers();
        //$this->installStatuses();
        $this->makeConnections();
    }

    public function installUsers(){
        $maleNames = array("Maaiz Rahim","Aayid Akram","Salaah Fadel","Raadi Zaman","Yahya Morad","Sirajuddeen Sultan","Saleem Yusuf","Salaah Hoque","Maaiz Jafari","Mushtaaq Sultan","Anwar Shahidi","Awni Ameen","Jawaad Barakat","Mamoon Bari","Hibbaan Baten","Abdul Baasid Ali","Abdul Ghafoor Kazemi","Bishr Shehata","Mujahid Rahaim","Badraan Abdullah","Anwar Shahidi","Awni Ameen","Jawaad Barakat","Mamoon Bari","Hibbaan Baten","Abdul Baasid Ali","Abdul Ghafoor Kazemi","Bishr Shehata","Mujahid Rahaim","Badraan Abdullah","Shuraih Daoud","Aiman Sabet","Sad Kamara","Munsif Hamidi","Usaama Hamed","Abdur Raqeeb Can","Muaaid Haider","Arkaan Afzal","Ashqar Hashem","Ammaar Rasul");
        $femaleNames = array("Nahla Fahmy","Umaima Rayes","Zuhra Jabbour","Laaiqa Naderi","Sitaara Hashmi","Awda Ozer","Sulama Afzal","Sanad Mahmood","Maimoona Amara","Nuha Neman","Mahdeeya Khan","Tareefa Salman","Rumaana Hadi","Qaaida Tariq","Wasmaaa Dada","Kinaana Barakat","Hadiyya Mahdi","Majeeda Mohammed","Hameeda Younan","Kawkab Vaziri","Labeeba Hamad","Tahaani Farhat","Zumruda Hussain","Nadheera Koroma","Suhaa Naim","Reema Shah","Saajida Samaan","Ameera Jamail","Almaasa Adel","Shamaail Fahs","Qamraaa Sadri","Nuzha Ghazal","Tamanna Azimi","Fareeda Kanan","Hamaama Gaber","Majeeda Hamidi","Nabeeha Suleiman","Manaara Khalil","Saaliha Abraham","Faraah Hammad");

        for ($i=0; $i<100; $i++){
            $user = new User();
            if ($i%2==0){
                $male = "male";
                $name = $maleNames[rand(0,6)];
                $img = $this->getImage("men");
            }else{
                $male = "female";
                $names = $femaleNames[rand(0,6)];
                $img = $this->getImage("women");
            }
            $username = str_replace(' ', '', $name);
            $mail = $username . "@gmail.com";
            $num = "658806711";

            $user->setUsername($username . rand(1,50000));
            $user->setFullName($name);
            $user->setEmail($mail);
            $user->setNumber($num);
            $days = rand(10,40)*365+rand(1,400);
            $str = $days . " days ago";
            $user->setDob(new \DateTime($str));
            $user->setPassword("pass");
            $user->setGender($male);
            $user->setPicture($img);

            $em = $this->getDoctrine()->getManager();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();
        }
    }

    public function installStatuses(){
        $lat[0] = array("38.00348525357065","39.61284718152819","41.185647662199244","41.737264506294785","42.2109905663293","42.57610752064736","43.29996349129225","44.761133956795085","45.03351774453939","45.056804888441924","45.67429861916082","45.81230586252669","46.20907740527054","46.50478857698029","47.02405131707226","48.17960905051401",);
        $lng[0] = array("23.869524362499988","22.683000924999988","22.595110299999988","22.331438424999988","21.716204049999988","21.979875924999988","21.902971628124988","20.430803659374988","19.167375924999988","18.859758737499988","16.124163034374988","16.003313424999988","15.838518503124988","15.706682565624988","15.497942331249988","16.354875924999988",);
        $name[0] = array("Pallini, Greece","Greece","Macedonia (FYROM)","Odzhalija, Macedonia (FYROM)","Tabanovtse, Macedonia (FYROM)","Serbia","Niš, Serbia","Beograd, Serbia","Morović, Serbia","Komletinci, Croatia","Vukovina, Croatia","Zagreb, Croatia","Lukovčak, Croatia","Miklavž na Dravskem Polju, Slovenia","Austria","Wien, Austria",);

        $lat[1] = array("38.38339990077319","40.21953658623963","41.08635543289601","41.63060135058462","42.23539776867171","42.76996770441298","43.18792268596057","44.37762908453388","45.25049576904045","46.094916173231944","46.28505334558834","47.12132323777253","47.81201698528252","47.81201698528252","48.311301351695455","48.39161308837714","48.718843696068284","49.20213599426695","50.020770101333575","50.77708418600636","51.20582215532301","51.03342042578812",);
        $lng[1] = array("27.055559518749988","29.077043893749988","27.736711862499988","26.682024362499988","24.792375924999988","23.495989206249988","22.858782174999988","21.057024362499988","19.848528268749988","19.760637643749988","20.024309518749988","20.123186471874988","19.200334909374988","18.727922799999988","18.112688424999988","17.508440378124988","16.959123971874988","16.618547799999988","15.783586862499988","15.091448190624988","14.761858346874988","13.762102487499988",);
        $name[1] = array("Turkey","Turkey","Turkey","Turkey","Krislovo, Bulgaria","Sofia, Bulgaria","Rosomač, Serbia","Krnjevo, Serbia","Novi Sad, Serbia","Palić, Serbia","Szeged, Hungary","Tószeg, Hungary","Kosd, Hungary","Štúrovo, Slovakia","Nitra, Slovakia","Zvončín, Slovakia","Lanžhot, Czech Republic","Brno, Czech Republic","Pardubice, Czech Republic","Liberec, Czech Republic","Waldhufen, Germany","Dresden, Germany");

        $lat[2] = array("37.86478972018881","37.49961249931874","38.21953542479568","39.57046398144063","39.63818063605196","40.13554357581903","40.44560610641187","40.662636262738225","41.45787483013272","42.9390492805565","44.031053800165346","44.628319184718315","45.20402194988419","45.9728357727322","47.038981313485635","47.5753370061347","48.689797206263535","48.23081759331813","48.776751580868975","49.3454508854304","50.38288170491833","50.92270253861618",);
        $lng[2] = array("23.913500375000012","22.364428109375012","21.782152718750012","20.881273812500012","19.859545296875012","18.442308968750012","17.299730843750012","16.607592171875012","15.552904671875012","13.861010140625012","12.542650765625012","10.905687875000012","9.213793343750012","8.917162484375012","8.323900765625012","7.609789437500012","6.170580453125012","3.2592035000000124","2.0946527187500124","1.0509515468750124","1.6936517421875124","1.8968988125000124",);
        $name[2] = array("Greece","Greece","Greece","Greece","Kontokali, Greece","Italy","Italy","Matera, Italy","Foggia, Italy","Italy","Italy","Modena, Italy","Prado, Italy","Collina d'Oro, Switzerland","Luzern, Switzerland","Basel, Switzerland","Nancy, France","Courtois-sur-Yonne, France","Guyancourt, France","Oissel, France","Conchil-le-Temple, France","Coulogne, France",);


        $lat[3] = array("40.253027829040356","41.053169267194356","41.67979764033788","42.10506254207182","42.6730592243708","43.331883899999994","43.674539876248836","44.4795693345164","44.784479590558384","45.27364277478799","46.13294535438203","47.7307457904646","48.157578390870114","48.17956009880629","47.59756355912642","48.09891443686151",);
        $lng[3] = array("29.252894843750028","28.879359687500028","26.616176093750028","24.748500312500028","23.496058906250028","21.936000312500028","21.551478828125028","21.046107734375028","20.463832343750028","19.837611640625028","19.969447578125028","17.662318671875028","16.508754218750028","14.135707343750028","13.102992500000028","11.608851875000028",);
        $name[3] = array("Turkey","Turkey","Turkey","Bulgaria","Ravno pole, Bulgaria","Niš, Serbia","Ražanj, Serbia","Lozovik, Serbia","Beograd, Serbia","Novi Sad, Serbia","Horgoš, Serbia","Győr, Hungary","Wien, Austria","Sinnersdorf, Austria","Kuchl, Austria","München, Germany",);


        $lat[4] = array("41.06889268496554","41.60506961035775","42.128680307260815","42.63183481636169","42.97038927008261","43.903728373621995","45.00160991371104","45.05596212967341","45.210969785736495","45.4426913240742","45.78083505977475","46.37526248577096","47.03072691562953","48.14217159649315","48.12017386437256",);
        $lng[4] = array("28.84213308749986","26.66684011874986","24.82113699374986","23.40390066562486","22.03060964999986","20.38266043124986","19.65756277499986","19.07528738437486","17.47128347812486","16.71322683749986","16.07601980624986","15.87826589999986","15.51571707187486","14.10946707187486","11.68148855624986",);
        $name[4] = array("Turkey","Turkey","Plovdiv, Bulgaria","Горубляне, Bulgaria","Serbia","Čačak, Serbia","Sremska Mitrovica, Serbia","Lipovac, Croatia","Staro Petrovo Selo, Croatia","Repušnica, Croatia","Zagreb, Croatia","Pobrežje, Slovenia","Lamberg, Austria","Weyerbach, Austria","München, Germany",);

        $ha =4 ;
        $max = rand(0, $ha);
        $this->doItWith($lat[$max],$lng[$max],$name[$max]);
    }


     public function doItWith($lat, $lng, $name){
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $allUsers = $repo->findAll();

        $notes = array("I arrived safely! The people here are very nice, but it is a bit cold.",
"The weather is still warm and sunny, and we have enough food. We will continue the trip on the 18th of November.",
"Mom, I will be here for 5 days. I'll be on Skype every day from 4 to 6pm if you can come and talk to me",
"Alli, don't go through Hungary, the roads are too dangerous right now. I would advise you to stay in Serbia. Stay safe.",
"I reach the camp safely today. There is not much food, but we have shelter and water. Please let me know you are okay!",
"The bus was late 2h but we reached Frankfurt finally! I will get a job soon and send for you guys. Stay safe.",
"Me and Edin reached Serbia today via train. The people are nice and the conditions are okay.",
"Aadil and Amir are with me, we were able to meetup in Zagreb like we planned. The border is not safe, but we have shelter and supplies. Stay safe dad!",
"Aasif, do not go over the border yet. Keep Abdul and you sister safe. We will meet in Nish.");

        foreach ($allUsers as $user){
            $max = 0;
            $finish = rand(count($lat)-3,count($lat));
            $days = 0;
            while ($max<$finish){

                $status = new Status();
                $status->setLtd($lat[$max]+rand(0.01, 0.001));
                $status->setLng($lng[$max]+rand(0.01, 0.001));
                $status->setLocation($name[$max]);

                $status->setNote($notes[rand(0,count($notes)-1)]);
                $status->setUser($user);

                $days = $days+rand(1,5);
                $str = $days . " days ago";
                $status->setDate(new \DateTime($str));

                $max = rand($max+1, min($finish-1,$max+(rand(1,3))));

                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
            }
        }
     }

     public function makeConnections(){
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $allUsers = $repo->findAll();

        $max = count($allUsers);
        foreach($allUsers as $user){
            $ab = rand(3,7);
            for ($i=0; $i<$ab; $i++){
                $user1 = $allUsers[rand(0,$max-1)];
                if ($user1->getId()===$user->getId()) continue;
                if ($user->getPeopleIFollow()->contains($user1)) continue;
                $user->addPeopleIFollow($user1);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
     }

    public function getImage($male)
   {
       $url = "http://api.randomuser.me/portraits/". $male ."/".rand(1, 95).".jpg";
       $ch = curl_init ($url);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $raw=curl_exec($ch);
       curl_close ($ch);
       // echo '<img src="data:jpg;base64,'.base64_encode($raw).'">';
       // return new JsonResponse($response);
       return 'data:jpg;base64,'.base64_encode($raw);
   }

}