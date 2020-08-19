<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;
    private TeamRepository $team_repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager, TeamRepository $team_repository)
    {
        parent::__construct($registry, Player::class);
        $this->manager = $manager;
        $this->team_repository = $team_repository;
    }

    public function save(Player $player)
    {
        $this->manager->persist($player);
        $this->manager->flush();
    }

    public function remove(Player $player)
    {
        $this->manager->remove($player);
        $this->manager->flush();
    }

    public function fetchByProperties(array $filters) : array
    {
        $criteria = [];

        if (array_key_exists('position', $filters)) {
            $criteria['position'] = $filters['position'];
        }

        if (array_key_exists('team', $filters)) {
            $team = $this->getTeamByName($filters['team']);
            if (!$team instanceof Team) {
                return [];
            }
            $criteria['team'] = $team->getId();
        }

        return $this->findBy($criteria);
    }

    public function fetchOneByProperties(array $filters) : ?Player
    {
        $criteria = [];
        $criteria['id'] = $filters['id'];

        if (array_key_exists('position', $filters)) {
            $criteria['position'] = $filters['position'];
        }

        if (array_key_exists('team', $filters)) {
            $team = $this->getTeamByName($filters['team']);
            if (!$team instanceof Team) {
                return null;
            }
            $criteria['team'] = $team->getId();
        }

        return $this->findOneBy($criteria);
    }

    public function getTeamId(int $id): Team
    {
        return $this->team_repository->find($id);
    }

    public function getTeamByName(string $name): ?Team
    {
        return $this->team_repository->findOneBy( ['name' => $name]);
    }
}
