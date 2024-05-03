<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post as Store;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' =>['read', 'read:item']]
        ),
        new GetCollection(
            normalizationContext: ['groups' =>['read', 'read:collection']]
        ),
        new Patch(),
        new Store(),
    ],
    // normalizationContext: [
    //     'groups' => ['read'], //GET
    // ],
    denormalizationContext:[
        'groups' => ['write'], //POST, PUT, PATCH
    ]
    
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $title = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item', 'write'])]
    private ?string $body = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['read'])]
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    #[Groups(['read'])]
    public function getBody(): ?string
    {
        return $this->body;
    }

    #[Groups(['read:collection'])]
    public function getSummary($len=70): ?string
    {
        if(strlen($this->body)<= $len){
            return $this->body;
        }
        return substr($this->body,0,70) . '...';
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    #[Groups(['read'])]
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
