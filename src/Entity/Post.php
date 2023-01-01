<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as MetadataPost;
use ApiPlatform\Metadata\Put;
use App\Controller\PostCountController;
use App\Controller\PostPublishController;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[
    ApiResource(
        normalizationContext: ['groups' => ['read:collection'], 'openapi_definition_name' => 'Collection'],
        denormalizationContext: ['groups' => ['write:Post']],
        paginationItemsPerPage: 2,
        paginationClientItemsPerPage: true,
        paginationMaximumItemsPerPage: 3,
        operations: [
            new Put(),
            new Delete(),
            new MetadataPost(),
            new Get(normalizationContext: ['groups' => ['read:collection', 'read:item', 'read:Post'], 'openapi_definition_name' => 'Detail']),
            new GetCollection(
                name: 'count',
                uriTemplate: '/posts/count',
                controller: PostCountController::class,
                read: false

            ),
            new GetCollection(),
            new MetadataPost(
                name: 'publish',
                uriTemplate: '/posts/{id}/publish',
                controller: PostPublishController::class,
                openapiContext: [
                    'summary' => 'Permet de publier un article',
                    'requestBody' => [
                        'content' => [
                            'application/json' => [
                                'schema' => [],
                                'example' => []
                            ]
                        ]
                    ]
                ]
            )
        ],
    ),
    ApiFilter(
        SearchFilter::class,
        properties: ['id' => 'exact', 'title' => 'partial']
    )

]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[
        Groups(['read:collection', 'write:Post']),
        Length(min: 5, max: 255, groups: ['create:Post'])
    ]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[
        Groups(['read:collection', 'write:Post'])
    ]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Groups(['read:item', 'write:Post'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(['read:item'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[
        Groups(['read:item', 'write:Post']),
        Valid()
    ]
    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    private ?Category $category = null;

    #[Groups(['read:collection'])]
    #[ApiProperty(openapiContext: ['type' => 'boolean'])]
    #[ORM\Column(options: ['default' => 0])]
    private ?bool $online = false;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
