<?php

namespace App\Controller;

use App\Entity\Team;
use App\Exceptions\NotValidValueException;
use App\Helpers\Validators;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TeamController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class TeamController extends AbstractController
{
    private TeamRepository $repository;

    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/team", name="team_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $teams = $this->repository->findAll();

        $data = [];

        foreach ($teams as $team) {
            $data[] = [
                'id' => $team->getId(),
                'name' => $team->getName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/team", name="team_store", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->isInvalidFields($data);
        } catch (NotValidValueException $exception) {
            return new JsonResponse(['status' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $team = new Team();
        $team->setName($data['name']);

        $this->repository->save($team);

        return new JsonResponse(['status' => 'Team created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/team/{id}", name="team_show", methods={"GET"})
     */
    public function show(int $id) : JsonResponse
    {
        $team = $this->repository->find($id);

        if (!$team) {
            $data = [
                'status' => 404,
                'errors' => "Team not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        $data = [
          'id' => $team->getId(),
          'name' => $team->getName()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/team/{id}", name="team_update", methods={"PUT"})
     */
    public function update(Request $request,int $id): JsonResponse
    {
        $team = $this->repository->find($id);
        $data = json_decode($request->getContent(), true);

        try {
            $this->isInvalidFields($data);
        } catch (NotValidValueException $exception) {
            return new JsonResponse(['status' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $team->setName($data['name']);

        $this->repository->save($team);

        return new JsonResponse(['status' => 'Team updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("/team/{id}", name="team_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $team = $this->repository->find($id);

        $this->repository->remove($team);

        return new JsonResponse(['status' => 'Team deleted'], Response::HTTP_OK);
    }

    private function isInvalidFields(array $data)
    {
        if (Validators::isEmpty($data['name'])) {
            throw new NotValidValueException('Expecting mandatory parameter name');
        }
    }
}
