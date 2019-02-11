<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"user_output_default"}},
 *         "denormalization_context"={"groups"={"user_input_default"}}
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={"denormalization_context"={"groups"={"user_input_creation"}}}
 *     },
 *     itemOperations={
 *         "get"={"access_control"="(is_granted('ROLE_USER') and object == user)", "normalization_context"={"groups"={"user_output_profile"}}},
 *         "put"={"access_control"="(is_granted('ROLE_USER') and object == user)", "denormalization_context"={"groups"={"user_update"}}}
 *     }
 * )
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
	/**
	 * @var int The entity Id
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @var string The username
	 *
	 * @ORM\Column(unique=true)
	 * @Assert\NotBlank
	 */
	private $username;

	/**
	 * @var string The user password
	 *
	 * @ORM\Column(unique=true)
	 * @Assert\NotBlank
	 */
	private $password;

	/**
	 * @var array The user roles
	 *
	 * @ORM\Column(type="json", nullable=true)
	 * @Assert\NotBlank
	 */
	private $roles;

	public function getId(): int
	{
		return $this->id;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;
		return $this;
	}

	public function getRoles(): array
	{
		return $this->roles;
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;
		return $this;
	}

	public function getSalt(): ?string
	{
		return null;
	}

	public function eraseCredentials(): void
	{
	}
}
