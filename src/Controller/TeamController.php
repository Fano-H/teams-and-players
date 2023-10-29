<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/teams')]
/**
 * Handle things relative to teams.
 */
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_team_index')]
    /**
     * Index access teams listing.
     */
    public function index(Request $request, TeamRepository $teamRepository): Response
    {
        // $teams = $teamRepository->findAll();
        $page = $request->get('page', 1);
        $postsPerPage = 2;

        $teams = $teamRepository->getPaginatedTeams($page, $postsPerPage);
        $total = $teams->count();
        $totalTrunc = (int) ceil($total / $postsPerPage);

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'total' => $totalTrunc,
        ]);
    }

    #[Route('/new', name: 'app_team_new')]
    /**
     * New team creation.
     */
    public function newTeam(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($team);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'The team was recorded'
            );

            return $this->redirectToRoute('app_team_index');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/getteamdata', name: 'app_team_get_data', methods: ['GET'])]
    /**
     * Get a team data with ajax.
     */
    public function getTeamData(Request $request, TeamRepository $teamRepository): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(null);
        }

        $teamId = $request->query->get('teamId');

        if ($teamId) {
            $team = $teamRepository->find($teamId);

            $players = [];

            foreach ($team->getPlayers() as $player) {
                $players[] = [
                    'id' => $player->getId(),
                    'fullname' => $player->getName().' '.$player->getSurname(),
                ];
            }

            $data = [
                'teamId' => $team->getId(),
                'country' => $team->getCountry(),
                'moneyBalance' => $team->getMoneyBalance(),
                'players' => $players,
            ];

            return new JsonResponse($data);
        }

        return new JsonResponse(null);
    }

    #[Route('/edit/{id}', name: 'app_team_edit')]
    public function editTeam(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                'The team data was updated'
            );

            return $this->redirectToRoute('app_team_index');
        }

        return $this->render('team/edit.html.twig', [
            'form' => $form,
            'team' => $team,
        ]);
    }

    #[Route('/drop/{id}', name: 'app_team_delete')]
    public function dropTeam(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($team);
            $entityManager->flush();

            $this->addFlash(
                'danger',
                'The team was deleted'
            );

            return $this->redirectToRoute('app_team_index');
        } catch (\Exception $exec) {
            return $this->render('team/cannot-delete.html.twig', [
            ]);
        }
    }
}
