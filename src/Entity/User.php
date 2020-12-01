<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
     * @ORM\Entity(repositoryClass=UserRepository::class)
     * @UniqueEntity(
     *      fields = {"email"},
     *      message=" un compte est déja existant à cette Email !!"
     * )
     * @UniqueEntity(
     *      fields = {"username"},
     *      message=" ce nom est déja existant, veuillez saisir un nouveau!"
     * )
     */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner un Email")
     * @Assert\Email(message="Veuillez saisir une adresse Email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Veuillez renseigner un nom d'utilisateur")
     * @Assert\Length(
     *      min="2",
     *      minMessage="Votre nom d'utilisateur doit faire minumum 2 caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min="8",
     *      minMessage="Votre mot de passe doit faire minumum 8 caractères")
     * 
     * @Assert\EqualTo(
     *      propertyPath="confirm_password",
     *      message="Les mots de passe ne correspondent pas"
     * )
     * 
     */
    private $password;

    /**
     *  @Assert\EqualTo(
     *      propertyPath="password",
     *      message="Les mots de passe ne correspondent pas"
     * )
     */

    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    // nettoyer les mots de passes en brute
    public function eraseCredentials()
    {
        
    }
    // renvoie le mot de passe saisi en clair a l'internaute 
    public function getSalt()
    {
        
    }
    // renvoie les role accordé a l'utilisateur

    public function getRoles()
    {
        //return ["ROLE_USER"];
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


}
