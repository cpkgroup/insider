<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="`match`", indexes={@ORM\Index(name="league_id", columns={"league_id"}), @ORM\Index(name="team_guest_id", columns={"team_guest_id"}), @ORM\Index(name="team_host_id", columns={"team_host_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 */
class Match
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="goals_host", type="smallint", nullable=true)
     */
    private $goalsHost;

    /**
     * @var int|null
     *
     * @ORM\Column(name="goals_guest", type="smallint", nullable=true)
     */
    private $goalsGuest;

    /**
     * @var int
     *
     * @ORM\Column(name="week_number", type="integer", nullable=false, options={"default"="1"})
     */
    private $weekNumber = 1;

    /**
     * @var League
     *
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     * })
     */
    private $league;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_host_id", referencedColumnName="id")
     * })
     */
    private $teamHost;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_guest_id", referencedColumnName="id")
     * })
     */
    private $teamGuest;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="played_at", type="datetime", nullable=true)
     */
    private $playedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Match
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGoalsHost()
    {
        return $this->goalsHost;
    }

    /**
     * @param int|null $goalsHost
     * @return Match
     */
    public function setGoalsHost($goalsHost)
    {
        $this->goalsHost = $goalsHost;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGoalsGuest()
    {
        return $this->goalsGuest;
    }

    /**
     * @param int|null $goalsGuest
     * @return Match
     */
    public function setGoalsGuest($goalsGuest)
    {
        $this->goalsGuest = $goalsGuest;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeekNumber()
    {
        return $this->weekNumber;
    }

    /**
     * @param int $weekNumber
     * @return Match
     */
    public function setWeekNumber($weekNumber)
    {
        $this->weekNumber = $weekNumber;
        return $this;
    }

    /**
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param League $league
     * @return Match
     */
    public function setLeague($league)
    {
        $this->league = $league;
        return $this;
    }

    /**
     * @return Team
     */
    public function getTeamHost()
    {
        return $this->teamHost;
    }

    /**
     * @param Team $teamHost
     * @return Match
     */
    public function setTeamHost($teamHost)
    {
        $this->teamHost = $teamHost;
        return $this;
    }

    /**
     * @return Team
     */
    public function getTeamGuest()
    {
        return $this->teamGuest;
    }

    /**
     * @param Team $teamGuest
     * @return Match
     */
    public function setTeamGuest($teamGuest)
    {
        $this->teamGuest = $teamGuest;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    /**
     * @param DateTime $playedAt
     * @return Match
     */
    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;
        return $this;
    }
}
