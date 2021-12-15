<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=VideosRepository::class)
 */
class Videos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "El nombre del archivo debe tener al menos {{limit}} caracteres",
     *      maxMessage = "El nombre del archivo no puede tener más de {{limit}} caracteres"
     * )
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=3000)
     * @Assert\Length(
     *      min = 2,
     *      max = 3000,
     *      minMessage = "La descripción debe tener al menos {{limit}} caracteres",
     *      maxMessage = "La descripción del archivo no puede tener más de {{limit}} caracteres"
     * )
     */
    private $Descripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Caratula;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->Descripcion;
    }

    public function setDescripcion(string $Descripcion): self
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getCaratula(): ?string
    {
        return $this->Caratula;
    }

    public function setCaratula(?string $Caratula): self
    {
        $this->Caratula = $Caratula;

        return $this;
    }
}
