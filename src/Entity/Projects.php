<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $client = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $productionPeriod = null;

    #[ORM\Column(length: 255)]
    private ?string $work_link = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $proj_image = null;

    #[ORM\OneToMany(mappedBy: 'projects', targetEntity: Tags::class)]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'projects', targetEntity: Images::class)]
    private Collection $images;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getProductionPeriod(): ?\DateTimeImmutable
    {
        return $this->productionPeriod;
    }

    public function setProductionPeriod(\DateTimeImmutable $productionPeriod): static
    {
        $this->productionPeriod = $productionPeriod;

        return $this;
    }

    public function getWorkLink(): ?string
    {
        return $this->work_link;
    }

    public function setWorkLink(string $work_link): static
    {
        $this->work_link = $work_link;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProjImage(): ?string
    {
        return $this->proj_image;
    }

    public function setProjImage(string $proj_image): static
    {
        $this->proj_image = $proj_image;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setProjects($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getProjects() === $this) {
                $tag->setProjects(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProjects($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProjects() === $this) {
                $image->setProjects(null);
            }
        }

        return $this;
    }
}
