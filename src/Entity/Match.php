<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="match", indexes={@ORM\Index(name="league_id", columns={"league_id"}), @ORM\Index(name="team_guest_id", columns={"team_guest_id"}), @ORM\Index(name="team_host_id", columns={"team_host_id"})})
 * @ORM\Entity
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
     * @ORM\Column(name="week", type="integer", nullable=false, options={"default"="1"})
     */
    private $week = 1;

    /**
     * @var \League
     *
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     * })
     */
    private $league;

    /**
     * @var \Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_host_id", referencedColumnName="id")
     * })
     */
    private $teamHost;

    /**
     * @var \Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_guest_id", referencedColumnName="id")
     * })
     */
    private $teamGuest;


}
