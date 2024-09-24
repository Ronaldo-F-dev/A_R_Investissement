<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Form\UserType;
use App\Entity\Investissement;
use App\Form\InvestissementType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageController extends AbstractController
{

    public function __construct(private MailerInterface $mailer,private ParameterBagInterface $params)
{
}
    

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute("app_dashbord");
        }
        return $this->render('page/index.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/dashbord', name: 'app_dashbord')]
    public function dashbord(EntityManagerInterface $em): Response
    {
       
        $user = $this->getUser();
        $investissements = $user->getInvestissements();
        $montant_total_investi = 0;
        $gain_journalier  = 0;
        $montant = $user->getSolde();
        $today = new \DateTimeImmutable(); // La date d'aujourd'hui
        
        foreach ($investissements as $investissement) {
            if ($investissement->getStatut() == 1) {
                // Montant investi
                $montant_investi = $investissement->getMontant();
                // Mise à jour du montant total investi
                $montant_total_investi += $montant_investi;
                // Calcul du gain sur le montant investi
                $gain = $montant_investi * $investissement->getPlan()->getGain() / 100;
                $gain_journalier += $gain;
                $lastUpdatedAt = $investissement->getUpdatedAt();
                // Date de fin
                $endedDate = $investissement->getEndAt();

                // Vérifier si la date de fin est arrivée
                if ($endedDate > $today && ($lastUpdatedAt->format('Y-m-d') < $today->format('Y-m-d'))) {
                    // Mise à jour du solde si le gain n'a pas encore été ajouté aujourd'hui
                    $montant += $gain;
                    $user->setSolde($montant);
                    $investissement->setUpdatedAt($today);
                    $em->flush();
                }
            }
        }

        $gainParrainage = 0;
        $nbrInvestissments = 0;
        $mInvestissement = 0;
        //On récupère tous les gens que l'utilisateur à parrainer
        foreach($user->getParrains() as $p){
            //On récupère toutes les investissements de cette personnes
            $investissements_referal = $p->getInvestissements();
            //on parcours chaque investissments
            foreach($investissements_referal as $ir){
                if($ir->getStatut() == 1){
                    $mInvestissement += $ir->getMontant();
                    $nbrInvestissments += 1;
                }
            }
            
        }
        $gainParrainage = $mInvestissement / 100;
        return $this->render('page/dashbord.html.twig',[
            "gain_journalier" => $gain_journalier,
            "montant_total_investi" => $montant_total_investi,
            "gainParrainage" => $gainParrainage
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Your profile has been updated.');
                return $this->redirectToRoute('app_dashbord');
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', 'An error occurred: ' . $e->getMessage());
        }
        return $this->render('page/profil.html.twig', [
            'form' => $form->createView() // Render the form in the template
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/investissement', name: 'app_investissement')]
    public function investissement(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $investissement = new Investissement();
        $form = $this->createForm(InvestissementType::class,$investissement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $investissement->setMontant(0);
            $investissement->setUser($user);
            $investissement->setCreatedAt(new \DateTimeImmutable());
            $investissement->setUpdatedAt(new \DateTimeImmutable());
            $investissement->setStatut(0);

            $duree = $investissement->getPlan()->getDuree();
            $endedDate = $investissement->getCreatedAt()->modify("+$duree days");
            $investissement->setEndAt($endedDate);
            $entityManager->persist($investissement);
            $entityManager->flush();
            return $this->redirectToRoute("app_dashbord");
        }
        return $this->render('page/investissement.html.twig',[
            "form" => $form->createView()
        ]);
    
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/investissements', name: 'app_investi')]
    public function investissements(Request $request,EntityManagerInterface $entityManager): Response
    {
        //$investissements = $this->getUser()->getInvestissements();
        return $this->render('page/investissements.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/investissement/{id}/show', name: 'app_investissement_show')]
    public function investissement_show(): Response
    {
        
        return $this->json([
            "message" => "OK"
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/plan/{id}', name: 'app_plan')]
    public function investissement_info(Plan $plan): Response
    {
        $info = [
            "montant_min" => $plan->getMontantMin(),
            "montant_max" => $plan->getMontantMax(),
            "gain" => $plan->getGain(),
            "duree" => $plan->getDuree()
        ];
        return $this->json($info);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('reference/verif', name: 'app_ref_verif')]
    public function ref_verif(Request $request,TransactionRepository $transRepo): Response
    {

        $ref = json_decode($request->getContent(),true);
        $reference = $ref['ref'];
        $nom = $ref['nom'];
        /*$transaction = $transRepo->findOneBy([
            "refTrans" => $ref
        ]);*/
        
        /*if($transaction){
            $data = [
                "message" => "Demande prise en compte."
            ];
        }else{
            $data = [
                "message" => "Votre paiement n'a pas été trouvé."
            ];
        }*/

        $email = (new Email())
        ->from(new Address($this->getUser()->getEmail(), $this->params->get('NOM_SITE')))
        ->to($this->params->get('EMAIL'))
        ->subject('Demande de retrait')
        ->html("<p>Bonjour,</p><p>Demande de retrait reçu de la part de : ".$this->getUser().".<br/>Numéro : $numero<br/>Montant : $montant</p>");

    // Envoi de l'email
    $this->mailer->send($email);

        return $this->json([
            "message" => "success"
        ]);
    }

    #[Route("/retrait", name: "app_retrait")]
    public function retrait(Request $request) : Response
    {
        $user = $this->getUser();
        if($user->getNb() < 5){
        
            $this->addFlash("danger","Vous devez parrainer 5 personnes au moins");
            $email = (new Email())
                ->from(new Address($this->params->get('EMAIL'), $this->params->get('NOM_SITE')))
                ->to((string) $user->getEmail())
                ->subject('Demande de retrait')
                ->html('<p>Bonjour,</p><p>Pour éffectuer un retrait, vous devez parrainer 05 personnes minimum.</p>');

            // Envoi de l'email
            $this->mailer->send($email);
            return $this->redirectToRoute("app_dashbord");
        }
        $form = $this->createFormBuilder()
            ->add("numero",TextType::class,[
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->add("montant",TextType::class,[
                "attr" => [
                    "class" => "form-control"
                ]
            ])
            ->getForm()
        ;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $numero = trim($form->get('numero')->getData());
            $montant = trim($form->get('montant')->getData());
            
            
            
            $email = (new Email())
                ->from(new Address($user->getEmail(), $this->params->get('NOM_SITE')))
                ->to($this->params->get('EMAIL'))
                ->subject('Demande de retrait')
                ->html("<p>Bonjour,</p><p>Demande de retrait reçu de la part de : ".$this->getUser().".<br/>Numéro : $numero<br/>Montant : $montant</p>");

            // Envoi de l'email
            $this->mailer->send($email);
            $this->addFlash("success","Demande de retrait envoyée");
            return $this->redirectToRoute("app_dashbord");
            
        }
        return $this->render("page/retrait.html.twig",[
            "form" => $form
        ]);
    }
    

    
}
//dd($this->params->get('EMAIL'));