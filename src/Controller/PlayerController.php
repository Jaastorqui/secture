<?php

namespace App\Controller;

use App\Entity\Player;
use App\Exceptions\NotValidValueException;
use App\Helpers\Validators;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api")
 */
class PlayerController extends AbstractController
{
    private PlayerRepository $repository;

    public function __construct(PlayerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/player", name="player_index", methods={"GET"})
     */
    public function index(Request $request): JsonResponse
    {
        $players = $this->repository->fetchByProperties($request->query->all());

        $currency = $request->query->has('currency') ? $request->query->get('currency'): false;
        $data = [];

        foreach ($players as $player) {
            $data[] = [
                'id' => $player->getId(),
                'name' => $player->getName(),
                'team' => $player->getTeam()->getName(),
                'price' => $player->getPrice($currency),
                'position' => $player->getPosition()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/player", name="player_store", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->isInvalidFields($data);
        } catch (NotValidValueException $exception) {
            return new JsonResponse(['status' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $team = $this->repository->getTeamId($data['team_id']);


        $player = new Player();
        $player->setName($data['name'])
            ->setTeam($team)
            ->setPrice($data['price'])
            ->setPosition($data['position']);

        $this->repository->save($player);

        return new JsonResponse(['status' => 'Player created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/player/{id}", name="player_show", methods={"GET"})
     */
    public function show(Request $request, int $id) : JsonResponse
    {
        $request->query->set('id', $id);
        $player = $this->repository->fetchOneByProperties($request->query->all());
        $currency = $request->query->has('currency') ? $request->query->get('currency'): false;

        if (!$player) {
            $data = [
                'status' => 404,
                'errors' => "Player not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        $data = [
            'id' => $player->getId(),
            'name' => $player->getName(),
            'team' => $player->getTeam()->getName(),
            'price' => $player->getPrice($currency),
            'position' => $player->getPosition()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/player/{id}", name="player_update", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $player = $this->repository->find($id);
        $data = json_decode($request->getContent(), true);

        try {
            $this->isInvalidFields($data);
        } catch (NotValidValueException $exception) {
            return new JsonResponse(['status' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $team = $this->repository->getTeamId($data['team_id']);
        $player->setName($data['name'])
            ->setPrice($data['price'])
            ->setPosition($data['position'])
            ->setTeam($team);

        $this->repository->save($player);

        return new JsonResponse(['status' => 'Player updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("/player/{id}", name="player_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $player = $this->repository->find($id);

        $this->repository->remove($player);

        return new JsonResponse(['status' => 'Player deleted'], Response::HTTP_OK);
    }

    private function isInvalidFields(array $data)
    {
        if (Validators::isEmpty(@$data['name'])) {
            throw new NotValidValueException('Expecting mandatory parameter name');
        }
        if (Validators::isEmpty(@$data['team_id'])) {
            throw new NotValidValueException('Expecting mandatory parameter team_id');
        }
        if (Validators::isEmpty(@$data['price'])) {
            throw new NotValidValueException('Expecting mandatory parameter price');
        }
        if (Validators::isEmpty(@$data['position'])) {
            throw new NotValidValueException('Expecting mandatory parameter position');
        }
        if (Validators::is_valid_position($data['position'])) {
            throw new NotValidValueException('Wrong position');
        }
    }
}
