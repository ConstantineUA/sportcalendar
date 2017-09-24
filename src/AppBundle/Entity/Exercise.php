<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class representing Exercise entity
 *
 * @ORM\Entity
 */
class Exercise
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * Short description of an exercise
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $description;

    /**
     * Used weights
     *
     * @ORM\Column(type="decimal", scale=2)
     *
     * @var float
     */
    protected $weight;

    /**
     * Number of repetitions
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $repetitions;

    /**
     * Exercise date
     *
     * @ORM\Column(type="date")
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Exercise time
     *
     * @ORM\Column(type="time")
     *
     * @var \DateTime
     */
    protected $time;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }


    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getRepetitions()
    {
        return $this->repetitions;
    }

    /**
     * @param int $repetitions
     */
    public function setRepetitions($repetitions)
    {
        $this->repetitions = $repetitions;
    }
}
