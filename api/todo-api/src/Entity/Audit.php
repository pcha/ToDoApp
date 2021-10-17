<?php

namespace App\Entity;

use App\Repository\AuditRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: AuditRepository::class)]
class Audit
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
    private ?int $id;
    #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
    private ?int $task_id;
    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 16)]
    private ?string $action;
    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 255)]
    private ?string $description;
    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $performed_at;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTaskId(): ?int
    {
        return $this->task_id;
    }
    public function setTaskId(int $task_id): self
    {
        $this->task_id = $task_id;

        return $this;
    }
    public function getAction(): ?string
    {
        return $this->action;
    }
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getPerformedAt(): ?\DateTimeImmutable
    {
        return $this->performed_at;
    }
    public function setPerformedAt(\DateTimeImmutable $performed_at): self
    {
        $this->performed_at = $performed_at;

        return $this;
    }
}
